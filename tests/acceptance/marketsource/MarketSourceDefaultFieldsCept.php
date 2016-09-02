<?php
//@group marketsource

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test that Market Source default fields are created.');

$I->am('admin');
$I->login();

$I->amOnPage('/node/add/webform');
$I->fillField('Title', 'market source test');
$I->click('Save');

$I->see('Market Source');
$I->see('Campaign ID');
$I->see('Referrer');
$I->see('Initial Referrer');
$I->see('Search Engine');
$I->see('Search String');
$I->see('User Agent');

$I->click('View');
$I->click('Submit');
$I->see('Thank you, your submission has been received.');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('default_ms', '#webform-component-ms');
$I->see('/form-components/components', '#webform-component-referrer');
