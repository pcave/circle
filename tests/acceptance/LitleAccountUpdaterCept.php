<?php
//@group misc

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test extending sustainers with the Litle Account Updater');
$scenario->incomplete();

/**
 * Test Steps for account updater
 *
 * First need to enable and configure Litle to use the commerce_litle_test gateway.
 *
 * Disabled the Extend option on the payment methods screen.
 * (admin/commerce/config/payment-methods/manage/commerce_payment_commerce_litle_cc)
 *
 * Made a recurring donation with a future expiration date,
 * and verified that the series was only created through that month.
 * (springboard/donations/64/recurring)
 *
 * Enabled the Extend option on the payment methods screen.
 * (admin/commerce/config/payment-methods/manage/commerce_payment_commerce_litle_cc)
 *
 * Made a recurring donation with a future expiration date,
 * and verified that the series was created through that date plus one month.
 * (springboard/donations/67/recurring)
 *
 * While the Litle gateway was configured to Extend,
 * made a recurring donation to a form using the Example gateway,
 * and confirmed that the donations were only scheduled through the expiry date.
 * (springboard/donations/71/recurring).
 */
