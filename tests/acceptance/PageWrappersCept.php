<?php

//@group misc

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('configure Page wrappers and add a template.');

$title = 'page wrappers test';

$I->am('admin');
$I->login();

// Check admin settings.
$I->amOnPage(PageWrapperPage::$URL);
$I->seeCheckboxIsChecked('Donation Form');
$I->checkOption('Webform');
$I->click('Save configuration');

// Add a pagewrapper.
$I->amOnPage(PageWrapperPage::route('add'));
$I->fillField(PageWrapperPage::$internalTitleField, $title);
$I->attachFile(PageWrapperPage::$styleField, 'page-wrapper.css');
$I->attachFile(PageWrapperPage::$jsField, 'page-wrapper.js');
$template = '[title][messages]Here is the prefix.[content]here is the suffix';
$I->fillField(PageWrapperPage::$htmlTemplateField, $template);
$I->seeElement(PageWrapperPage::$themeCss);
$I->click(PageWrapperPage::$saveButton);
$I->see('Page Wrapper ' . $title . ' has been created.');

// Check the admin dashboard.
$I->amOnPage(PageWrapperPage::$dashboardURL);
$I->see('page wrappers test', 'a');
$I->see('Create Page Wrapper', 'a');
$I->see('Search');

// Test a page wrapper.
$I->amOnPage('node/2/edit');
$I->click('Display settings');
$I->selectOption(PageWrapperPage::$pSelect, $title);
$I->click("Save");
$I->see('Billing Information');
$I->dontSee('Your Information');
$I->see('I see page wrappers');
$I->see('Here is the prefix', '*:not(.content)');
$I->see('Here is the suffix', '*:not(.content)');
