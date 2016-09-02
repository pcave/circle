<?php

//@group fundraiser;

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test Secure Prepopulate.');

// Normal Secure prepopulate test case.
$I->login();
$I->configureSecurePrepopulate('7576586e4a5cb0611e3a1f080a30615a', 'cae023134cfcbf45');
$I->configureEncrypt();
//$afToken = $I->generateSecurePrepopulateToken();
$I->logout();

$query_string = '?af=' . urlencode('7vGjVvf/xlO/nVEq8FtS+/VAjjZGbAYBHddPjrpNCN5twzqjTrOf4W/RV5MEdvbpGAZ1c5wjBeIjljD8a9A5O2iQuxjk/lVnUWBO8Vg+WRCqH0basvWtivCxQg060w4vaDEZvoBcgzkkODZ9om4pR5psjwL1Q2wtcZQxmBoOsED8UTtjo73ufEOmXR411N93bIEMiJMJnS3Wb97F2FKCRA==');

//$query_string = '?' . $afToken;
$I->amOnPage(DonationFormPage::$URL . $query_string);

$I->seeInField(DonationFormPage::$firstNameField, 'Allen');
$I->seeInField(DonationFormPage::$lastNameField, 'Freeman');
$I->seeInField(DonationFormPage::$emailField, 'allen.freeman@example.com');
$I->seeInField(DonationFormPage::$addressField, '12345 Test Dr');
$I->seeInField(DonationFormPage::$addressField2, 'Apt 2');
$I->seeInField(DonationFormPage::$cityField, 'Springfield');
$I->seeInField(DonationFormPage::$countryField, 'US');
$I->seeInField(DonationFormPage::$stateField, 'IL');
$I->seeInField(DonationFormPage::$zipField, '55555');

$I->selectOption(\DonationFormPage::$askAmountField, 10);
$I->fillInMyCreditCard();

$I->click(DonationFormPage::$donateButton);
$I->see('Allen Freeman');
$I->see('allen.freeman@example.com');
$I->see('12345 Test Dr');
$I->see('Apt 2');
$I->see('Springfield');
$I->see('US');
$I->see('IL');
$I->see('55555');

// Test case with only first and last name and no other fields.
// Overwrite existing prepopulate values as well.
$I->login();
$I->configureSecurePrepopulate('12345678901234561234567890123456', '1234567890123456');
$I->logout();

$query_string = '?af=' . urlencode('zBYBEVb6oX39UMG3b7HFlWTHbQ/L6Gd20MM2JexIVQ6msawBjeTa/MMjQFc9jnNA');
$I->amOnPage(DonationFormPage::$URL . $query_string);
$I->seeInField(DonationFormPage::$firstNameField, 'Euro');
$I->seeInField(DonationFormPage::$lastNameField, 'Guy');

$I->fillInMyName('Overwrite Euro', 'Overwrite Guy');
$I->fillField(DonationFormPage::$emailField, 'euro.guy@example.com');
$I->fillInMyAddress('123 Main St', '', 'Washington', 'DC', '12345', 'United States');
$I->fillInMyCreditCard();
$I->selectOption(\DonationFormPage::$askAmountField, 10);


$I->click(DonationFormPage::$donateButton);

$I->see('Overwrite Euro Overwrite Guy');
$I->see('euro.guy@example.com');
$I->see('123 Main St');
$I->see('Washington');
$I->see('US');
$I->see('DC');
$I->see('12345');

// Test case with some fields defined but left empty.
// Before the fix from the below pull request, this test would fail.
// https://github.com/JacksonRiver/springboard_modules/pull/676
$I->login();
$I->configureSecurePrepopulate('P4FLWBTjp2]ciKEMuy2h2rpemofXJLKf', '2QzFjpkYq8CsscZM');
$I->logout();

$query_string = '?af=' . urlencode('ggFLo2N6X8/SirEmw6RM3p2wc4K5dYoW3nJDfw3ZFpaHkmEjvzRStv5iKL3L9mYrEn+rs2WXxjj+aAVKfUc8x+ArjwuOKLpUfJC5WQZKa2OQ7EAS4iiiUNxwUvwcnZcK');
$I->amOnPage(DonationFormPage::$URL . $query_string);
$I->seeInField(DonationFormPage::$firstNameField, 'Gary');
$I->seeInField(DonationFormPage::$emailField, 'gary@example.com');

$I->fillField(DonationFormPage::$lastNameField, 'Nobody');
$I->fillField(DonationFormPage::$emailField, 'overwritegary@example.com');
$I->fillInMyAddress('123 Main St', '', 'Washington', 'DC', '11111', 'United States');
$I->fillInMyCreditCard();
$I->selectOption(\DonationFormPage::$askAmountField, 10);

$I->click(DonationFormPage::$donateButton);

$I->see('Gary Nobody');
$I->see('overwritegary@example.com');
$I->see('123 Main St');
$I->see('Washington');
$I->see('US');
$I->see('DC');
$I->see('11111');
