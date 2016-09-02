<?php

class SalesforceMapPage
{

  /**
  * Declare UI map for this page here. CSS or XPath allowed.
  * public static $usernameField = '#username';
  * public static $formSubmitButton = "#mainForm input[type=submit]";
  */

  //fields
  public static $objectType = '#salesforce-object-type';
  public static $recordType = '#salesforce-record-type';
  public static $mapMs = '#salesforce-map-ms';
  public static $mapNid = '#salesforce-map-nid';
  public static $mapSid = '#salesforce-map-sid';
  public static $mapContact = '#edit-webform-user-webform-user-foreign-key';
  public static $saveMap = '#edit-submit--2';
  public static $unmap = '#edit-unmap';

   //labels
  public static $syncOptions = 'Salesforce Sync Options';
  public static $fieldMap = 'Field Mapping';
  public static $component = 'Webform Componant';
  public static $subProp = 'Submission Property';
  public static $nodeProp = 'Node property';
  public static $objTypeLabel = 'Object Record Type';

  //urls
  public static $queuePage = 'springboard/reports/integration-reports/queue';
  public static $cronPage = 'admin/reports/status/run-cron';
  public static $batchPage = 'springboard/reports/integration-reports/batch';
  public static $syncOptionsCheckbox = '#edit-map-config-sync-options-update';


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

    function configureSalesforce() {
      $config = \Codeception\Configuration::config();
      $settings = \Codeception\Configuration::suiteSettings('acceptance', $config);
      $I = $this->acceptanceTester;

      if (!empty($settings['Salesforce'])) {
        foreach ($settings['Salesforce'] as $key => $value) {
          $I->haveInDatabase('variable', array(
            'name' => $key,
            'value' => $value
          ));
        }
      }
    }
}

