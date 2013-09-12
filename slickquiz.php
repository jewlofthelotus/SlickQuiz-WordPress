<?php

/*
Plugin Name: SlickQuiz
Plugin URI: http://www.jewlofthelotus.com/2011/12/23/slickquiz-jquery-plugin-now-on-github/
Description: Plugin for displaying and managing pretty, dynamic quizzes.
Version: 1.2.2
Author: Julie Cameron, Quicken Loans
Author URI: http://www.quickenloans.com
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html
*/

/*
Copyright (c) 2013 Quicken Loans

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuiz' ) ) {
    class SlickQuiz
    {

        // Constructor
        function __construct()
        {
            $this->plugin_name = basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ );

            // Activate for New Installs
            register_activation_hook( $this->plugin_name, array( &$this, 'activate' ) );

            // Activate for Updates
            add_action( 'plugins_loaded', array( &$this, 'activate' ) );

            // Include Quiz Helper (Shared Methods)
            include_once ( dirname ( __FILE__ ) . '/php/slickquiz-helper.php' );

            // Include Quiz Widgets
            include_once ( dirname ( __FILE__ ) . '/php/slickquiz-widgets.php' );

            // Include Quiz Model
            include_once ( dirname ( __FILE__ ) . '/php/slickquiz-model.php' );

            // Setup Quiz Shortcodes
            include_once ( dirname ( __FILE__ ) . '/php/slickquiz-front.php' );
            $slickQuizFront = new SlickQuizFront();

            // Register AJAX actions
            include_once ( dirname ( __FILE__ ) . '/php/slickquiz-functions.php' );
            $slickQuizFunctions = new SlickQuizFunctions();

            // Register non-menu pages
            add_action( 'admin_menu', array( &$this, 'register_aux_pages' ) );

            // Add the admin menu
            add_action( 'admin_menu', array( &$this, 'add_menu' ) );

            // Add the script and style files
            add_action( 'admin_enqueue_scripts', array( &$this, 'load_resources' ) );
        }

        // On Activation - Create SlickQuiz Database Table And Setup Options
        function activate()
        {
            $this->create_quiz_table();
            $this->create_score_table();

            $quizHelper = new SlickQuizHelper;
            $quizHelper->get_admin_options();

            load_plugin_textdomain('SlickQuizPlugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
        }

        // Add SlickQuiz Menu to Navigation
        function add_menu()
        {
            // Accessible to Authors, Editors, and Admins
            add_menu_page( 'SlickQuizzes', 'SlickQuizzes', 'publish_posts', 'slickquiz', array( &$this, 'direct_route' ) );

            // Accessible to Editors and Admins
            add_submenu_page( 'slickquiz', 'Add Quiz', 'Add Quiz', 'publish_pages', 'slickquiz-new', array( &$this, 'direct_route') );

            // Accessible to Admins
            add_submenu_page( 'slickquiz', 'Options', 'Options', 'manage_options', 'slickquiz-options', array( &$this, 'direct_route') );
        }

        // Register Non-Menu Pages
        function register_aux_pages()
        {
            global $_registered_pages;

            $hooknameEdit = get_plugin_page_hookname( 'slickquiz-edit', 'admin.php' );
            if ( !empty( $hooknameEdit ) ) {
                add_action( $hooknameEdit, array( &$this, 'direct_route' ) );
            }
            $_registered_pages[$hooknameEdit] = true;

            $hooknamePreview = get_plugin_page_hookname( 'slickquiz-preview', 'admin.php' );
            if ( !empty( $hooknamePreview ) ) {
                add_action( $hooknamePreview, array( &$this, 'direct_route' ) );
            }
            $_registered_pages[$hooknamePreview] = true;

            $hooknameScores = get_plugin_page_hookname( 'slickquiz-scores', 'admin.php' );
            if ( !empty( $hooknameScores ) ) {
                add_action( $hooknameScores, array( &$this, 'direct_route' ) );
            }
            $_registered_pages[$hooknameScores] = true;
        }

        // Basic Router
        function direct_route()
        {
            switch ( $_GET['page'] ) {
            case 'slickquiz-new' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-new.php' );
                break;
            case 'slickquiz-edit' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-edit.php' );
                break;
            case 'slickquiz-preview' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-preview.php' );
                break;
            case 'slickquiz-functions' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-functions.php' );
                break;
            case 'slickquiz-options' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-options.php' );
                break;
            case 'slickquiz-scores' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-scores.php' );
                break;
            case 'slickquiz' :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-admin.php' );
                break;
            default :
                include_once ( dirname ( __FILE__ ) . '/php/slickquiz-admin.php' );
                break;
            }
        }

        // Add Admin JS and styles
        function load_resources()
        {
            // Only load resources when in SlickQuiz Admin section
            preg_match( '/slickquiz/is', $_SERVER['REQUEST_URI'], $matches );
            if ( count( $matches) == 0 ) return;

            // Scripts
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'slickquiz_admin_js', plugins_url( '/js/admin.js', __FILE__ ) );

            // Styles
            wp_enqueue_style( 'slickquiz_admin_css', plugins_url( '/css/admin.css', __FILE__ ) );
        }

        // Create Quiz Database Table
        function create_quiz_table()
        {
            global $wpdb;

            $table_name = $wpdb->prefix . 'plugin_slickquiz';

            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                publishedJson longtext NULL,
                workingJson longtext NULL,
                publishedQCount int(11) NULL,
                workingQCount int(11) NULL,
                hasBeenPublished tinyint(1) NOT NULL DEFAULT '0',
                publishedBy bigint(20) unsigned NOT NULL DEFAULT '0',
                publishedDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                createdBy bigint(20) unsigned NOT NULL DEFAULT '0',
                createdDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                lastUpdatedBy bigint(20) unsigned NOT NULL DEFAULT '0',
                lastUpdatedDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY  (id),
                KEY createdBy_index (createdBy)
            );";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        // Create User Score Database Table
        function create_score_table()
        {
            global $wpdb;

            $table_name = $wpdb->prefix . 'plugin_slickquiz_scores';

            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                score varchar(50) NOT NULL,
                quiz_id bigint(20) unsigned NOT NULL DEFAULT '0',
                createdBy bigint(20) unsigned NOT NULL DEFAULT '0',
                createdDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY  (id),
                KEY quiz_id_index (quiz_id),
                KEY score_index (score)
            );";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

    } // End Class SlickQuiz
}

if ( class_exists( 'SlickQuiz' ) ) {
    global $slickQuiz;
    $slickQuiz = new SlickQuiz();
}

?>
