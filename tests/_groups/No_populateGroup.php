<?php


use \Codeception\Event\TestEvent;
/**
* Group class is Codeception Extension which is allowed to handle to all internal events.
* This class itself can be used to listen events for test execution of one particular group.
* It may be especially useful to create fixtures data, prepare server, etc.
*
* INSTALLATION:
*
* To use this group extension, include it to "extensions" option of global Codeception config.
*/

class No_populateGroup extends \Codeception\Platform\Group
{
  public static $group = 'no_populate';

  public function _before(TestEvent $e)
  {
    $test = $e->getTest();
    // get the Db module
    $db = $this->getModule('Db');

    //we do actually wipe the db, then initialize a second time with new config
    $db->_initialize();
    $db->_before($test);

    $db->_reconfigure(array('populate' => FALSE));
    $db->_initialize();
    $db->_before($test);
  }

  public function _after(TestEvent $e)
  {
  }
}