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
$I->wantTo('Test edsitable two-step Message Actions');

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
$I->see('Take Action User Flow');
$I->see('One-step action flow allows advocates to take action without entering their address as a separate step; however, if your action will have multiple messages (ie, thank/spank) they cannot be edited by advocates. Multi-step action flow allows advocates to edit multiple messages under the same action; advocates will be required to enter their address prior to viewing, editing, and sending the messages.');
$I->see('"Your Message" Intro Text');
$I->see('This text will appear below the "Your Message" label in one-step forms, or below the "Step 1 of 2" label in multi-step forms. On one-step forms with multiple messages, it will appear above the "View all possible messages link."
Show');


$I->selectOption('#edit-field-sba-action-flow-und-multi', 'multi');
$I->see('Step-Two Intro Text');
$I->see('The header text at the top of the step two page.');
$I->see('Step-Two Submit Button Text');
$I->fillField('#edit-field-sba-multistep-prompt-und-0-value', 'Here is my prompt');
$I->fillField('#edit-field-sba-action-step-two-header-und-0-value', 'Step Two Intro');
$I->fillField('#edit-field-sba-action-step-two-submit-und-0-value', 'Send now, yo');
$I->seeCheckboxIsChecked('#edit-field-sba-test-mode-und-1');
$I->seeInField('#edit-field-sba-test-mode-email-und-0-value', 'admin@example.com');
$I->click('Publishing options');
$I->checkOption('#edit-status');
$I->click(\NodeAddPage::$save);

//Create a districted message.
$I->click('Messages');
$I->click('.sba-add-button');
$I->fillField('#edit-name', "Test Message");
$I->checkOption('#edit-field-sba-user-editable-und-editable');
$I->selectOption('#edit-field-sba-subject-editable-und-editable', 'Editable');
$I->fillField('#edit-field-sba-subject-und-0-value', "Message Subject");
$I->fillField('#edit-field-sba-placeholder-greeting-und-0-value', 'The placeholder greeting');
$I->seeInField('#edit-field-sba-greeting-und-0-value', 'Dear [target:salutation] [target:last_name]');
$I->fillField('#edit-field-sba-message-und-0-value', 'Message Body');
$I->fillField('#edit-field-sba-editable-area-und-0-value', 'Editable message');
$I->seeInField('#edit-field-sba-signature-und-0-value', "Sincerely, \n\n[contact:first_name] [contact:last_name]");
$I->checkOption('//input[@name="search_role_1[FS]"]');
$I->click('#quick-target');
$I->wait(1);
$I->see("Federal Senators");
$I->click('#edit-submit');
$I->wait(3);

$node_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');

// Fill out and submit the form.
$I->logout();
$I->wait(5);
$I->amOnPage('node/' . $node_id);
$I->see('Call to action, yo');
$I->see('Take Action, yo');
$I->see('Test action title');
$I->see('Test action body');
$I->see('Here is my prompt');
$I->see("Step 1 of 2");

$I->seeElement('//input[@value="Send Your Massage"]');
$I->selectOption('#edit-submitted-sbp-salutation', 'Mr');
$I->fillField('First name', "John");
$I->fillField('Last name', "Doe");
$I->fillField('E-mail address', 'admin@exammple.com');
$I->fillField('Address', "1100 Broadway");
$I->fillField('City', "Schenectady");
$I->fillField('Zip Code', "12345");
$I->selectOption('State', 'New York');
$I->click('#edit-submit');

$I->see('Preview Messages');
$I->see('Step 2 of 2');
$I->see('Step Two Intro');
$I->see('Please review and edit the messages below; or if you prefer, simply send now');
$I->see('These message(s) were generated based on the address you entered');
$I->see("1100 Broadway");
$I->see('If this is incorrect please click here to enter a different address.');
$I->see('The message below will be sent to');
$I->see('Sen. Kirsten Gillibrand (D)');
$I->see('US Senator, NY');
$I->seeElement('//input[@value="Message Subject"]');
$I->seeElement('//textarea[contains(@name, \'edited_body\')]');
$I->see('Editable message');
$I->see('Dear Sen. Gillibrand');
$I->see('Sen. Kirsten Gillibrand (D)');
$I->see('US Senator, NY');
$I->see('Sen. Charles Schumer (D)');
$I->see('Dear Sen. Schumer');

$I->seeElement('//input[@value="Send now, yo"]');

$I->click('#edit-submit');
// Process the preview page.
$I->see('Test action title');
$I->see('Thank you, John for participating in the messaging campaign');
$I->see("Kirsten Gillibrand");
$I->see("Charles Schumer");

$I->am('admin');
$I->login();
$I->wait(5);
$I->amOnPage('node/' . $node_id);
$I->click('Messages');
$I->click('#draggableviews-table-sba_messages_node-block_1  tr.views-row-first td.views-field-edit-sba-message a.first');
$I->selectOption('#edit-field-sba-subject-editable-und-editing-is-required', 'Editing is required');
$I->selectOption('#edit-field-sba-editable-area-optional-und-required', 'required');
$I->click('#edit-submit');
$I->wait(3);
$I->logout();
$I->wait(3);
$I->amOnPage('node/' . $node_id);
$I->selectOption('#edit-submitted-sbp-salutation', 'Mr');
$I->fillField('First name', "John");
$I->fillField('Last name', "Doe");
$I->fillField('E-mail address', 'admin@exammple.com');
$I->fillField('Address', "1100 Broadway");
$I->fillField('City', "Schenectady");
$I->fillField('Zip Code', "12345");
$I->selectOption('State', 'New York');
$I->click('#edit-submit');
$I->see("You must edit this subject line before sending");
$I->see("You must edit the body of this message before sending");
$I->fillField('#edit-messages-1us-senategillibrand-message-subject', 'edited');
$I->fillField('#edit-messages-1us-senategillibrand-message-edited-body', 'edited');
$I->fillField('#edit-messages-1us-senateschumer-message-subject', 'edited');
$I->fillField('#edit-messages-1us-senateschumer-message-edited-body', 'edited');
$I->click('#edit-submit');
$I->see('Test action title');
$I->see('Thank you, John for participating in the messaging campaign');
$I->see("Kirsten Gillibrand");
$I->see("Charles Schumer");
