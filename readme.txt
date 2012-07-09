=== SlickQuiz ===
Contributors: jewlofthelotus
Tags: quiz, jquery, slickquiz, javascript, education, generator, manager, test
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 1.0.11
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

SlickQuiz is a plugin for displaying and managing pretty, dynamic quizzes. It uses the SlickQuiz jQuery plugin.

== Description ==

Create and manage pretty, dynamic quizzes using the SlickQuiz jQuery plugin.

Managing and creating new quizzes is simple and intuitive.

* Quiz questions can have single or multiple correct responses.
* Correct and Incorrect response messages.
* Results will include a score and ranking (Savant, Genius, Pretty Smart, Not Smart, etc.)
* Unlimited questions, unlimited answers
* Make changes to your quiz without having to publish them immediately.
* Customize error messages for missing or unpublished quizzes.
* Customize the quiz start button text, as well as score and ranking text.

NOTE: If the Preview window does not appear from the create / edit page, you may need to allow popups for your domain.

NOTE: Do not place the same quiz on the page multiple times, things will break.

Created by [Julie Bellinson](http://jewlofthelotus.com/) - Software Engineer at [Quicken Loans](http://quickenloans.com), Detroit, MI

== Installation ==

1. Upload the SlickQuiz plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create and publish a quiz through the SlickQuiz Management interface.
1. Place [slickquiz id=X] in your templates, where X is the ID of the quiz you created.
1. To use the [slickquiz] shortcode in the sidebar Text widget, add add_filter( 'widget_text', 'do_shortcode' ) to your theme.

== Frequently Asked Questions ==

No questions yet.

== Screenshots ==

1. The quiz management / listing interface
2. Creating a quiz
3. Adding quiz questions
4. A quiz embedded in a post - your styles will vary depending on your theme and preferences.
5. The plugin options allow you to alter messages and quiz features.

== Changelog ==

= 1.0.1 =
* Updating the SlickQuiz jQuery plugin to commit 93a16427269df6f80215b02b44a9a1ddfd1d94b8.
* Includes: Optional "Back" buttons
* Includes: Optional randomly sorted questions and answers
* Includes: Easier answer selection (you can now click the text to select the checkbox / radio button)
* Bug fixes!

= 1.0 =
This is the initial setup of the plugin.

== Upgrade Notice ==

= 1.0.1 =
Updating the SlickQuiz jQuery plugin. New back button and random sorting options. Bug fixes!

= 1.0 =
This is the first version of the plugin
