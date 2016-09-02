<?php
//@group fundraiser;
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test fundraiser offline');

$title = 'fundraiser offline test ' . time();

$I->am('admin');
$I->login();
$I->configureEncrypt();
$I->enableModule('Fundraiser Offline');
$I->amOnPage('node/2/offline');
$I->fillInMyName();
$I->fillInMyAddress();
$I->fillInMyCreditCard();
$I->selectOption('//input[@name="submitted[donation][amount]"]', 10);
$I->click('#edit-submit');
$I->amOnPage('user/2/edit');
$mail = $I->grabValueFrom('#edit-mail');
if (strpos($mail, '@sb-offline-donation.com') !== FALSE) {
}
else {
  $I->see('this test will fail');
}
