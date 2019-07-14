<?php
declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

class Accessories {
    /*db column variables*/

    //private $Id;
    //private $Name;
    //private $Quantity;
    //private $ScanRequired; //0 or 1

    /*none db variables*/

    private $instance;
    private $_db; //for connecting to specific db.
    private $filteredDevices;
    private $accessoriesByScan;

    /* Constructors are called dynamically based on argument type or number of arguments passed */
    public function __construct() {
      $this->instance = & get_instance();
      $this->_db = $this->instance->load->database('', TRUE);

      $args = func_get_args(); //get arguments passed to function
      $numArgs = func_num_args(); //get number of argumetns passed to function

      if($numArgs == 1) {
        if( is_int($args[0]) ) {
          if(method_exists($this, $func='__constructInt')) {
            call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
          }
        }
        if( is_bool($args[0]) ) {
          if(method_exists($this, $func='__constructBool')) {
            call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
          }
        }
      } else if($numArgs > 1) {
        if(method_exists($this, $func='__construct'.$numArgs)) {
          call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
        }
      }
    }

    public function __constructBool(bool $scanBool) {
      $this->accessoriesByScan = $this->GetAccessoriesByScan($scanBool); //Require accessory scan before lending
    }

    public function __constructInt(int $deviceId) {
      $this->filteredDevices = $this->GetFilteredAccessories($deviceId);
    }

/* GETTERS */

    /*
    * Argument: An intenger device id
    * Purpose: Create an array of accessories linked and not linked to the given device id.
    * Return: Array of accessories data with key hasAccessories and hasNotAccessories;
    or an array with key allAccessories; or an array with key empty if no accessories were found or an exception occurred.
    * Notes: This is used in the manageDevices view to list checked and non-checked accessories.
    If this device does not have accessories linked, all accessories will be queried and return instead
    */
    public function GetFilteredAccessories(int $deviceId) {
        $accessories = $this->GetHasAccessories($deviceId); // can have key hasAccessories, or empty, or exception

        if( isset($accessories['exception']) ) {

          return ( array('empty' => $accessories['exception']) ); // Return error

        } else if ( isset($accessories['empty']) ) {

          $accessories['allAccessories'] = $this->GetAccessories(); //get all accessories if the device didn't have any linked accessories

          if( isset($accessories['allAccessories']['exception']) ) {

            return ( array('empty' => $accessories['allAccessories']['exception']) ); // Return error

          } else {
            if( ! empty($accessories['allAccessories']) ) {
              return $accessories; // Return all accessories from accessories table
            } else {
              return ( array('empty' => "There are no accessories in the database") );
            }
          }

        } else {

          //Create array of ids for where_not_in
          $hasAccssrIds = array();
          foreach($accessories['hasAccessories'] as $ids) {
            $hasAccssrIds[] = $ids['Id'];
          }

          $accessories['hasNotAccessories'] = $this->GetHasNotAccessories($hasAccssrIds);

          if( isset($accessories['hasNotAccessories']['exception']) ) {
            return array('empty' => $accessories['hasNotAccessories']['exception']); //Return error
          } else {
            return $accessories; //Contains hasAccessories key and hasNotAccessories key
          }

        } //end else
    }


    /*
    * Argument: Boolean 0 or 1
    * Purpose: Get accessories based on whether they need to be scanned (1) or not (0).
    * Return: Array of accessories data found
    */
    private function GetAccessoriesByScan(bool $scanBool) {
      $db_debug = $this->_db->db_debug;
      $this->_db->debug = FALSE;
      
      $query = $this->_db->select('Id, Name, Quantity, ScanRequired')
                                         ->from('inventory.accessories')
                                         ->where('scanRequired ='. $scanBool)
                                         ->order_by('Name', 'ASC')
                                         ->get();
      try {
        if( ! $query ) {
          $this->_db->db_debug = $db_debug;
          throw new CustomException();
        } else {
          $this->_db->db_debug = $db_debug;
          return $query->result_array();
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories.", $error);
        return $exception;
      }
    }

    /*
    * Purpose: Get all accessories from the accessories table
    * Return: Array of accessories data or array with key exception on failure
    */
    private function GetAccessories() {
      $db_debug = $this->_db->db_debug;
      $this->_db->debug = FALSE;
      $query = $this->_db->select('Id, Name, Quantity, ScanRequired')
                                         ->from('inventory.accessories')
                                         ->order_by('Name', 'ASC')
                                         ->get();
      try {
        if( ! $query ) {
          $this->_db->db_debug = $db_debug;
          throw new CustomException();
        } else {
          $this->_db->db_debug = $db_debug;
          return $query->result_array();
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories.", $error);
        return $exception;
      }

    }

    /*
    * Argument: An array of accessory ids
    * Purpose: Validate the accessory ids and get all data for each id from the database
    * Return: Array of accessories data or array with key exception on failure
    */
    private function GetAccessoriesById($aryIds) {
      $flag = 1;
      $exception = array();
      //Check argument is numeric
      foreach($aryIds as $id) {
        try {
          if( ! is_numeric($id) ) {
            throw new CustomException();
          }
        } catch(CustomException $e) {
            $exception['exception'] = $e->getCustomError("Sorry, could not get accessories. Wrong argument type.");
            $flag = 0;
            break;
        }
      }

      if( ! $flag ) {
        return $exception;
      } else {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        $query = $this->_db->select('Id, Name, Quantity, ScanRequired')
                                    ->from('inventory.accessories')
                                    ->where_in('Id', $aryIds)
                                    ->order_by('Name', 'ASC')
                                    ->get();
        try {
          if (! $query) {
              $this->_db->db_debug = $db_debug;
              throw new CustomException();
          } else {
              $this->_db->db_debug = $db_debug;
              return $query->result_array();
          }
        } catch(CustomException $e) {
          $error = $this->_db->error();
          $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories.", $error);
          return $exception;
        }

      }
    }

    /*
    * Argument: Takes an intenger device id
    * Purpose: Get the accessories linked to the given device id
    * Return: Array of accessories linked to a device with key hasAccessories, or array with key empty
    * Notes: Used in borrowForm view. A possible code improvement for this project could be to dettach this logic from the view. It would not be easy, however.
    */
    public function GetHasAccessories(int $deviceId) {
      $db_debug = $this->_db->db_debug;
      $this->_db->debug = FALSE;

      $query = $this->_db->select('Id, Name, Quantity, ScanRequired')
                                  ->from('inventory.device_has_accessories')
                                  ->join('inventory.accessories', 'accessories_Id = Id', 'right')
                                  ->where('device_Id='.$deviceId)
                                  ->order_by('Name', 'ASC')
                                  ->get();
        try {
          if (! $query) {
              $this->_db->db_debug = $db_debug;
              throw new CustomException();
          } else {
              $this->_db->db_debug = $db_debug;

              $queryAccessories = $query->result_array();

              if(!empty($queryAccessories)) {
                return (array('hasAccessories' => $queryAccessories));
              } else if(empty($queryAccessories)) {
                return ( array('empty' => "There are no accessories for this device.") );
              }
          }
        } catch(CustomException $e) {
          $error = $this->_db->error();
          $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories. Please try again", $error);
          return $exception;
        }
    }

    /*
    * Argument: Takes an intenger device id
    * Purpose: Get the accessories not linked to the given device id
    * Return: Array of accessories linked to a device with key hasAccessories, or array with key empty
    * Notes: Used in borrowForm view. A possible code improvement for this project could be to dettach this logic from the view. It would not be easy, however.
    */
    public function GetHasNotAccessories($accssrIds) {
      $db_debug = $this->_db->db_debug;
      $this->_db->debug = FALSE;

      $query = $this->_db->select('Id, Name, Quantity, ScanRequired')
                                  ->from('inventory.accessories')
                                  ->where_not_in('Id', $accssrIds) //pass array of accessories ids
                                  ->order_by('Name', 'ASC')
                                  ->get();
        try {
          if ( ! $query ) {
              $this->_db->db_debug = $db_debug;
              throw new CustomException();
          } else {
              $this->_db->db_debug = $db_debug;
              return $query->result_array();
          }
        } catch(CustomException $e) {
          $error = $this->_db->error();
          $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories. Please try again", $error);
          return $exception;
        }
    }

    /*
    * Argument: An array of accessory ids
    * Return: Array of accessories, or an array with key exception on failure
    */
    public static function getSelectedAccessories($aryIds) {
      $accessories = (new self())->GetAccessoriesById($aryIds);

      return $accessories;
    }

    public static function getAllAccessories() {
      return ( (new self)->GetAccessories() );
    }

    public function getAccessoriesFiltered() {
      return $this->filteredDevices;
    }

    public function accessoriesByScan() {
      return $this->accessoriesByScan;
    }

/* UPDATE */

  /*
  * Argument: An array of accessory data to be updated. Keys can be Name, Quantity and ScanRequired. Also requires Id key
  * Purpose: Update the accessory with the given Id
  * Return: 1 on success or array with key exception on failure
  * Notes: This function is for possible future usage. At the moment it is not used as there is no interface for updating accessories yet.
  */
  public function UpdateAccessory($updateAccessoryAry) {
    $db_debug = $this->_db->db_debug;
    $this->_db->db_debug = FALSE;

    $trans_status;

    try {
      if( ! $this->_db->update('inventory.accessories', array('Id' => $updateAccessoryAry['Id'])) ) {
        $this->_db->db_debug = $db_debug;
        throw new CustomException();
      } else {
        $this->_db->db_debug = $db_debug;
        $trans_status = 1;
      }
    } catch(CustomException $e) {
      $error = $this->_db->error();
      $exception['exception'] = $e->getCustomError("Sorry, could not link the accessories to this device.", $error);
      $trans_status = $exception;
    }

    return $trans_status;
  }

/* Insert */

    /*
    * Argument: An array with accessory Name, Quantity and ScanRequired
    * Purpose: Insert a new accessory to database
    * Return: 1 on success or array with key exception on failure
    */
    public function InsertAccessory($accessoryAry) {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $trans_status;

      try {
        if( ! $this->_db->insert('inventory.accessories', $accessoryAry)) {
          $this->_db->db_debug = $db_debug;
          throw new CustomException();
        } else {
          $this->_db->db_debug = $db_debug;
          $trans_status = 1;
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not insert this accessory(s).", $error);
        $trans_status = $exception;
      }

      return $trans_status;
    }

    /*
    * Argument: An array of device ids and accessory ids with keys device_id and Accessories_Id
    * Purpose: Insert accessory and device ids to has accessories table to link them together
    * Return: 1 on success or array with key exception on failure
    */
    public function InsertHasAccessories($deviceAccssrAry) {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $trans_status;

      try {
        if( ! $this->_db->insert_batch('inventory.device_has_accessories', $deviceAccssrAry) ) {
          $this->_db->db_debug = $db_debug;
          throw new CustomException();
        } else {
          $this->_db->db_debug = $db_debug;
          $trans_status = 1;
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not link the accessories to this device.", $error);
        $trans_status = $exception;
      }

      return $trans_status;
    }

/* Delete */

    /*
    * Argument: An array of accessory ids
    * Purpose: Checks array is of type int and prepares data for deletion. Calls DeleteLinkedAccessories function to delete the accessories
    * Return: 1 on success or array with key exception on failure
    */
    public static function DeleteSelectedAccessories($accessoriesId) {
      $i = 0;
      $typeException;
      //Check ids are numeric before delete
      foreach($accessoriesId as $accessoryId ) {
        try {
          if( ! is_numeric($accessoryId[$i]) ) {
            throw new CustomException();
          }
        } catch (CustomException $e) {
          $typeException['exception'] = $e->getCustomError("Wrong arguments passed.");
          break;
        }
        $i++;
      }

      if( ! isset($typeException['exception'])) {
        $accessoriesId = implode(",", $accessoriesId['Accessories_Id']); //convert to string, comma separated ids

        $trans_status = (new self())->DeleteLinkedAccessories($accessoriesId); //call store procedure
      } else {
        $trans_status = $typeException;
      }

      return $trans_status;
    }

    /*
    * Argument: String of comma separated Ids
    * Purpose: delete accessories from accessories and hasAccessories table. Uses store procedure. Called from DeleteSelectedAccessories function.
    * Return: 1 on success or array with key exception on failure
    * Possible future improvement: if a device is loaned with accessories, but these accessories are removed, the loan will not show those accessories anymore.
    * These exceptions must be handled
    */
    private function DeleteLinkedAccessories(string $accessoriesId) {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $query = "call inventory.delete_accessory(?)";

      try {
        if( ! $this->_db->query($query, $accessoriesId) ) {
          throw new CustomException();
        } else {
          $trans_status = 1;
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not remove the selected accessory(s).", $error);
        $trans_status = $exception;
      } finally {
        $this->_db->db_debug = $db_debug;
      }

      return $trans_status;
    }

    /*
    * Argument: Intenger device Id
    * Purpose: remove the link between this device and its accessories
    * Return: 1 on success or array with key exception on failure
    */
    public function DeleteHasAccessoryByDevice(int $deviceId) {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $trans_status;

      try {
        if( ! $this->_db->delete('inventory.device_has_accessories', array('device_Id' => $deviceId)) ) {
          $this->_db->db_debug = $db_debug;
          throw new CustomException();
        } else {
          $this->_db->db_debug = $db_debug;
          $trans_status = 1;
        }
      } catch(CustomException $e) {
        $error = $this->_db->error();
        $exception['exception'] = $e->getCustomError("Sorry, could not unlike the accessories linked to this device.", $error);
        $trans_status = $exception;
      }

      return $trans_status;
    }

/*OTHERS*/

  /*
  * Argument: an array of device ids and accessory ids with keys device_id and Accessories_Id
  * Purpose: Uses DeleteHasAccessoryByDevice to unlink the accessories to the device and InsertHasAccessories to create new links
  * Return: 1 on success or array with key exception on failure
  * Notes: Ideally this function should be in a store procedure.
  */
  public static function linkItems($deviceAccssrAry) {
    $deviceId = (int)$deviceAccssrAry[0]['device_Id'];

    $trans_status = (new self())->DeleteHasAccessoryByDevice($deviceId);
    if( isset($trans_status['exception']) ) {
      return $trans_status;
    } else if($trans_status) {
      $trans_status = (new self())->InsertHasAccessories($deviceAccssrAry);
      return $trans_status;
    }
  }

  /*
  * Argument: an associative array with Accessories_Id key and accessories id array within.
  * Purpose: Checks the array of ids are valid. Function used in insert and update in Device_loan class
  * Return: a json string of accessories ids or array with key exception on failure
  */
  public static function validateAccessories($accessoryAry) {
    $accessories = array();
    try {
      if (array_key_exists('Accessories_Id', $accessoryAry)) { //accessories may not have been selected, must check
        for($i = 0; $i < sizeOf($accessoryAry['Accessories_Id']); $i++) {
          if(is_numeric($accessoryAry['Accessories_Id'][$i])) {
            $accessories[] = $accessoryAry['Accessories_Id'][$i];
          } else {
            throw new CustomException();
            break;
          }
        }
      }
    } catch(CustomException $e) {
      $exception['exception'] = $e->getCustomError("Sorry, could not validate the accessories.");
      return $exception;
    }

     return ( json_encode($accessories) ); //unreachabe code if exception thrown
  }

} //end of file
