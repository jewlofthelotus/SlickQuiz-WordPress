// Mortgage Knowledge Quiz
jQuery(document).ready(function($) {

    var adminPath = location.pathname.replace(/wp-admin.*/, 'wp-admin/');
    var imagePath = location.pathname.replace(/wp-admin.*/, 'wp-content/plugins/slickquiz/images/');

    // Setup Quiz Form
    $.setupQuizForm = function(element, options) {
        var $element = $(element),
             element = element;

        var plugin = this;
        plugin.config = {}
        plugin.config = $.extend(plugin.config, options);

        // If editing a quiz, quizJSON will exist
        var quizValues = (typeof quizJSON != 'undefined' ? quizJSON : null);

        var defaults = {
            quizArea:           'div.quizFormWrapper',
            quizForm:           'form.quizForm',
            bottomButtons:      'div.bottom_button_bar',
            addQuestion:        'a.addQuestion',
            addAnswer:          'a.addAnswer',
            removeQuestion:     'a.removeQuestion',
            removeAnswer:       'a.removeAnswer',
            moveQuestionSet:    'a.moveQuestionSet',
            toggleQuestionSet:  'a.toggleQuestionSet',
            toggleQuestionSets: 'a.toggleQuestionSets',
            requiredString:     '<img alt="*" height="16" src="' + imagePath + 'required.png" width="16"> ',
            fields:             [
                {
                    "q":          "Quiz Name",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "name"
                },
                {
                    "q":          "Main Copy",
                    "type":       "textarea",
                    "required":   false,
                    "jsonName":   "main"
                },
                {
                    "q":          "Result Copy",
                    "type":       "textarea",
                    "required":   false,
                    "jsonName":   "results"
                },
                {
                    "label":      "Knowledge Levels <small>(1 = Best, 5 = Worst)</small>",
                    "q":          "Level 1",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level1"
                },
                {
                    "q":          "Level 2",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level2"
                },
                {
                    "q":          "Level 3",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level3"
                },
                {
                    "q":          "Level 4",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level4"
                },
                {
                    "q":          "Level 5",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level5"
                }
            ]
        }

        plugin.formBuilder = {
            // Sets up the quiz fields and delegate listeners
            setup: function() {
                // Setup Form Element
                quizForm = $('<form class="quizForm"></form>');

                // Setup Base Form Fieldset
                fieldset = $('<fieldset id="quizFields"><legend>Quiz Content</legend></fieldset>');
                fieldset = plugin.formBuilder.addDefaultFields(fieldset);
                fieldset.append(plugin.formBuilder.addToggles('top'));
                fieldset.append(plugin.formBuilder.addAddQuestionLink());
                fieldset.append(plugin.formBuilder.addToggles('bottom'));
                quizForm.append(fieldset);

                // Add Bottom Button Controls to the form
                quizForm.append($(defaults.bottomButtons));

                // Add the form to the page
                $(defaults.quizArea).append(quizForm);

                // Add question / answer fields to the form
                // NOTE: must happen after form is added to the page so that jquery can locate the addQuestion link
                plugin.formBuilder.addQuestionFields();

                // Delegate form buttons
                plugin.formListener.setup();
            },

            // Add default Fields to Fieldset
            addDefaultFields: function(fieldset) {
                for (f in defaults.fields) {
                    // get field info - if quizJSON exists, use quizJSON data
                    field     = defaults.fields[f];
                    inputName = field.q.replace(/\W/g,'');
                    required  = field.required ? defaults.requiredString : '';
                    nameAndId = 'name="' + inputName + '" id="' + inputName + '"';

                    if (quizValues != null) {
                        value = plugin.formHelper.htmlspecialchars(quizValues.info[field.jsonName]);
                    } else {
                        value = '';
                    }

                    // Setup Field Container
                    defaultQuestionHTML = $('<div class="question ' + inputName + '"></div>');

                    // Add Input Group Label (e.g. "Knowledge Levels")
                    if (field.label) {
                        defaultQuestionHTML.append('<label class="main">' + field.label + '</label>');
                    }

                    // Add Input Label
                    defaultQuestionHTML.append('<label>' + required + field.q + '</label> ');

                    // Add Field
                    if (field.type == 'text') {
                        defaultQuestionHTML.append('<input type="text" ' + nameAndId + ' value="' + value + '" />');
                    } else if (field.type == 'textarea') {
                        defaultQuestionHTML.append('<textarea ' + nameAndId + '>' + value + '</textarea>');
                    }

                    // Add Field to the Fieldset
                    fieldset.append(defaultQuestionHTML);
                };

                return fieldset;
            },

            // Add question/answer fields to the form
            addQuestionFields: function() {
                if (quizValues != null) {
                    for (f in quizValues.questions) {
                        plugin.formBuilder.addQuestion(quizValues.questions[f]);
                    }
                } else { // Add blank question to NEW quiz form
                    plugin.formBuilder.addQuestion();
                }
            },

            // Adds a question to the quiz
            addQuestion: function(fieldGroup) {
                newQuestionSetHTML = $('<div class="questionSet"><div class="questionSetOptions"><a href="#toggleQuestion" class="toggleQuestionSet expand" title="Expand Question"><img alt="Expand Question" height="16" src="' + imagePath + 'expand.png" width="16"></a> &nbsp;&nbsp; <a href="#toggleQuestion" class="toggleQuestionSet collapse" title="Collapse Question"><img alt="Collapse Question" height="16" src="' + imagePath + 'minimize.png" width="16"></a> &nbsp;&nbsp; <a href="#moveQuestion" class="moveQuestionSet up" title="Move Up"><img alt="Move Up" height="16" src="' + imagePath + 'green_arrow_up.png" width="16"></a> &nbsp;&nbsp; <a href="#moveQuestion" class="moveQuestionSet down" title="Move Down"><img alt="Move Down" height="16" src="' + imagePath + 'green_arrow_down.png" width="16"></a> &nbsp;&nbsp; <a href="#removeQuestion" class="removeQuestion" title="Remove Question"><img alt="Remove Question" height="16" src="' + imagePath + 'remove.png" width="16"></a></div></div>');

                newQuestionHTML = $('<div class="question actual"></div>');
                newQuestionHTML.append('<label>' + defaults.requiredString + ' Question</label> ');
                newQuestionHTML.append('<input type="text" name="question" value="' + (fieldGroup ? plugin.formHelper.htmlspecialchars(fieldGroup.q) : '') + '" />');

                newQuestionCorrectHTML = $('<div class="question correct"></div>');
                newQuestionCorrectHTML.append('<label>' + defaults.requiredString + ' Correct Response Message</label> ');
                newQuestionCorrectHTML.append('<textarea name="correct">' + (fieldGroup ? fieldGroup.correct : '') + '</textarea>');

                newQuestionIncorrectHTML = $('<div class="question incorrect"></div>');
                newQuestionIncorrectHTML.append('<label>' + defaults.requiredString + ' Incorrect Response Message</label> ');
                newQuestionIncorrectHTML.append('<textarea name="incorrect">' + (fieldGroup ? fieldGroup.incorrect : '') + '</textarea>');

                newAnswerHTML = $('<p class="addAnswer"><a href="#addAnswer" class="addAnswer"><img alt="*" height="16" src="' + imagePath + 'new.png" width="16"> Add Answer</a></p>');

                newQuestionSetHTML.append(newQuestionHTML);
                newQuestionSetHTML.append(newQuestionCorrectHTML);
                newQuestionSetHTML.append(newQuestionIncorrectHTML);
                newQuestionSetHTML.append(newAnswerHTML);

                newQuestionSetHTML.hide();

                $('p.addQuestion').before(newQuestionSetHTML);

                // Add answer fields to the form (if fieldGroup passed in)
                if (fieldGroup != null) { // Add existing answer to quiz question
                    for (f in fieldGroup.a) {
                        plugin.formBuilder.addAnswer(newAnswerHTML, fieldGroup.a[f]);
                    }
                } else { // Add blank answers to NEW quiz form question
                    plugin.formBuilder.addAnswer(newAnswerHTML.children('a')[0]);
                    plugin.formBuilder.addAnswer(newAnswerHTML.children('a')[0]);
                }

                newQuestionSetHTML.fadeIn(800);
            },

            // Adds an answer to the selected question
            addAnswer: function(element, fieldGroup) {
                addAnswerLink = fieldGroup ? $(element) : $(element).parent();

                var newAnswerHTML = '<div class="question answer">'
                    + '<label>' + defaults.requiredString + ' Answer</label> '
                    + '<input type="text" name="answer" value="' + (fieldGroup ? plugin.formHelper.htmlspecialchars(fieldGroup.option) : '') + '" />'
                    + '&nbsp; <label class="correctAnswer">Correct Answer?</label> '
                    + '<input type="checkbox" name="correct_answer"' + (fieldGroup && fieldGroup.correct ? ' checked="checked"' : '') + ' />'
                    + '&nbsp; <a href="#removeAnswer" class="removeAnswer" title="Remove Answer">'
                    + '<img alt="Remove Answer" height="16" src="' + imagePath + 'remove.png" width="16"></a>'
                    + '</div>';

                addAnswerLink.before($(newAnswerHTML).hide().fadeIn(800));
            },

            // Return toggle elements
            addToggles: function(position) {
                toggles = '<a href="#toggleQuestionSets" class="toggleQuestionSets expand">'
                    + '<img alt="expand" height="12" src="' + imagePath + 'expand.png" width="12">'
                    + ' Expand All</a> &nbsp; '
                    + '<a href="#toggleQuestionSets" class="toggleQuestionSets collapse">'
                    + '<img alt="collapse" height="12" src="' + imagePath + 'minimize.png" width="12">'
                    + ' Collapse All</a>';

                return '<p class="toggleSets ' + position + '">' + toggles + '</p>';
            },

            // Return Add Question link
            addAddQuestionLink: function() {
                linkStr = '<p class="addQuestion">'
                    + '<a href="#addQuestion" class="addQuestion">'
                    + '<img alt="*" height="16" src="' + imagePath + 'new.png" width="16">'
                    + ' Add Question</a></p>'

                return linkStr;
            }
        }

        plugin.formListener = {
            // Delegate form buttons
            setup: function() {
                // Delegate "add question" link
                $("#quizFields").delegate(defaults.addQuestion, "click", function(){
                    plugin.formBuilder.addQuestion();
                });

                // Delegate "add answer" link
                $("#quizFields").delegate(defaults.addAnswer, "click", function(){
                    plugin.formBuilder.addAnswer(this);
                });

                // Delegate "remove answer" link
                $("#quizFields").delegate(defaults.removeAnswer, "click", function(){
                    plugin.formListener.removeAnswer(this);
                });

                // Delegate "remove question" link
                $("#quizFields").delegate(defaults.removeQuestion, "click", function(){
                    plugin.formListener.removeQuestion(this);
                });

                // Delegate "move up / down" links
                $("#quizFields").delegate(defaults.moveQuestionSet, "click", function(){
                    plugin.formListener.moveQuestionSet(this);
                });

                // Delegate "expand / collapse" question links
                $("#quizFields").delegate(defaults.toggleQuestionSet, "click", function(){
                    plugin.formListener.toggleQuestionSet(this);
                });

                // Delegate "expand / collapse" all question links
                $(".toggleSets").delegate(defaults.toggleQuestionSets, "click", function(){
                    plugin.formListener.toggleAllQuestionSets(this);
                });

                // Delegate "Preview" button
                $(".bottom_button_bar").delegate('.preview', "click", function(e){
                    e.preventDefault();
                    plugin.formListener.preview(this);
                });

                // Delegate "Revert" button
                $(".top_button_bar").delegate('.revert', "click", function(e){
                    e.preventDefault();
                    plugin.formListener.revert(this);
                });
            },

            // Removes a question
            removeQuestion: function(element) {
                currentQuestionSet = $(element).parents('.questionSet');

                if (confirm('Are you sure you want to remove this question and all its answers?')) {
                    currentQuestionSet.fadeOut(1050, function() {
                        $(this).remove();
                    });
                }
            },

            // Removes an answer
            removeAnswer: function(element) {
                currentAnswerSet = $(element).parents('.answer');

                if (confirm('Are you sure you want to remove this answer?')) {
                    currentAnswerSet.fadeOut(1050, function() {
                        $(this).remove();
                    });
                }
            },

            // Shift question set up / down
            moveQuestionSet: function(element) {
                currentQuestionSet = $(element).parents('.questionSet');

                if ($(element).hasClass('up')) {
                    prevQuestionSet = currentQuestionSet.prev('.questionSet');

                    if (prevQuestionSet.length > 0) {
                        prevQuestionSet.before(currentQuestionSet);
                    } else {
                        alert('This question is already at the top.');
                    }
                } else {
                    nextQuestionSet = currentQuestionSet.next('.questionSet');

                    if (nextQuestionSet.length > 0) {
                        nextQuestionSet.after(currentQuestionSet);
                    } else {
                        alert('This question is already at the bottom.');
                    }
                }
            },

            // Expand / Collapse question set
            toggleQuestionSet: function(element) {
                currentQuestionSet = $(element).parents('.questionSet');

                if ($(element).hasClass('expand')) {
                    currentQuestionSet.children(':gt(1)').slideDown();
                } else {
                    currentQuestionSet.children(':gt(1)').slideUp();
                }
            },

            // Expand / Collapse ALL question sets
            toggleAllQuestionSets: function(element) {
                questionSetToggles = $('.questionSet .' + ($(element).hasClass('expand') ? 'expand' : 'collapse'));

                questionSetToggles.each(function(i, toggle){
                    plugin.formListener.toggleQuestionSet(toggle);
                });
            },

            // Save working copy of quiz and preview it in new window
            preview: function(element) {
                var formValues = plugin.formHelper.getValidQuizJson();

                if (!formValues) {
                    alert('There were a few errors with your submission. Please fix them and try again.');
                    return false;
                }

                formJSON = JSON.stringify(formValues);
                pubJSON  = JSON.stringify(quizValues);

                if ($('.revert').length == 0 && $('.notPublished').length == 0 && formJSON == pubJSON) {
                    changeStr = 'You have not made any changes to this quiz.\n'
                        + 'You must modify the quiz before you can preview the changes.\n\n'
                        + 'If you\'d like to preview the published version of this quiz,'
                        + ' you may do so from the main quiz listing.';

                    alert(changeStr);
                    return false;
                }

                var location = window.location.pathname + window.location.search;
                var saveUrl  = location.replace('admin.php', 'admin-ajax.php');

                // Save working copy and open preview pane
                $.ajax({
                    type:     'POST',
                    url:      saveUrl,
                    data:     {
                                action: location.match('slickquiz-new') ? 'create_quiz' : 'update_quiz',
                                json: formJSON
                              },
                    dataType: 'text',
                    async:    false, // for Safari
                    success:  function(data) {
                        if (location.match('slickquiz-new')) {
                            window.location = location.replace('slickquiz-new', 'slickquiz-edit') + '&id=' + data;
                            var previewUrl  = location.replace('slickquiz-new', 'slickquiz-preview') + '&id=' + data;
                        } else {
                            window.location.reload();
                            var previewUrl  = location.replace('slickquiz-edit', 'slickquiz-preview');
                        }
                        window.open(previewUrl, 'quizPreview', 'resizable=1,width=900,height=700,scrollbars=1');
                    }
                });
            },

            // Revert working copy of quiz and to published copy
            revert: function(element) {
                if (confirm('Are you sure you want to revert your unpublished changes?')) {
                    revertUrl = (window.location.pathname + window.location.search).replace('admin.php', 'admin-ajax.php');

                    $.ajax({
                        type:     'POST',
                        url:      revertUrl,
                        data:     {action: 'revert_quiz'},
                        success:  function(data) {
                            window.location.reload();
                        }
                    });
                }
            }
        }

        plugin.formValidator = {
            // Validate presence of text
            required: function(input, message, errorAppend) {
                if (input.attr('value') == '') {
                    input.addClass('error');
                    if (input.siblings('.error').length == 0) {
                        error = '<p class="error">Please enter ' + message + '.</p>';
                        if (errorAppend) {
                            $(errorAppend).append(error)
                        } else {
                            $(input).after(error);
                        }
                    }
                    return false;
                } else {
                    input.removeClass('error');
                    input.siblings('.error').remove();
                }
                return true;
            },

            // Validate slug formatting
            slug: function(input, message) {
                if (input.attr('value').match(/^[a-z0-9_-]*$/)) {
                    input.removeClass('error');
                    input.siblings('.error').remove();
                    return true;
                } else {
                    input.addClass('error');
                    input.after('<p class="error">Please enter a valid ' + message + ' (letters, numbers, -, _).</p>');
                    return false;
                }
            },

            // Validate presence of at least 1 question
            numberOfQuestions: function(questions) {
                if (questions.length == 0) {
                    $('#quizFields').after('<p class="error oneQuestion">You must enter at least one quiz question.</p>')
                    return false;
                }
                return true;
            },

            // Validate presence of at least two answer options
            numberOfAnswers: function(answers, questionSet) {
                if (answers.length <= 1) {
                    if ($(questionSet).children('.twoAnswers').length == 0) {
                        $(questionSet).append('<p class="error twoAnswers">You must have at least two answers per question.</p>')
                    }
                    return false;
                } else {
                    $(questionSet).children('.twoAnswers').remove();
                }
                return true;
            },

            // Validate presence of at least one correct answer
            numberOfCorrectAnswers: function(correctAnswers, questionSet) {
                if (!correctAnswers) {
                    if ($(questionSet).children('.correctAnswer').length == 0) {
                        $(questionSet).append('<p class="error correctAnswer">You must have at least one correct answer per question.</p>')
                    }
                    return false;
                } else {
                    $(questionSet).children('.correctAnswer').remove();
                }
                return true;
            }
        }

        plugin.formHelper = {
            // Gather all form data into a JSON object, validate while we go
            getValidQuizJson: function() {
                var quizJson  = {"info": {}, "questions": []};
                var fields    = defaults.fields;
                var questions = $('.questionSet');
                var valid     = true;

                // Get default question responses
                for (f in fields) {
                    field = defaults.fields[f];
                    input = $('#' + field.q.replace(/\W/g,''));

                    if (field.required && !plugin.formValidator.required(input, field.q)) {
                        valid = false;
                    } else {
                        if (field.jsonName == 'slug' && !plugin.formValidator.slug(input)) {
                            valid = false;
                        } else {
                            index = field.jsonName;
                            value = input.attr('value');
                            quizJson["info"][index] = value;
                        }
                    }
                }

                if (!plugin.formValidator.numberOfQuestions(questions)) {
                    valid = false;
                }

                // Get question and answer responses
                questions.each(function(i, questionSet) {
                    questionInput     = $($(questionSet).children('.actual').children('input')[0]);
                    correctResponse   = $($(questionSet).children('.correct').children('textarea')[0]);
                    incorrectResponse = $($(questionSet).children('.incorrect').children('textarea')[0]);
                    answers           = $($(questionSet).children('.answer'));
                    question          = {"a": []};
                    correctAnswers    = false;

                    answers.each(function(i, answerSet) {
                        option  = $($(answerSet).children('input[type="text"]')[0]);
                        correct = $($(answerSet).children('input[type="checkbox"]')[0]).attr('checked');

                        if (!plugin.formValidator.required(option, 'an answer', answerSet)) {
                            valid = false;
                        } else {
                            answer = {
                                "option":  option.attr('value'),
                                "correct": correct
                            };
                            question["a"].push(answer);
                        }

                        if (correct) {
                            correctAnswers = true;
                        }
                    });

                    questionValidations = [
                        plugin.formValidator.required(questionInput, 'a question'),
                        plugin.formValidator.required(correctResponse, 'a correct response message'),
                        plugin.formValidator.required(incorrectResponse, 'an incorrect response message'),
                        plugin.formValidator.numberOfAnswers(answers, questionSet),
                        plugin.formValidator.numberOfCorrectAnswers(correctAnswers, questionSet)
                    ];

                    if ($.inArray(false, questionValidations) > -1) {
                        valid = false;
                    } else {
                        question["q"]         = questionInput.attr('value'),
                        question["correct"]   = correctResponse.attr('value'),
                        question["incorrect"] = incorrectResponse.attr('value'),
                        quizJson["questions"].push(question);
                    }
                });

                return !valid ? false : quizJson;
            },

            // Convert special characters to HTML entities
            htmlspecialchars: function(string, quote_style, charset, double_encode) {
                // http://phpjs.org/functions/htmlspecialchars

                var optTemp = 0,
                    i = 0,
                    noquotes = false;
                if (typeof quote_style === 'undefined' || quote_style === null) {
                    quote_style = 2;
                }
                string = string.toString();
                if (double_encode !== false) { // Put this first to avoid double-encoding
                    string = string.replace(/&/g, '&amp;');
                }
                string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');

                var OPTS = {
                    'ENT_NOQUOTES': 0,
                    'ENT_HTML_QUOTE_SINGLE': 1,
                    'ENT_HTML_QUOTE_DOUBLE': 2,
                    'ENT_COMPAT': 2,
                    'ENT_QUOTES': 3,
                    'ENT_IGNORE': 4
                };
                if (quote_style === 0) {
                    noquotes = true;
                }
                if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
                    quote_style = [].concat(quote_style);
                    for (i = 0; i < quote_style.length; i++) {
                        // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
                        if (OPTS[quote_style[i]] === 0) {
                            noquotes = true;
                        }
                        else if (OPTS[quote_style[i]]) {
                            optTemp = optTemp | OPTS[quote_style[i]];
                        }
                    }
                    quote_style = optTemp;
                }
                if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
                    string = string.replace(/'/g, '&#039;');
                }
                if (!noquotes) {
                    string = string.replace(/"/g, '&quot;');
                }

                return string;
            }
        }

        plugin.init = function() {
            plugin.formBuilder.setup();
        }

        plugin.init();
    }
    $.fn.setupQuizForm = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('setupQuizForm')) {
                var plugin = new $.setupQuizForm(this, options);
                $(this).data('setupQuizForm', plugin);
            }
        });
    }

    // Setup Quiz List
    $.setupQuizList = function(element, options) {
        var $element = $(element),
             element = element;

        var plugin = this;
        plugin.config = {}
        plugin.config = $.extend({}, options);

        plugin.method = {
            // Open Preview Quiz popup
            previewQuiz: function(element) {
                window.open($(element).attr('href'), 'quizPreview', 'resizable=1,width=900,height=700,scrollbars=1');
            },

            unpublishQuiz: function(element) {
                unpublishMsg = 'Are you sure you want to unpublish this quiz?\n\n'
                 + 'It will no longer be accessible to your visitors.';

                if (confirm(unpublishMsg)) {
                    $.ajax({
                        type:     'POST',
                        data:     {action: 'unpublish_quiz'},
                        url:      $(element).attr('href'),
                        success:  function(data) {
                            window.location = adminPath + 'admin.php?page=slickquiz&unpublish';
                        }
                    });
                }
            },

            deleteQuiz: function(element) {
                deleteMsg = 'Are you sure you want to delete this quiz?\n\n'
                 + 'It will no longer be accessible to your visitors.';

                if (confirm(deleteMsg)) {
                    $.ajax({
                        type:     'POST',
                        data:     {action: 'delete_quiz'},
                        url:      $(element).attr('href'),
                        success:  function(data) {
                            window.location = adminPath + 'admin.php?page=slickquiz&delete';
                        }
                    });
                }
            },

            clearSuccessMessage: function() {
                if ($('.success')) {
                    setTimeout("jQuery(document).ready(function($) {$('.success').fadeOut(1000)})", 5000);
                    setTimeout("jQuery(document).ready(function($) {$('.success').remove()})", 10000);
                }
            }
        }

        plugin.init = function() {
            // Bind "Preview" button
            $('.preview').bind('click', function(e) {
                e.preventDefault();
                plugin.method.previewQuiz(this);
            });

            // Bind "Unpublish" button
            $('.unpublish').bind('click', function(e) {
                e.preventDefault();
                plugin.method.unpublishQuiz(this);
            });

            // Bind "Delete" button
            $('.delete').bind('click', function(e) {
                e.preventDefault();
                plugin.method.deleteQuiz(this);
            });

            // Clear success message on load
            $(window).load(function() {
                plugin.method.clearSuccessMessage();
            });
        }

        plugin.init();
    }
    $.fn.setupQuizList = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('setupQuizList')) {
                var plugin = new $.setupQuizList(this, options);
                $(this).data('setupQuizList', plugin);
            }
        });
    }

    // Setup Quiz Preview
    $.setupQuizPreview = function(element, options) {
        var $element = $(element),
             element = element;

        var plugin = this;
        plugin.config = {}
        plugin.config = $.extend({}, options);

        plugin.method = {
            // Publish Quiz submission
            publishQuiz: function() {
                var confirmStr = "Are you ABSOLUTELY sure you want to publish this quiz?\n\n";
                confirmStr    += "If it has been added to any posts or pages, it will become ";
                confirmStr    += "immediately available.";

                publishUrl = window.location.pathname
                                .replace('admin.php', 'admin-ajax.php')
                                .replace('slickquiz-preview', 'slickquiz-publish')
                                + window.location.search;

                if (confirm(confirmStr)) {
                    $.ajax({
                        type: 'POST',
                        url:  publishUrl,
                        data: {action: 'publish_quiz'},
                        success: function(data) {
                            window.opener.document.location = adminPath + 'admin.php?page=slickquiz&success';
                            window.close();
                        }
                    });
                }
            }
        }

        plugin.init = function() {
            // Bind "Publish" button (from preview pane)
            $('.publish').bind('click', function(e) {
                e.preventDefault();
                plugin.method.publishQuiz();
            });

            // Bind "Reload" button (from preview pane)
            $('.reload').bind('click', function(e) {
                window.location.reload();
            });

            // Bind "Continue Editing" button (from preview pane)
            $('.continueEditing').bind('click', function(e) {
                window.close();
            });
        }

        plugin.init();
    }
    $.fn.setupQuizPreview = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('setupQuizPreview')) {
                var plugin = new $.setupQuizPreview(this, options);
                $(this).data('setupQuizPreview', plugin);
            }
        });
    }

    $('.quizFormWrapper').setupQuizForm();
    $('.quizList, .quizOptions').setupQuizList();
    $('.quizPreview').setupQuizPreview();

});
