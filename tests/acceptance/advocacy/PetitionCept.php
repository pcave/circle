<?php


//@group no_populate
//@group advocacy

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Enable and test petition functions');

$salesforce = new SalesforceMapPage($I);
$salesforce->configureSalesforce();

// We need a unique title so we can pick it from the template list.
$title = 'Petition test ' . time();
//

$I->am('admin');
$I->login();

$I->enableModule('Springboard Petition');
$I->enableModule('Springboard Advocacy Quick Sign');

$I->wait(5);
$I->amOnPage(NodeAddPage::route('springboard-petition'));
$I->fillField(NodeAddPage::$title, "Test Petition");
$I->fillField(NodeAddPage::$internalTitle, "Test Petition");

$I->click(\NodeAddPage::$save);

$I->see('User profile fields have been mapped to webform components');
$I->see('E-mail address', 'td.first');
$I->see('First name', 'td.first');
$I->see('Last name', 'td.first');

// Check that the field types are correct
$I->see('Hidden', '//td[text()="Market Source"]/following-sibling::td');
$I->see('E-mail', '//td[text()="E-mail address"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Campaign ID"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Referrer"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Initial Referrer"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Search Engine"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Search String"]/following-sibling::td');
$I->see('Hidden', '//td[text()="User Agent"]/following-sibling::td');
$I->see('Textfield', '//td[text()="First name"]/following-sibling::td');
$I->see('Textfield', '//td[text()="Last name"]/following-sibling::td');
$I->seeCheckboxIsChecked('//td[text()="E-mail address"]/following-sibling::td//input');

$node_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');

// check that the fields are mapped correctly
$I->amOnPage('node/' . $node_id . '/webform/user_mapping');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="E-mail address"]/following-sibling::td//select', 'E-mail address');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Market Source"]/following-sibling::td//select', 'Market Source');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Campaign ID"]/following-sibling::td//select', 'Campaign ID');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Referrer"]/following-sibling::td//select', 'Referrer');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Initial Referrer"]/following-sibling::td//select', 'Initial Referrer');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Search Engine"]/following-sibling::td//select', 'Search Engine');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Search String"]/following-sibling::td//select', 'Search String');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="User Agent"]/following-sibling::td//select', 'User Agent');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="First name"]/following-sibling::td//select', 'First name');
$I->seeOptionIsSelected('//table[contains(@class, "tableheader-processed")]//td[text()="Last name"]/following-sibling::td//select', 'Last name');


$I->amOnPage('node/' . $node_id);
$I->see('Show my signature');
$I->see('E-mail address');
$I->see('First name');
$I->see('Last name');
$I->click('Edit');
$I->click('Webform user settings');
$I->seeCheckboxIsChecked('//input[@name="is_webform_user"]');
$I->wait(10);
$I->click('Quick Sign settings');
$I->checkOption('//input[@name="quicksign_enabled"]');
$I->fillField('//input[@name="quicksign_label"]', '123');
$I->fillField('//textarea[@name="quicksign_description[value]"]', '456');
$I->fillField('//input[@name="quicksign_button_text"]', 'Sign Me');
$I->click('#edit-submit');
$I->amOnPage('node/' . $node_id);
$I->see('123');
$I->see('456');
$I->seeElement('//input[contains(@name, \'quicksign_mail\')]');
$I->wait(10);

$I->seeElement('//input[@value="Sign Me"]');

// check to see that authenticated user fields are pre-populated

$I->amOnPage('node/' . $node_id);
$I->fillField('E-mail address', 'mail@example.com');
$I->fillField('First name', 'first');
$I->fillField('Last name', 'last');
$I->click('#edit-submit');

$I->amOnPage('node/' . $node_id);

if ($I->grabValueFrom('E-mail address') == '') {
  $I->fail();
}
if ($I->grabValueFrom('First name') == '') {
  $I->fail();
}
if ($I->grabValueFrom('Last name') == '') {
  $I->fail();
}

$I->logout();
$I->amOnPage('node/' . $node_id);

if ($I->grabValueFrom('E-mail address') != '') {
  $I->fail();
}

if ($I->grabValueFrom('First name') != '') {
  $I->fail();
}
if ($I->grabValueFrom('Last name') != '') {
  $I->fail();
}


// // check for the salesforce mapping function
//$I->login();
//$I->amOnPage('node/' . $node_id . '/salesforce');
//$I->see('Salesforce Object Mapping', 'span');
//$I->seeOptionIsSelected(SalesforceMapPage::$objectType, 'Actions');
//$I->seeOptionIsSelected(SalesforceMapPage::$recordType, 'Petition Submission');
//// check actions object and petition record type
//$I->see(SalesforceMapPage::$objTypeLabel, 'label');
//// check to see if all the config sections are visible
//$I->see(SalesforceMapPage::$fieldMap,'span');
//$I->see(SalesforceMapPage::$component,'th');
//$I->see(SalesforceMapPage::$nodeProp,'th');
//$I->see(SalesforceMapPage::$subProp,'th');
//$I->see(SalesforceMapPage::$syncOptions,'label');
//$I->see('Contact Field','label');
//
//// check if fields are configured
//$I->seeOptionIsSelected(SalesforceMapPage::$mapMs, 'Market Source');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapNid, 'Drupal Node ID');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapSid, 'Submission ID');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapContact, 'Contact');
//
//// unmap the node and check to see if fields are no longer selected
//$I->click(SalesforceMapPage::$unmap);
//$I->cantSee(SalesforceMapPage::$fieldMap,'span');
//$I->cantSee(SalesforceMapPage::$component,'th');
//$I->cantSee(SalesforceMapPage::$nodeProp,'th');
//$I->cantSee(SalesforceMapPage::$subProp,'th');
//$I->cantSee(SalesforceMapPage::$syncOptions,'label');
//
//// remap the actions object in preparation for webform submission by anonymous visitor
//$I->selectOption(SalesforceMapPage::$objectType, 'Actions');
//$I->wait(3);
//$I->selectOption(SalesforceMapPage::$recordType, 'Petition Submission');
//$I->wait(3);
//$I->selectOption(SalesforceMapPage::$mapMs, 'Market_Source__c');
//$I->selectOption(SalesforceMapPage::$mapNid, 'Drupal_Node_ID__c');
//$I->selectOption(SalesforceMapPage::$mapSid, 'Submission_ID__c');
//$I->selectOption(SalesforceMapPage::$mapContact, 'Contact__c');
//$I->click('#edit-submit');
//$I->seeOptionIsSelected(SalesforceMapPage::$objectType, 'Actions');
//$I->seeOptionIsSelected(SalesforceMapPage::$recordType, 'Petition Submission');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapMs, 'Market Source');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapNid, 'Drupal Node ID');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapSid, 'Submission ID');
//$I->seeOptionIsSelected(SalesforceMapPage::$mapContact, 'Contact');


//$I->amOnPage(SalesforceMapPage::$queuePage);
//// there should be at least one row containing the submission
//$I->seeElement('tr.views-row-first');
//// run cron
//$I->amOnPage(SalesforceMapPage::$cronPage);
//// check to see if submission successfully processed
//$I->amOnPage(SalesforceMapPage::$queuePage);
//$I->cantSee('tr.views-row-first', '#views-form-sbv-sf-queue-page');
//$I->amOnPage(SalesforceMapPage::$batchPage);
//$I->canSeeInField('.views-row-first .views-field-failures', 0);
//
//// edit the submission and check to see that it is requeued.
//$I->amOnPage('node/' . $node_id . '/salesforce');
//$I->checkOption(SalesforceMapPage::$syncOptionsCheckbox);
//$I->amOnPage('node/' . $node_id .'/results');
//$I->click(['link' => 'Edit'], '.sticky-table');
//$I->fillField('E-mail address', 'anothermail@example.com');
//$I->click('#edit-submit');
//$I->amOnPage(SalesforceMapPage::$queuePage);
//$I->seeElement('tr.views-row-first');
