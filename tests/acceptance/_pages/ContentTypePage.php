<?php

class ContentTypePage
{
    // include url of current page
    public static $addURL = '/admin/structure/types/add/';
    public static $manageURL = '/admin/structure/types/manage/';
    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function addRoute()
     {
         return static::$addURL;
     }

    public static function manageRoute($param)
    {
        return static::$manageURL.$param;
    }


    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    // generic variables
    public static $name = '#edit-name';
    public static $body = '#edit-body';
    public static $save = '#edit-submit';
    public static $fundraiserTab ='Fundraiser settings';
    public static $fundraiser = '#edit-fundraiser';
    public static $fundraiserTickets = '#edit-fundraiser-tickets';
    public static $webformUserTab ='Webform user settings';
    public static $webformUser = '//input[@name="webform_user"]';


    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    /**
     * @return NodeAddPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
