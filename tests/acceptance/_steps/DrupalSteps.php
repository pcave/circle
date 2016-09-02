<?php
namespace AcceptanceTester;

class DrupalSteps extends \AcceptanceTester
{
    public function login($name = 'admin', $password = 'admin')
    {
        $I = $this;
        $I->amOnPage(\UserPage::$URL);
        $I->fillField(\UserPage::$usernameField, $name);
        $I->fillField(\UserPage::$passwordField, $password);
        $I->click(\UserPage::$loginButton);
    }

    public function logout()
    {
        $I = $this;

        $I->amOnPage(\UserPage::route('/logout'));
    }

    public function installModule($module)
    {
        $this->enableModule($module);
    }

    public function enableModule($module)
    {
        $I = $this;
        $I->amOnPage(\ModulesPage::$URL);
        $I->checkOption($module);
        $I->click(\ModulesPage::$submitButton);
        $heading = $I->grabTextFrom('h1');
        if($heading == 'Some required modules must be enabled') {
          $I->click('#edit-submit');
        }
    }
    public function disableModule($module)
    {
        $I = $this;

        $I->amOnPage(\ModulesPage::$URL);
        $I->uncheckOption($module);
        $I->click(\ModulesPage::$submitButton);

    }
    public function uninstallModule($module)
    {
        $I = $this;

        $I->disableModule($module);

        $I->amOnPage(\ModulesPage::route('/uninstall'));
        $I->checkOption($module);
        $I->click(\ModulesPage::$uninstallButton);

        // Confirmation page.
        $I->click(\ModulesPage::$uninstallButton);

        $I->see('The selected modules have been uninstalled.', '.status');

    }
    public function runCron()
    {
        $I = $this;
    }

    public function waitForModal($timeout = 10) {
        $I = $this;

        $I->waitForElementVisible('#modal-content', 10);
    }

    public function seeModal() {
        $I = $this;

        $I->seeElement('#modal-content');
    }

    public function dontSeeModal() {
        $I = $this;

        $I->dontSeeElement('#modal-content');
    }

     public function createUser($name = 'testuser', $email = 'testeruser@example.com', $rid = '2')
    {
        $I = $this;
        $I->amOnPage('admin/people/create');
        $I->fillField('Username', $name);
        $I->fillField('E-mail address', $email);
        $I->fillField('Password', $name);
        $I->fillField('Confirm password', $name);
        if($rid != 2 || $rid != NULL) {
          $I->checkOption('//input[@name="roles[' . $rid .']"]');
        }

        $I->click('#edit-submit');
    }

    public function getRid($label) {
      $I = $this;
      $I->amOnPage('admin/people/create');
      $rid = $I->grabValueFrom('//label[normalize-space(text())="' . $label . '"]/preceding-sibling::input');
      return $rid;
    }

    public function createWebform(array $details = array()) {

      $defaults = array(
        'title' => 'Webform title',
        'body' => 'Webform body.',
      );

      $settings = array_merge($defaults, $details);

      $I = $this;
      $I->amOnPage('springboard/add/webform');
      $I->fillField('title', $settings['title']);
      $I->fillField('body[und][0][value]', $settings['body']);
      $I->click('#edit-submit');
      $nid = $I->grabFromCurrentUrl('~/springboard/node/(\d+)/form-components/components~');
      codecept_debug($nid);
      $I->fillField('add[name]', 'Component 1');
      $I->click('#edit-add-add');
      $I->click('#edit-actions-submit');

      return $nid;
    }

    public function seeInMessages($message) {
      $I = $this;
      $I->see($message, '.status');
    }

    public function runModuleUpdates() {
      $I = $this;
      $I->amOnPage('update.php');
      $I->click('Continue');
      $up = $I->canSee('Apply pending updates');
      if ($up) {
        $I->click('Apply pending updates');
      }
    }

}
