<?php

class AdvocacyAddPage
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
    public static $legislativeIssueCheckbox = '#edit-field-sba-legislative-issues-und-1';
    public static $menu = '#edit-menu-enabled';
    public static $internalTitle = '#edit-field-webform-user-internal-name-und-0-value';
    public static $tags = '#edit-field-tags-und';
    public static $format = '#edit-body-und-0-format--2';

    //Social Action
    public static $imageFileSocial = '#edit-field-sba-social-action-img-und-0-upload';
    public static $imageButtonSocial = '#edit-field-sba-social-action-img-und-0-upload-button--3';
    public static $callToActionSocial = '#edit-field-sba-social-call-to-action-und-0-value';
    public static $userFormLabelSocial = '#edit-field-sba-social-fieldset-title-und-0-value';
    public static $submitButtonTextSocial = '#edit-social-submit-button-text';
    public static $stepTwoIntroTextSocial = '#edit-field-sba-social-step-two-header-und-0-value';
    public static $StepTwoSubmitButtonTextSocial = '#edit-field-sba-social-step-two-submit-und-0-value';
    public static $requireTwitterAuth = '#edit-field-sba-social-require-auth-und-1';



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
