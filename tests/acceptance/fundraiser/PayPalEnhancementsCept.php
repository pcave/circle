<?php
//@group fundraiser;

$scenario->skip('Requires sensitive configuration');

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test enhanced gateway options.');

$title = 'fundraiser paypal enhancements ' . time();

$I->am('admin');
$I->login();
$I->configureEncrypt();
$paypal = new PayPalPage($I);
$paypal->configurePaypal();

// Fill out our enhanced fields.
$I->click('Enable payment method: PayPal WPS');
$I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-submit-text', 'Donate on Paypal');
$I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-intro-html', 'Introduction');
$I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-selected-image', '/misc/feed.png');
$I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-unselected-image', '/misc/grippie.png');
$I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-standalone-image', '/misc/druplicon.png');
$I->click('Save');
$I->acceptPopup();

// Enable paypal on the default donation form.
$I->amOnPage('node/2/edit');
$I->click('Payment methods');
$I->unCheckOption('#edit-gateways-credit-status');
$I->checkOption('#edit-gateways-paypal-status');
$I->click('Save');

// Check for custom gateway submit text.
$I->amOnPage('node/2');
$I->seeElement('//input[@value="Donate"]');
$I->amOnPage('springboard/node/2/form-components/confirmation-page-settings');
$I->click('Advanced settings');
$I->fillField('#edit-submit-text', '');
$I->click('Save configuration');
$I->amOnPage('node/2');
$I->seeElement('//input[@value="Donate on Paypal"]');

// Check for standalone image and intro text.
$I->seeElement('//img[@src="/misc/druplicon.png"]');
$I->see('Introduction');

// Enable the credit payment method.
$I->amOnPage('node/2/edit');
$I->click('Payment methods');
$I->checkOption('#edit-gateways-credit-status');
$I->fillField('#edit-gateways-paypal-label', 'Paypal');
$I->fillField('#edit-gateways-credit-label', 'Credit');
$I->selectOption('#edit-gateways-default--2', 'credit');
$I->click('Save');

// Check for selected and unselected gateway images.
$I->amOnPage('node/2');
$I->seeElement('//img[@src="/misc/grippie.png"]');
$I->moveMouseOver('//img[@src="/misc/grippie.png"]');
$I->seeElement('//img[@src="/misc/feed.png"]');
$I->selectOption('//input[@name="submitted[payment_information][payment_method]"]', 'paypal');
$I->see('Introduction');

// Make a donation.
$I->fillInMyName();
$I->fillInMyAddress();
$I->selectOption('//input[@name="submitted[donation][amount]"]', 10);
$I->click('//input[@value="Donate on Paypal"]');
//$I->see('Please wait while you are redirected to the payment server.');
//$I->click('//input[@value="Proceed to PayPal"]');

// Log into paypal
$config = \Codeception\Configuration::config();
$settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
$I->fillField('#login_email', $settings['PayPal']['paypal_payer']);
$I->fillField('#login_password', $settings['PayPal']['paypal_payer_pass']);
$I->click('#submitLogin');
$I->wait(30);

// Cancel the donation
$I->waitForElement("#cancel_return", 20);
$I->click("#cancel_return");
$I->seeInCurrentUrl('node/2');

// Make another donation.
$I->selectOption('//input[@name="submitted[payment_information][payment_method]"]', 'paypal');
$I->selectOption('//input[@name="submitted[donation][amount]"]', 10);

// Log into paypal and complete the donation.
$I->click('//input[@value="Donate on Paypal"]');
$I->fillField('#login_password', $settings['PayPal']['paypal_payer_pass']);
$I->click('#submitLogin');
$I->waitForElement('//input[@name="continue"]', 20);
$I->click('//input[@name="continue"]');

// Verify redirect.
try {
  $I->wait(10);
  // If redirecting to non-https url
 $I->acceptPopup();
}
catch (Exception $e) {
}

$I->seeInCurrentUrl('node/2/done');

//PayPal Recurring Only Test
//Update the donation form recurring options on a donation form so that recurring is an option on the form.
//View your form and toggle the recurring option. Verify that the PayPal radio option goes away when recurring is selected on the form.
$I->amOnPage('node/2/edit');
$I->click('Fundraiser settings');
$I->selectOption('#edit-recurring-setting', 'always');
$I->click('Save');
$I->amOnPage('node/2');
$I->dontSeeElement('//input[@value="Donate on Paypal"]');

// @todo IPN won't work locally, hmmm.
//Support refunds for PayPal transactions
//Confirm that you are returned to the original donation form.
//Go to an existing PayPal donation and click on the Payment tab.
//Press refund and verify that the process works. In this test, use a full refund.
//Go to Revisions tab for donation and verify that the refund took place.
//Go to an existihg PayPal donation and click on the Payment tab.
//Press refund and verify that you can do a partial refund.
//Go to Revisions tab for donation and verify that the refund took place.
//Go to the donation report and export.
//In your export, verify that you can see the refunds.
//Log into the PayPal sandbox associated with your donations.
//Verify that you can see the correct refunds in the sandbox.

