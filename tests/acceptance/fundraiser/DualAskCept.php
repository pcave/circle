<?php
//@group fundraiser;


$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test fundraiser dual ask amount functions.');

$title = 'fundraiser dual ask test ' . time();

$I->am('admin');
$I->login();
$I->configureEncrypt();
//check server-side validation of payment method selection
$I->amOnPage(DonationFormPage::route('/edit'));
$I->seeOptionIsSelected("#edit-recurring-setting", "Donor chooses one-time or recurring");
$I->checkOption('#edit-recurring-dual-ask-amounts');
$I->click('Save');
$I->seeElement('#webform-component-donation--recurs-monthly');
$I->amOnPage(DonationFormPage::route('/edit'));
$I->seeElement('#fundraiser-recurring-ask-amounts-table');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][0][amount]"]', 11);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][1][amount]"]', 22);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][2][amount]"]', 33);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][3][amount]"]', 44);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][0][label]"]', '$11');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][1][label]"]', '$22');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][2][label]"]', '$33');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][3][label]"]', '$44');
$I->click('Save');
$I->executeJS("jQuery('input[value=\"recurs\"]').siblings('label').click()");
$I->see('$11');
$I->makeADonation(array('first_name' => 'John', 'last_name' => 'Tester', 'amount' => '11'), TRUE, TRUE);
$I->see("Thank you John Tester for your donation of $11.00.");
$I->amOnPage(DonationFormPage::route('/'));
$I->executeJS("jQuery('input[value=\"onetime\"]').siblings('label').click()");
$I->see('$10');
$I->makeADonation(array('first_name' => 'John', 'last_name' => 'Tester', 'amount' => '10'), FALSE);
$I->see("Thank you John Tester for your donation of $10.00.");
$I->amOnPage(DonationFormPage::route('/edit'));
$I->selectOption("#edit-recurring-setting", "Recurring only");
$I->click('Save');
$I->see('$11');
$I->dontSee('One-time');
$I->makeADonation(array('first_name' => 'John', 'last_name' => 'Tester', 'amount' => '11'), TRUE, FALSE, TRUE);
$I->see("Thank you John Tester for your donation of $11.00.");
$I->amOnPage(DonationFormPage::route('/edit'));
$I->selectOption("#edit-recurring-setting", "One-time only");
$I->click('Save');
$I->makeADonation(array('first_name' => 'John', 'last_name' => 'Tester', 'amount' => '11'), FALSE);
$I->see("Thank you John Tester for your donation of $11.00.");
