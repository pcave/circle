<?php

class AdvocacyPage
{

  /**
   * Declare UI map for this page here. CSS or XPath allowed.
   * public static $usernameField = '#username';
   * public static $formSubmitButton = "#mainForm input[type=submit]";
   */


  //urls
  public static $settingsPage = 'admin/config/services/advocacy';

  /**
   * @var AcceptanceTester;
   */
  protected $acceptanceTester;

  public function __construct(AcceptanceTester $I)
  {
    $this->acceptanceTester = $I;

  }

  /**
   * @return a thing.
   */
  public static function of(AcceptanceTester $I)
  {
    return new static($I);
  }

  function configureAdvocacy() {
    $settings = array();
    if (empty(getenv('springboard_advocacy_server_url'))) {
      $config = \Codeception\Configuration::config();
      $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
    }
    else {
      // Scrutinizer env vars.
      $settings['Advocacy'] = array(
        'springboard_advocacy_server_url' => getenv('springboard_advocacy_server_url'),
        'springboard_advocacy_client_id' => getenv('springboard_advocacy_client_id'),
        'springboard_advocacy_client_secret' => getenv('springboard_advocacy_client_secret'),
        'springboard_advocacy_smarty_authid' => getenv('springboard_advocacy_smarty_authid'),
        'springboard_advocacy_smarty_authtoken' => getenv('springboard_advocacy_smarty_authtoken'),
        'social_action_twitter_consumer_key' => getenv('social_action_twitter_consumer_key'),
        'social_action_twitter_consumer_secret' => getenv('social_action_twitter_consumer_secret'),
        'springboard_advocacy_test_email' => getenv('springboard_advocacy_test_email'),
      );
    }
    $I = $this->acceptanceTester;

    foreach ($settings['Advocacy'] as $key => $value) {
      $I->haveInDatabase('variable', array('name' => $key, 'value' => serialize($value)));
    }
  }

  function twitterLogin() {
    if (empty($_ENV['twitter_name'])) {
      $config = \Codeception\Configuration::config();
      $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
    }
    else {
      // Scrutinizer env vars.
      $settings['Twitter'] = array('name' => getenv('twitter_name'), 'pass' => getenv('twitter_pass'));
    }

    $I = $this->acceptanceTester;
    $I->fillField('#username_or_email', $settings['Twitter']['name']);
    $I->fillField('#password', $settings['Twitter']['pass']);
    $I->click('#allow');
  }
}
