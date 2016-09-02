<?php
/**
 * Class ticketsCest
 */
class ticketsCest {
  /**
   * @var $nid
   */
  public static $nid;

  public function _construct ($nid) {
    $this->nid = $nid;
  }
  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _before(AcceptanceTester\SpringboardSteps $I) {
    $tickets = new TicketsTabPage($I, $this);
    $tickets->configureTickets();
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _after(AcceptanceTester\SpringboardSteps $I) {
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function testClosedEvents(AcceptanceTester\SpringboardSteps $I) {
    $I = $this->_closedEvents($I);
    $this->_expiredEvents($I);
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function testSoldOutEvents(AcceptanceTester\SpringboardSteps $I) {
    $this->_soldOutEvents($I);
  }

  public function testDonorFormWidget(AcceptanceTester\SpringboardSteps $I) {
    $this->_enableAddOn($I);
    $this->_donorFormWidget($I);
    $this->_testAddOn($I);
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _createContentType(AcceptanceTester\SpringboardSteps $I) {
    $I->wantTo('Create a fundraiser tickets content type.');
    $I->amOnPage(\ContentTypePage::addRoute());
    $I->fillField(\ContentTypePage::$name, 'Ticketed Event');
    $I->click('Fundraiser settings');
    $I->click(\ContentTypePage::$fundraiserTab);
    $I->checkOption(\ContentTypePage::$fundraiser);
    $I->checkOption(\ContentTypePage::$fundraiserTickets);
    $I->click(\ContentTypePage::$webformUserTab);
    $I->checkOption(\ContentTypePage::$webformUser);
    $I->click(\ContentTypePage::$save);
    return $I;
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _createWaitListForm(AcceptanceTester\SpringboardSteps $I) {
    $I->amOnPage(\NodeAddPage::route('webform'));
    $I->fillField(\NodeAddPage::$title, 'Wait List Form');
    $I->click(\NodeAddPage::$save);
    return $I;
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _createTicketNode(AcceptanceTester\SpringboardSteps $I) {
    $I->amOnPage(\NodeAddPage::route('ticketed-event'));
    $I->fillField(\NodeAddPage::$title, 'Ticketed Event');
    $I->fillField('#edit-field-fundraiser-internal-name-und-0-value', 'Ticketed Event');
    $I->click("Payment methods");
    $I->checkOption('//input[@name="gateways[credit][status]"]');
    $I->click(\NodeAddPage::$save);
    $this->nid = $I->grabFromCurrentUrl('~.*/node/(\d+)/.*~');
    $I->click('View');
    $I->wait(3);
    $I->see('The form will not work properly until tickets have been created. To add tickets, click here.');
    $I->see('Tickets', '.fieldset-legend');
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->see('Wait List options');
    $I->see('Close settings');
    $I->see('Sell out settings');
    $I->see('Donation add-on');
    $I->selectOption(\TicketsTabPage::$waitList, 'Wait List Form');
    $I->click('Save');
    return $I;
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _createTickets(AcceptanceTester\SpringboardSteps $I) {

    $I->amOnPage(\TicketsTabPage::route($this->nid) . '/tickets');
    $I->click('Add ticket type');
    $I->fillField(ProductsUIPage::$sku, '1111');
    $I->fillField(ProductsUIPage::$title, 'Ticket 1');
    $I->fillField(ProductsUIPage::$price, 10);
    $I->fillField(ProductsUIPage::$description, 'Ticket 1 description.');
    $I->fillField(ProductsUIPage::$threshold, 1);
    $I->fillField(ProductsUIPage::$message, 'A warning message.');
    $I->fillField(ProductsUIPage::$quantity, 2);
    $I->click('Save product');

    $I->amOnPage(\TicketsTabPage::route($this->nid) . '/tickets');
    $I->click('Add ticket type');
    $I->fillField(ProductsUIPage::$sku, '1112');
    $I->fillField(ProductsUIPage::$title, 'Ticket 2');
    $I->fillField(ProductsUIPage::$price, 10);
    $I->fillField(ProductsUIPage::$description, 'Ticket 2 description.');
    $I->fillField(ProductsUIPage::$threshold, 1);
    $I->fillField(ProductsUIPage::$message, 'A warning message.');
    $I->fillField(ProductsUIPage::$quantity, 2);
    $I->click('Save product');
    $I->amOnPage('node/' .  $this->nid);
    $I->see('Ticket 1 description.');
    $I->see('Ticket 1 ($10.00)');
    return $I;
  }

  public function _closedEvents(AcceptanceTester\SpringboardSteps $I) {

    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->checkOption(\TicketsTabPage::$closed);
    $I->selectOption(\TicketsTabPage::$closedOptions, 'form');
    $I->click('Save');
    $I->amOnPage('node/' . $this->nid);
    $I->see('This form is closed and users are being redirected to the waitlist form');
    $I->logout();
    $I->amOnPage('node/' . $this->nid);
    $I->see('Wait List Form');

    $I->am('admin');
    $I->login();
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->selectOption(\TicketsTabPage::$closedOptions, 'message');
    $I->waitForElement(\TicketsTabPage::$closedMessage, 5);
    $I->fillField(\TicketsTabPage::$closedMessage, 'This event is closed.');
    $I->click('Save');
    $I->logout();
    $I->amOnPage('node/' . $this->nid);
    $I->see('This event is closed.');

    $I->am('admin');
    $I->login();
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->selectOption(\TicketsTabPage::$closedOptions, 'redirect');
    $I->waitForElement(\TicketsTabPage::$closedRedirect, 5);

    $I->fillField(\TicketsTabPage::$closedRedirect, 'node/2');
    $I->click('Save');
    $I->logout();
    $I->amOnPage('node/' . $this->nid);
    $I->seeInCurrentUrl('node/2');
    return $I;
  }

  public function _expiredEvents(AcceptanceTester\SpringboardSteps $I) {
    $I->am('admin');
    $I->login();
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->unCheckOption(\TicketsTabPage::$closed);
    $I->fillField(\TicketsTabPage::$closedDate, '1/1/2016');
    $I->click('Save');
    $I->logout();
    $I->amOnPage('node/' . $this->nid);
    $I->seeInCurrentUrl('node/2');

    $I->am('admin');
    $I->login();
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->fillField(\TicketsTabPage::$closedDate, '');
    $I->click('Save');
    return $I;
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _soldOutEvents(AcceptanceTester\SpringboardSteps $I) {
    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->selectOption(\TicketsTabPage::$soldOutOptions, 'message');
    $I->fillField(\TicketsTabPage::$soldOutMessage, 'My sold out message');
    $I->click('Save');
    $I->logout();
    $I->amOnPage('node/' . $this->nid);
    $I->fillInMyName();
    $I->fillInMyCreditCard();
    $I->fillInMyAddress();
    $I->fillField(\DonationFormPage::$emailField, 'admin@example.com');
    $I->selectOption(\TicketsTabPage::$ticketOneQuant, 1);
    $I->selectOption(\TicketsTabPage::$ticketTwoQuant, 1);
    $I->see('$20.00');
    $I->see('2', '#fundraiser-tickets-total-quant');
    $I->click('Submit');
    $I->amOnPage('node/' . $this->nid);
    $I->see('*Only 1 ticket remaining!*');
    $I->fillInMyName();
    $I->fillInMyCreditCard();
    $I->fillInMyAddress();
    $I->fillField(\DonationFormPage::$emailField, 'admin@example.com');
    $I->selectOption(\TicketsTabPage::$ticketOneQuant, 1);
    $I->selectOption(\TicketsTabPage::$ticketTwoQuant, 1);
    $I->click('Submit');
    $I->amOnPage('node/' . $this->nid);
    $I->see('My sold out message');
  }


  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _enableAddOn(AcceptanceTester\SpringboardSteps $I) {

    $I->amOnPage(\TicketsTabPage::route($this->nid));
    $I->checkOption(\TicketsTabPage::$addOnBox);
    $I->seeElement(\TicketsTabPage::$addOnAuto);
    $I->see('You may type either the form\'s Title or Internal Name when searching.');
    $I->see('Target donation forms must have an "other amount" field and must have the same currency as this form. Any required fields on target donation forms must also be required on this form.');
//    $I->see("Salesforce field to relate add-on to original donation");
//    $I->seeElement(\TicketsTabPage::$addOnSf);
    $I->fillField(\TicketsTabPage::$addOnAuto, 'Test Donation Form');
    $I->waitForElement('#autocomplete', 15);
    $I->wait('5');
    $I->click('#autocomplete ul:first-child li');
    $I->wait('5');
    $I->click('Save');
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _donorFormWidget(AcceptanceTester\SpringboardSteps $I) {
    $I->amOnPage('node/' . $this->nid);
    $I->selectOption(\TicketsTabPage::$ticketOneQuant, 2);
    $I->see('$20.00', '.fundraiser-ticket-type-total');
    $I->see('$20.00', '#fundraiser-tickets-total-cost');
    $I->see('2', '#fundraiser-tickets-total-quant');
    $I->selectOption(\TicketsTabPage::$ticketTwoQuant, 2);
    $I->see('$40.00', '#fundraiser-tickets-total-cost');
    $I->see('4', '#fundraiser-tickets-total-quant');
    $I->seeElement(\TicketsTabPage::$addOnAmt);
    $I->fillField(\TicketsTabPage::$addOnAmt, 50);
    $I->see('$90.00', '#fundraiser-tickets-total-cost');
  }

  /**
   * @param \AcceptanceTester\SpringboardSteps $I
   */
  public function _testAddOn(AcceptanceTester\SpringboardSteps $I) {
    $I->fillInMyName();
    $I->fillInMyCreditCard();
    $I->fillInMyAddress();
    $I->fillField(\DonationFormPage::$emailField, 'admin@example.com');
    $I->click('Submit');
    $I->see('Donation was successfully processed');
    $I->amOnPage('springboard/donations/');
    $I->see('$40.00', 'tr.views-row-first');
    $I->see('$50.00', 'tr.views-row-last');
  }

}
