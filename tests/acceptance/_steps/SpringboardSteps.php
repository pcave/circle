<?php
namespace AcceptanceTester;

class SpringboardSteps extends \AcceptanceTester\DrupalSteps
{
    public function makeADonation(array $details = array(), $recurs = FALSE, $dual_ask = FALSE, $recurring_only = FALSE)
    {
        $I = $this;

        $defaults = $I->donationData();
        $settings = array_merge($defaults, $details);

        $I = $this;

        if (!$recurs || ($recurs && !$dual_ask)) {
            $I->selectOption(\DonationFormPage::$askAmountField, $settings['amount']);
        }
        elseif($recurs && $dual_ask) {
            $I->selectOption(\DonationFormPage::$recursAmountField, $settings['amount']);
        }

        $I->fillInMyName($settings['first_name'], $settings['last_name']);
        $I->fillField(\DonationFormPage::$emailField, $settings['mail']);
        $I->fillInMyAddress($settings['address'], $settings['address2'], $settings['city'], $settings['state'], $settings['zip'], $settings['country_name']);
        $I->fillInMyCreditCard($settings['card_number'], $settings['card_expiration_year'], $settings['card_expiration_month_name'], $settings['card_cvv']);

        if ($recurs && !$dual_ask && !$recurring_only) {
            $I->selectOption(\DonationFormPage::$recursField, 'recurs');
        }

        $I->click(\DonationFormPage::$donateButton);
    }

    /**
     * Submits a Fundraiser donation via the Springboard API.
     * Tests a valid submission, a submission with invalid data, and a
     * submission with failing payment method.
     *
     * @param $api_key
     *   The api key necessary for authentication to the API.
     * @param $form_id
     *   The ID of the fundraiser form to be tested.
     * @param array $form_data
     *   An array of form data keyed by the fundraiser field name.
     *
     */
    public function makeApiDonation($api_key, $form_id, $form_data = array()) {
        $I = $this;
        // find the configured site URL for testing
        $config = \Codeception\Configuration::config();
        $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
        $url = $settings['modules']['config']['WebDriver']['url'];
        // Set headers for submitting form data
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept','application/json');
        // test a successful submission response
        $I->sendPost($url . '/springboard-api/springboard-forms/submit?form_id=' . $form_id . '&api_key=' . $api_key, json_encode($form_data));
        $I->seeResponseCodeIs(200);
        // test validation error response
        $form_data['mail'] = 'notanemail';
        $I->sendPost($url . '/springboard-api/springboard-forms/submit?form_id=' . $form_id . '&api_key=' . $api_key, json_encode($form_data));
        //$I->seeResponseCodeIs(406);
        $I->seeResponseContains('Please enter a valid email address');
        // test payment rejection response
        $form_data['card_number'] = '4111111111111114';
        $I->sendPost($url . '/springboard-api/springboard-forms/submit?form_id=' . $form_id . '&api_key=' . $api_key, json_encode($form_data));
        //$I->seeResponseCodeIs(406);
        $I->seeResponseContains('You have entered an invalid credit card number.');
    }


    public function fillInMyName($first = 'John', $last = 'Tester') {
        $I = $this;
        $I->fillField(\DonationFormPage::$firstNameField, $first);
        $I->fillField(\DonationFormPage::$lastNameField, $last);
    }

    public function fillInMyCreditCard($number = '4111111111111111', $year = NULL, $month = 'January', $cvv = '456') {
        $I = $this;

        $I->fillField(\DonationFormPage::$creditCardNumberField, $number);
        $I->selectOption(\DonationFormPage::$creditCardExpirationMonthField, $month);

        if (is_null($year)) {
            $year = date('Y', strtotime('+ 1 year'));
        }

        $I->selectOption(\DonationFormPage::$creditCardExpirationYearField, $year);

        $I->fillField(\DonationFormPage::$CVVField, $cvv);
    }

    public function fillInMyAddress($address = '1234 Main St', $address2 = '', $city = 'Washington', $state = 'Maryland', $zip = '00000', $country = 'United States') {
        $I = $this;

        $I->fillField(\DonationFormPage::$addressField, $address);
        // @todo Address 2
        $I->fillField(\DonationFormPage::$cityField, $city);
        $I->selectOption(\DonationFormPage::$countryField, $country);
        $I->selectOption(\DonationFormPage::$stateField, $state);
        $I->fillField(\DonationFormPage::$zipField, $zip);
    }

    /**
     * Clones a donation form.
     *
     * @param $nid
     *   The node id of the form to clone. Defaults to the build in
     *   donation form nid.
     *
     * @return $nid of newly created form.
     */
    public function cloneADonationForm($nid = 2) {
        $I = $this;

        $I->amOnPage('/node/' . $nid . '/clone/confirm');
        $I->click('Clone');
        $cloneNid = $I->grabFromCurrentUrl('~/springboard/node/(\d+)/edit~');
        codecept_debug($cloneNid);
        return $cloneNid;
    }

    /**
     * Configures a confirmation page title and message.
     *
     * @param $nid
     *   The id of the form to configue.
     *
     * @param $pageTitle
     *   The title to user for the confirmation page.
     *
     * @param $pageContent
     *   The content to use for the confirmation page.
     */
    public function configureConfirmationPage($nid, $pageTitle, $pageContent) {
        $I = $this;

        $I->amOnPage('/node/' . $nid . '/edit');
        $I->click('Form components');
        $I->click('Confirmation page & settings');
        $I->fillField('#edit-confirmation-confirmation-page-title', $pageTitle);
        $I->fillField('#edit-confirmation-value', $pageContent);
        $I->selectOption('confirmation[format]', 'full_html');
        $I->click('Save configuration');
    }

    /**
     * Make multiple donations with random info.
     *
     * @param string $path
     *   The path of the donation form. For example, '/node/2'.
     * @param int $numberOfDonations
     *   How many donations to make.
     */
    public function makeMultipleDonations($path, $numberOfDonations = 10) {
        $I = $this;
        // Used in combination with an iterator number to create a unique email address on each donation.

        $I->am('a donor');
        $I->wantTo('donate.');

        for ($iterator = 0; $iterator < $numberOfDonations; $iterator++) {
            $defaults = $I->donationData();
            $recurring = ($iterator % 2) ? TRUE : FALSE;
            $I->amOnPage($path);
            $I->makeADonation($defaults, $recurring);
        }
    }

    public function configureSecurePrepopulate($key, $iv) {
        $I = $this;

        $I->amOnPage('admin/config/system/secure-prepopulate');
        $I->fillField('#edit-secure-prepopulate-key', $key);
        $I->fillField('#edit-secure-prepopulate-iv', $iv);
        $I->click('#edit-submit');
        $I->seeInMessages('The configuration options have been saved.');
    }

    public function generateSecurePrepopulateToken() {
      $I = $this;
      $I->amOnPage('admin/config/system/secure-prepopulate/token_generator');
      $I->click("Secure Pre-populate Token");
      $I->fillField('#edit-first-name', 'Allen');
      $I->fillField('#edit-last-name', 'Freeman');
      $I->fillField('#edit-email', 'allen.freeman@example.com');
      $I->fillField('#edit-address', '12345 Test Dr');
      $I->fillField('#edit-address-line-2', 'Apt 2');
      $I->fillField('#edit-city', 'Springfield');
      $I->fillField('#edit-country', 'US');
      $I->fillField('#edit-state', 'IL');
      $I->fillField('#edit-zip', '55555');
      $I->click('#edit-submit');
      $afToken = $I->grabTextFrom('//*[@id="console"]/div[2]/ul/li[2]');
      codecept_debug($afToken);

      return $afToken;
    }

    public function configureEncrypt() {

        $settings = array();
        if (empty(getenv('sustainers_key_path'))) {
            $config = \Codeception\Configuration::config();
            $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
        }
        else {
            // Scrutinizer env vars.
            $settings['Sustainers'] = array(
              'sustainers_key_path' => getenv('sustainers_key_path'),
            );
        }


        $I = $this;
        $I->amOnPage('admin/config/system/encrypt');
        $I->fillField('Secure Key Path', $settings['Sustainers']['sustainers_key_path']);
        $I->click("Save configuration");
        $I->see('Key found and in secure place.');
    }

    public function generateSustainerUpgradeToken($anon, $amount, $uid, $did, $form_id = NULL, $rollback = FALSE) {
        $I = $this;
        $I->amOnPage('user/login');
        $uri = $I->grabFromCurrentUrl();
        if ($uri == '/user/login') {
            $I->login();
        }

        $I->amOnPage('admin/config/system/fundraiser/token_generator');
        $I->fillField('#edit-uid', $uid);
        $I->fillField('#edit-amount', $amount);
        $I->fillField('#edit-did', $did);
        if ($form_id != NULL) {
            $I->fillField('#edit-nid', $form_id);
        }
        else {
            $I->fillField('#edit-nid', '');
        }
        if ($rollback == TRUE) {
            $I->checkOption('#edit-rollback', 1);
        }
        else {
            $I->unCheckOption('#edit-rollback', 1);
        }
        $I->click('#edit-submit');
        $afToken = $I->grabValueFrom('//div[@id="console"]//textarea');
        if ($anon == TRUE) {
            $I->logout();
        }

        return $afToken;
    }

    /**
     * Initial configuration of Springboard API for testing. Returns the
     * API key necessary for authentication.
     * @return string $api_key
     *
     */
    public function configureSpringboardAPI() {
        $I = $this;
        // Enable API-related modules.
        $I->amOnPage('admin/modules');
        $I->checkOption('#edit-modules-jackson-river-springboard-extras-springboard-api-enable');
        $I->checkOption('#edit-modules-services-services-enable');
        $I->checkOption('#edit-modules-services-servers-rest-server-enable');
        $I->click("Save configuration");
        //$I->see('The configuration options have been saved.');
        // Create a REST server endpoint for Springboard API.
        $I->amOnPage('admin/structure/services/import');
        $endpoint_config = '$endpoint = new stdClass();
            $endpoint->disabled = FALSE; /* Edit this to true to make a default endpoint disabled initially */
            $endpoint->api_version = 3;
            $endpoint->name = "acceptance_tests";
            $endpoint->server = "rest_server";
            $endpoint->path = "springboard-api";
            $endpoint->authentication = array();
            $endpoint->server_settings = array();
            $endpoint->resources = array(
              "springboard-forms" => array(
                "operations" => array(
                  "retrieve" => array(
                    "enabled" => "1",
                  ),
                  "index" => array(
                    "enabled" => "1",
                  ),
                ),
                "actions" => array(
                  "submit" => array(
                    "enabled" => "1",
                  ),
                ),
              ),
            );';
        $I->fillField('#edit-import', $endpoint_config);
        //$I->checkOption('#edit-overwrite');

        $I->click('Continue');
        $I->see('You have unsaved changes');

        $I->click('Save');

        // Configure the API Key.
        $I->amOnPage('admin/config/services/springboard_api');
        $I->selectOption('#edit-springboard-api-management-service', 'basic');
        $I->fillField('#edit-add-basic-app-name', 'acceptance_tests');
        $I->click('Add');
        $I->see("The configuration options have been saved");
        $key = $I->grabTextFrom('//td[text()="acceptance_tests"]/following-sibling::td');
        // Enable the new API key.
        $I->checkOption('#edit-api-key-list-' . $key);
        $I->click('Save configuration');
        $I->see("The configuration options have been saved.");
        return $key;
    }

    /**
     * Generate a variable array of valid donation form data.
     *
     * @return array $form_data
     *   An array of form data keyed by Fundraiser field names.
     */
    public function donationData() {
        $amounts = array('10', '20', '50', '100');
        $firsts = array('Alice', 'Tom', 'TJ', 'Phillip', 'David', 'Shaun', 'Ben', 'Jennie', 'Sheena', 'Danny', 'Allen', 'Katie', 'Jeremy', 'Julia', 'Kate', 'Misty', 'Pat', 'Jenn', 'Joel', 'Katie', 'Matt', 'Meli', 'Jess');
        $lasts = array('Hendricks', 'Williamson', 'Griffen', 'Cave', 'Barbarisi', 'Brown', 'Clark', 'Corman', 'Donnelly', 'Englander', 'Freeman', 'Grills', 'Isett', 'Kulla-Mader', 'McKenney', 'McLaughlin', 'O\'Brien', 'Olivia', 'Rothschild', 'Shaw', 'Thomas', 'Trumbo', 'Walls');
        $numbers = array('4111111111111111');
        $months = cal_info(0)['months'];
        $month_nums = array_keys($months);
        $request_time = strtotime('now');
        $form_data = array(
          'amount' => $amounts[array_rand($amounts)],
          'first_name' => $firsts[array_rand($firsts)],
          'last_name' => $lasts[array_rand($lasts)],
          'mail' => 'test_' . $request_time . '@example.com',
          'address' => '1234 Main St',
          'address2' => '',
          'city' => 'Washington',
          'state' => 'DC',
          'zip' => '20036',
          'country' => 'US',
          'country_name' => 'United States',
          'card_number' => $numbers[array_rand($numbers)],
          'card_expiration_year' => date('Y', strtotime('+1 years')),
          'card_expiration_month_name' => $months[array_rand($months)],
          'card_expiration_month' => $month_nums[array_rand($month_nums)],
          'card_cvv' => rand(100, 999),
        );
        return $form_data;
    }

    public function rebuildSpringboardAdminMenu() {
        $I = $this;
        $I->amOnPage('springboard/rebuild-sb-menu');
        $I->click('Rebuild');
        $I->seeInMessages('The Springboard Admin Menu has been rebuilt.');
    }

}
