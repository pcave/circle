<?php
//@group fundraiser;

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Test fundraiser sustainer upgrade.');

$title = 'fundraiser sustainer upgrade ' . time();

$I->am('admin');
$I->logIn();
$I->configureEncrypt();
$I->configureSecurePrepopulate('rSJ22SIRITmX9L7ikkLMVwqD90g9SSQg', 'OCb34r2i3tfMvPZD');
$I->enableModule('Fundraiser Sustainer Upgrade');

$I->logOut();

// Make donation.
$I->amOnPage('node/2');
$I->makeADonation(array('first_name' => 'John', 'last_name' => 'Tester', 'amount' => '10'), TRUE);
$I->see("Thank you John Tester for your donation of $10.00.");

$I->am('admin');
$I->logIn();

$I->amOnPage('springboard/donations/1/payment');
// Grab donation date.
$date = $I->grabTextFrom('td.views-field-created');

// Test as logged in user
doSustainerSteps($I, $date);

// Test as  anon.
doSustainerSteps($I, $date, TRUE);

// Test with wrong logged in user.
$I->am('admin');
$I->login();
$I->createUser();
$url = $I->generateSustainerUpgradeToken(TRUE, "1001", 2, 1);
$I->login('testuser', 'testuser');
$I->amOnUrl($url);
$I->see("An error occurred and we could not complete the operation.");

function doSustainerSteps($I, $date, $anon = FALSE) {

  // Generate a token.
  $url = $I->generateSustainerUpgradeToken($anon, "1001", 2, 1);

  // Go to the upgrade form.
  $I->amOnUrl($url);

  // Check form text and tokens.
  $I->see("Thank you John Tester. (Not John? Click here.) To upgrade your monthly donation to $10.01, click Confirm below.");
  $I->see("Your original donation for $10.00, was made on");
  $I->see($date);
  $I->see("with your card ending in 1111.");
  $I->see("charges remaining in this series, which will be upgraded to $10.01");

  // Submit.
  $I->click("#edit-submit");

  // Check confirmation text.
  $I->see("Default Sustainers Upgrade Form");
  $I->see("Thank you John Tester for upgrading your sustaining donation to $10.01.");

  // Generate a token.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 2, 1);

  // Go to the upgrade form.
  $I->amOnUrl($url);

  // Cancel the upgrade.
  $I->click('Click here');
  $I->canSeeInCurrentUrl("node/2");

  // Go to the upgrade form with expired token.
  $I->amOnUrl($url);
  $I->see('An error occurred and we could not complete the operation.');
  if (!$anon) {
    $I->see("Authentication token has already been used.");
  }

  // Use an invalid User ID.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 6, 1);
  $I->amOnUrl($url);
  $I->see('An error occurred and we could not complete the operation.');
  if (!$anon) {
    $I->see("Invalid User ID.");
  }

  // Use an invalid donation ID.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 2, 0);
  $I->amOnUrl($url);
  $I->see('Donation upgrade can not be completed.');
  if (!$anon) {
    $I->see("Donation ID not found in session.");
  }

  // Use an invalid sustainers ID.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 2, 3);
  $I->amOnUrl($url);
  $I->see('Donation upgrade can not be completed.');
  if (!$anon) {
    $I->see("Donation ID is not a master ID.");
  }


  // Use an invalid doantion amount.
  $url = $I->generateSustainerUpgradeToken($anon, "555", 2, 1);
  $I->amOnUrl($url);
  $I->see('Donation upgrade can not be completed.');
  if (!$anon) {
    $I->see("The upgrade amount is lower than or equal to the current donation. Donation amount must be greater than $10.00.");
  }

  // Use an invalid form id ID.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 2, 1, 77);
  $I->amOnUrl($url);
  $I->see("Thank you John Tester. (Not John? Click here.) To upgrade your monthly donation to $10.02, click Confirm below.");

  // Use a valid form id ID.
  $url = $I->generateSustainerUpgradeToken($anon, "1002", 2, 1, 4);
  $I->amOnUrl($url);
  $I->see("Thank you John Tester. (Not John? Click here.) To upgrade your monthly donation to $10.02, click Confirm below.");

  // Do a rollback.
  $url = $I->generateSustainerUpgradeToken($anon, "1000", 2, 1, NULL, TRUE);
  $I->amOnUrl($url);
  $I->see("Hello John Tester. (Not John? Click here.) To rollback your sustaining donation to $10.00, click Confirm below.");
  // Submit.
  $I->click("#edit-submit");
  $I->see("Your donation upgrade has been canceled and rolled back to $10.00.");
}
