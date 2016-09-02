<?php
//@group fundraiser;
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test fundraiser date mode');

$title = 'fundraiser offline test ' . time();

$I->am('admin');
$I->login();
$I->configureEncrypt();
$I->enableModule('Fundraiser Date Mode');
$I->amOnPage('admin/people/permissions');
$I->see('Administer Fundraiser date mode');
$I->amOnPage('node/2');
$I->makeADonation(array(), TRUE);

$today = date('j');
if ($today > 26) {
  $today = 26;
}
else {
  $today = $today + 1;
}

// Configure date mode.
$I->amOnPage('admin/config/system/fundraiser/date-mode');
$I->checkOption('#edit-fundraiser-date-mode-set-date-mode');
$I->selectOption('#edit-fundraiser-date-mode-set-dates', $today);
$I->fillField('#edit-fundraiser-date-mode-batch-record-count', 500);
$I->checkOption('#edit-fundraiser-date-mode-skip-on-cron');
$I->selectOption('#edit-fundraiser-date-mode-set-seconds', 22);
$I->click('Save configuration');
$I->waitForElement('#edit-fundraiser-date-mode-set-seconds', 10); //time for ajax process

// Check if date mode updated existing series correctly.
$I->amOnPage('springboard/donations/1/recurring');
$new_date = '/' . $today . '/' . date('y');

$I->see($new_date);

// Set date mode to a different date.
$I->amOnPage('admin/config/system/fundraiser/date-mode');
$I->selectOption('#edit-fundraiser-date-mode-set-dates', $today + 1);

$I->click('Save configuration');
$I->waitForElement('#edit-fundraiser-date-mode-set-seconds', 10); //time for ajax process


// Check if date mode updated existing series correctly.
$I->amOnPage('springboard/donations/1/recurring');
$new_date = '/' . ($today + 1) . '/' . date('y');

$I->see($new_date);

// Unset date mode
$today = date('j');
$I->amOnPage('admin/config/system/fundraiser/date-mode');
$I->unCheckOption('#edit-fundraiser-date-mode-set-date-mode');
$I->wait('4');
$I->click('Save configuration');
$I->waitForElement('#edit-fundraiser-date-mode-set-seconds', 10); //time for ajax process


$I->amOnPage('springboard/donations/1/recurring');
$original_date = '/' . ($today) . '/' . date('y');
$I->see($original_date);

//@todo sustainer processing, salesforce