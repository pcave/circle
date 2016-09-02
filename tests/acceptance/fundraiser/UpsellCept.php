<?php

//@group fundraiser;

$I = new \AcceptanceTester\SpringboardSteps($scenario);

$I->wantTo('configure upsell and complete an upsell.');

$I->am('admin');
$I->wantTo('configure Upsell.');

$I->login();
$I->configureEncrypt();
$I->installModule('Fundraiser Sustainer Upsell');

$I->amOnPage('/admin/springboard/options/fundraiser/fundraiser_upsell');

$I->fillField('#edit-fundraiser-upsell-brackets-low-0', '0');
$I->fillField('#edit-fundraiser-upsell-brackets-high-0', '50');
$I->fillField('#edit-fundraiser-upsell-brackets-upsell-0', '25');

$I->fillField('#edit-fundraiser-upsell-brackets-low-1', '51');
$I->fillField('#edit-fundraiser-upsell-brackets-high-1', '100');
$I->fillField('#edit-fundraiser-upsell-brackets-upsell-1', '75');

$I->fillField('#edit-fundraiser-upsell-brackets-low-2', '101');
$I->fillField('#edit-fundraiser-upsell-brackets-high-2', '200');
$I->fillField('#edit-fundraiser-upsell-brackets-upsell-2', '150');

$I->fillField('#edit-fundraiser-upsell-brackets-low-3', '201');
$I->fillField('#edit-fundraiser-upsell-brackets-high-3', '300');
$I->fillField('#edit-fundraiser-upsell-brackets-upsell-3', '250');

$I->click('Add another');
$I->waitForElement('#edit-fundraiser-upsell-brackets-high-4', 10);

$I->fillField('#edit-fundraiser-upsell-brackets-low-4', '301');
$I->fillField('#edit-fundraiser-upsell-brackets-high-4', '400');
$I->fillField('#edit-fundraiser-upsell-brackets-upsell-4', '350');

$I->seeOptionIsSelected('#edit-fundraiser-upsell-default-charge-time-one-month', 'one_month');

$I->fillField('#edit-fundraiser-upsell-default-content', 'Get an upsell joker');
$I->fillField('#edit-fundraiser-upsell-default-content-disclaimer', 'I disclaim.');

$I->checkOption('#edit-fundraiser-upsell-acceptance-enabled');
$I->fillField('#edit-fundraiser-upsell-acceptance-lifetime', '30');

$I->checkOption('#edit-fundraiser-upsell-rejection-enabled');
$I->fillField('#edit-fundraiser-upsell-rejection-lifetime', '30');

$I->click('Save configuration');

$I->see('The configuration options have been saved.', '.status');

$I->seeInField('#edit-fundraiser-upsell-brackets-low-4', '301');
$I->seeInField('#edit-fundraiser-upsell-brackets-high-4', '400');
$I->seeInField('#edit-fundraiser-upsell-brackets-upsell-4', '350');

// @todo More assertions here.

$I->click('Thank you settings');

$I->fillField('#edit-fundraiser-upsell-thank-you-content', 'Thanks for upselling bro.');
$I->fillField('#edit-subject', 'This is the subject.');
$I->fillField('#edit-html-body-value', 'This is the html.');
$I->fillField('#edit-text-body', 'This is the text.');
$I->click('Mail headers');
$I->fillField('#edit-from-name', 'From name');
$I->fillField('#edit-from-mail', 'bob@example.com');
$I->click('Save');

$I->am('admin');
$I->wantTo('create a new donation form and Upsell enable it using default values.');

$I->amOnPage('/node/add/donation-form');
$I->fillField('Title', 'My upsell form');
$I->fillField('Internal Name', 'My upsell form internal name');
$I->click('Upsell settings');
$I->checkOption('Upsell enabled');
$I->seeOptionIsSelected('#edit-fundraiser-upsell-charge-time-one-month', 'one_month');
$I->seeInField('Form specific Upsell Content', 'Get an upsell joker');
$I->seeInField('Form specific Upsell Footer', 'I disclaim.');
$I->seeInField('Thank you content', 'Thanks for upselling bro.');
$I->seeInField('From name', 'From name');
$I->seeInField('From mail', 'bob@example.com');
$I->seeInField('Subject', 'This is the subject.');
$I->seeInField('HTML message version', 'This is the html.');
$I->seeInField('Text message version', 'This is the text.');
$I->click('Save');
$I->click('View');
$url = $I->grabFromCurrentUrl();

$I->logout();

$I->am('donor');
$I->wantTo('complete an upsell.');

$I->amOnPage($url);

$I->makeADonation();

//won't work without encrypt being configured.

$I->expectTo('see an upsell modal.');
//
$I->waitForText('Monthly donation', 10);
//
$I->see('Monthly donation', '#modalContent');
$I->see('Get an upsell joker', '#modalContent');
$I->seeElement('#modalContent input[type=submit]');

$I->see('No thanks', '#modalContent');
$I->seeLink('No thanks');

$I->click('Yes, Sign Me Up!');

$I->waitForText('Close', 20, '#modalContent');
$I->see('Thanks for upselling bro.', '#modalContent');
$I->click('Close', '#modalContent');
$I->see('Thank you');

$I->am('admin');
$I->login();
$I->uninstallModule('Fundraiser Sustainer Upsell');
$I->logout();
