<?php

//@group p2p

// Personal Campaign Creation

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Configure and test p2p personal campaigns.');

$I->am('admin');
//
$I->login();

$admin = new P2pAdminPage($I);
$admin->enableFeature();

// generate dummy content
$I->amOnPage($admin->starterUrl);
$I->click('Create content');
$I->waitForElement('.campaign-landing-grid', 30);
$I->amOnPage('springboard/p2p');
$I->click('edit','//tr[td//text()[contains(., "Cross River Gorilla")]]');
$editable_camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
$campaign_description = $I->grabValueFrom($admin->body);

$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->wait(4);
$campaign_video = $I->grabValueFrom($admin->video);
$I->checkOption($admin->catImageEdit);
$I->checkOption($admin->videoEdit);

$I->click('Personal campaign introduction');
$campaign_intro = $I->grabValueFrom($admin->persIntro);
$I->checkOption($admin->persIntroEdit);
$I->click('#edit-submit');

// // add permssions for campaigner and create campaign user
$rid = $I->getRid('Springboard P2P campaigner');
$I->amOnPage('admin/people/permissions/' . $rid);
$I->executeJS('jQuery("#springboard-admin-home-link").remove()');
$I->checkOption('#user-admin-permissions td input#edit-' . $rid . '-create-p2p-personal-campaign-content');
$I->checkOption('#user-admin-permissions td input#edit-' . $rid . '-edit-own-p2p-personal-campaign-content');
$I->executeJS("jQuery('#edit-{$rid}-edit-own-p2p-personal-campaign-content').prop('checked', true)");

$I->click('#edit-submit');
$I->createUser('Campaigner', 'campaigner@example.com', $rid);

$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');

// Create personal campaign "standard case"
// view node/add/p2p-personal-campaign?p2p_cid=<campaign_node_id>
// confirm Campaign Name is prefilled with node title from parent campaign
// confirm campaign select box is hidden
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $editable_camp_id);
$I->seeElement('//input[@value="Cross River Gorilla"]');
$I->cantSeeElement('//select');

// add & remove images


//  confirm campaign select box does not appear after ajax event. ???????????
// confirm campaign url is empty ?????????????


// confirm video embed field contains value from parent campaign.
// confirm campaign intro is displayed as a textarea with the default campaign intro from the parent campaign set as the default value.
// confirm images are prefilled with values from the parent campaig
$I->seeElement('//input[@value="' . $campaign_video .'"]');
$I->seeElement('//textarea[text()="' . $campaign_intro .'"]');
$I->seeElement('//table[@id="edit-field-p2p-campaign-images-und-table"]//img[contains(@src, ".png")]');

// save personal campaign node, confirm personal campaign saves with no errors.
$string = time();
$I->fillField('//input[@name="field_p2p_personal_campaign_url[und][0][value]"]', $string);
$I->wait(4);
$I->fillField('//input[@name="field_p2p_personal_campaign_goal[und][0][value]"]', '123');
$I->wait(2);
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->click('#edit-submit');
$I->wait(2);
$I->seeElement('.pane-campaign-header img');
$I->seeElement('iframe');
// Edit new personal campaign node, confirm all settings saved.
// Resave, confirm no errors, if possible confirm no duplicate entry in {url_alias}
$I->click('Edit');
$editable_pers_camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
$I->wait(4);
$I->seeElement('//input[@value="' . $string . '"]');
$I->seeElement('//input[@value="123.00"]');
$I->click('#edit-submit');
$I->see('has been updated');


// Create personal campaign "missing campaign id"
// visit node/add/p2p-personal-campaign (no campaign id in url)
$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');

$I->amOnPage('node/add/p2p-personal-campaign');
// confirm no error messages
$I->cantSeeElement('.error');
$I->click('#edit-submit');
$I->see('Campaign Name field is required.', '.error');
$I->see('Campaign Introduction field is required.', '.error');
$I->see('Campaign field is required.', '.error');
$I->see('Fundraising Goal field is required.', '.error');
$I->see('Campaign URL field is required.', '.error');

// confirm no fields are prepopulated
$I->seeElement('//input[@name="title" and @value=""]');
$I->seeElement('//input[@name="field_p2p_personal_campaign_url[und][0][value]" and @value=""]');

// confirm campaign select is displayed as a select box, is required, and has no prefilled value
$I->seeOptionIsSelected('//select', '- Select a value -');
$I->selectOption('//select', 'Cross River Gorilla');
$I->wait(5);
// confirm selecting campaign redirects to node add form
// confirm campaign id is in the url after redirect
// confirm campaign name, campaign introduction, images, and video embed are prepopulated correctly

$I->seeInCurrentURl('p2p_cid');
$I->seeElement('//input[@value="Cross River Gorilla"]');
$I->seeElement('//input[@value="' . $campaign_video .'"]');
$I->seeElement('//textarea[text()="' . $campaign_intro .'"]');
$I->seeElement('//table[@id="edit-field-p2p-campaign-images-und-table"]//img[contains(@src, ".png")]');


// Create personal campaign "campaign id invalid"
// visit node/add/p2p-personal-campaign?campaign=<invalid node id> with invalid (numeric) node id
// confirm no error messages
// confirm no fields are prepopulated
// confirm campaign select is displayed as a select box, is required, and has no prefilled value
// visit node/add/p2p-personal-campaign?campaign=<invalid node id> with invalid (non-numeric) node id
// confirm no error messages
// confirm no fields are prepopulated
// confirm campaign select is displayed as a select box, is required, and has no prefilled value

$I->amOnPage('node/add/p2p-personal-campaign/?p2p_cid=1234567');
$I->cantSeeElement('.error');
$I->click('#edit-submit');
$I->see('Campaign Name field is required.', '.error');
$I->see('Campaign Introduction field is required.', '.error');
$I->see('Campaign field is required.', '.error');
$I->see('Fundraising Goal field is required.', '.error');
$I->see('Campaign URL field is required.', '.error');
$I->seeOptionIsSelected('//select', '- Select a value -');

$I->amOnPage('node/add/p2p-personal-campaign/?p2p_cid=wooblewooble');
$I->cantSeeElement('.error');
$I->click('#edit-submit');
$I->see('Campaign Name field is required.', '.error');
$I->see('Campaign Introduction field is required.', '.error');
$I->see('Campaign field is required.', '.error');
$I->see('Fundraising Goal field is required.', '.error');
$I->see('Campaign URL field is required.', '.error');
$I->seeOptionIsSelected('//select', '- Select a value -');

// Create personal campaign "campaign is private, user authorized"
// Create campaign, check "Personal campaigns require approval"
$I->logout();
$I->wait(4);
$I->login();
$I->amOnPage($admin->addCampUrl);
$I->fillField($admin->title, 'Private Campaign');
$I->selectOption($admin->catSelect, "Animal Rights");
$I->wait(5); //load up
$I->fillField($admin->body, 'A private campaign description.');
$I->checkOption($admin->campP2pDonation);
$I->fillField($admin->campP2pDonationGoal, '123');
//takes two  clicks
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');

$I->checkOption($admin->campApproval);
$I->fillField($admin->orgIntro, 'A private campaign organization intro');
$I->fillField($admin->campExpire, 'A private campaign expiration message');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->attachFile($admin->slider, '1170x360.png');
$I->attachFile($admin->banner, '1170x360.png');
$I->attachFile($admin->campThumb, '400x240.png');
$I->click('//input[@value="Save"]');
$I->click('Edit');
$camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');
$I->amOnPage('node/' . $camp_id);
$I->click('Get Started');
$I->logout();
$I->wait(4);
$I->login();
$I->amOnPage('springboard/p2p');
$I->click('//input[@value="Approve"]','//tr[//td//a//text()[contains(., "campaigner@example.com")]]');
$I->logout();
$I->wait(4);

// // // Log in as a user authorized for this campaign
// // // view node/add/p2p-personal-campaign?p2p_cid=<node id> with the node id of the campaign
// // // confirm node add form is populated with defaults from the campaign.
// // // save personal campaign
// // // confirm settings saved with no errors.
$I->login('Campaigner', 'Campaigner');
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $camp_id);
$I->seeInCurrentURl('p2p_cid');
$I->seeElement('//input[@value="Private Campaign"]');


// // // Create personal campaign "campaign is private, user is not authorized"
// // // Log in as a user that is not authorized for the campaign created in the previous segment.
$I->logout();
$I->wait(4);
$I->login();
$rid = $I->getRid('Springboard P2P campaigner');
$I->createUser('invalid campaigner', 'invalidcampaigner@example.com', $rid);
$I->logout();
$I->wait(4);
$I->login('invalid campaigner', 'invalid campaigner');
// // view node/add/p2p-personal-campaign?p2p_cid=<node id> with the node id of the campaign
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $camp_id);
//
//// confirm node add form is replaced with a message explaining the campaign is private.
////Bug in p2p
//// confirm link is available to request authorization.
////$I->see('a meesage that is not there');
//
//// Edit personal campaign "uneditable defaults"
//// Find or create a personal campaign associated with a campaign with uneditable default values for intro, images, and video
//// edit this personal campaign.
//// confirm campaign introduction is visible but disabled
//// confirm no UI available for images or video
//// Save settings, confirm no error messages & settings save
$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $camp_id);
$I->cantSeeElement('//textarea[@name="body[und][0][value]"]');
$I->cantSeeElement('//input[@name="field_p2p_video_embed[und][0][video_url]"]');
$I->cantSee('Upload a Campaign Image');
$I->cantSee('Suggested Donation Amount');
$string = time();
$I->fillField('//input[@name="field_p2p_personal_campaign_url[und][0][value]"]', $string);
$I->wait(4);
$I->fillField('//input[@name="field_p2p_personal_campaign_goal[und][0][value]"]', '123');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->wait(2);
$I->click('#edit-submit');
$I->wait(4);
$I->click('Edit');

$uneditable_pers_camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
//
//
//// Edit existing campaign with uneditable defaults
//// On a campaign with personal campaigns associated,
//// Edit the default personal campaign intro, image settings & embedded video settings
//// Save.
//// View one or more personal campaigns associated with this campaign
//// In each case confirm existing settings were overwritten by changes to the parent campaign

$I->logout();
$I->wait(4);
$I->login();
$I->amOnPage('node/' . $camp_id . '/edit');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_personal_intro"]');
$I->fillField('//textarea[@name="field_p2p_personal_intro[und][0][value]"]', 'kaboooooom!');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->click('//input[@name="field_p2p_images_und_0_remove_button"]');
$I->wait(5);
$I->fillField('//input[@name="field_p2p_video_embed[und][0][video_url]"]', '');
$I->click('#edit-submit');
$I->amOnPage('node/' . $uneditable_pers_camp_id);
$I->see('kaboooooom!');
$I->cantSeeElement('//iframe');
//
//

// Edit existing campaign with editable defaults
// Find a campaign with personal campaigns associated
// Edit the default personal campaign intro, image settings & embedded video settings
// Save.
// View one or more personal campaigns associated with this campaign
// In each case confirm existing settings were not overwritten by changes to the parent campaign
$I->logout();
$I->wait(4);
$I->login();
$I->amOnPage('node/' . $editable_camp_id . '/edit');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_personal_intro"]');
$I->fillField('//textarea[@name="field_p2p_personal_intro[und][0][value]"]', 'kaboom!');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->click('//input[@name="field_p2p_images_und_0_remove_button"]');
$I->wait(4);

$I->fillField('//input[@name="field_p2p_video_embed[und][0][video_url]"]', '');
$I->click('#edit-submit');
$I->amOnPage('node/' . $editable_pers_camp_id);
$I->cantSee('kaboom!');
$I->seeElement('//iframe');
////it seems that the thumbnail does not have any specific selectors
//
//
//// Edit personal campaign "editable defaults"
//// Find or create a personal campaign associated with a campaign with editable default values for intro, images, and video
//// Edit this personal campaign
//// On edit form confirm UI is available for intro, images, and video
//// change set
$I->logout();
$I->wait(4);
$I->login();
//// confirm no error messages on save & settings save successfully.

$I->amOnPage('node/' . $camp_id . '/edit/');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');
$I->checkOption('//input[@name="field_p2p_ask_amount_edit[und]"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_personal_intro"]');
$I->checkOption('//input[@name="field_p2p_personal_intro_edit[und]"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->checkOption('//input[@name="field_p2p_images_edit[und]"]');
$I->checkOption('//input[@name="field_p2p_video_embed_edit[und]"]');
$I->click('#edit-submit');
$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $camp_id);
$I->see('Upload a Campaign Image');
$I->seeElement('//input[@name="field_p2p_video_embed[und][0][video_url]"]');
$I->see('Suggested Donation Amount');
$I->fillField('//input[@name="field_p2p_personal_campaign_goal[und][0][value]"]', '123');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->wait(2);
$I->click('#edit-submit');

//$I->cantSeeElement('.error');
$I->click('Edit');
$p_camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');



// Missing campaign defaults
// Create a campaign with no default personal campaign intro, no attached images, and no embedded video, leave these fields uneditable.
// Add a personal campaign for this campaign.
// On the node edit form confirm campaign introduction is editable.
// Confirm images UI is unavailable.
// Confirm embedded video UI is unavailable.
// save personal campaign.
// confirm no error messages & settings saved

$I->logout();
$I->wait(4);
$I->login();
$I->amOnPage($admin->addCampUrl);
$I->fillField($admin->title, 'No defaults');
$I->selectOption($admin->catSelect, "Animal Rights");
$I->wait(4);
$I->checkOption($admin->campP2pDonation);
$I->fillField($admin->campP2pDonationGoal, '123');
$I->fillField($admin->body, 'A private campaign description.');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_images"]');
$I->attachFile($admin->slider, '1170x360.png');
$I->attachFile($admin->banner, '1170x360.png');
$I->attachFile($admin->campThumb, '400x240.png');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->click('//ul//li//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');
$I->click('//ul//li//a[@href="#node_p2p_campaign_form_group_p2p_campaign_defaults"]');

$I->fillField($admin->campExpire, 'A private campaign expiration message');
$I->executeJS('jQuery("table.sticky-header thead").remove()');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_personal_intro"]');
$I->click('//a[@href="#node_p2p_campaign_form_group_p2p_personal_intro"]');
$I->checkOption('//input[@name="field_p2p_personal_intro_edit[und]"]');
$I->click('//input[@value="Save"]');
$I->click('Edit');
$node_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
$I->logout();
$I->wait(4);
$I->login('Campaigner', 'Campaigner');
$I->amOnPage('node/add/p2p-personal-campaign?p2p_cid=' . $node_id);
$I->wait(1);
$I->seeElement('//textarea[@name="body[und][0][value]"]');
$I->cantSeeElement('//input[@name="field_p2p_video_embed[und][0][video_url]"]');
$I->cantSee('Upload a Campaign Image');
$I->cantSee('Suggested Donation Amount');