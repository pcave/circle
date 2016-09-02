<?php
//@group webform

// Acceptance tests for webform saleforce integration.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Configure and test webform user settings.');

$I->am('admin');
$I->login();

// enable webform user for the webform content type
$I->amOnPage('admin/structure/types/manage/webform');
$I->see('Webform user settings', '.vertical-tab-button');
$I->click('Webform user settings');
$I->checkOption('#edit-webform-user--2');
$I->click('#edit-submit');

// create a new form node
$I->amOnPage(NodeAddPage::route('webform'));
$I->fillField(NodeAddPage::$title, "Test Webform");
$I->fillField(NodeAddPage::$internalTitle, "Test Webform");
$I->click(\NodeAddPage::$save);

$I->see('User profile fields have been mapped to webform components');
$I->see('E-mail address', 'td.first');
$I->see('First name', 'td.first');
$I->see('Last name', 'td.first');
$I->see('Address', 'td.first');
$I->see('Address Line 2', 'td.first');
$I->see('City', 'td.first');
$I->see('State/Province', 'td.first');
$I->see('Postal Code', 'td.first');
$I->see('Country', 'td.first');

// Check that the field types are correct
$I->see('Hidden', '//td[text()="Market Source"]/following-sibling::td');
$I->see('E-mail', '//td[text()="E-mail address"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Campaign ID"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Referrer"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Initial Referrer"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Search Engine"]/following-sibling::td');
$I->see('Hidden', '//td[text()="Search String"]/following-sibling::td');
$I->see('Hidden', '//td[text()="User Agent"]/following-sibling::td');
$I->see('Textfield', '//td[text()="First name"]/following-sibling::td');
$I->see('Textfield', '//td[text()="Last name"]/following-sibling::td');
$I->see('Textfield', '//td[text()="Address"]/following-sibling::td');
$I->see('Textfield', '//td[text()="Address Line 2"]/following-sibling::td');
$I->see('Textfield', '//td[text()="City"]/following-sibling::td');
$I->see('Select options', '//td[text()="State/Province"]/following-sibling::td');
$I->see('Textfield', '//td[text()="Postal Code"]/following-sibling::td');
$I->see('Select options', '//td[text()="Country"]/following-sibling::td');
$I->seeCheckboxIsChecked('//td[text()="E-mail address"]/following-sibling::td//input');

// get the new node's id
$node_id = $I->grabFromCurrentUrl('~.*/springboard/node/(\d+)/.*~');

// check that the fields are mapped correctly
$I->amOnPage('node/' . $node_id . '/webform/user_mapping');
$I->seeOptionIsSelected('//td[text()="E-mail address"]/following-sibling::td//select', 'E-mail address');
$I->seeOptionIsSelected('//td[text()="Market Source"]/following-sibling::td//select', 'Market Source');
$I->seeOptionIsSelected('//td[text()="Campaign ID"]/following-sibling::td//select', 'Campaign ID');
$I->seeOptionIsSelected('//td[text()="Referrer"]/following-sibling::td//select', 'Referrer');
$I->seeOptionIsSelected('//td[text()="Initial Referrer"]/following-sibling::td//select', 'Initial Referrer');
$I->seeOptionIsSelected('//td[text()="Search Engine"]/following-sibling::td//select', 'Search Engine');
$I->seeOptionIsSelected('//td[text()="Search String"]/following-sibling::td//select', 'Search String');
$I->seeOptionIsSelected('//td[text()="User Agent"]/following-sibling::td//select', 'User Agent');
$I->seeOptionIsSelected('//td[text()="First name"]/following-sibling::td//select', 'First name');
$I->seeOptionIsSelected('//td[text()="Last name"]/following-sibling::td//select', 'Last name');
$I->seeOptionIsSelected('//td[text()="Address"]/following-sibling::td//select', 'Address');
$I->seeOptionIsSelected('//td[text()="Address Line 2"]/following-sibling::td//select', 'Address Line 2');
$I->seeOptionIsSelected('//td[text()="City"]/following-sibling::td//select', 'City');
$I->seeOptionIsSelected('//td[text()="State/Province"]/following-sibling::td//select', 'State/Province');
$I->seeOptionIsSelected('//td[text()="Postal Code"]/following-sibling::td//select', 'Postal Code');
$I->seeOptionIsSelected('//td[text()="Country"]/following-sibling::td//select', 'Country');


/**
 * Testpad: profile field drop down contains all configured profile fields;
 * Would have to bootstrap drupal to do that...
 */


// check to see that authenticated user fields are pre-populated

$I->amOnPage('node/' . $node_id);
$I->fillField('E-mail address', 'mail@example.com');
$I->fillField('First name', 'first');
$I->fillField('Last name', 'last');
$I->fillField('Address', 'address');
$I->fillField('City', 'city');
$I->selectOption('Country', 'United States');
$I->selectOption('State/Province ', 'New York');
$I->fillField('Postal Code', '12205');
$I->click('#edit-submit');

$I->amOnPage('node/' . $node_id);

if ($I->grabValueFrom('E-mail address') == '') {
  $I->fail();
}
if ($I->grabValueFrom('Address') == '') {
  $I->fail();
}
if ($I->grabValueFrom('First name') == '') {
  $I->fail();
}
if ($I->grabValueFrom('Last name') == '') {
  $I->fail();
}
if ($I->grabValueFrom('City') == '') {
  $I->fail();
}
if ($I->grabValueFrom('State/Province') == '') {
  $I->fail();
}
if ($I->grabValueFrom('Postal Code') == '') {
  $I->fail();
}
if ($I->grabValueFrom('Country') == '') {
  $I->fail();
}

$I->logout();
$I->amOnPage('node/' . $node_id);

if ($I->grabValueFrom('E-mail address') != '') {
  $I->fail();
}
if ($I->grabValueFrom('Address') != '') {
  $I->fail();
}
if ($I->grabValueFrom('First name') != '') {
  $I->fail();
}
if ($I->grabValueFrom('Last name') != '') {
  $I->fail();
}
if ($I->grabValueFrom('City') != '') {
  $I->fail();
}
if ($I->grabValueFrom('State/Province') != '') {
  $I->fail();
}
if ($I->grabValueFrom('Postal Code') != '') {
  $I->fail();
}
if ($I->grabValueFrom('Country') != '') {
  $I->fail();
}

// new user created and profile fields set when form is submitted with a unique email address

$I->amOnPage('node/' . $node_id);
$I->fillField('E-mail address', 'newuser@example.com');
$I->fillField('First name', 'new');
$I->fillField('Last name', 'user');
$I->fillField('Address', 'address');
$I->fillField('City', 'city');
$I->selectOption('Country', 'United States');
$I->selectOption('State/Province ', 'New York');
$I->fillField('Postal Code', '12205');
$I->click('#edit-submit');

$I->am('admin');
$I->login();

$I->amOnPage('admin/people');
$I->see('newuser@example.com', 'td');
$I->click('edit','//tr[td//text()[contains(., "newuser@example.com")]]');
$user_id = $I->grabFromCurrentUrl('~.*/springboard/user/(\d+)/.*~');

$address = $I->grabValueFrom('Address');
if ($address == '') {
  $I->fail();
}
$first = $I->grabValueFrom('First name');
if ($first == '') {
  $I->fail();
}
$last =$I->grabValueFrom('Last name');
if ($last == '') {
  $I->fail();
}
$city = $I->grabValueFrom('City');
if ($city == '') {
  $I->fail();
}
$state =$I->grabValueFrom('State/Province');
if ($state == '') {
  $I->fail();
}
$zip  = $I->grabValueFrom('Postal Code');
if ($zip == '') {
  $I->fail();
}
$country = $I->grabValueFrom('Country');
if ($country == '') {
  $I->fail();
}
// existing user profile fields updated when form is submitted with a matching email address

$I->logout();

$I->amOnPage('node/' . $node_id);
$I->fillField('E-mail address', 'newuser@example.com');
$I->fillField('First name', 'newfirst');
$I->fillField('Last name', 'newlast');
$I->fillField('Address', 'newaddress');
$I->fillField('City', 'newcity');
$I->selectOption('Country', 'Canada');
$I->selectOption('State/Province ', 'Quebec');
$I->fillField('Postal Code', '11111');
$I->click('#edit-submit');

$I->am('admin');
$I->login();

$I->amOnPage('admin/people');
$I->see('newuser@example.com', 'td');
$I->click('edit','//tr[td//text()[contains(., "newuser@example.com")]]');

if ($I->grabValueFrom('Address') == $address) {
  $I->fail();
}
if ($I->grabValueFrom('First name') == $first) {
  $I->fail();
}
if ($I->grabValueFrom('Last name') == $last) {
  $I->fail();
}
if ($I->grabValueFrom('City') == $city) {
  $I->fail();
}
if ($I->grabValueFrom('State/Province') == $state) {
  $I->fail();
}
if ($I->grabValueFrom('Postal Code') == $zip) {
  $I->fail();
}
if ($I->grabValueFrom('Country') == $country) {
  $I->fail();
}


// @TODO new account email sent when option enabled


// Alter webform components permission grants access to add//edit/clone/delete webform components
// Administer user map permission grants access to user map
// Configure webform settings permission grants access to the "form settings" tab.
// Configure webform emails permission grants access to the "emails" tab and add/edit/delete paths for webform emails.

$I->amOnPage('springboard/user/' . $user_id . '/edit');
$I->checkOption('//label[normalize-space(text())="Springboard administrator"]/preceding-sibling::input');

$rid = $I->grabValueFrom('//label[normalize-space(text())="Springboard administrator"]/preceding-sibling::input');

$I->fillField('Password', 'password');
$I->fillField('Confirm password', 'password');
$I->click('#edit-submit');

$I->amOnPage('admin/people/permissions/' . $rid);
$I->checkOption('#edit-' . $rid . '-alter-webform-components', 'td#module-webform_user');
$I->checkOption('#edit-' . $rid . '-administer-user-map', 'td#module-webform_user');
$I->checkOption('#edit-' . $rid . '-configure-webform-settings', 'td#module-webform_user');
$I->checkOption('#edit-' . $rid . '-configure-webform-emails', 'td#module-webform_user');
$I->logout();

$I->am('newuser@example.com');
$I->login('newuser@example.com', 'password');

$I->amOnPage('node/' . $node_id . '/webform/user_mapping');
$I->see('Address Line 2');

$I->amOnPage('node/' . $node_id . '/form-components/confirmation-emails');
$I->checkOption('#edit-email-option-component');
$I->click('#edit-add-button');
$I->click('#edit-actions-submit');

$I->amOnPage('node/' . $node_id . '/form-components/confirmation-emails/1');
$I->see('Template', 'label');

$I->amOnPage('springboard/node/' . $node_id . '/form-components/confirmation-page-settings');
$I->see('Submission settings', 'legend');

$I->amOnPage('node/' . $node_id . '/form-components');
$I->click('Edit', '//table[@id="webform-components"]/tbody/tr[1]');
$I->see('Field Key', 'label');
$I->amOnPage('node/' . $node_id . '/form-components');
$I->click('Clone', '//table[@id="webform-components"]/tbody/tr[1]');
$I->see('Field Key', 'label');
$I->amOnPage('node/' . $node_id . '/form-components');
$I->click('Delete', '//table[@id="webform-components"]/tbody/tr[1]');
$I->see('This will immediately delete the', 'form');


