<?php

class P2pAdminPage
{
    // include url of current page
    public static $url = '/springboard/p2p';

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

      //urls
      $this->rulesUrl = 'springboard/p2p/rules';
      $this->starterUrl = 'admin/springboard/p2p/starter';
      $this->settingsUrl = 'springboard/p2p/settings';
      $this->addCatUrl = 'springboard/add/p2p-category';
      $this->addCampUrl = 'springboard/add/p2p-campaign';


      //fields
      //settings fields
      $this->messageArea = '//textarea[@name="login_message_area[value]"]';
      $this->helpArea = '//textarea[@name="help_message_area[value]"]';
      $this->donationEnable = '//input[@name="fundraiser_items[donation_form][enabled]"]';
      $this->p2pEnable = '//input[@name="fundraiser_items[p2p_donation_form][enabled]"]';
      $this->sbpZipEnable = '//input[@name="registration_fields[sbp_zip][enabled]"]';
      $this->sbpStateEnable = '//input[@name="registration_fields[sbp_state][enabled]"]';
      $this->sbpCountryEnable = '//input[@name="registration_fields[sbp_country][enabled]"]';
      $this->sbpAddr2Enable = '//input[@name="registration_fields[sbp_address_line_2][enabled]"]';
      $this->sbpAddrEnable = '//input[@name="registration_fields[sbp_address][enabled]"]';
      $this->sbpCityEnable = '//input[@name="registration_fields[sbp_city][enabled]"]';

      //add category fields
      $this->catImageThumb = '//input[@name="files[field_p2p_category_image_und_0]"]';
      $this->catImageEdit = '//input[@name="field_p2p_images_edit[und]"]';

      //add p2p campaign
      $this->campCatSelect = '//select[@name="field_p2p_category[und]"]';
      $this->campP2pDonation = '//input[@name="field_p2p_campaign_goals[und][0][campaign_goals][form_types][fundraiser][p2p_donation_form][enabled]"]';
      $this->campP2pDonationGoal = '//input[@name="field_p2p_campaign_goals[und][0][campaign_goals][form_types][fundraiser][p2p_donation_form][goal_value]"]';
      $this->campApproval = '//input[@name="field_p2p_campaigns_approval[und]"]';
      $this->campExpire = '//textarea[@name="field_p2p_expiration_message[und][0][value]"]';
      $this->campThumb = '//input[@name="files[field_p2p_campaign_thumbnail_und_0]"]';

      //fields in multiple content types
      $this->orgIntro = '//textarea[@name="field_p2p_org_intro[und][0][value]"]';
      $this->persIntro = '//textarea[@name="field_p2p_personal_intro[und][0][value]"]';
      $this->persIntroEdit = '//input[@name="field_p2p_personal_intro_edit[und]"]';
      $this->catImage = '//input[@name="files[field_p2p_category_image_und_0]"]';
      $this->catSelect = '//select[@name="field_p2p_category[und]"]';
      $this->body = '//textarea[@name="body[und][0][value]"]';
      $this->slider = '//input[@name="files[field_p2p_campaign_slider_und_0]"]';
      $this->title = '//input[@name="title"]';
      $this->banner='//input[@name="files[field_p2p_campaign_banner_und_0]"]';
      $this->video = '//input[@name="field_p2p_video_embed[und][0][video_url]"]';
      $this->videoEdit = '//input[@name="field_p2p_video_embed_edit[und]"]';


      //dom elements

      $this->rulesDesc = '.rules-element-content .description';

    }

    /**
     * @return a thing.
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }

    function enableFeature() {
      $I = $this->acceptanceTester;
      $I->enableModule('Features');
      $I->amOnPage('admin/structure/features');
      $I->click("Springboard P2P",'.vertical-tabs');
      $I->checkOption('#edit-status-springboard-p2p');
      $I->click('#edit-submit');
    }

}
