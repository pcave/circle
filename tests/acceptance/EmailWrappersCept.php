<?php

//@group misc


$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('configure Email wrappers and add a template.');

// @todo Test BCC

// We need a unique title so we can pick it from the template list.
$title = 'email templates test ' . time();
$subject = 'this is my subject';
$html_template = 'this is my html template. here comes the html message token: %html_message did you see it?';
$html_message = 'this is the default html message';
$text_template = 'this is my text template. here comes the text message token: %text_message did you see it?';
$text_message = 'this is the default text message';
$from_name = 'this is my from name';
$from_mail = 'from@example.com';

$I->am('admin');

$I->login();

$I->amOnPage(EmailWrapperPage::$URL);
$I->dontSeeCheckboxIsChecked('Limit templates by group.');

$I->amOnPage(EmailWrapperPage::route('add'));

$I->fillField(EmailWrapperPage::$internalTitleField, $title);
$I->fillField(EmailWrapperPage::$fromNameField, $from_name);
$I->fillField(EmailWrapperPage::$fromEmailField, $from_mail);

// @todo Doesn't seem like reply to address shows up in the webform email config.
$I->fillField(EmailWrapperPage::$replyToEmailField, 'replyto@example.com');

$I->fillField(EmailWrapperPage::$subjectField, $subject);
$I->fillField(EmailWrapperPage::$htmlTemplateField, $html_template);
$I->fillField(EmailWrapperPage::$htmlMessageField, $html_message);
$I->fillField(EmailWrapperPage::$textTemplateField, $text_template);
$I->fillField(EmailWrapperPage::$textMessageField, $text_message);

$I->click(EmailWrapperPage::$saveButton);
$I->see('Email Template ' . $title . ' has been created.');

$I->cloneADonationForm();
$I->click('Save');

$I->click('Form components', 'ul.primary');
$I->click('Confirmation emails', 'ul.secondary');

$I->click(WebformPage::$addEmailButton);

$I->selectOption('Email Template', $title);
$I->waitForJS('return jQuery.active == 0;', 10);

$I->seeCheckboxIsChecked(WebformPage::$emailSubjectField, 'Custom');

$I->seeInField(WebformPage::$emailSubjectCustomField, $subject);

$I->seeCheckboxIsChecked(WebformPage::$emailFromAddressField, 'custom');
$I->seeInField(WebformPage::$emailFromAddressCustomField, $from_mail);

$I->seeCheckboxIsChecked(WebformPage::$emailFromNameField, 'custom');
$I->seeInField(WebformPage::$emailFromNameCustomField, $from_name);
$I->seeInField(WebformPage::$emailWrappersHTMLMessage, $html_message);
$I->seeInField(WebformPage::$emailWrappersTextMessage, $text_message);

$I->click('Preview');

$I->waitForText('Template Preview', 10, '#modal-title');

$I->see($subject, '#modal-content');
$I->see('<' . $from_name . '>' . $from_mail, '#modal-content');

// Perform the transformations manually to confirm it happens in the Preview.
$full_html = str_replace('%html_message', $html_message, $html_template);
$full_text = str_replace('%text_message', $text_message, $text_template);

$I->see($full_html, '#modal-content');
$I->see($full_text, '#modal-content');

$I->click('Close Window', '.modal-header');

$I->click(WebformPage::$saveEmailSettingsButton);

$I->see('Email settings added.', '.status');

$I->see($subject);
$I->see('"' . $from_name . '" <' . $from_mail . '>');

// @todo Go back and edit the email settings and confirm they changed/saved.

$I->logout();
