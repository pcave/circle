<?php

//@group webform


$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('make sure I cannot inject html or js tags into the webform confirmation via donation tokens.');

$I->am('a malicious donor');
$I->amOnPage('/node/2');
$data = array(
    'first_name' => '<div class="badstuff-first">injected html in first name</div>',
    'address' => '<div class="badstuff-address">injected html in address</div>',
);

$I->makeADonation($data);

// We still want to see the safe values.
$I->see('injected html in first name');
$I->see('injected html in address');

// But we don't want the divs to be in the DOM.
$I->dontSeeElementInDOM('.badstuff-first');
$I->dontSeeElementInDOM('.badstuff-address');
