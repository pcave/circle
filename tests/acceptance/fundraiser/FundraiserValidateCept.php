<?php
//@group fundraiser;


$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test fundraiser validation.');
$I->login();
$I->configureEncrypt();
$I->logout();
$title = 'fundraiser validation test ' . time();

// Fill out fundraiser donation page form
$I->amOnPage(DonationFormPage::$URL);
$I->fillField(DonationFormPage::$otherAmountField, '120');
$I->fillField(DonationFormPage::$firstNameField, 'Miles');
$I->fillField(DonationFormPage::$lastNameField, 'Davis');
$I->fillField(DonationFormPage::$emailField, 'md@example.com');
$I->fillField(DonationFormPage::$addressField, '10 Fusion Drive');
$I->fillField(DonationFormPage::$addressField2, 'Suite Cool');
$I->fillField(DonationFormPage::$cityField, 'Jazzville');
$I->selectOption(DonationFormPage::$stateField, 'NY');
$I->selectOption(DonationFormPage::$countryField, 'US');
$I->fillField(DonationFormPage::$zipField, '12345');
$I->fillField(DonationFormPage::$creditCardNumberField, '4111111111111111');
$I->selectOption(DonationFormPage::$creditCardExpirationMonthField, '6');
$I->selectOption(DonationFormPage::$creditCardExpirationYearField, '2017');
$I->fillField(DonationFormPage::$CVVField, '123');

//click on neutral space to remove focus from the last filled element
$I->click('#webform-client-form-2');

$I->waitForElementVisible('#edit-submitted-donation-other-amount',20);

// check for validation classes
$I->seeElement('#edit-submitted-donation-other-amount.valid');
$I->seeElement('#edit-submitted-donor-information-first-name.valid');
$I->seeElement('#edit-submitted-donor-information-last-name.valid');
$I->seeElement('#edit-submitted-donor-information-mail.valid');
$I->seeElement('#edit-submitted-billing-information-address.valid');
$I->seeElement('#edit-submitted-billing-information-address-line-2.valid');
$I->seeElement('#edit-submitted-billing-information-city.valid');
$I->seeElement('#edit-submitted-billing-information-state.valid');
$I->seeElement('#edit-submitted-billing-information-zip.valid');
$I->seeElement('#edit-submitted-payment-information-payment-fields-credit-card-number.valid');
$I->seeElement('#edit-submitted-payment-information-payment-fields-credit-card-cvv.valid');

// fill fields with invalid values and check validation
$I->fillField(DonationFormPage::$otherAmountField, '0');
$I->see('The amount entered is less than the minimum donation amount.');
$I->fillField(DonationFormPage::$firstNameField, '');
$I->click('#webform-client-form-2');
$I->wait(5);
$I->see('This field is required');
$I->fillField(DonationFormPage::$lastNameField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$emailField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$addressField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$cityField, ' ');
$I->see('This field is required');
$I->selectOption(DonationFormPage::$stateField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$zipField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$creditCardNumberField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$CVVField, '');
$I->see('This field is required');
$I->fillField(DonationFormPage::$emailField, 'asdsadasd');
$I->see('Enter a valid email address');
$I->fillField(DonationFormPage::$zipField, '123');
$I->see('Enter a valid zipcode.');
$I->fillField(DonationFormPage::$creditCardNumberField, '41111111');
$I->see('Enter a valid credit card number.');
