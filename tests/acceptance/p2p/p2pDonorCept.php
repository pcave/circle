<?php

//@group p2p

$I = new \AcceptanceTester\SpringboardSteps($scenario);
//$scenario->incomplete();
$I->wantTo('Configure and test p2p donor functions.');

$I->am('admin');
$I->login();
$I->configureEncrypt();
$admin = new P2pAdminPage($I);
$admin->enableFeature();

// // generate dummy content
$I->amOnPage($admin->starterUrl);
$I->click('Create content');
$I->waitForElement('.campaign-landing-grid', 30);


$I->amOnPage('springboard/p2p');
$I->wait(4);
$I->click('edit','//tr[td//text()[contains(., "No to Nets")]]');
$camp_id = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
$goal = $I->grabValueFrom('//input[@name="field_p2p_personal_campaign_goal[und][0][value]"]');
$I->logout();
$I->wait(4);


// Goal Block
// The goal displayed has the correct value and currency formatted
// Goal progress increases correctly after each donation
// Visual progress bar is representative of the actual progress (e.g., 50% complete the bar should filled halfway)
// Deadline date renders when a deadline configured on the personal campaign
// Link to donation form is rendered and passes the correct personal campaign id to the donation form
$I->amOnPage('node/' . $camp_id);

// $ 1,000.00
$pretty_goal = '$ ' . number_format($goal, 2);

$I->see($pretty_goal, '.goal-amount');
$I->click('Donate now');
$I->wait(4);
//$I->makeADonation();
$I->seeInCurrentURl('p2p_pcid=' . $camp_id);
$I->executeJS("jQuery('input[value=\"10\"]').siblings('label').click()");
$I->fillInMyName();
$I->fillField(\DonationFormPage::$emailField, 'bob@example.com');
$I->fillInMyAddress();
$I->fillInMyCreditCard();
$I->executeJS("jQuery('input[value=\"recurs\"]').siblings('label').click()");$I->executeJS("jQuery('input[value=\"recurs\"]').siblings('label').click()");$I->executeJS("jQuery('input[value=\"recurs\"]').siblings('label').click()");$I->executeJS("jQuery('input[value=\"recurs\"]').siblings('label').click()");

$I->click(\DonationFormPage::$donateButton);
$I->wait(4);
$I->amOnPage('node/' . $camp_id);
$I->see('10.00 raised to date');
$I->see('Campaign Deadline');


// Share Block
// User is able to share the personal campaign to the configured social networks

// Recent Donors List
// The recent donors list appears when the "Show donor scroll on personal campaign pages" is set on the campaign the personal campaign is associated with
$I->see('Recent donors');

// The most recent donor appears at the top
// The donors name is not rendered if they do not check the "Show my name on the campaign page" when making a donation to the personal campaign
$I->see('Anonymous');

// The amount and dollar formatting are correct for each recent donation
$I->see('$ 10.00');

// Content
// The campaign banner image configured at the peer to peer campaign appears at the top of the personal campaign page
// The personal campaign introduction comes from the personal campaigner when set to be overridable
// The personal campaign introduction comes from the personal campaigner when set to be overridable
// The personal campaign images come from the personal campaigner when set to be overridable
// The organization introduction content configured at the peer to peer campaign appears on the personal campaign page
//above are redundant to other tests



// All donate buttons point to the form configured on the peer to peer campaign the personal campaign is associated with
// All donate buttons pass the id of the personal campaign to the donation form on the url
$parent_campaign_id = $I->grabFromDatabase('field_data_field_p2p_campaign', 'field_p2p_campaign_target_id', array('entity_id' => $camp_id));
$parent_goal_id = $I->grabFromDatabase('field_data_field_p2p_campaign_goals', 'field_p2p_campaign_goals_goal_set_id', array('entity_id' => $parent_campaign_id));
$donation_form_id = $I->grabFromDatabase('springboard_p2p_fields_campaign_goals', 'nid', array('goal_set_id' => $parent_goal_id));
$donation_form_alias = $I->grabFromDatabase('url_alias', 'alias', array('source' => 'node/' . $donation_form_id));

$I->seeElement('//div[contains(@class, "pane-progress")]//a[contains(@href, "' . $donation_form_alias .'")]');
$I->seeElement('//div[contains(@class, "pane-personal-campaign-call-to-action")]//a[contains(@href, "' . $donation_form_alias .'")]');
$I->seeElement('//div[contains(@class, "pane-org-intro")]//a[contains(@href, "' . $donation_form_alias .'")]');
$I->seeElement('//div[contains(@class, "pane-progress")]//a[contains(@href, "p2p_pcid=' . $camp_id .'")]');
$I->seeElement('//div[contains(@class, "pane-personal-campaign-call-to-action")]//a[contains(@href, "p2p_pcid=' . $camp_id .'")]');
$I->seeElement('//div[contains(@class, "pane-org-intro")]//a[contains(@href, "p2p_pcid=' . $camp_id .'")]');

// Donor Comments
// Donor comments display when the "Show donor comments on personal campaign pages" setting is enabled on the peer to peer campaign the personal campaign is associated with
// The donors name is not rendered in the comments if they leave a comment and do not check the "Show my name on the campaign page" when making a donation to the personal campaign
$I->amOnPage('node/' . $camp_id);
$I->click('Donate now');
$I->wait(3);
$I->executeJS("jQuery('input[value=\"10\"]').siblings('label').click()");
$I->fillInMyName();
$I->fillField(\DonationFormPage::$emailField, 'bob@example.com');
$I->fillInMyAddress();
$I->fillInMyCreditCard();
$I->fillField('//textarea[@name="springboard_p2p_personal_campaign_action[comment]"]', 'xxx');
$I->click(\DonationFormPage::$donateButton);
$I->amOnPage('node/' . $camp_id);
$I->dontSee('John Tester', '.pane-recent-donors');
$I->click('Donate now');
$I->fillInMyName();
$I->fillField(\DonationFormPage::$emailField, 'bob@example.com');
$I->fillInMyAddress();
$I->fillInMyCreditCard();
$I->wait(5);
$I->executeJS('return jQuery("input[type=checkbox]").css("display", "block")');
$I->checkOption('#edit-springboard-p2p-personal-campaign-action-show-name');
$I->fillField('//textarea[@name="springboard_p2p_personal_campaign_action[comment]"]', 'xxx');
$I->click(\DonationFormPage::$donateButton);

// Search Block
// Search block contains a link to all personal campaigns
// User can search for personal campaigns by personal campaign title or campaigner name
$I->login();
$I->wait(8);
$I->amOnPage('springboard/user/1/edit');
$I->fillField('//input[@name="sbp_first_name[und][0][value]"]', 'admin');
$I->click('#edit-submit');
$I->logout();
$I->wait(3);
$I->amOnPage('node/' . $parent_campaign_id);
$I->seeElement('.search-wrapper');
$I->fillField('//input[@name="combine"]', 'no to nets');
$I->click('//input[@value="Search"]');
$I->wait(3);
$I->see('No to Nets', '.view-p2p-search-for-a-campaign');
$I->fillField('//input[@name="combine"]', 'admin');
$I->click('//input[@value="Search"]');
$I->wait(3);
$I->seeElement('.view-p2p-search-for-a-campaign tr.even');

// Donate
// Donation form displays the same banner image as the personal campaign
// Donation form displays the title of the personal campaign
// Donation form displays the personal campaign goal
// Donation form displays the personal campaign's goal progress
$I->amOnPage('node/' . $camp_id);
$banner = $I->grabAttributeFrom('.pane-campaign-header img', 'src');
$I->click('Donate now');
$I->wait(3);
$pos = strpos($banner, '?');
if($pos !== FALSE) {
  $banner = substr($banner, 0, $pos);
}
$I->seeElement('//img[contains(@src, "' . $banner .'")]');
$I->see('Personal Fundraising Goal');
$I->see('Personal campaign progress');
$I->see('raised to date');
$I->seeElement('.progress-bar');

// Donation form displays the name of the personal campaigner
// eh?

// Goal progress is updated correctly after a successful donation to the personal campaign is made
//no way to test that?

// The donation confirmation page and email can utilize personal campaign specific tokens
// Personal campaigner user first name
// Personal campaigner user last name
// personal campaign title
// URL for personal campaign page
// URL for personal campaigner donation page (current node)
// Node id of personal campaign page (nid)
// Personal campaign goal
// Personal campaign deadline
// Donation amount (already handled by fundraiser)
