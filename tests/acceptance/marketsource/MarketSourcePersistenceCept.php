<?php
//@group marketsource

$I = new \AcceptanceTester\SpringboardSteps($scenario);
$I->wantTo('test Market Source Persistence');

$I->am('admin');
$I->login();

$I->click('Marketing & Analytics', 'header');
$I->click('Source Codes', '.aggregate-links');

$I->click('#edit-market-source-global-fields-custom-fields-wrapper-0-persistence-on');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-0-name', 'On field');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-0-key', 'on_field');

$I->click('#edit-market-source-global-fields-add-more');

$I->seeInMessages('Market Source settings saved.');

$I->click('#edit-market-source-global-fields-custom-fields-wrapper-1-persistence-off');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-1-name', 'Off field');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-1-key', 'off_field');

$I->click('#edit-market-source-global-fields-add-more');

$I->seeInMessages('Market Source settings saved.');

$I->click('#edit-market-source-global-fields-custom-fields-wrapper-2-persistence-direct');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-2-name', 'Direct field');
$I->fillField('#edit-market-source-global-fields-custom-fields-wrapper-2-key', 'direct_field');

$I->click('#edit-submit');

$I->seeInMessages('Market Source settings saved.');
$nid = $I->createWebform();

$query_string_a = '?on_field=A&off_field=A&direct_field=A';
$query_string_b = '?on_field=B&off_field=B&direct_field=B';


// 1. No default. No param.
// Expecting all blanks.
$I->amOnPage('node/' . $nid);
$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('', '#webform-component-on-field');
$I->see('', '#webform-component-off-field');
$I->see('', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');


// 2. No default. Param A before the form.
// Expecting A, A, blank
$I->amOnPage('' . $query_string_a);
$I->amOnPage('node/' . $nid);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('A', '#webform-component-off-field');
$I->see('', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');


// 3. No default. Param A on the form.
// Expecting A, A, A
$I->amOnPage('node/' . $nid . $query_string_a);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('A', '#webform-component-off-field');
$I->see('A', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');


// 4. No default. Param A before the form. Param B on the form.
// Expecting A, B, B
$I->amOnPage('' . $query_string_a);
$I->amOnPage('node/' . $nid . $query_string_b);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('B', '#webform-component-off-field');
$I->see('B', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');


// Configure default value on the webform components.
$I->click('Form components', 'ul.primary');

$js = "jQuery('a', jQuery('td:contains(\"On field\")').siblings(':contains(\"Edit\")'))[0].click();";
$I->executeJS($js);
$I->waitForElement('#edit-value', 10);
$I->fillField('#edit-value', 'on_field_default');
$I->click('Save component', '.form-actions');
$I->seeInMessages('Component On field updated.');

$js = "jQuery('a', jQuery('td:contains(\"Off field\")').siblings(':contains(\"Edit\")'))[0].click();";
$I->executeJS($js);
$I->waitForElement('#edit-value', 10);
$I->fillField('#edit-value', 'off_field_default');
$I->click('Save component', '.form-actions');
$I->seeInMessages('Component Off field updated.');

$js = "jQuery('a', jQuery('td:contains(\"Direct field\")').siblings(':contains(\"Edit\")'))[0].click();";
$I->executeJS($js);
$I->waitForElement('#edit-value', 10);
$I->fillField('#edit-value', 'direct_field_default');
$I->click('Save component', '.form-actions');
$I->seeInMessages('Component Direct field updated.');


// 5. Default. No param.
// Expecting default, default, default
$I->amOnPage('node/' . $nid);
$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('on_field_default', '#webform-component-on-field');
$I->see('off_field_default', '#webform-component-off-field');
$I->see('direct_field_default', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');


// 6. Default. Param A before the form.
// Expecting A, A, default
$I->amOnPage('' . $query_string_a);
$I->amOnPage('node/' . $nid);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('A', '#webform-component-off-field');
$I->see('direct_field_default', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');

// 7. Default. Param A on the form.
// Expecting A, A, A
$I->amOnPage('node/' . $nid . $query_string_a);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('A', '#webform-component-off-field');
$I->see('A', '#webform-component-direct-field');

$I->click('Delete', 'ul.primary');
$I->click('Delete', '.form-actions');
$I->seeInMessages('Submission deleted.');

$I->resetCookie('market_source__on_field');
$I->resetCookie('market_source__off_field');
$I->resetCookie('market_source__direct_field');

// 8. Default. Param A before the form. Param B on the form.
// Expecting A, B, B
$I->amOnPage('' . $query_string_a);
$I->amOnPage('node/' . $nid . $query_string_b);

$I->click('#edit-submit');
$I->click('Go back to the form');
$I->click('Results');
$I->click('View', '//*[@id="block-system-main"]/div/table[2]/tbody/tr/td[5]');
$I->see('A', '#webform-component-on-field');
$I->see('B', '#webform-component-off-field');
$I->see('B', '#webform-component-direct-field');

// No point in wasting time deleting the submission or
// resetting cookies as this is the last case.
