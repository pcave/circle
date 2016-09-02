<?php
//@group fundraiser;
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Submit remotely to a form with dual ask amounts.');
$title = 'fundraiser dual ask API test ' . time();
$I->am('admin');
$I->login();
$api_key = $I->configureSpringboardAPI();

///**
// *  Forms to test
// *  63 : QA1 - Donor chooses with the same ask amounts and no default
// *  64 : QA2 - Donor chooses with the same ask amounts and a default amount
// *  65 : QA3 - Donor chooses with two different ask strings and no default amounts
// *  66 : QA4 - Donor chooses with two different ask strings and default amounts
// *  67 : QA5 - Recurring only form with a default amount set
// *  68 : QA6 - One-time form with a default amount set
// **/


$I->amOnPage(DonationFormPage::route('/edit'));
$I->seeOptionIsSelected("#edit-recurring-setting", "Donor chooses one-time or recurring");
$I->checkOption('#edit-recurring-dual-ask-amounts');
$I->click('Save');
$donation63 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation63);

$I->amOnPage(DonationFormPage::route('/edit'));
$I->checkOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->seeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->click('Save');
$donation64 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation64);

$I->amOnPage(DonationFormPage::route('/edit'));
$I->unCheckOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][0][amount]"]', 11);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][1][amount]"]', 22);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][2][amount]"]', 33);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][3][amount]"]', 44);
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][0][label]"]', '$11');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][1][label]"]', '$22');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][2][label]"]', '$33');
$I->fillField('//input[@name="recurring_amount_wrapper[recurring_donation_amounts][3][label]"]', '$44');
$I->click('Save');
$donation65 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation65);

$I->amOnPage(DonationFormPage::route('/edit'));
$I->checkOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->checkOption('#edit-recurring-amount-wrapper-recurring-donation-amounts-2-default-amount');
$I->click('Save');
$donation66 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation66);


$I->amOnPage(DonationFormPage::route('/edit'));
$I->selectOption("#edit-recurring-setting", "Recurring only");
$I->checkOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->click('Save');
$donation67 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation67);


$I->amOnPage(DonationFormPage::route('/edit'));
$I->selectOption("#edit-recurring-setting", "One-time only");
$I->checkOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->click('Save');
$donation67 = array_merge($I->donationData(), array('amount' => 'other', 'other_amount' => 50));
$I->makeApiDonation($api_key, 2, $donation67);