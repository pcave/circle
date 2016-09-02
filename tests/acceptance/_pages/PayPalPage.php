<?php

class PayPalPage
{

  /**
   * @var AcceptanceTester;
   */
  protected $acceptanceTester;

  public function __construct(AcceptanceTester $I)
  {
    $this->acceptanceTester = $I;

  }

  /**
   * @return a thing.
   */
  public static function of(AcceptanceTester $I)
  {
    return new static($I);
  }

  function configurePayPal() {
    $settings = array();
    if (empty(getenv('paypal_email'))) {
      $config = \Codeception\Configuration::config();
      $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
    }
    else {
      // Scrutinizer env vars.
      $settings['PayPal'] = array(
        'email' => getenv('paypal_email'),
        'username' => getenv('paypal_username'),
        'password' => getenv('paypal_password'),
        'signature' => getenv('paypal_signature'),
      );
    }
    $I = $this->acceptanceTester;
    $I->amOnPage('admin/commerce/config/payment-methods/manage/5/enable');
    $I->click('Confirm');
    $I->amOnPage('admin/commerce/config/payment-methods');
    $I->amOnPage('admin/commerce/config/payment-methods/manage/commerce_payment_paypal_wps/edit/3');
    $I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-business', $settings['PayPal']['paypal_email']);
    $I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-api-username', $settings['PayPal']['paypal_username']);
    $I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-api-password', $settings['PayPal']['paypal_password']);
    $I->fillField('#edit-parameter-payment-method-settings-payment-method-settings-api-signature', $settings['PayPal']['paypal_signature']);
    $I->click("Save");
    $I->acceptPopup();
  }
}
