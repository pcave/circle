<?php

class EmailWrapperPage
{
    // include url of current page
    public static $URL = '/admin/config/system/email-wrappers';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $internalTitleField = '#edit-title';
    public static $fromNameField = '#edit-email-wrapper-from-name-und-0-value';
    public static $fromEmailField = '#edit-email-wrapper-from-email-und-0-value';
    public static $replyToEmailField = '#edit-email-wrapper-reply-email-und-0-value';
    public static $subjectField = '#edit-email-wrapper-subject-und-0-value';
    public static $htmlTemplateField = '#edit-email-wrapper-html-template-und-0-value';
    public static $htmlMessageField = '#edit-email-wrapper-html-message-und-0-value';
    public static $textTemplateField = '#edit-email-wrapper-text-template-und-0-value';
    public static $textMessageField = '#edit-email-wrapper-text-message-und-0-value';
    public static $saveButton = '#edit-submit';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
         if ($param == 'add') {
             return '/node/add/email-wrapper';
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
