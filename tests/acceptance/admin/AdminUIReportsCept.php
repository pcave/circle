<?php
//@group admin'admin');

// Acceptance tests for admin UI and menus.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('use the Springboard Admin UI to view reports');

// Make 5 donations to fill the report.
for ($i = 0; $i <= 4; $i++) {
  $defaults = array(
    'mail' => 'bob' . $i . '@example.com',
  );
  $I->amOnPage(DonationFormPage::$URL);
  $I->makeADonation($defaults);
}

$I->am('admin');
$I->wantTo('view the donation report.');
$I->login();
$I->seeInCurrentUrl('/springboard');

// Check the contact report.
$I->wantTo('view the contact report.');
$I->moveMouseOver('li.reports');
$I->click('Contacts');
$I->seeInCurrentUrl('/springboard/reports/contacts');
$headers = array(
  'Uid',
  'First name',
  'Last name',
  'E-mail',
  'Address',
  'City',
  'State',
  'Zip',
  'Country',
  'Created',
  'Action',
);

foreach($headers as $header) {
  $I->canSee($header, '#block-system-main table.views-table th.views-field');
}

for ($i = 0; $i <= 4; $i++) {
  $I->canSee('Bob' . $i . '@Example.Com', '#block-system-main table.views-table td.views-field-mail a');
}

// Check the donation report.
$I->moveMouseOver('li.reports');
$I->click('Donations');
$I->seeInCurrentUrl('/springboard/reports/donations');

$headers = array(
  'Order ID',
  'Date',
  'Amount',
  'Name',
  'E-mail',
  'Form',
  'Order status',
  'Actions',
);

foreach($headers as $header) {
  $I->canSee($header, '#block-system-main table.views-table th.views-field');
}

// The 5 donations made previously should be in the report.
for ($i = 0; $i <= 4; $i++) {
  $I->canSee('Bob' . $i . '@Example.Com', '#block-system-main table.views-table td.views-field-mail a');
}