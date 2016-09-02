<?php

class DonationFormEditPage
{
    // include url of current page
    public static $URL = '/node/2/edit';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $otherAmountField = '#edit-amount-wrapper-show-other-amount';
    public static $minimumAmountField = '#edit-amount-wrapper-show-other-amount';

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
