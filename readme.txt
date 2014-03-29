=== SlickQuiz ===
Contributors: jewlofthelotus
Donate link: http://www.gofundme.com/slickquiz
Tags: quiz, test, jquery, javascript, education, elearning, generator, manager, question, answer, score, rank
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.2.374
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

SlickQuiz is a plugin for displaying and managing pretty, dynamic quizzes. It uses the SlickQuiz jQuery plugin.


== Description ==

**Create and manage pretty, dynamic quizzes** using the SlickQuiz jQuery plugin.

Managing and creating new quizzes is simple and intuitive.

* **Unlimited** questions, unlimited answers.
* **Save** user scores (must be enabled in the options).
* **Share** results via Twitter and Facebook sharing buttons.

More Features:

* Questions can have single or multiple correct responses.
* Answers have correct and incorrect response messaging.
* Show correct / incorrect response message after each question and / or at the end of the quiz.
* End results include a score (8/10) and customizable ranking (ex. Super Genius).
* Quiz changes can be saved to a draft.
* Randomly sort questions and answers.
* Customize button text, as well as score and ranking text.
* Customize error messages for removed or unpublished quizzes.
* Load a set number of questions from a larger group.
* Prevent submitting questions without answers.
* Allows multiple quizzes on the same page.
* Save user emails along with quiz scores.

Created by [Julie Cameron](http://juliecameron.com/). Based off the [SlickQuiz jQuery plugin](https://github.com/JewlOfTheLotus/SlickQuiz).

The SlickQuiz WordPress Plugin is now open source - contribute on [Github](https://github.com/JewlOfTheLotus/SlickQuiz-WordPress)


== Installation ==

1. Install the SlickQuiz plugin directly from the WordPress plugin directory. Or upload the SlickQuiz plugin to your `/wp-content/plugins/` directory.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. Create / publish quizzes via the "SlickQuizzes" option in the WordPress sidebar.
1. To add a quiz to a post or page, place `[slickquiz id=X]` the content area, where `X` is the ID of the quiz you created. The ID can be found in the main SlickQuiz listing.

= Dynamic URL Shortcode Setup =

You may also dynamically render a quiz by setting the shortcode to `[slickquiz id=url]`.  This will tell the plugin to look for an ID at **the end of the page URL** and select the quiz with that ID. Note: additional query string parameters will not interfere.

= Text Widget Setup =

To use the `[slickquiz id=X]` shortcode in the sidebar Text widget, add the following to yours theme's `functions.php` file.

`add_filter( 'widget_text', 'do_shortcode' )`

= Developer Hooks =

There are currently three filter actions that you may hook into:

`slickquiz_admin_options` - This allows you to add custom admin options.

`slickquiz_after_options` - This allows you to add custom markup to the bottom of SlickQuiz Options form (you would likely add data to your custom `slickquiz_admin_options` here).

`slickquiz_after_result` - This allows you to add custom markup to the bottom of the quiz results area at the end of the quiz (you would likely output data from your custom `slickquiz_admin_options` here).

For an example of how to utilize these hooks, see this
[gist](https://gist.github.com/jewlofthelotus/9022902).


== Frequently Asked Questions ==

= Why can't I see the quiz Preview? =

The quiz Preview opens in a popup window. You may need to allow popups for the domain in your browser. Check the URL bar for a popup-blocked indicator and click it for more information.

= Why isn't the quiz showing up on my page? OR why is the quiz broken? =

There are a lot of reasons this could be happening - usually there is an issue with the theme or a conflict with another plugin. Here are a few things to look for:

* Make sure your theme's `footer.php` template contains the following code snippet - this ensures that plugins are able to add necessary code to the page.

`<?php wp_footer(); ?>`

* Check your browser's Development Console for errors. Click [here](http://webmasters.stackexchange.com/questions/8525/how-to-open-the-javascript-console-in-different-browsers) for instructions on how to find to this panel in your browser (Note: if you're on a Mac, use the `Command ⌘` key in place of `CTRL`). Once you've got it open, look for anything in red - it will all look pretty foreign, but if you see anything in red, scan the text for keywords that might indicate the plugin the error is coming from. Try disabling those plugins and then see if the quiz loads.
* Still having trouble? Create a new [support ticket](http://wordpress.org/support/plugin/slickquiz/) with the details of your issue.

= Can I add pictures to my questions / answers / responses? =

Yes, you can place any HTML tags that you like within any of the content fields. For images, get the URL of the image you want to add, and use something like the following to add the image to a content area:

`<img src="/uploads/2014/02/my_photo.jpg">`

= Is there a way to email user scores and answers? =

Yes, if you "Enable sharing buttons" from the SlickQuiz Options page, your users will have a button to email their results at the end of the quiz.  Currently, this feature does not capture all answers - only the user's overall score and ranking.

= Can I put the same quiz on the same page multiple times? =

Nope, things will break.  This might happen if you place the same quiz within multiple blog posts and more than one of those posts is displayed on the page.

= I've got an idea for a feature or have a bug to report, what should I do? =

Checkout the [SlickQuiz WordPress Support forum](http://wordpress.org/support/plugin/slickquiz/) to see if someone else has experienced your issue, the answer might already be there; if not - please create a new support ticket!

Also, see the [SlickQuiz Issues](https://github.com/jewlofthelotus/SlickQuiz-WordPress/issues) page on Github for a complete list of upcoming features and bug fixes, and feel free to add your own ideas!


== Screenshots ==

1. The quiz management / listing interface.
2. Creating a quiz.
3. Adding quiz questions.
4. A quiz embedded in a post - your styles will vary depending on your theme and preferences.
5. The plugin options allow you to alter messages and quiz features.
6. When user score saving is enabled, the user will be prompted for their name before starting the quiz, unless they're already logged in.
7. The listing of user scores when saving is enabled.


== Changelog ==

= 1.2.374 =
* Thanks to [@nfreear](https://github.com/nfreear) - you can now tell SlickQuiz to dynamically render a quiz based off an ID in the URL via `[slickquiz id=url]`

= 1.2.373 =
* BUG FIX: Some users experienced issues with the Score Widget not sorting the rankings properly. This resolves that issue. Thanks to [@Ciao121](http://wordpress.org/support/profile/ciao121) for suggesting a fix!
* Updating SlickQuiz jQuery lib

= 1.2.372 =
* BUG FIX: Some users experienced issues with the Score and Ranking values missing form share verbiage. This resolves that issue.

= 1.2.371 =
* BUG FIX: Some users experienced issues with the "Start" button not enabling after entering their name in the input for storage. This resolves that issue.

= 1.2.37 =
* Added the ability to delete saved scores.
* NEW option to save email addresses with scores.

= 1.2.36 =
* Improved sharing buttons - now more stylistically customizable, facebook includes score and quiz information
* "Email" button has been added to sharing buttons!
* New developer hooks! See [Installation](http://wordpress.org/plugins/slickquiz/installation/) for more details. Thanks to [@phh](https://github.com/phh) for the contribution!
* Increasing admin style specificity to avoid clashing with WP and other plugins
* Standardized notification message styles

= 1.2.35 =
* Fixes readme syntax issues
* Bug fix: resolved escaped apostrophe issue in option page inputs

= 1.2.34 =
* Added Share button to Facebook Like button
* REMOVES single option to randomly sort questions and answers, instead you should use the individual "randomly sort questions" and "randomly sort answers" options.
* Made "Back" button styles less prominent to avoid confusion
* Added some cursor styles to checkboxes, radios, and labels to make them more user friendly
* Bug fix: unpublishing works again!
* Updated readme / FAQ documentation

= 1.2.33 =
* Made the "Preview" step optional when saving quizzes
* Added separate "Save Draft" and "Publish" options to simplify publishing

= 1.2.32 =
* Separate options to display per question response messages (enabled by default) and completion response messaging
* REMOVES single option to disable response messages entirely, instead you should disable both per question and completion response message options
* Added new option for questions with multiple answers - considers the selection of any single correct answer from the set of correct answers a valid response
* Attempts to eliminate some unnecessary styles that might conflict with your theme
* Adds some helpful form input descriptions
* Updated plugin screenshots
* Bug fix: prevents critical buttons from disappearing in preview window when certain quiz settings are enabled

= 1.2.21 =
* Bug fix: start button wasn't getting enabled due to an order of operations issue

= 1.2.2 =
* Bug fix: adding disabled button check to on quizStarter.on() event

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

= 1.2.374 =
* Thanks to [@nfreear](https://github.com/nfreear) - you can now tell SlickQuiz to dynamically render a quiz based off an ID in the URL via `[slickquiz id=url]`

= 1.2.373 =
* BUG FIX: Some users experienced issues with the Score Widget not sorting the rankings properly. This resolves that issue. Thanks to [@Ciao121](http://wordpress.org/support/profile/ciao121) for suggesting a fix!
* Updating SlickQuiz jQuery lib

= 1.2.372 =
* BUG FIX: Some users experienced issues with the Score and Ranking values missing form share verbiage. This resolves that issue.

= 1.2.371 =
* BUG FIX: Some users experienced issues with the "Start" button not enabling after entering their name in the input for storage. This resolves that issue.

= 1.2.37 =
* Added the ability to delete saved scores.
* NEW option to save email addresses with scores.

= 1.2.36 =
* Improved sharing buttons - now more stylistically customizable, facebook includes score and quiz information
* "Email" button has been added to sharing buttons!
* New developer hooks! See [Installation](http://wordpress.org/plugins/slickquiz/installation/) for more details. Thanks to [@phh](https://github.com/phh) for the contribution!
* Increasing admin style specificity to avoid clashing with WP and other plugins
* Standardized notification message styles

= 1.2.35 =
* Fixes readme syntax issues
* Bug fix: resolved escaped apostrophe issue in option page inputs

= 1.2.34 =
* Added Share button to Facebook Like button
* REMOVES single option to randomly sort questions and answers, instead you should use the individual "randomly sort questions" and "randomly sort answers" options.
* Made "Back" button styles less prominent to avoid confusion
* Added some cursor styles to checkboxes, radios, and labels to make them more user friendly
* Bug fix: unpublishing works again!
* Updated readme / FAQ documentation

= 1.2.33 =
* Made the "Preview" step optional when saving quizzes
* Added separate "Save Draft" and "Publish" options to simplify publishing

= 1.2.32 =
* Separate options to display per question response messages (enabled by default) and completion response messaging
* REMOVES single option to disable response messages entirely, instead you should disable both per question and completion response message options
* Added new option for questions with multiple answers - considers the selection of any single correct answer from the set of correct answers a valid response
* Attempts to eliminate some unnecessary styles that might conflict with your theme
* Adds some helpful form input descriptions
* Updated plugin screenshots
* Bug fix: prevents critical buttons from disappearing in preview window when certain quiz settings are enabled

= 1.2.21 =
* Bug fix: start button wasn't getting enabled due to an order of operations issue

= 1.2.2 =
* Bug fix: adding disabled button check to on quizStarter.on() event

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
