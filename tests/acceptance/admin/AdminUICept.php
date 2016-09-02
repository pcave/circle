<?php
//@group admin'admin');


// Acceptance tests for admin UI and menus.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('use the Springboard Admin UI to manage my settings and content');

// Create a user with the Springboard Administrator role.
$I->am('admin');
$I->wantTo('login and create a user with the Springboard Administrator role.');
$I->login();
$rid = $I->getRid('Springboard administrator');
$I->createUser('john', 'john@example.com', $rid);
$I->logout();

$I->am('user with the Springboard Administrator role');
$I->login('john', 'john');

$I->seeInCurrentUrl('/springboard');
$I->see('Administration', '.page-title');
//$I->cantSeeElement('.error');

// Main menu checks.
$I->see('Donation Forms', 'a.dropdown-toggle');
$I->see('Forms', 'a.dropdown-toggle');
$I->see('Asset Library', 'a.dropdown-toggle');
$I->see('Marketing & Analytics', 'a.dropdown-toggle');
$I->see('Reports', 'a.dropdown-toggle');

$I->see('Recent donation forms', 'h2');
$I->see('Create donation form', '.add-button');
$I->see('View All Donation Forms', '.more-button');

$I->see('Recent Forms', 'h2');
$I->see('Create form', '.add-button');
$I->see('View All Forms', '.more-button');

$I->see('Sync Status', 'h2');
$I->see('Springboard Version:', '.sb-version-info');

$I->click('Donation Forms');
$I->seeInCurrentUrl('/springboard/donation-forms/all');
$I->see('Donation Forms', '.page-title');
//$I->cantSeeElement('.error');
$I->see('Donation Forms', '.page-title');
$I->see('Donation Form', 'h2');
$I->see('Create Donation Form', '.add-button');
$I->see('View All Donation Forms', '.more-button');
$I->see('Options', 'button');

// Form view.
$I->see('Internal Name', '#block-system-main table.views-table th');
$I->see('Form Name', '#block-system-main table.views-table th');
$I->see('Form ID', '#block-system-main table.views-table th');
$I->see('Status', '#block-system-main table.views-table th');
$I->see('Last Updated', '#block-system-main table.views-table th');
$I->see('Action', '#block-system-main table.views-table th');
$I->see('Clone', 'td');
$I->see('Edit', 'td');
$I->see('View', 'td');

$I->click('View all Donation Forms');
$I->seeInCurrentUrl('/springboard/donation-forms/donation_form');
$I->see('Donation Forms', '.page-title');
$I->see('Internal Name', '#block-system-main table.views-table th');
$I->see('Form Name', '#block-system-main table.views-table th');
$I->see('Form ID', '#block-system-main table.views-table th');
$I->see('Status', '#block-system-main table.views-table th');
$I->see('Last Updated', '#block-system-main table.views-table th');
$I->see('Action', '#block-system-main table.views-table th');
$I->see('Clone', 'td');
$I->see('Edit', 'td');
$I->see('View', 'td');
$I->fillField('#edit-combine', 'NORESULT');
$I->click('Go');
$I->waitForElementNotVisible('.views-table', 30);
$I->dontSeeElement('.views-table');
$I->fillField('#edit-combine', 'Test');
$I->click('Go');
$I->waitForElementVisible('.views-table', 30);
$I->see('Test Donation Form', 'td');

$I->moveMouseOver('#menu-wrapper li.donationforms');
$I->click('Create a Donation Form');
$I->seeInCurrentUrl('/springboard/add/donation-form');
$I->see('Create Donation Form', '.page-title');

$I->moveMouseOver('#menu-wrapper li.donationforms');
$I->click('Donation Reports');
$I->seeInCurrentUrl('/springboard/reports/donations');
$I->see('Donations', '.page-title');
$I->seeElement('#views-exposed-form-sbv-donations-page');

// Forms menu item.
$I->click('Forms');
$I->seeInCurrentUrl('/springboard/forms/all');
$I->see('Forms', '.page-title');
$I->moveMouseOver('#menu-wrapper li.forms');
$I->click('View All Forms');
$I->seeInCurrentUrl('/springboard/forms/all');

// Asset Library
$I->click('Asset Library');
$I->seeInCurrentUrl('/springboard/asset-library');
$I->see('Templates', '.page-title');
$I->see('Page Wrapper', '#block-system-main .types-wrapper h2');
$I->see('Create Page Wrapper', '#block-system-main div.buttons-wrapper a');
$I->see('View all Page Wrappers', '#block-system-main  a');
$I->see('Email Template', '#block-system-main .types-wrapper h2');
$I->see('Create Email Template', '#block-system-main div.buttons-wrapper a');
$I->see('View all Email Templates', '#block-system-main a');

$I->moveMouseOver('#menu-wrapper li.assetlibrary');
$I->click('Email Templates');
$I->seeInCurrentUrl('/springboard/asset-library/email_wrapper');
$I->see('Email Templates', '.page-title');
$I->see('Title', '#block-system-main table.views-table th');
$I->see('Status', '#block-system-main table.views-table th');
$I->see('Date Created', '#block-system-main table.views-table th');
$I->see('Action', '#block-system-main table.views-table th');
$I->see('View', '#block-system-main table.views-table td a');
$I->see('Edit', '#block-system-main table.views-table td a');
$I->see('Clone', '#block-system-main table.views-table td a');
$I->see('Delete', '#block-system-main table.views-table td a');
$I->click('Create Email Template');
$I->seeInCurrentUrl('/springboard/add/email-wrapper');
$I->see('Create Email Template', '.page-title');

$I->moveMouseOver('#menu-wrapper li.assetlibrary');
$I->click('Page Wrappers');
$I->seeInCurrentUrl('/springboard/asset-library/page_wrapper');
$I->see('Page Wrappers', '.page-title');
$I->click('Create Page Wrapper');
$I->seeInCurrentUrl('/springboard/add/page-wrapper');
$I->see('Create Page Wrapper', '.page-title');

$I->click('Marketing & Analytics');
$I->seeInCurrentUrl('/springboard/marketing-analytics');
$I->see('Marketing & Analytics', '.page-title');
$I->see('Source Codes', '#block-system-main .content a');
$I->see('Multivariate Testing', '#block-system-main .content a');

$I->click('Reports');
$I->seeInCurrentUrl('/springboard/reports');
$I->see('Reports', '.page-title');
$I->see('Donations', '#block-system-main .content a');
$I->see('Contacts', '#block-system-main .content a');
$I->see('Integration Reports', '#block-system-main .content a');
$I->click('Donations', '.aggregate-links');
$I->seeInCurrentUrl('/springboard/reports/donations');

$I->click('Reports');
$I->click('Contacts', '.aggregate-links');
$I->seeInCurrentUrl('/springboard/reports/contacts');
$I->see('Springboard Contacts', '.page-title');
$I->seeElement('#edit-submit-sbv-contacts');

$I->click('Reports');
$I->click('Integration Reports', '.aggregate-links');
$I->seeInCurrentUrl('/springboard/reports/integration-reports');
$I->see('Integration Reports', '.page-title');
$I->see('Batch Log', '#block-system-main .content a');
$I->see('Item Log', '#block-system-main .content a');
$I->see('Queue', '#block-system-main .content a');
