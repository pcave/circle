<?php

class TicketsTabPage
{
    // include url of current page
    public static $URL = 'springboard/node/%nid/tickets';
    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
         $url = static::$URL;
         return str_replace('%nid', $param, $url);
     }

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    // generic variables
    public static $waitList = '//select[@name="fr_tickets_waitlist_form[und]"]';
    public static $closed = '//input[@name="fr_tickets_closed_is_closed[und]"]';
    public static $closedDate = '//input[@name="fr_tickets_closed_close_date[und][0][value][date]"]';
    public static $closedOptions = '//input[@name="fr_tickets_closed_options[und]"]';
    public static $closedMessage = '//textarea[@name="fr_tickets_closed_message[und][0][value]"]';
    public static $closedRedirect = '//input[@name="fr_tickets_closed_redirect[und][0][value]"]';
    public static $soldOutOptions = '//input[@name="fr_tickets_soldout_options[und]"]';
    public static $soldOutMessage = '//textarea[@name="fr_tickets_soldout_message[und][0][value]"]';
    public static $soldOutRedirect = '//input[@name="fr_tickets_sold_out_redirect[und][0][value]"]';
    public static $ticketOneQuant = "#product-1-ticket-quant";
    public static $ticketTwoQuant = "#product-2-ticket-quant";

    public static $addOnBox = '//input[@name="fr_tickets_donation_donation[und]"]';
    public static $addOnAuto = '//input[@name="fr_tickets_donation_addon_form[und][0][target_id]"]';
    public static $addOnSf = '//input[@name="fr_tickets_donation_salesforce_field"]';
    public static $addOnAmt = '//input[@name="submitted[tickets][ticket_box][fundraiser-tickets-extra-donation]"]';

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;
    protected $test;

    public function __construct(AcceptanceTester $I, ticketsCest $test)
    {
        $this->acceptanceTester = $I;
        $this->test = $test;
    }

    /**
     * @return NodeAddPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }

    function configureTickets() {
        $I = $this->acceptanceTester;
        $I->am('admin');
        $I->login();
        $I->configureEncrypt();
        $I->enableModule('Fundraiser Tickets');
        $this->test->_createContentType($I);
        $this->test->_createWaitListForm($I);
        $this->test->_createTicketNode($I);
        $this->test->_createTickets($I);
    }
}
