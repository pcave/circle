<?php

class SpringboardSocialAdminPage
{
    // include url of current page
    public static $url = '/springboard/sb-social';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        $this->debugMode = '#edit-springboard-social-debug-mode';
        // general settings
        $this->AddThisID = '#edit-springboard-social-addthis-profile-id';
        $this->blockTitle = '#edit-springboard-social-default-block-title';
        $this->blockDescription = '#edit-springboard-social-default-block-description';
        $this->enableDonationForm = '#edit-springboard-social-enabled-content-types-donation-form';

        // enable services checkboxes
        $this->enableFacebookService = '#edit-springboard-social-services-facebook';
        $this->enableTwitterService = '#edit-springboard-social-services-twitter';
        $this->enableEmailService = '#edit-springboard-social-services-email';

        // facebook settings
        $this->facebookCustomButtonText = '#edit-springboard-social-facebook-custom-text';
        $this->facebookCustomButtonIcon = '#edit-springboard-social-facebook-custom-icon-upload';
        $this->facebookTitle = '#edit-springboard-social-facebook-title';
        $this->facebookDescription = '#edit-springboard-social-facebook-description';
        $this->facebookImage = '#edit-springboard-social-facebook-image-upload';

        // email settings
        $this->emailCustomButtonText = '#edit-springboard-social-email-custom-text';
        $this->emailCustomButtonIcon = '#edit-springboard-social-email-custom-icon-upload';
        $this->emailSubject = '#edit-springboard-social-email-subject';
        $this->emailMessage = '#edit-springboard-social-email-message';

        // twitter settings
        $this->twitterCustomButtonText = '#edit-springboard-social-twitter-custom-text';
        $this->twitterCustomButtonIcon = '#edit-springboard-social-twitter-custom-icon-upload';
        $this->twitterMessage = '#edit-springboard-social-twitter-message';

    }

    /**
     * @return a thing.
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }

    function enableModule() {
      $I = $this->acceptanceTester;
      $I->amOnPage('/admin/modules');
      $I->checkOption('#edit-modules-jackson-river-springboard-extras-sb-social-enable');
      $I->click('#edit-submit');
    }

    function setAdminDefaults() {
      $I = $this->acceptanceTester;
      // View admin settings.
      $I->amOnPage('springboard/sb-social');

      // Enable debug mode
      $I->checkOption($this->debugMode);

      // Configure default block settings
      $I->fillField($this->blockTitle, 'Springboard Social Sharing Block Title');
      $I->fillField($this->blockDescription, 'Springboard Social Sharing Block Description.');

      // Enable Social on Donation Forms
      $I->checkOption($this->enableDonationForm);

      // Turn on Email, Facebook, Twitter
      $I->checkOption($this->enableFacebookService);
      $I->checkOption($this->enableTwitterService);
      $I->checkOption($this->enableEmailService);

      // Configure individual service defaults.
      // Facebook
      $I->fillField($this->facebookCustomButtonText, 'Share on Facebook!');
      $I->fillField($this->facebookTitle, 'Global default Facebook title.');
      $I->fillField($this->facebookDescription, 'Global default Facebook description.');
      // Email
      $I->fillField($this->emailCustomButtonText, 'Share with Email!');
      $I->fillField($this->emailSubject, 'Global default email subject.');
      $I->fillField($this->emailMessage, 'Global default email message.');
      // Twitter
      $I->fillField($this->twitterCustomButtonText, 'Share on Twitter!');
      $I->fillField($this->twitterMessage, 'Global default Twitter message.');
      $I->click('#edit-submit');
    }

    function enableBlock($region = 'content') {
      $I = $this->acceptanceTester;
      $I->amOnPage('admin/structure/block');
      $I->selectOption('#edit-blocks-sb-social-social-sharing-region', 'content');
      $I->click('#edit-submit');
    }
}
