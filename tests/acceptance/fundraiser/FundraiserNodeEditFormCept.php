<?php

//@group fundraiser;

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Confirm the settings can be saved and changed through the node edit form.');

$I->am('admin');

$I->login();
$I->configureEncrypt();

// Import three new payment gateway configurations
for ($i = 1; $i <= 3; $i++) {
  $I->amOnPage('/admin/config/workflow/rules/reaction/import');

  $import = <<<EOT
{ "commerce_payment_commerce_payment_example_test_$i" : {
    "LABEL" : "Example payment",
    "PLUGIN" : "reaction rule",
    "TAGS" : [ "Commerce Payment" ],
    "REQUIRES" : [ "commerce_payment" ],
    "ON" : [ "commerce_payment_methods" ],
    "DO" : [
      { "commerce_payment_enable_commerce_payment_example" : {
          "commerce_order" : [ "commerce-order" ],
          "payment_method" : "commerce_payment_example"
        }
      }
    ]
  }
}
EOT;

  $I->fillField('import', $import);
  $I->checkOption('#edit-overwrite');
  $I->click('Import');
}

// Also import a paypal configuration with ACTIVE set to true.
$I->amOnPage('/admin/config/workflow/rules/reaction/import');
$import = <<<EOT
{ "commerce_payment_paypal_wps" : {
    "LABEL" : "PayPal WPS",
    "PLUGIN" : "reaction rule",
    "ACTIVE" : true,
    "TAGS" : [ "Commerce Payment" ],
    "REQUIRES" : [ "commerce_payment" ],
    "ON" : [ "commerce_payment_methods" ],
    "DO" : [
      { "commerce_payment_enable_paypal_wps" : {
          "commerce_order" : [ "commerce-order" ],
          "payment_method" : "paypal_wps"
        }
      }
    ]
  }
}
EOT;

$I->fillField('import', $import);
$I->checkOption('#edit-overwrite');
$I->click('Import');

// Go to the edit form.
$I->amOnPage('/node/2/edit');

// Enable the paypal gateway.
$I->click('Payment methods');
$I->checkOption('#edit-gateways-paypal-status');

// Fill out the paypal label.
$I->fillField('gateways[paypal][label]', 'Paypal label change 1');

// Set the payment gateway to test 1.
$I->selectOption('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_1');

// Fill out the label on the credit option.
$I->fillField('gateways[credit][label]', 'Credit card label change 1');

// Select credit as the default gateway.
$I->click('form');  //selectOption will not work without this
$I->selectOption("//input[@value='credit']", 'credit');

// Save the form.
$I->click('Save');
$I->waitForElement('#webform-component-payment-information', 10);
// Confirm the label on the credit option.
$I->see('Credit card label change 1', '#webform-component-payment-information');

// Confirm the label on the paypal option.
$I->see('Paypal label change 1', '#webform-component-payment-information');

// Confirm the default payment method is credit.
$I->seeOptionIsSelected('input[name=submitted\[payment_information\]\[payment_method\]]', 'credit');

// Go to the edit form.
$I->amOnPage('/node/2/edit');
$I->click('Payment methods');

// Confirm the payment gateway is set to test 1.
$I->seeInField('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_1');

// Set the payment gateway to test 2.
$I->selectOption('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_2');

// Change the payment label to test 2.
$I->fillField('gateways[credit][label]', 'Credit card label change 2');

// Save.
$I->click('Save');

// Confirm the label on the credit option.
$I->see('Credit card label change 2', '#webform-component-payment-information');

// Go to the edit form.
$I->amOnPage('/node/2/edit');
$I->click('Payment methods');

// Confirm the payment gateway is set to test 2.
$I->seeInField('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_2');

// Set the payment gateway to test 3.
$I->selectOption('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_3');

// Change the payment label to test 3.
$I->fillField('gateways[credit][label]', 'Credit card label change 3');

// Save.
$I->click('Save');

// Confirm the label on the credit option.
$I->see('Credit card label change 3', '#webform-component-payment-information');

// Go to the edit form.
$I->amOnPage('/node/2/edit');
$I->click('Payment methods');

// Confirm the payment gateway is set to test 3.
$I->seeInField('gateways[credit][id]', 'commerce_payment_example|commerce_payment_commerce_payment_example_test_3');

// Go to the edit form.
$I->amOnPage('/node/2/edit');
$I->click('Payment methods');

// Change the label the paypal label.
$I->fillField('gateways[paypal][label]', 'Paypal label change 2');

// Select paypal as the default gateway.
$I->selectOption('input[name=gateways\[_default\]]', 'paypal');
$I->click('Fundraiser settings');

// Fill the existing amount fields.
$I->fillField('amount_wrapper[donation_amounts][0][amount]', 25);
$I->fillField('amount_wrapper[donation_amounts][0][label]', '$25');
$I->fillField('amount_wrapper[donation_amounts][1][amount]', 35);
$I->fillField('amount_wrapper[donation_amounts][1][label]', '$35');

// Click the ajax add more button.
$I->click('#edit-amount-wrapper-amount-more');

// Wait for the additional elements to be added.
$I->waitForElementVisible('#edit-amount-wrapper-donation-amounts-2-amount');

// Fill out the new fields.
$I->fillField('amount_wrapper[donation_amounts][2][amount]', 55);
$I->fillField('amount_wrapper[donation_amounts][2][label]', '$55');

// Set the default amount.
$I->click('body');
$I->wait(2);
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->wait(2);
$I->checkOption('//td//input[@value="35"]/../../..//td//input[@type="checkbox"]');

// Save.
$I->click('Save');
// Confirm the correct amount field is selected.
$I->seeOptionIsSelected('//input[@value="35"]', 35);

// Confirm the label on the paypal option.
$I->see('Paypal label change 2', '#webform-component-payment-information');

// Confirm the default payment method is paypal.
$I->seeOptionIsSelected('input[name=submitted\[payment_information\]\[payment_method\]]', 'paypal');
