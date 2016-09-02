<?php

class PageWrapperPage
{
    // include url of current page
    public static $URL = '/admin/config/content/page-wrappers';
    public static $dashboardURL = '/springboard/asset-library/page_wrapper';

    public static $internalTitleField = '#edit-title';
    public static $htmlTemplateField = '#edit-page-wrappers-html-template-und-0-value';
    public static $styleField = '#edit-page-wrappers-css-und-0-upload';
    public static $stylebutton = '//input[@name="page_wrappers_css_und_0_upload_button';
    public static $jsField = '#edit-page-wrappers-js-und-0-upload';
    public static $jsbutton = '//input[@name="page_wrappers_js_und_0_upload_button';
    public static $themeCss = '#edit-page-wrappers-theme-css-und';
    public static $saveButton = '#edit-submit';
    public static $pSelect = '//select[@name="page_wrappers[new:0][wrapper_nid]"]';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
         if ($param == 'add') {
             return '/node/add/page-wrapper';
         }

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
     * @return EmailWrapperPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
