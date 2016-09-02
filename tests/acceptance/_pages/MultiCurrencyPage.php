<?php

class MultiCurrencyPage
{
    // include url of current page
    public static $URL = '/springboard/settings/config/currency';

    public static $select = '#edit-commerce-default-currency';
    public static $eur = 'EUR - Euro - €';
    public static $adminFieldset = "Enabled currencies";
    public static $configSave = "Save configuration";
    public static $node = "node/2";
    public static $nodeSelect = '#edit-field-fundraiser-currency-und';
    public  static $paymentSelect = '#edit-commerce-line-items-und-line-items-1-commerce-unit-price-und-0-currency-code';
    public  static $editFee = '#edit-fee-amount';

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
