<?php
//@group webform


// Acceptance tests for webform confirmations.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test webform confirmation pages.');

$I->am('admin');
$I->login();
$I->amOnPage('admin/config/system/encrypt');
$I->fillField('Secure Key Path', '/tmp');
$I->click("Save configuration");
$I->nid = $I->cloneADonationForm();
$confirmationMessage = <<<MESSAGE
<p id="donation-address">[donation:address]</p>
<p id="donation-address_line_2">[donation:address_line_2]</p>
<p id="donation-amount">[donation:amount]</p>
<p id="donation-card_expiration_month">[donation:card_expiration_month]</p>
<p id="donation-card_expiration_year">[donation:card_expiration_year]</p>
<p id="donation-card_type">[donation:card_type]</p>
<p id="donation-city">[donation:city]</p>
<p id="donation-country">[donation:country]</p>
<p id="donation-did">[donation:did]</p>
<p id="donation-payment-transaction">[donation:payment-transaction]</p>
<p id="donation-mail">[donation:mail]</p>
<p id="donation-first_name">[donation:first_name]</p>
<p id="donation-card_number">[donation:card_number]</p>
<p id="donation-last_name">[donation:last_name]</p>
<p id="donation-payment_method">[donation:payment_method]</p>
<p id="donation-quantity">[donation:quantity]</p>
<p id="donation-recurs_monthly">[donation:recurs_monthly]</p>
<p id="donation-state">[donation:state]</p>
<p id="donation-zip">[donation:zip]</p>
<p id="user-sbp_address_line_2">[donation:user:sbp_address_line_2]</p>
<p id="user-sbp_city">[donation:user:sbp_city]</p>
<p id="user-sbp_country">[donation:user:sbp_country]</p>
<p id="user-created">[donation:user:created]</p>
<p id="user-mail">[donation:user:mail]</p>
<p id="user-sbp_first_name">[donation:user:sbp_first_name]</p>
<p id="user-sbp_last_name">[donation:user:sbp_last_name]</p>
<p id="user-sbp_name">[donation:user:name]</p>
<p id="user-sbp_zip">[donation:user:sbp_zip]</p>
<p id="user-sbp_state">[donation:user:sbp_state]</p>
<p id="user-uid">[donation:user:uid]</p>
MESSAGE;
$I->configureConfirmationPage($I->nid, 'Hello, World!', $confirmationMessage);

// Ensure our 2 built in roles have the correct permissions.
$editorRid = $I->getRid('Springboard editor');
$adminRid = $I->getRid('Springboard administrator');

$I->amOnPage('/admin/people/permissions');
$I->executeJS('jQuery("#springboard-admin-home-link").remove()');
$I->seeCheckboxIsChecked('#edit-' . $adminRid . '-access-all-webform-results');
$I->seeCheckboxIsChecked('#edit-' . $editorRid . '-access-all-webform-results');

// Create users to test their access to webform results.
$I->createUser('sb-admin', 'sb-admin@example.com', $adminRid);
$I->createUser('sb-editor', 'sb-editor@example.com', $editorRid);
$I->createUser('auth', 'auth@example.com');

$webformId = $I->createWebform();

$I->logout();

$I->amOnPage('node/' . $I->nid);
$I->makeADonation(array('amount' => 10, 'first_name' => 'John', 'last_name' => 'Tester', 'mail' => 'bob@example.com'));
$I->see('Hello, World!', 'h1.page-title');
$I->see('10', '#donation-amount');
$I->see('John', '#donation-first_name');
$I->see('John', '#user-sbp_first_name');
$I->see('Tester', '#donation-last_name');
$I->see('Tester', '#user-sbp_last_name');
//$I->see('1111', '#donation-card_number');
$I->see('bob@example.com', '#donation-mail');
$I->see('bob@example.com', '#user-mail');
// TODO: Add more token checks.

$I->sid = $I->grabFromCurrentUrl('~/done\?sid=(\d+)~');
//codecept_debug($sid);
$I->seeInDatabase('webform_confirmations_submissions', array('sid' => $I->sid));

// Browse away and make sure user can access their own page when they return.
$I->amOnPage('node/' . $I->nid);
$I->wait(3);
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Hello, World!', 'h1.page-title');
$I->see('10', '#donation-amount');
$I->see('John', '#donation-first_name');
$I->see('John', '#user-sbp_first_name');
$I->see('Tester', '#donation-last_name');
$I->see('Tester', '#user-sbp_last_name');
//$I->see('1111', '#donation-card_number');
$I->see('bob@example.com', '#donation-mail');
$I->see('bob@example.com', '#user-mail');

$I->am('admin');
$I->login();
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Hello, World!', 'h1.page-title');
$I->see('10', '#donation-amount');
$I->see('John', '#donation-first_name');
$I->see('John', '#user-sbp_first_name');
$I->see('Tester', '#donation-last_name');
$I->see('Tester', '#user-sbp_last_name');
//$I->see('1111', '#donation-card_number');
$I->see('bob@example.com', '#donation-mail');
$I->see('bob@example.com', '#user-mail');
$I->logout();
$I->wait(3);

$I->am('Springboard administrator');
$I->login('sb-admin', 'sb-admin');
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Hello, World!', 'h1.page-title');
$I->see('10', '#donation-amount');
$I->see('John', '#donation-first_name');
$I->see('John', '#user-sbp_first_name');
$I->see('Tester', '#donation-last_name');
$I->see('Tester', '#user-sbp_last_name');
//$I->see('1111', '#donation-card_number');
$I->see('bob@example.com', '#donation-mail');
$I->see('bob@example.com', '#user-mail');
$I->logout();
$I->wait(3);

$I->am('Springboard editor');
$I->login('sb-editor', 'sb-editor');
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Hello, World!', 'h1.page-title');
$I->see('10', '#donation-amount');
$I->see('John', '#donation-first_name');
$I->see('John', '#user-sbp_first_name');
$I->see('Tester', '#donation-last_name');
$I->see('Tester', '#user-sbp_last_name');
//$I->see('1111', '#donation-card_number');
$I->see('bob@example.com', '#donation-mail');
$I->see('bob@example.com', '#user-mail');
$I->logout();

// Regular authenticated user with no permissions.
$I->am('authenticated user');
$I->login('auth', 'auth');
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Access denied', 'h1.page-title');
$I->see('You are not authorized to access this page.');
$I->dontSee('Hello, World!', 'h1.page-title');
$I->dontSee('10', '#donation-amount');
$I->dontSee('John', '#donation-first_name');
$I->dontSee('John', '#user-sbp_first_name');
$I->dontSee('Tester', '#donation-last_name');
$I->dontSee('Tester', '#user-sbp_last_name');
//$I->dontSee('1111', '#donation-card_number');
$I->dontSee('bob@example.com', '#donation-mail');
$I->dontSee('bob@example.com', '#user-mail');
$I->logout();

// Anonymous user, can't access another user's confirmation page.
$I->wait(3);
$I->am('anonymous user');
$I->amOnPage('/node/' . $I->nid . '/done?sid=' . $I->sid);
$I->see('Access denied', 'h1.page-title');
$I->see('You are not authorized to access this page.');
$I->dontSee('Hello, World!', 'h1.page-title');
$I->dontSee('10', '#donation-amount');
$I->dontSee('John', '#donation-first_name');
$I->dontSee('John', '#user-sbp_first_name');
$I->dontSee('Tester', '#donation-last_name');
$I->dontSee('Tester', '#user-sbp_last_name');
//$I->dontSee('1111', '#donation-card_number');
$I->dontSee('bob@example.com', '#donation-mail');
$I->dontSee('bob@example.com', '#user-mail');

$I->amOnPage('/node/' . $webformId);
$I->fillField('submitted[component_1]', 'Value');
$I->click('#edit-submit');
$I->canSee('Webform title', 'h1.page-title');
$webformSid = $I->grabFromCurrentUrl('~/done\?sid=(\d+)~');

$I->am('authenticated user');
$I->login('auth', 'auth');
$I->amOnPage('/node/' . $webformId . '/done?sid=' . $webformSid);
$I->see('Access denied', 'h1.page-title');
$I->see('You are not authorized to access this page.');
