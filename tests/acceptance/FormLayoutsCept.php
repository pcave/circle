<?php
//@group misc

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Enable and test form layouts');

// We need a unique title so we can pick it from the template list.
$title = 'Form layouts test ' . time();

$I->am('admin');
$I->login();
$I->enableModule('Form Layouts');
$I->amOnPage('admin/structure/types/manage/donation_form');
$I->click('Form layout settings');
$I->checkOption('//input[@name="form_layouts"]');
$I->click('//input[@value="Save content type"]');
$I->amOnPage('node/2/edit');
$I->wait(1);
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','two_column_donation');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/2');

$I->seeElement('div#left #webform-component-donor-information');
$I->seeElement('div#left #edit-submitted-donor-information-first-name');
$I->seeElement('div#left #edit-submitted-donor-information-mail');
$I->seeElement('div#left #edit-submitted-billing-information-address');
$I->seeElement('div#left #edit-submitted-billing-information-address-line-2');
$I->seeElement('div#left #edit-submitted-billing-information-city');
$I->seeElement('div#left #edit-submitted-billing-information-country');
$I->seeElement('div#left #edit-submitted-billing-information-state');
$I->seeElement('div#left #edit-submitted-billing-information-zip');


$I->seeElement('div#right #webform-component-donation');
$I->seeElement('div#right #edit-submitted-donation-amount');
$I->seeElement('div#right #edit-submitted-donation-other-amount');
$I->seeElement('div#right #edit-submitted-payment-information-payment-fields-credit-card-number');
$I->seeElement('div#right #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-month');
$I->seeElement('div#right #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-year');
$I->seeElement('div#right #edit-submitted-payment-information-payment-fields-credit-card-cvv');
$I->seeElement('div#right #edit-submitted-payment-information-recurs-monthly-1');
$I->seeElement('#donation-form-footer #edit-submit');

$I->amOnPage('node/2/edit');
$I->wait(1);
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','two_column_hybrid_donation');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/2');

$I->seeElement('div.row-fluid > #webform-component-donation');
$I->seeElement('div.row-fluid  #edit-submitted-donation-amount');
$I->seeElement('div.row-fluid  #edit-submitted-donation-other-amount');


$I->seeElement('div.row-fluid > div#left #webform-component-donor-information');
$I->seeElement('div.row-fluid > div#left #edit-submitted-donor-information-first-name');
$I->seeElement('div.row-fluid > div#left #edit-submitted-donor-information-mail');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-address');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-address-line-2');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-city');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-country');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-state');
$I->seeElement('div.row-fluid > div#left #edit-submitted-billing-information-zip');


$I->seeElement('div.row-fluid > div#right #edit-submitted-payment-information-payment-fields-credit-card-number');
$I->seeElement('div.row-fluid > div#right #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-month');
$I->seeElement('div.row-fluid > div#right #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-year');
$I->seeElement('div.row-fluid > div#right #edit-submitted-payment-information-payment-fields-credit-card-cvv');
$I->seeElement('div.row-fluid > div#right #edit-submitted-payment-information-recurs-monthly-1');
$I->seeElement('#donation-form-footer #edit-submit');

$I->amOnPage('node/2/edit');
$I->wait(1);
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','two_column_left_right');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/2');

$I->seeElement('div#right #webform-component-donor-information');
$I->seeElement('div#right #edit-submitted-donor-information-first-name');
$I->seeElement('div#right #edit-submitted-donor-information-mail');
$I->seeElement('div#right #edit-submitted-billing-information-address');
$I->seeElement('div#right #edit-submitted-billing-information-address-line-2');
$I->seeElement('div#right #edit-submitted-billing-information-city');
$I->seeElement('div#right #edit-submitted-billing-information-country');
$I->seeElement('div#right #edit-submitted-billing-information-state');
$I->seeElement('div#right #edit-submitted-billing-information-zip');


$I->seeElement('div#left #webform-component-donation');
$I->seeElement('div#left #edit-submitted-donation-amount');
$I->seeElement('div#left #edit-submitted-donation-other-amount');
$I->seeElement('div#left #edit-submitted-payment-information-payment-fields-credit-card-number');
$I->seeElement('div#left #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-month');
$I->seeElement('div#left #edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-year');
$I->seeElement('div#left #edit-submitted-payment-information-payment-fields-credit-card-cvv');
$I->seeElement('div#left #edit-submitted-payment-information-recurs-monthly-1');
$I->seeElement('#donation-form-footer #edit-submit');

$I->enableModule('Springboard Advocacy');
$I->enableModule('Springboard Petition');

$I->amOnPage('admin/structure/types/manage/springboard_petition');
$I->click('Form layout settings');
$I->checkOption('//input[@name="form_layouts"]');
$I->click('//input[@value="Save content type"]');
$I->amOnPage('node/4/edit');
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','two_column_petition_form_left');
$I->fillField('//input[@name="field_webform_user_internal_name[und][0][value]"]', '---');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/4');

$I->seeElement('div#left #edit-submitted-mail');
$I->seeElement('div#left #edit-submitted-sbp-rps-optin-1');
$I->seeElement('div#left #edit-submitted-sbp-first-name');
$I->seeElement('div#left #edit-submitted-sbp-last-name');
$I->seeElement('div#left #edit-submit');
$I->seeElement('div#right .field-name-body');

$I->amOnPage('node/4/edit');
$I->wait(1);
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','two_column_petition_form_right');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/4');
$I->seeElement('div#right #edit-submitted-mail');
$I->seeElement('div#right #edit-submitted-sbp-rps-optin-1');
$I->seeElement('div#right #edit-submitted-sbp-first-name');
$I->seeElement('div#right #edit-submitted-sbp-last-name');
$I->seeElement('div#right #edit-submit');
$I->seeElement('div#left .field-name-body');
$I->amOnPage('node/4/edit');
$I->wait(1);
$I->click('Display settings');
$I->wait(1);
$I->selectOption('form_layouts','one_column_petition_form_top');
$I->click('//input[@value="Save"]');
$I->amOnPage('node/4');
$I->seeElement('div#left #edit-submitted-mail');
$I->seeElement('div#left #edit-submitted-sbp-rps-optin-1');
$I->seeElement('div#left #edit-submitted-sbp-first-name');
$I->seeElement('div#left #edit-submitted-sbp-last-name');
$I->seeElement('div#left #edit-submit');
$I->seeElement('div#right .field-name-body');



