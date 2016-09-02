<?php
//@group misc

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('Enable and test Springboard Tags');

// We need a unique title so we can pick it from the template list.
$title = 'Form layouts test ' . time();

$I->am('admin');
$I->login();
$I->enableModule('Springboard Tag');

// Configure Permissions.
$I->amOnPage('admin/people/permissions');
$I->checkOption('#edit-4-administer-springboard-tags');

// Check menu.
$I->amOnPage('springboard/rebuild-sb-menu');
$I->click('Rebuild');
$I->moveMouseOver('.marketinganalytics');
$I->see('Tags');

// Check default/overridden/disable/enable administration.
$I->amOnPage('springboard/springboard-tags');
$I->see('General Datalayer');
$I->click('Edit');
$I->click('Save');
$I->see('Overridden');
$I->click('.ctools-link');
$I->click('Revert');
$I->click('Revert');
$I->dontSee('Overridden');
$I->click('.ctools-link');
$I->click('Enable');
$I->waitForElementVisible('.ctools-export-ui-enabled', 5);
$I->click('.ctools-link');
$I->click('Disable');
$I->wait(2);
$I->dontSeeElement('.ctools-export-ui-enabled');

// Add a new global tag.
$I->click('Add');
$I->fillField('#edit-admin-title', 'Administrative title');
$I->fillField('#edit-admin-description', 'Administrative description');
$I->fillField('#edit-tag', '<script language="Martian">Grok</script>');
$I->click("Save");

// Check Body placement.
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//body/script[@language="Martian"]');

// Check Header placement.
$I->click('Edit');
$I->selectOption('#edit-placement', 'In the head tag');
$I->click("Save");
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//head/script[@language="Martian"]');

// Check Content placement.
$I->click('Edit');
$I->selectOption('#edit-placement', 'Main content');
$I->click("Save");
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//div[@id="main"]//script[@language="Martian"]');

// Check anon user and placement
$I->logout();
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//div[@id="main"]//script[@language="Martian"]');
$I->am('admin');
$I->login();
$I->amOnPage('springboard/springboard-tags');
$I->click('Edit');
$I->selectOption('#edit-placement', 'In the head tag');
$I->click("Save");
$I->logout();
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//head/script[@language="Martian"]');
$I->am('admin');
$I->login();
$I->amOnPage('springboard/springboard-tags');
$I->click('Edit');
$I->selectOption('#edit-placement', 'In the head tag');
$I->click("Save");
$I->logout();
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//head/script[@language="Martian"]');

// Check admin permissions.
$I->amOnPage('springboard/springboard-tags');
$I->see('Access Denied');
$I->am('admin');
$I->login();

// Check springboard admin perms.
$I->createUser('testuser', 'testeruser@example.com', '4');
$I->logout();
$I->am('testuser');
$I->login();
$I->amOnPage('springboard/springboard-tags');
$I->dontSee('Access Denied');

// Check fundraiser confirmation.
$I->click("Edit");
$I->click('Fundraiser Visibility');
$I->waitForElementVisible('#edit-visibility-fundraiser-confirmation', 10);
$I->checkOption('#edit-visibility-fundraiser-confirmation');
$I->click('Content Visibility');
$I->waitForElementVisible('#edit-visibility-node-type-donation-form', 10);
$I->checkOption('#edit-visibility-node-type-donation-form');
$I->click("Save");
$I->click('Donation Forms');
$I->click("view");
$I->makeADonation();
$I->seeInSource('<script language="Martian">Grok</script>');
$I->seeElementInDOM('//head/script[@language="Martian"]');

// Check that it is not on an excluded content type.
$I->amOnPage('node/1');
$I->dontSeeInSource('<script language="Martian">Grok</script>');

// Limit by role.
$I->amOnPage('springboard/springboard-tags/list/administrative_title/edit');
$I->click('Fundraiser Visibility');
$I->waitForElementVisible('#edit-visibility-fundraiser-confirmation', 10);
$I->unCheckOption('#edit-visibility-fundraiser-confirmation');
$I->click('User Visibility');
$I->waitForElementVisible('#edit-visibility-user-roles-1', 10);
$I->checkOption('#edit-visibility-user-roles-1');
$I->click('Save');
$I->logout();
$I->amOnPage('node/2');
// Limit by Path.
$I->dontSeeInSource('<script language="Martian">Grok</script>');
$I->am('testuser');
$I->login();
$I->amOnPage('springboard/springboard-tags/list/administrative_title/edit');
$I->click('User Visibility');
$I->waitForElementVisible('#edit-visibility-user-roles-1', 10);
$I->unCheckOption('#edit-visibility-user-roles-1');
$I->click('Path Visibility');
$I->waitForElementVisible('#edit-visibility-path-pages', 10);
$I->fillField('#edit-visibility-path-pages', 'node/*/edit');
$I->click('Save');
$I->amOnPage('node/2/edit');
$I->dontSeeInSource('<script language="Martian">Grok</script>');
