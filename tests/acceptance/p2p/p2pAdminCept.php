<?php

//@group p2p

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Configure and test p2p administration.');

$I->am('admin');
$I->login();
$I->configureEncrypt();

$admin = new P2pAdminPage($I);
$admin->enableFeature();

// User has the ability to set the peer to peer login message
$I->amOnPage($admin->settingsUrl);
$I->wait(1);
$I->see('P2P Login message', 'label');
$I->fillField($admin->messageArea, 'My Custom Login Message');
$I->see('P2P Help message', 'label');
$I->fillField($admin->helpArea, 'My Custom Help Message');
$I->click('#edit-submit');
$I->see('My Custom Login Message', $admin->messageArea);
$I->see('My Custom Help Message', $admin->helpArea);

// A list of all fundraiser types and the forms of that type can be enabled for peer to peer fundraising

$I->see('Peer to peer enabled form types', 'legend');
$I->seeElement($admin->donationEnable);
$I->seeElement($admin->p2pEnable);

// User has the ability to choose which configured profile fields display on the registration page, excluding first, last, and email, which are always required.
$I->see('Registration fields', 'legend');
$I->seeElement($admin->sbpZipEnable);
$I->seeElement($admin->sbpStateEnable);
$I->seeElement($admin->sbpCountryEnable);
$I->seeElement($admin->sbpAddr2Enable);
$I->seeElement($admin->sbpAddrEnable);
$I->seeElement($admin->sbpCityEnable);

// User has the ability to sort the order in which the fields appear on the registration page

$I->dragAndDrop('//table[@id="registration-fields-table"]//tr[contains(@class, "draggable")][1]//a', '//table[@id="registration-fields-table"]//tr[contains(@class, "draggable")][2]//a');

// User can make non-required profile fields required on the registration page
$I->checkOption('//input[@name="registration_fields[sbp_state][enabled]"]');
$I->checkOption('//input[@name="registration_fields[sbp_state][required]"]');
$I->click('#edit-submit');
//generate dummy content
$I->amOnPage($admin->starterUrl);
$I->click('Create content');
$I->waitForElement('.campaign-landing-grid', 30);
$I->logout();
//check required fields
$I->amOnPage('p2p/register?p2p_cid=11');
$I->wait(1);
$I->seeElement('.form-item-sbp-state-und .form-required');

// Rules
// User can configure an email to an admin when a campaigner is requesting approval to a private campaign
// User can configure an email to the campaigner when requesting approval to a private campaign
// User can configure an email for when a campaigner is approved for a private campaign
// User can configure an email for when a campaigner is rejected for a private campaign
// User can configure an email for when a new campaigner registers on the site
// User can configure an email for when an existing drupal user requests a password reset via the P2P UI.

$I->login();
$I->amOnPage($admin->rulesUrl);

$I->see('rules_p2p_admin_email_private_campaign_approval', $admin->rulesDesc);
$I->see('rules_p2p_password_reset_mail', $admin->rulesDesc);
$I->see('rules_p2p_user_email_personal_campaign_creation', $admin->rulesDesc);
$I->see('rules_p2p_user_email_private_campaign_approval_request', $admin->rulesDesc);
$I->see('rules_p2p_user_email_private_campaign_approved', $admin->rulesDesc);
$I->see('rules_p2p_user_email_private_campaign_rejected', $admin->rulesDesc);
$I->see('rules_p2p_user_email_registration', $admin->rulesDesc);
$I->see('rules_p2p_user_email_registration_private_campaign', $admin->rulesDesc);
$I->see('rules_p2p_user_email_registration_public_campaign', $admin->rulesDesc);

$I->click('Send user email after creating a personal campaign');
$I->seeElement('#edit-settings');

// Dashboard
// User sees a list of configured peer to peer campaigns

$I->amOnPage(p2pAdminPage::$url);
$I->see('Cross River Gorilla');
// User has the ability to create a new peer to peer campaign
$I->click('Create a new campaign');
$I->see('Create Peer to Peer Campaign', 'H1');
// User sees a list of configured peer to peer categories
$I->amOnPage(p2pAdminPage::$url);
$I->see('Runs and Walks');
// User has the ability to create a new peer to peer category
$I->click('Create a new category');
$I->see('Create Peer to Peer Category', 'H1');

// User sees a list of personal campaigners that require approval
$I->amOnPage(p2pAdminPage::$url);
$I->see('Users awaiting approval', 'H2');
// User has the ability to approve or reject a campaigner from the dashboard

// User sees a list of personal campaigns
$I->amOnPage(p2pAdminPage::$url);
$I->see('Personal campaigns', 'H2');

// Approval Queue
// Approval email is sent to personal campaigner when approved
// Rejection email is sent to personal campaigner when rejected

// Peer to Peer Category Creation
// Only a permissioned user can create campaign categories
$rid = $I->getRid('Springboard administrator');
$I->amOnPage('admin/people/permissions/' . $rid);
$I->executeJS('jQuery("#springboard-admin-home-link").remove()');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->checkOption('#edit-' . $rid . '-create-p2p-category-content');
$I->checkOption('#edit-' . $rid . '-create-p2p-campaign-content');
$I->checkOption('#edit-' . $rid . '-create-p2p-campaign-landing-content');
$I->checkOption('#edit-' . $rid . '-create-p2p-personal-campaign-content');
$I->checkOption('#edit-' . $rid . '-edit-any-p2p-category-content');
$I->checkOption('#edit-' . $rid . '-edit-any-p2p-campaign-content');
$I->checkOption('#edit-' . $rid . '-edit-any-p2p-campaign-landing-content');
$I->checkOption('#edit-' . $rid . '-edit-any-p2p-personal-campaign-content');
$I->click('#edit-submit');
$I->createUser('testp2p', 'testp2p@example.com', $rid);
$I->logout();
$I->wait(4);
$I->login('testp2p', 'testp2p');

//User must provide a name, description and image when creating a new category
$I->amOnPage($admin->addCatUrl);
$I->click('#edit-submit');
$I->see('Name field is required');
$I->see('Description field is required');
$I->see('Category image field is required');

$I->fillField($admin->title, 'My Category Title');
$I->fillField($admin->body, 'This is a category description');
// User can upload a donation form banner
$I->click('Form banner');
$I->attachFile($admin->banner, '1170x240.png');
$I->click('Upload');

// User can provide default content that can be used in campaigns and personal campaigns
// User can set an organization introduction
$I->click("Advanced");
$I->wait(1);
$I->see('Organization introduction', 'Label');
$I->fillField($admin->orgIntro, 'My organization introduction');

// User can set a personal campaign introduction
$I->click("Personal campaign introduction");
$I->wait(1);
$I->see("Personal campaign introduction", 'label');
$I->fillField($admin->persIntro, 'My personal introduction');

// User can specify if the personal campaigner can override the personal campaign introduction
$I->checkOption($admin->persIntroEdit);
// User can upload personal campaign images
$I->click("Media");
//category
$I->attachFile($admin->catImage, '400x240.png');
//thumbnail;
$I->attachFile($admin->catImageThumb, '400x240.png');
// User can specify if the personal campaigner can override the images
$I->checkOption($admin->catImageEdit);
// User can set a video embed url
$I->fillField($admin->video, 'http://www.youtube.com/watch?v=');
// User can specify if the personal campaigner can override the video embed
$I->checkOption($admin->videoEdit);
$I->click("#edit-submit");
// Peer to Peer Campaign Creation
$I->amOnPage('springboard/add/p2p-campaign');
$I->click('#edit-submit');
// User must select a campaign category
// User must provide a name, description, thumbnail and slider image when creating a new campaign
// User must configure exactly 1 form to use with the campaign and configure an associated goal
$I->see('Name field is required.', '.error li');
$I->see('Description field is required.', '.error li');
$I->see('Campaign banner field is required.', '.error li');
$I->see('Landing page slider field is required.', '.error li');
$I->see('Campaign thumbnail field is required.', '.error li');
$I->see('Category field is required', '.error li');
$I->see('Category field is required', '.error li');
$I->see('Personal Campaign Expiration Message field is required', '.error li');
$I->see('Select a goal type.', '.error li');

// What?
// Goal types match the selected form type (amount raised for fundraiser enabled forms, submissions for all others)


// If the selected category has a donation form banner it is pre-populated in the campaign banner field
// If the selected category has an organization introduction it is pre-populated
// If the selected category has a personal campaign introduction it is pre-populated
// The personal campaign introduction's overridable setting is inherited from the selected category
// If the selected category has personal campaign images they are pre-populated
// The personal campaign images' overridable setting is inherited from the selected category
// If the selected category has a video embed it is pre-populated
// The video embed's overridable setting is inherited from the selected category

$I->selectOption($admin->catSelect, "My Category Title");
$I->wait('5');
$I->click('Personal campaign defaults');
$I->seeElement('//textarea[normalize-space(text())="My organization introduction"]');
$I->click('Personal campaign introduction');
$I->seeElement('//textarea[normalize-space(text())="My personal introduction"]');
$I->seeCheckboxIsChecked($admin->persIntroEdit);
$I->click('Media');
$I->seeCheckboxIsChecked($admin->catImageEdit);
//$I->seeElement('.draggable .image-preview img');
//$I->seeElement('div.field-name-field-p2p-campaign-banner img');
//$I->see('This is a Youtube or Vimeo video URL');

