<?php
$scenario->skip();
//@group webform

//@group no_populate

// Acceptance tests for webform saleforce integration.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Create a generic webform and test salesforce integration.');

$salesforce = new SalesforceMapPage($I);
$salesforce->configureSalesforce();

$I->am('admin');
$I->login();

// enable webform user for the webform content type
$I->amOnPage('admin/structure/types/manage/webform');
$I->see('Webform user settings', '.vertical-tab-button');
$I->click('Webform user settings');
$I->checkOption('#edit-webform-user--2');
$I->click('#edit-submit');

// create a new form node
$I->amOnPage(NodeAddPage::route('webform'));
$I->fillField(\NodeAddPage::$title, "Test Webform");
$I->fillField(\NodeAddPage::$internalTitle, "Test Webform");
$I->click(\NodeAddPage::$save);

// get the new node's id
$node_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');

// check for the salesforce mapping function
$I->amOnPage('node/' . $node_id . '/salesforce');
$I->wait(5);
$I->see('Salesforce Object Mapping', 'span');

// check opportunity object and donation record type
$I->selectOption(SalesforceMapPage::$objectType, 'Opportunity');
$I->wait(3);
$I->see(SalesforceMapPage::$objTypeLabel, 'label');
$I->selectOption(SalesforceMapPage::$recordType, 'Donation');

// check actions object and petition record type
$I->selectOption(SalesforceMapPage::$objectType, 'Actions');
$I->wait(3);
$I->see(SalesforceMapPage::$objTypeLabel, 'label');
$I->selectOption(SalesforceMapPage::$recordType, 'Petition Submission');

// check to see if all the config sections are visible
$I->see(SalesforceMapPage::$fieldMap,'span');
$I->see(SalesforceMapPage::$component,'th');
$I->see(SalesforceMapPage::$nodeProp,'th');
$I->see(SalesforceMapPage::$subProp,'th');
$I->see(SalesforceMapPage::$syncOptions,'label');
$I->see('Contact Field','label');

// map a few fields
$I->selectOption(SalesforceMapPage::$mapMs, 'Market_Source__c');
$I->selectOption(SalesforceMapPage::$mapNid, 'Drupal_Node_ID__c');
$I->selectOption(SalesforceMapPage::$mapSid, 'Submission_ID__c');
$I->selectOption(SalesforceMapPage::$mapContact, 'Contact__c');

// save mapping
$I->click('//input[@value="Save"]');

// check if fields are still selected after page reload
$I->seeOptionIsSelected(SalesforceMapPage::$recordType, 'Petition Submission');
$I->seeOptionIsSelected(SalesforceMapPage::$mapMs, 'Market Source');
$I->seeOptionIsSelected(SalesforceMapPage::$mapNid, 'Drupal Node ID');
$I->seeOptionIsSelected(SalesforceMapPage::$mapSid, 'Submission ID');
$I->seeOptionIsSelected(SalesforceMapPage::$mapContact, 'Contact');

// unmap the node and check to see if fields are no longer selected
$I->click(SalesforceMapPage::$unmap);
$I->cantSee(SalesforceMapPage::$fieldMap,'span');
$I->cantSee(SalesforceMapPage::$component,'th');
$I->cantSee(SalesforceMapPage::$nodeProp,'th');
$I->cantSee(SalesforceMapPage::$subProp,'th');
$I->cantSee(SalesforceMapPage::$syncOptions,'label');

// remap the actions object in preparation for webform submission by anonymous visitor
$I->selectOption(SalesforceMapPage::$objectType, 'Actions');
$I->wait(3);
$I->selectOption(SalesforceMapPage::$mapMs, 'Market_Source__c');
$I->selectOption(SalesforceMapPage::$mapNid, 'Drupal_Node_ID__c');
$I->selectOption(SalesforceMapPage::$mapSid, 'Submission_ID__c');
$I->selectOption(SalesforceMapPage::$mapContact, 'Contact__c');
$I->click('#edit-submit');
$I->seeOptionIsSelected(SalesforceMapPage::$mapMs, 'Market Source');
$I->seeOptionIsSelected(SalesforceMapPage::$mapNid, 'Drupal Node ID');
$I->seeOptionIsSelected(SalesforceMapPage::$mapSid, 'Submission ID');
$I->seeOptionIsSelected(SalesforceMapPage::$mapContact, 'Contact');

// logout and submit form
$I->logout();
$I->amOnPage('node/' . $node_id);
$I->fillField('E-mail address', 'mail@example.com');
$I->fillField('First name', 'first');
$I->fillField('Last name', 'last');
$I->fillField('Address', 'address');
$I->fillField('City', 'city');
$I->selectOption('Country', 'United States');
$I->selectOption('State/Province ', 'New York');
$I->fillField('Postal Code', '12205');
$I->click('#edit-submit');

// log in as admin and check salesforce queue
$I->am('admin');
$I->login();

$I->amOnPage(SalesforceMapPage::$queuePage);
// there should be at least one row containing the submission
$I->seeElement('tr.views-row-first');
// run cron
$I->amOnPage(SalesforceMapPage::$cronPage);
// check to see if submission successfully processed
$I->amOnPage(SalesforceMapPage::$queuePage);
$I->cantSee('tr.views-row-first', '#views-form-sbv-sf-queue-page');
$I->amOnPage(SalesforceMapPage::$batchPage);
$I->canSeeInField('.views-row-first .views-field-failures', 0);

// edit the submission and check to see that it is requeued.
$I->amOnPage('node/' . $node_id . '/salesforce');
$I->checkOption(SalesforceMapPage::$syncOptionsCheckbox);
$I->amOnPage('node/' . $node_id .'/results');
$I->click(['link' => 'Edit'], '.sticky-table');
$I->fillField('E-mail address', 'anothermail@example.com');
$I->click('#edit-submit');
$I->amOnPage(SalesforceMapPage::$queuePage);
$I->seeElement('tr.views-row-first');

