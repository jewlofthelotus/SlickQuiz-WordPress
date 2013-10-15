*** THIS PROJECT IS NO LONGER ACTIVELY MAINTAINED. ***

*** PLEASE SEE [jewlofthelotus/SlickQuiz-WordPress](https://github.com/jewlofthelotus/SlickQuiz-WordPress) for updates. ***


=== SlickQuiz ===
Contributors: jewlofthelotus
Tags: quiz, jquery, slickquiz, javascript, education, generator, manager, test
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

SlickQuiz is a plugin for displaying and managing pretty, dynamic quizzes. It uses the SlickQuiz jQuery plugin.

== Description ==

Create and manage pretty, dynamic quizzes using the SlickQuiz jQuery plugin.

Managing and creating new quizzes is simple and intuitive.

* NEW: Saved user scores! (must be enabled in the options)
* Quiz questions can have single or multiple correct responses.
* Correct and Incorrect response messages.
* Multiple correct / incorrect response message display formats.
* Results will include a score and ranking (example rankings: Savant, Genius, Pretty Smart, Not Smart, etc.)
* Unlimited questions, unlimited answers
* Make changes to your quiz without having to publish them immediately.
* Customize error messages for missing or unpublished quizzes.
* Customize the quiz start button text, as well as score and ranking text.
* Randomly sort questions and answers
* Load a set number of questions from a larger group
* Prevent submitting questions without answers
* Multiple quizzes on the same page
* Easily share results with Twitter and Facebook sharing buttons

NOTE: If the Preview window does not appear from the create / edit page, *you may need to allow popups for your domain.*

NOTE: Do not place the same quiz on the page multiple times, things will break.

Created by [Julie Cameron](http://juliecameron.com/) while previously employed at [Quicken Loans](http://quickenloans.com), Detroit, MI

Based off the [SlickQuiz jQuery plugin](https://github.com/QuickenLoans/SlickQuiz).

The SlickQuiz WordPress Plugin is now opensource - contribute on [Github](https://github.com/QuickenLoans/SlickQuiz-WordPress)

== Installation ==

1. Upload the SlickQuiz plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create and publish a quiz through the SlickQuiz Management interface. (You may need to allow popups to see the SlickQuiz Preview window.)
1. Place [slickquiz id=X] in your templates, where X is the ID of the quiz you created.
1. To use the [slickquiz] shortcode in the sidebar Text widget, add add_filter( 'widget_text', 'do_shortcode' ) to your theme.

== Frequently Asked Questions ==

= Is there a way to save or email user scores and answers? =

YES! As of version 1.1.0, you can now enable the saving of user scores! Visit the SlickQuiz Options page to turn saving on as well as to customize the user name input label.

Score saving is still in it's infancy, so I'd love your feedback on how it works and what features you'd like to see!

= Is there any social integration? Twitter? Facebook? =

YES! As of version 1.1.4, you can now enable sharing buttons for Twitter and Facebook.  There is also an option to customize the Twitter share message.

== Screenshots ==

1. The quiz management / listing interface
2. Creating a quiz
3. Adding quiz questions
4. A quiz embedded in a post - your styles will vary depending on your theme and preferences.
5. The plugin options allow you to alter messages and quiz features.
6. When user score saving is enabled, the user will be prompted for their name before starting the quiz.
7. The listing of user scores when saving is enabled.

== Changelog ==

= 1.2.1 =
* Bug fix: changed PHP short tag to long form which would break things on servers without short tags enabled

= 1.2.0 =
* NEW Top 10 Scores widget!
* Automatically save user names for logged in users if score saving is enabled
* Updating the SlickQuiz jQuery plugin

= 1.1.8 =
* Added "Skip Start Button" option (loads first question instead of start button)
* Added "Number of Questions" option (loads the specified number of questions, instead of all questions)

= 1.1.7 =
* Added "Try Again" option to end of quiz (resets and restarts quiz)
* Bug fixes, code cleanup - YAY!

= 1.1.6 =
* Updating the SlickQuiz jQuery plugin to commit d9213bf14be5ac216e8d622bc8a5c9376e035782
* Resolved bug with HTML entities and tags in answers
* Resolved bug with ranking level calculation

= 1.1.5 =
* Updating the SlickQuiz jQuery plugin to commit 661e8fcd9d5fe47bf4d28cb4080ae9a724827bc3
* Resolved mobile bug where buttons weren't clickable
* Resolved "Quiz not found" warning bug

= 1.1.4 =
* Added optional Twitter and Facebook sharing buttons to quiz results
* Changed Question input to a textarea for easier form editing

= 1.1.3 =
* Tweaking user permissions - Admins can do everything; Editors can do everything EXCEPT manage SlickQuiz Options; Authors can view the quiz list, scores and previews.
* SlickQuiz is now opensource - contribute on [Github](https://github.com/QuickenLoans/SlickQuiz-WordPress)!

= 1.1.2 =
* Bug fix: resolved issue with quizzes not working when they are the only thing in the post / page

= 1.1.1 =
* Bug fix: resolved issue with user scores not saving for logged out users

= 1.1.0 =
* Added NEW option to enable the saving of user scores!!
* Added NEW option to customizable the user's name label when score saving is enabled

= 1.0.19 =
* Bug fix: resolved issue with fuzzy URL matching in the admin interface

= 1.0.18 =
* Bug fix: resolved issue with Safari not being able to open the quiz preview pane

= 1.0.17 =
* Added NEW option to hide all correct and incorrect response messages until the quiz is completed
* Added NEW option to hide all correct and incorrect response messages entirely
* Added class to quizLevel header for easier styling
* Bug fixes: variable declarations for IE, jQuery Mobile compatibility

= 1.0.16 =
* Adding random sort ONLY questions / ONLY answers options
* Resolved styling issue with answer labels
* Resolved a secret BUG that prevented multiple quizzes on the same page

= 1.0.15 =
* Cleaning up options page
* Added option to prevent submitting a question with no answers selected

= 1.0.14 =
* Resolving issue where IE thought every answer was wrong (BUG FIX!)

= 1.0.13 =
* Added hasOwnProperty method to jQuery for...in loops to resolve issues with prototype modifications (BUG FIX!)

= 1.0.12 =
* Adjusted selectors to work with themes and plugins that modify the quiz layout

= 1.0.1 =
* Updating the SlickQuiz jQuery plugin to commit 93a16427269df6f80215b02b44a9a1ddfd1d94b8.
* Includes: Optional "Back" buttons
* Includes: Optional randomly sorted questions and answers
* Includes: Easier answer selection (you can now click the text to select the checkbox / radio button)
* Bug fixes!

= 1.0 =
This is the initial setup of the plugin.

== Upgrade Notice ==

= 1.2.1 =
* Bug fix: changed PHP short tag to long form which would break things on servers without short tags enabled

= 1.2.0 =
* NEW Top 10 Scores widget!
* Automatically save user names for logged in users if score saving is enabled
* Updating the SlickQuiz jQuery plugin

= 1.1.8 =
* Added "Skip Start Button" option (loads first question instead of start button)
* Added "Number of Questions" option (loads the specified number of questions, instead of all questions)

= 1.1.7 =
* Added "Try Again" option to end of quiz (resets and restarts quiz)
* Bug fixes, code cleanup - YAY!

= 1.1.6 =
* Updating the SlickQuiz jQuery plugin to commit d9213bf14be5ac216e8d622bc8a5c9376e035782
* Resolved bug with HTML entities and tags in answers
* Resolved bug with ranking level calculation

= 1.1.5 =
* Updating the SlickQuiz jQuery plugin to commit 661e8fcd9d5fe47bf4d28cb4080ae9a724827bc3
* Resolved mobile bug where buttons weren't clickable
* Resolved "Quiz not found" warning bug

= 1.1.4 =
* Added optional Twitter and Facebook sharing buttons to quiz results
* Changed Question input to a textarea for easier form editing

= 1.1.3 =
* Tweaking user permissions - Admins can do everything; Editors can do everything EXCEPT manage SlickQuiz Options; Authors can view the quiz list, scores and previews.
* SlickQuiz is now opensource - contribute on [Github](https://github.com/QuickenLoans/SlickQuiz-WordPress)!

= 1.1.2 =
* Bug fix: resolved issue with quizzes not working when they are the only thing in the post / page

= 1.1.1 =
* Bug fix: resolved issue with user scores not saving for logged out users

= 1.1.0 =
* Added NEW option to enable the saving of user scores!!
* Added NEW option to customizable the user's name label when score saving is enabled

= 1.0.19 =
* Bug fix: resolved issue with fuzzy URL matching in the admin interface

= 1.0.18 =
* Bug fix: resolved issue with Safari not being able to open the quiz preview pane

= 1.0.17 =
* Added NEW option to hide all correct and incorrect response messages until the quiz is completed
* Added NEW option to hide all correct and incorrect response messages entirely
* Added class to quizLevel header for easier styling
* Bug fixes: variable declarations for IE, jQuery Mobile compatibility

= 1.0.16 =
* Adding random sort ONLY questions / ONLY answers options
* Resolved styling issue with answer labels
* Resolved a secret BUG that prevented multiple quizzes on the same page

= 1.0.15 =
* Cleaning up options page
* Added option to prevent submitting a question with no answers selected

= 1.0.14 =
* Resolving issue where IE thought every answer was wrong (BUG FIX!)

= 1.0.13 =
* Added hasOwnProperty method to jQuery for...in loops to resolve issues with prototype modifications (BUG FIX!)

= 1.0.12 =
* Adjusted selectors to work with themes and plugins that modify the quiz layout

= 1.0.1 =
Updating the SlickQuiz jQuery plugin. New back button and random sorting options. Bug fixes!

= 1.0 =
This is the first version of the plugin
