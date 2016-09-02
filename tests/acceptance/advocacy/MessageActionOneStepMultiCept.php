<?php

$config = \Codeception\Configuration::config();
$settings = \Codeception\Configuration::suiteSettings('acceptance', $config);

if (empty($settings['Advocacy']) && empty(getenv('springboard_advocacy_server_url'))) {
  $scenario->skip("Advocacy settings are not configured.");
}

//@group no_populate
//@group advocacy

// Acceptance tests for admin UI and menus.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test One-step Message Actions with multiple messages');

$I->am('admin');
$I->login();
$I->enableModule('Springboard Advocacy');
$advocacy = new AdvocacyPage($I);
$advocacy->configureAdvocacy();
$I->enableModule('form#system-modules input#edit-modules-springboard-advocacy-sba-message-action-enable');
$I->amOnPage(\AdvocacyPage::$settingsPage);
// Submit to get an access token.
$I->click('#edit-submit');

// Add a social action.
$I->amOnPage(NodeAddPage::route('sba-message-action'));
$I->fillField(\NodeAddPage::$title, "Test action title");
$I->fillField(\NodeAddPage::$internalTitle, "Test Action");

$I->fillField('#edit-field-message-call-to-action-und-0-value', 'Call to action, yo');
$I->fillField('#edit-body-und-0-value', "Test action body");
$I->fillField('#edit-field-sba-message-action-label-und-0-value', 'Take Action, Yo');
$I->fillField('#edit-action-submit-button-text', 'Send Your Massage');
$I->selectOption('#edit-field-sba-legislative-issues-und-1', 1);
$I->selectOption('#edit-field-sba-action-flow-und-one', 'one');
$I->fillField('#edit-field-sba-multistep-prompt-und-0-value', 'Here is my prompt');
$I->seeCheckboxIsChecked('#edit-field-sba-test-mode-und-1');
$I->seeInField('#edit-field-sba-test-mode-email-und-0-value', 'admin@example.com');
$I->click(\NodeAddPage::$save);

//Create a districted message.
$I->click('Messages');
$I->click('.sba-add-button');
$I->fillField('#edit-name', "Test Message");
$I->seeOptionIsSelected('#edit-field-sba-subject-editable-und-not-editable', 'Not editable');
$I->fillField('#edit-field-sba-subject-und-0-value', "Message Subject");
$I->fillField('#edit-field-sba-placeholder-greeting-und-0-value', 'The placeholder greeting');
$I->seeInField('#edit-field-sba-greeting-und-0-value', 'Dear [target:salutation] [target:last_name]');
$I->fillField('#edit-field-sba-message-und-0-value', 'Message Body');
$I->seeInField('#edit-field-sba-signature-und-0-value', "Sincerely, \n\n[contact:first_name] [contact:last_name]");
$I->checkOption('//input[@name="search_role_1[FS]"]');
$I->click('#quick-target');
$I->wait(1);
$I->see("Federal Senators");
$I->click('#edit-submit');
$I->wait(3);

$I->click('.sba-add-button');
$I->fillField('#edit-name', "Test Message Two");
$I->seeOptionIsSelected('#edit-field-sba-subject-editable-und-not-editable', 'Not editable');
$I->fillField('#edit-field-sba-subject-und-0-value', "Message Subject Two");
$I->fillField('#edit-field-sba-placeholder-greeting-und-0-value', 'The placeholder greeting');
$I->seeInField('#edit-field-sba-greeting-und-0-value', 'Dear [target:salutation] [target:last_name]');
$I->fillField('#edit-field-sba-message-und-0-value', 'Message Body Two');
$I->seeInField('#edit-field-sba-signature-und-0-value', "Sincerely, \n\n[contact:first_name] [contact:last_name]");
$I->checkOption('//input[@name="search_role_1[FR]"]');
$I->click('#quick-target');
$I->wait(1);
$I->see("Federal Representatives");
$I->click('#edit-submit');
$I->see('Check this box if you wish to enforce message precedence by sort order. If you check this box, only the first message that is eligible for delivery to legislators - via the user\'s zip code/congressional district verification - will be sent. The subsequent messages will be ignored.');

$node_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');

// Fill out and submit the form.
$I->click('View');
$I->see('Call to action, yo');
$I->see('Take Action, yo');
$I->see('Test action title');
$I->see('Test action body');
$I->see('Here is my prompt');
$I->click('View all possible messages');
$I->waitForElement('.views-field-field-sba-subject', 10);
$I->see('Message Subject');
$I->see('Message Body');
$I->see('The placeholder greeting');

$I->see('Message Subject Two');
$I->see('Message Body Two');
$I->see('The placeholder greeting');
$I->click('a.close');
$I->seeElement('//input[@value="Send Your Massage"]');
$I->selectOption('#edit-submitted-sbp-salutation', 'Mr');
$I->fillField('First name', "John");
$I->fillField('Last name', "Doe");
$I->fillField('Address', "1100 Broadway");
$I->fillField('City', "Schenectady");
$I->fillField('Zip Code', "12345");
$I->selectOption('State', 'New York');
$I->click('#edit-submit');

// Process the preview page.
$I->see('Test action title');
$I->see('Thank you, John for participating in the messaging campaign');
$I->see("Kirsten Gillibrand");
