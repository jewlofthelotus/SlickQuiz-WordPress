jQuery(document).ready(function($) {

    var adminPath = location.pathname.replace(/wp-admin.*/, 'wp-admin/');
    var imagePath = location.pathname.replace(/wp-admin.*/, 'wp-content/plugins/slickquiz/images/');

    var clearSuccessMessage = function() {
        if ($('#message')) {
            setTimeout("jQuery(document).ready(function($) {$('#message').fadeOut(1000)})", 5000);
            setTimeout("jQuery(document).ready(function($) {$('#message').remove()})", 10000);
        }
    }

    // Setup Quiz Form
    $.setupQuizForm = function(element, options) {
        var $element = $(element),
             element = element;

        var plugin = this;
        plugin.config = {}
        plugin.config = $.extend(plugin.config, options);

        // If editing a quiz, quizJSON will exist
        var quizValues = (typeof quizJSON != 'undefined' ? quizJSON : null);

        // See if we need to hide and unrequire the ranking levels
        var rankingDisabled = (typeof disableRanking != 'undefined' ? disableRanking : null);

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
                    "q":          "Quiz Title",
                    "desc":       "The Quiz Title will appear at the top of your quiz.",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "name"
                },
                {
                    "q":          "Main Copy",
                    "desc":       "This will appear immediately below the Quiz Title and should be used to describe the quiz, provide instructions, or any other desired information.",
                    "type":       "textarea",
                    "required":   false,
                    "jsonName":   "main"
                },
                {
                    "q":          "Result Copy",
                    "desc":       "This will appear upon quiz completion along with the user's quiz score and ranking. This is an excellent place to link to additional resources or point users to the next step.",
                    "type":       "textarea",
                    "required":   false,
                    "jsonName":   "results"
                },
                {
                    "label":      "Ranking Levels",
                    "descGroup":  "Upon quiz completion the user's score percentage will be calculated and the corresponding ranking level will be presented to the user.",
                    "q":          "Level 1 <small>(81-100% Best)</small>",
                    "placeholder": 'For example, Prodigy or Savant',
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level1"
                },
                {
                    "q":          "Level 2 <small>(61-80%)</small>",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level2"
                },
                {
                    "q":          "Level 3 <small>(41-60%)</small>",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level3"
                },
                {
                    "q":          "Level 4 <small>(21-40%)</small>",
                    "type":       "text",
                    "required":   true,
                    "jsonName":   "level4"
                },
                {
                    "q":          "Level 5 <small>(0-20% Worst)</small>",
                    "placeholder": 'For example, Airhead or Schmuck',
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
                    field = defaults.fields[f];
                    inputName = field.q.replace(/\W/g,'');
                    nameAndId = 'name="' + inputName + '" id="' + inputName + '"';
                    placeholder = field.placeholder ? 'placeholder="' + field.placeholder + '"' : '';
                    required  = field.required ? defaults.requiredString : '';

                    if (rankingDisabled && /^level/.test(field.jsonName)) {
                        required = '';
                        field.required = false;
                    }

                    if (quizValues != null && quizValues.info[field.jsonName]) {
                        value = plugin.formHelper.htmlspecialchars(quizValues.info[field.jsonName]);
                    } else {
                        value = '';
                    }

                    // Setup Field Container
                    defaultQuestionHTML = $('<div class="question ' + inputName + '"></div>');

                    // Add Input Group Label (e.g. "Ranking Levels")
                    if (field.label) {
                        defaultQuestionHTML.append('<label class="main">' + field.label + '</label>');
                    }

                    // Add Group Description
                    if (field.descGroup) {
                        defaultQuestionHTML.append('<small class="desc">' + field.descGroup + '</small>');
                    }

                    if (rankingDisabled && /^level1/.test(field.jsonName)) {
                        defaultQuestionHTML.append('<p><small class="desc" style="color: goldenrod;">Ranking levels are currently disabled and will not appear in the quiz.</small></p>');
                    }

                    // Add Input Label
                    defaultQuestionHTML.append('<label>' + required + field.q + '</label> ');

                    // Add Input Description
                    if (field.desc) {
                        defaultQuestionHTML.append('<small class="desc">' + field.desc + '</small>');
                    }

                    // Add Field
                    if (field.type == 'text') {
                        defaultQuestionHTML.append('<input type="text" ' + nameAndId + ' value="' + value + '" ' + placeholder + ' />');
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
                newQuestionHTML.append('<textarea name="question">' + (fieldGroup ? plugin.formHelper.htmlspecialchars(fieldGroup.q) : '') + '</textarea>');

                newQuestionCorrectHTML = $('<div class="question correct"></div>');
                newQuestionCorrectHTML.append('<label>' + defaults.requiredString + ' Correct Response Message</label> ');
                newQuestionCorrectHTML.append('<small class="desc">The message that will display if the user answers the question correctly (and if response messaging is enabled).</small> ');
                newQuestionCorrectHTML.append('<textarea name="correct">' + (fieldGroup ? fieldGroup.correct : '') + '</textarea>');

                newQuestionIncorrectHTML = $('<div class="question incorrect"></div>');
                newQuestionIncorrectHTML.append('<label>' + defaults.requiredString + ' Incorrect Response Message</label> ');
                newQuestionIncorrectHTML.append('<small class="desc">The message that will display if the user answers the question incorrectly (and if response messaging is enabled).</small> ');
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

                    plugin.formBuilder.addForceCheckbox(newAnswerHTML, fieldGroup);
                    plugin.formBuilder.addSelectAny(newAnswerHTML, fieldGroup);

                } else { // Add blank answers to NEW quiz form question
                    plugin.formBuilder.addAnswer(newAnswerHTML.children('a')[0]);
                    plugin.formBuilder.addAnswer(newAnswerHTML.children('a')[0]);

                    plugin.formBuilder.addForceCheckbox(newAnswerHTML.children('a')[0]);
                    plugin.formBuilder.addSelectAny(newAnswerHTML.children('a')[0]);
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

            // Adds "select any (correct)" answer option to the selected question
            addSelectAny: function(element, fieldGroup) {
                addAnswerLink = fieldGroup ? $(element) : $(element).parent();

                var anyAnswerHTML = '<div class="question selectAny">'
                    + '<label><input type="checkbox" name="select_any"' + (fieldGroup && fieldGroup.select_any ? ' checked="checked"' : '') + ' /> '
                    + 'Selecting any <strong>single</strong> correct answer is valid</label><br /> '
                    + '<small class="desc">If you have more than one correct answer for this question, by default the user must choose all correct answers to pass.</small><br />'
                    + '<small class="desc">Checking this box will change the question so that choosing any single correct answer will result in a correct response.</small> '
                    + '</div>';

                addAnswerLink.after($(anyAnswerHTML).hide().fadeIn(800));
            },

            // Adds "force checkbox" answer option to the selected question
            addForceCheckbox: function(element, fieldGroup) {
                addAnswerLink = fieldGroup ? $(element) : $(element).parent();

                var forceCheckboxHTML = '<div class="question forceCheckbox">'
                    + '<label><input type="checkbox" name="force_checkbox"' + (fieldGroup && fieldGroup.force_checkbox ? ' checked="checked"' : '') + ' /> '
                    + '<strong>Use checkboxes</strong> even if the question only has one correct answer</label><br /> '
                    + '<small class="desc">If you only have one correct answer for this quesiton, by default the quiz will display radio buttons (which only allow a single selection).</small><br />'
                    + '<small class="desc">Checking this box will force the question to display checkboxes, which obscures the fact that there is a single answer from the user.</small> '
                    + '</div>';

                addAnswerLink.after($(forceCheckboxHTML).hide().fadeIn(800));
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

                // Delegate "Publish" button
                $('.bottom_button_bar').delegate('.publish', "click", function(e) {
                    e.preventDefault();
                    plugin.formListener.publish(this);
                });

                // Delegate "Draft" button
                $('.bottom_button_bar').delegate('.draft', "click", function(e) {
                    e.preventDefault();
                    plugin.formListener.draft(this);
                });

                // Delegate "Discard" button
                $(".top_button_bar").delegate('.discard', "click", function(e){
                    e.preventDefault();
                    plugin.formListener.discard(this);
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

            // Save draft and preview it in new window
            preview: function(element) {
                var formValues = plugin.formHelper.getValidQuizJson();

                if (!formValues) {
                    alert('There were a few errors with your submission. Please fix them and try again.');
                    return false;
                }

                formValues.extra = callPreSaveQuiz();

                var formJSON = JSON.stringify(formValues);
                var pubJSON  = JSON.stringify(quizValues);
                var location = window.location.pathname + window.location.search;

                if (formJSON == pubJSON) {
                    var previewUrl = location.replace('slickquiz-edit', 'slickquiz-preview');
                    window.open(previewUrl, 'quizPreview', 'resizable=1,width=900,height=700,scrollbars=1');
                    return false;
                }

                var postAction = location.match('slickquiz-new') ? 'create_draft_quiz' : 'update_draft_quiz';

                plugin.formHelper.saveQuiz(formJSON, postAction, false, function(data){
                    if (location.match('slickquiz-new')) {
                        window.location = location.replace('slickquiz-new', 'slickquiz-edit') + '&id=' + data;
                        var previewUrl  = location.replace('slickquiz-new', 'slickquiz-preview') + '&id=' + data;
                    } else {
                        window.location.reload();
                        var previewUrl  = location.replace('slickquiz-edit', 'slickquiz-preview');
                    }
                    window.open(previewUrl, 'quizPreview', 'resizable=1,width=900,height=700,scrollbars=1');
                });
            },

            // Save draft
            draft: function() {
                var formValues = plugin.formHelper.getValidQuizJson();

                if (!formValues) {
                    alert('There were a few errors with your submission. Please fix them and try again.');
                    return false;
                }

                formValues.extra = callPreSaveQuiz();

                var formJSON = JSON.stringify(formValues);
                var pubJSON  = JSON.stringify(quizValues);
                var location = window.location.pathname + window.location.search;

                if (formJSON == pubJSON) {
                    alert('There are no changes to save a draft of.')
                    return false;
                }

                var postAction = location.match('slickquiz-new') ? 'create_draft_quiz' : 'update_draft_quiz';

                plugin.formHelper.saveQuiz(formJSON, postAction, false, function(data){
                    if (location.match('slickquiz-new')) {
                        window.location = location.replace('slickquiz-new', 'slickquiz-edit') + '&id=' + data + '&success';
                    } else {
                        window.location = window.location + '&success';
                    }
                });
            },

            // Publish quiz
            publish: function() {
                var formValues = plugin.formHelper.getValidQuizJson();

                if (!formValues) {
                    alert('There were a few errors with your submission. Please fix them and try again.');
                    return false;
                }

                formValues.extra = callPreSaveQuiz();

                var formJSON = JSON.stringify(formValues);
                var pubJSON  = JSON.stringify(quizValues);

                if ($('.notPublished').length == 0 && formJSON == pubJSON) {
                    alert('There are no changes to publish.')
                    return false;
                }

                var location   = window.location.pathname + window.location.search;
                var postAction = location.match('slickquiz-new') ? 'create_published_quiz' : 'update_published_quiz';
                var confirmStr = "Are you sure you want to publish this quiz?";

                plugin.formHelper.saveQuiz(formJSON, postAction, confirmStr, function(data){
                    window.location = adminPath + 'admin.php?page=slickquiz&success';
                });
            },

            // Discard draft
            discard: function(element) {
                if (confirm('Are you sure you want to discard these drafted changes?')) {
                    discardUrl = (window.location.pathname + window.location.search).replace('admin.php', 'admin-ajax.php');

                    $.ajax({
                        type:     'POST',
                        url:      discardUrl,
                        data:     {action: 'discard_draft_quiz'},
                        success:  function(data) {
                            window.location = window.location + '&success';
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
                    questionInput     = $($(questionSet).children('.actual').children('textarea')[0]);
                    correctResponse   = $($(questionSet).children('.correct').children('textarea')[0]);
                    incorrectResponse = $($(questionSet).children('.incorrect').children('textarea')[0]);
                    answers           = $($(questionSet).children('.answer'));
                    selectAny         = $($(questionSet).find('.selectAny input[type="checkbox"]')[0]).attr('checked');
                    forceCheckbox     = $($(questionSet).find('.forceCheckbox input[type="checkbox"]')[0]).attr('checked');
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
                        question["q"]              = questionInput.attr('value');
                        question["correct"]        = correctResponse.attr('value');
                        question["incorrect"]      = incorrectResponse.attr('value');
                        question["select_any"]     = selectAny ? true : false;
                        question["force_checkbox"] = forceCheckbox ? true : false;
                        quizJson["questions"].push(question);
                    }
                });

                return !valid ? false : quizJson;
            },

            saveQuiz: function(formJSON, actionName, confirmMsg, callback) {
                actionUrl = window.location.pathname
                                .replace('admin.php', 'admin-ajax.php')
                                .replace('slickquiz-preview', 'slickquiz-publish')
                                + window.location.search;

                if (confirmMsg ? confirm(confirmMsg) : true) {
                    $.ajax({
                        type: 'POST',
                        url:  actionUrl,
                        data: {
                            action: actionName,
                            json: formJSON
                        },
                        dataType: 'text',
                        async:    false, // for Safari
                        success:  function(data) { callback(data) }
                    });
                }
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

    // Setup Score List
    $.setupScoreList = function(element, options) {
        var $element = $(element),
             element = element;

        var plugin = this;
        plugin.config = {}
        plugin.config = $.extend({}, options);

        plugin.method = {
            deleteScore: function(element) {
                deleteMsg = 'Are you sure you want to delete this score?';

                if (confirm(deleteMsg)) {
                    $.ajax({
                        type:     'POST',
                        data:     {action: 'delete_quiz_score'},
                        url:      $(element).attr('href'),
                        success:  function(data) {
                          window.location = window.location + '&success';
                        }
                    });
                }
            }
        }

        plugin.init = function() {
            // Bind "Delete" button
            $('.delete').bind('click', function(e) {
                e.preventDefault();
                plugin.method.deleteScore(this);
            });
        }

        plugin.init();
    }
    $.fn.setupScoreList = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('setupScoreList')) {
                var plugin = new $.setupScoreList(this, options);
                $(this).data('setupScoreList', plugin);
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

        plugin.method = {}

        plugin.init = function() {
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


    // #58: Set and call an optional callback.
    var preSaveQuizCallback;

    $.fn.setPreSaveQuiz = function (callback) {
        preSaveQuizCallback = callback;
    };

    function callPreSaveQuiz() {
        var extra_data = preSaveQuizCallback && preSaveQuizCallback();
        return extra_data || null;
    }


    $('.quizFormWrapper').setupQuizForm();
    $('.quizList, .quizOptions').setupQuizList();
    $('.scoreList').setupScoreList();
    $('.quizPreview').setupQuizPreview();

    // Clear success message on load
    $(window).load(function() {
        clearSuccessMessage();
    });

});
