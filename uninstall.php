<?php
    // If uninstall not called from WordPress - exit
    if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
        exit ();

    global $wpdb, $current_user;

    // Delete options
    delete_option( 'slick_quiz_options' );
    delete_user_meta( $current_user->ID, 'slickquiz_ignore_notice_disabled', 'true' );

    // Delete quiz tables
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}plugin_slickquiz" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}plugin_slickquiz_scores" );
?>
