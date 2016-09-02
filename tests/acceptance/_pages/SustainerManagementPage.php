<?php

class SustainerManagementPage
{
  // Donation amount update form elements.
  public static $donationAmountField = 'Donation Amount';
  public static $donationAmountUpdateButton = '//input[@value="Update donation amount"]';

  // Change charge date form elements.
  public static $chargeDateField = 'Select the day of the month for your recurring donation to charge';
  public static $chargeDateUpdateButton = 'Update donation charge date';

  public static $firstNameField = 'First Name';
  public static $lastNameField = 'Last Name';
  public static $addressField = 'Address';
  public static $address2Field = 'Address Line 2';
  public static $cityField = 'City';
  public static $countryField = 'Country';
  public static $stateField = 'State/Province';
  public static $zipField = 'ZIP/Postal Code';
  public static $creditCardNumberField = 'Credit card number';
  public static $expirationMonthField = '#edit-payment-fields-credit-expiration-date-card-expiration-month';
  public static $expirationYearField = '#edit-payment-fields-credit-expiration-date-card-expiration-year';
  public static $cvvField = 'CVV';
  public static $billingUpdateButton = '//input[@value="Save changes"]';

  // Cancellation form elements.
  public static $reasonField = 'Reason';
  public static $canelButton = 'Cancel all future payments';
}