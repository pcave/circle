<?php

class ProductsUIPage
{
    // include url of current page
    public static $addURL = '/springboard/commerce/products/add/';
    //public static $manageURL = '/admin/structure/types/manage/';
    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function addRoute($type)
     {
         return static::$addURL.$type;
     }

//    public static function manageRoute($param)
//    {
//        return static::$manageURL.$param;
//    }


    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    
    // generic variables
    public static $sku = '#edit-sku';
    public static $title = '#edit-title';
    public static $price = '//input[@name="commerce_price[und][0][amount]"]';
    public static $status ='//input[@name="status"]';

    // Fundraiser Tickets.
    public static $description ='//input[@name="fr_tickets_description[und][0][value]"]';
    public static $threshold = '//input[@name="fr_tickets_threshold[und][0][value]"]';
    public static $message = '//input[@name="fr_tickets_sold_out_message[und][0][value]"]';
    public static $quantity = '//input[@name="fr_tickets_quantity[und][0][value]"]';



    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    /**
     * @return NodeAddPage
     */
    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }
}
