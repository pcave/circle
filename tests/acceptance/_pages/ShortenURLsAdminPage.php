<?php

class ShortenURLsAdminPage
{
    // include url of current page
    public static $URL = '/admin/config/services/shorten';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */


    function setAdminDefaults() {
      $I = $this->acceptanceTester;
      $I->amOnPage($this->URL);
      $I->selectOption('#edit-shorten-service', 'TinyURL');
      $I->selectOption('#edit-shorten-service-backup', 'none');
      $I->click('#edit-submit');
    }

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        $this->URL = '/admin/config/services/shorten';
    }

    /**
     * @return
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
