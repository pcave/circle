<?php

class MarketSourceAdminPage
{
    // include url of current page
    public static $URL = '/springboard/market-source';


    public function createCustomField($name, $key, $default) {
      $I = $this->acceptanceTester;
      $I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-0-name', $name);
      $I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-0-key', $key);
      $I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-0-default', $default);
    }

    public function showDefaultFieldSettings() {
      $I = $this->acceptanceTester;
      $I->click('#edit-market-source-default-fields a.fieldset-title');
    }

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        $this->URL = '/springboard/market-source';
    }

    /**
     * @return
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
