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
$I->wantTo('Test the message action message UI');

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
$I->see('Messages', 'H2');
$I->see('Create a new message');
$I->see('Name');
$I->see('Subject text');
$I->see('Actions');
$I->see('edit | delete');
$I->see('Test Message ');
$I->see('Message Subject ');
$I->seeElement('.tabledrag-handle');
$I->click('#draggableviews-table-sba_messages_node-block_1  tr.views-row-first td.views-field-edit-sba-message a.first');
$I->selectOption('#edit-field-sba-subject-editable-und-editable', 'Editable');
$I->click('#edit-submit');
$I->see("This single-step action's current message is user-editable, please disable user editing or use the multi-step action flow to create additional messages.");
$I->click('Edit');
$I->selectOption('#edit-field-sba-action-flow-und-multi', 'multi');
$I->click('#edit-submit');
$I->click('Messages');
$I->dontSee("This single-step action's current message is user-editable, please disable user editing or use the multi-step action flow to create additional messages.");

$I->click('.sba-add-button');
$I->fillField('#edit-name', "Test Message Two");
$I->seeOptionIsSelected('#edit-field-sba-subject-editable-und-not-editable', 'Not editable');
$I->fillField('#edit-field-sba-subject-und-0-value', "Message Subject Two");
$I->fillField('#edit-field-sba-placeholder-greeting-und-0-value', 'The placeholder greeting two');
$I->fillField('#edit-field-sba-message-und-0-value', 'Message Body Two');
$I->checkOption('//input[@name="search_role_1[FR]"]');
$I->click('#quick-target');
$I->wait(1);
$I->see("Federal Representatives");
$I->click('#edit-submit');
$I->click('Edit');
$I->see("This option is disabled because you have multiple messages configured for this action, and at least one of them is user-editable. Make them non-editable to enable this option.");
$I->click('Messages');
$I->click('#draggableviews-table-sba_messages_node-block_1  tr.views-row-first td.views-field-edit-sba-message a.last');

$I->click('Delete');
$I->click('#draggableviews-table-sba_messages_node-block_1  tr.views-row-first td.views-field-edit-sba-message a.first');

if ($scenario->current('browser') == 'phantomjs' || $scenario->current('browser') == 'firefox') {
  $I->seeElement('//select[@name="search_district_name"][@disabled=""]');
}
$I->selectOption('//select[@name="search_state"]', "Alabama");
$I->waitForElement('//option[@value="Alabama District 1"]');
$I->selectOption('//select[@name="search_district_name"]', "Alabama District 1");
$I->wait(3);
$I->click('//select[@name="search_district_name"]');
$I->dontSeeElement('#quick-target');
$I->seeElement('#edit-search-role-1-wrapper.disabled');
$I->seeElement('#edit-search-gender-wrapper.disabled');
$I->seeElement('#edit-search-social-wrapper.disabled');
$I->click('Search');
$I->waitForElement('tr.views-row-first', 15);
$I->wait(3);
$I->click('reset');
$I->wait(3);
$I->dontSeeElement('#edit-search-role-1-wrapper.disabled');
$I->dontSeeElement('#edit-search-gender-wrapper.disabled');
$I->dontSeeElement('#edit-search-social-wrapper.disabled');
$I->dontSeeElement('tr.views-row-first');
$I->checkOption('//input[@name="search_role_1[FS]"]');
$I->click('Search');
$I->waitForElement('tr.views-row-first', 15);
$I->seeNumberOfElements('.views-table tr', [0,100]);
$I->executeJS('jQuery("#edit-submit-targets").hide()');
$I->click('reset');
$I->executeJS('jQuery("#edit-submit-targets").show()');
$I->unCheckOption('//input[@name="search_role_1[FS]"]');
$I->wait(1);
$I->fillField('#edit-combine', 'Barrack');
$I->wait(3);
$I->click('Search');
$I->waitForElement('tr.views-row-first', 15);
$I->wait(5);
$I->executeJS('jQuery("#edit-submit-targets").hide()');
$I->click('reset');
$I->executeJS('jQuery("#edit-submit-targets").show()');
$I->wait(5);
$I->click('.committee-search');
$I->waitForElement('#edit-search-committee', 5);
$I->fillField('#edit-search-committee', 'a');
$I->waitForElement('#autocomplete', 15);
$I->wait(5);
$I->see('House Committee');
$I->see('Try narrowing');
$I->selectOption('#edit-search-committee-chamber', 'Federal House');
$I->fillField('#edit-search-committee', 'Hemisphere');
$I->selectOption('#edit-search-committee-chamber', '- Any -');
$I->selectOption('#edit-search-state', 'Alabama');
$I->fillField('#edit-search-committee', 'a');
$I->waitForElement('#autocomplete', 15);
$I->wait(5);
$I->see('House Committee');
$I->selectOption('#edit-search-committee-chamber', 'State Senate');
$I->fillField('#edit-search-committee', 'a');
$I->waitForElement('#autocomplete', 15);
$I->wait(5);
$I->see('Senate Committee');
$I->click('#autocomplete ul:first-child div');
$I->wait(5);
$I->click('body');
$I->executeJS('jQuery("#edit-submit-targets").show()');
$I->waitForElementVisible('#edit-submit-targets', 10);
$I->click('#edit-submit-targets');
$I->seeNumberOfElements('.views-table tr', [0,100]);
$I->wait(5);
$I->waitForElement('#advo-add-all', 20);
$I->click('#advo-add-all');
$I->waitForElement('.target-recipient',20);
$I->wait(3);
$I->see('Group Target');
$I->see('Targeting: 2 groups');
$I->see('Remove All Targets');
$I->click('Remove All Targets');
$I->wait(2);
$I->dontSee('Targeting: 1 group');
$I->see('You have unsaved changes');
