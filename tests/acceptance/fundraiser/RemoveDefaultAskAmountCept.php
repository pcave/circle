<?php

//@group fundraiser;

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('remove the default ask amount.');

$I->am('admin');

$I->login();
$I->configureEncrypt();

$I->cloneADonationForm();
// Checkbox went away in SB 4.10
// $I->checkOption('No default ask amount');
// $I->click('Save');

$I->dontSeeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-0-default-amount');
$I->dontSeeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-1-default-amount');
$I->dontSeeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-2-default-amount');
$I->dontSeeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-3-default-amount');
$I->dontSeeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-5-default-amount');

$I->click('Edit');
$I->checkOption('#edit-amount-wrapper-donation-amounts-2-default-amount');
#$I->click('Save');
$I->seeCheckboxIsChecked('#edit-amount-wrapper-donation-amounts-2-default-amount');

$I->logout();
