<?php

class ModulesPage
{
    // include url of current page
    public static $URL = '/admin/modules';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $submitButton = 'Save configuration';
    public static $uninstallButton = '#edit-submit';

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
     * @return ModulesPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
