<?php
// Acceptance tests for advocacy permissions.
$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('verify advocacy permissions properly control access');
$I->am('admin');
$I->login();
$I->installModule('Springboard Message Action');
$I->rebuildSpringboardAdminMenu();
$advocacy = new AdvocacyPage($I);
$advocacy->configureAdvocacy();
$I->expect('the advocacy menu to be available.');
$I->see('Advocacy', 'a.dropdown-toggle');
$I->expect('permissions are set correctly for the Springboard administrator role');
$rid = $I->getRid('Springboard administrator');
$I->amOnPage('/admin/people/permissions/' . $rid);
$I->seeCheckboxIsChecked('#edit-' . $rid . '-add-target-to-action');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-delete-targets');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-edit-targets');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-create-targets');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-create-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-edit-own-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-edit-any-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-delete-own-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-delete-any-sba-message-action-content');
$I->createUser('sb_admin', 'sb_admin@example.com', $rid);
$I->expect('permissions are set correctly for the Springboard editor role');
$rid = $I->getRid('Springboard editor');
$I->amOnPage('/admin/people/permissions/' . $rid);
$I->seeCheckboxIsChecked('#edit-' . $rid . '-add-target-to-action');
$I->dontSeeCheckboxIsChecked('#edit-' . $rid . '-delete-targets');
$I->dontSeeCheckboxIsChecked('#edit-' . $rid . '-edit-targets');
$I->dontSeeCheckboxIsChecked('#edit-' . $rid . '-create-targets');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-create-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-edit-own-sba-message-action-content');
//$I->dontSeeCheckboxIsChecked('#edit-' . $rid . '-edit-any-sba-message-action-content');
$I->seeCheckboxIsChecked('#edit-' . $rid . '-delete-own-sba-message-action-content');
//$I->dontSeeCheckboxIsChecked('#edit-' . $rid . '-delete-any-sba-message-action-content');
$I->createUser('sb_editor', 'sb_editor@example.com', $rid);
$I->logout();
$I->am('Springboard administrator');
$I->login('sb_admin', 'sb_admin');
$I->expect('appropriate advocacy menu items to be available.');
$I->see('Advocacy', 'a.dropdown-toggle');
$I->moveMouseOver('li.advocacy');
$I->see('View All Actions');
$I->see('Create a Message Action');
$I->see('Custom Targets');
$I->click('View All Actions');
$I->seeInCurrentUrl('/springboard/advocacy/actions');
$I->see('Advocacy: Actions', '.page-title');
$I->see('Create a new message action');
$I->see('Manage Custom Targets');
$I->logout();
$I->am('Springboard editor');
$I->login('sb_editor', 'sb_editor');
$I->expect('appropriate advocacy menu items to be available.');
$I->see('Advocacy', 'a.dropdown-toggle');
$I->moveMouseOver('li.advocacy');
$I->see('View All Actions');
$I->see('Create a Message Action');
$I->see('Custom Targets');
$I->click('View All Actions');
$I->seeInCurrentUrl('/springboard/advocacy/actions');
$I->see('Advocacy: Actions', '.page-title');
$I->see('Create a new message action');

//@todo more