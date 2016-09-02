<?php

class DonationFormPage
{
    // include url of current page
    public static $URL = '/node/2';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $askAmountField = '#edit-submitted-donation-amount-1';
    public static $recursAmountField = '#edit-submitted-donation-recurring-amount-1';
    public static $otherAmountField = '#edit-submitted-donation-other-amount';
    public static $recursOtherAmountField = '#edit-submitted-donation-recurring-other-amount';

    public static $firstNameField = 'First Name';
    public static $lastNameField = 'Last Name';
    public static $emailField = 'E-mail address';
    public static $addressField = 'Address';
    public static $addressField2 = 'Address Line 2';
    public static $cityField = 'City';
    public static $stateField = '#edit-submitted-billing-information-state';
    public static $countryField = 'Country';
    public static $zipField = 'ZIP/Postal Code';
    public static $creditCardNumberField = 'Credit card number';
    public static $creditCardExpirationMonthField = '#edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-month';
    public static $creditCardExpirationYearField = '#edit-submitted-payment-information-payment-fields-credit-expiration-date-card-expiration-year';
    public static $CVVField = 'CVV';
    public static $donateButton = '#edit-submit';
    public static $recursField = '#edit-submitted-payment-information-recurs-monthly-1';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
        return static::$URL.$param;
     }

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    /**
     * @return DonationFormPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
