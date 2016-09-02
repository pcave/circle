<?php

//@group misc

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Enable Springboard Social on a donation form.');

// @todo Test BCC

// Form defaults
$facebook_title = 'Acceptance testing in Facebook is rad.';
$facebook_description = 'Seriously, Facebook Acceptance testing is the best thing ever.';
$facebook_image = '';

$twitter_message = 'Acceptance testing in Twitter is rad.';


$email_subject = 'Acceptance testing email subject.';
$email_message = 'Acceptance testing email message.';

// Log in an admin account.
$I->am('admin');
$I->login();
$I->configureEncrypt();
$admin = new SpringboardSocialAdminPage($I);
$shorten = new ShortenURLsAdminPage($I);
$marketsource = new MarketSourceAdminPage($I);

// Enable Springboard Social;
$admin->enableModule();

// Configure defaults
$admin->setAdminDefaults();

// Enable Share block.
$admin->enableBlock();

// Config Shorten URLS
$shorten->setAdminDefaults();

// Configure Market Source integration
// TODO: fix css on Social enable checkboxes so Selenium can check them.

$I->amOnPage($marketsource->URL);
// enable MS and CID global default fields
$marketsource->showDefaultFieldSettings();
$I->checkOption('#edit-market-source-default-fields-default-fields-wrapper-market-source-share-enabled');
$I->checkOption('#edit-market-source-default-fields-default-fields-wrapper-campaign-share-enabled');

// Create custom field & enable with Social
$marketsource->createCustomField('UTM Medium', 'utm_medium', 'utm_social_test');
$I->checkOption('#edit-market-source-global-fields-custom-fields-wrapper-0-share-enabled');
$I->click('#edit-submit');

// ### END BASE CONFIG ###


// Configure node-level settings on donation form.
$I->amOnPage('/node/2/share_settings');
// Save defaults
// TODO: set default values for enabled Market Source fields.

$I->click('#edit-submit');

// TODO: clone donation form & capture node id.

// confirm share display in block
/*$I->amOnPage('/node/2');
$I->see('Share on Facebook!');
$I->see('Share with Email!');
$I->see('Share on Twitter!');*/

// Configure confirmation message
$I->amOnPage('node/2/form-components/confirmation-page-settings');
// TODO: add all Social Share tokens.
$confirmation_message = $I->grabTextFrom('#edit-confirmation-value');
$I->fillField('#edit-confirmation-value', $confirmation_message . 'Share links: [sb_social:share_links]');
$I->click('#edit-submit');
$I->wait(4);
$I->logout();
$I->wait(4);
// Submit donation form
$I->amOnPage('node/2');
$I->see('Share on Facebook!');
$I->see('Share with Email!');
$I->see('Share on Twitter!');

$I->makeADonation();
$I->see('Share on Facebook!');
$I->see('Share with Email!');
$I->see('Share on Twitter!');

// twitter message + share URL
$I->click('Share on Twitter!');
$I->wait(3);
// Switch to Twitter popup window.
$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
     $handles=$webdriver->getWindowHandles();
     $last_window = end($handles);
     $webdriver->switchTo()->window($last_window);
});
$I->see('Global default Twitter message.');
//$twitter_share = $I->grabTextFrom('span.field textarea#status');
// TODO: get share URL
//preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match);

// TODO: close popup
// Switch back to main window
$I->switchToWindow();

// email subject, message, & share url
$I->click('Share with Email!');

// confirm share tokens replace in confirmation message
// confirm share urls generated correctly

$I->logout();
