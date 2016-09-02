<?php

class NodeAddPage
{
    // include url of current page
    public static $URL = '/springboard/add/';

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
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    // generic variables
    public static $title = '#edit-title';
    public static $body = '#edit-body';
    public static $save = '#edit-submit';

    
    public static $tags = '#edit-field-tags-und';
    public static $format = '#edit-body-und-0-format--2';
    public static $imageFile = '#edit-field-image-und-0-upload';
    public static $imageButton = '#edit-field-image-und-0-upload-button--2';
    public static $menu = '#edit-menu-enabled';

    // custom variables
    public static $internalTitle = '#edit-field-webform-user-internal-name-und-0-value';



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
