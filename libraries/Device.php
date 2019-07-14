<?php

declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

class Device {
    /* db column variables */

    //private $Id;
    //private $Name;
    //private $Barcode;
    //private $Type;
    //private $Notes;
    //private $TechnicalAvailability; //ex. is the device functional?
    //private $LoanAvailability; //Is the device loaned?

    /* none db variables */
    private $instance;
    private $_db; //for connecting to specific db.
    private $deviceRow_Id;
    private $devices_Type;
    private $deviceCount_Type;
    private $devices_Available;

    /* Constructors are called dynamically based on argument type or number of arguments passed */

    public function __construct() {
        $this->instance = & get_instance();
        $this->_db = $this->instance->load->database('', TRUE);

        $args = func_get_args(); //get arguments passed to function
        $numArgs = func_num_args(); //get number of argumetns passed to function

        if ($numArgs == 1) {
            if (is_int($args[0])) {
                if (method_exists($this, $func = '__constructInt')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            } else if (is_string($args[0])) {
                if (method_exists($this, $func = '__constructStr')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            } else if (is_bool($args[0])) {
                if (method_exists($this, $func = '__constructBool')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            }
        } else if ($numArgs > 1) {
            if (method_exists($this, $func = '__construct' . $numArgs)) {
                call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
            }
        }
    }

    public function __constructInt(int $id) {
        $this->deviceRow_Id = $this->GetDeviceById($id);
    }

    /*
     * Argument: String with the device type, such as Laptop or iPad
     * Purpose: Sets the devices_type instance variable with data for the specific device type.
     * Also sets the deviceCount_type instance variable with the amount of devices found. Errors are set into these variables if any.
     * Notes: Although the device table has a type column, a possible improvement would be best to have a table specific to device types and many to many relationship.
     * Right now this project uses static types
     */

    public function __constructStr(string $type) { //type of device, laptop, ipad... a reason for the try/catch is to catch the issue of passing a device Id as a string without (int) casting, triggering this constructor instead of constructInt
        try {
            if ($type == "iPad" || $type == "Laptop") {
                $this->devices_Type = $this->GetDevicesByType($type);
                $this->deviceCount_Type = sizeof($this->devices_Type);
            } else {
                throw new CustomException($type);
            }
        } catch (CustomException $e) {
            $message = "No device of type '" . $type . "' this type found.";
            $exception['exception'] = $e->deviceTypeError($message);
            $this->devices_Type = $exception;
            $this->deviceCount_Type = $exception;
        }
    }

    /*
     * Argument: Boolean 0 = device is unavailable, 1 = device is available
     * Purpose: Sets the devices_Available instance variable with data
     */

    public function __constructBool(bool $isAvailable) {
        $this->devices_Available = $this->GetDevicesByAvailability($isAvailable);
    }

    /* GETTERS */

    /*
     * Argument: An integer with the device id
     * Purpose: Get all columns from the device table for the given device id
     * Return: Returns an array with device data or an array with key exception on error
     */

    private function GetDeviceById(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        try {
            if (!$query = $this->_db->get_where('inventory.device', array('Id' => $id))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->row_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get this device.", $error);
            return $exception;
        }
    }

    /*
     * Purpose: Get data for devices that are currently loaned out
     * Return: Returns an array with device data or an array with key exception on error
     */

    private function GetLoanedDevices() {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        $query = $this->_db->select('Id, Name, Barcode')
                ->from('inventory.device')
                ->where('LoanAvailability = 0')
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->result_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get loaned devices.", $error);
            return $exception;
        }
    }

    /*
     * Argument: String with the device type, such as Laptop or iPad
     * Purpose: Get all columns for the this type of device
     * Return: Returns an array with device data or an array with key exception on error
     * Notes: One location this is used in is in the manage devices view for showing devices in different pages
     */

    private function GetDevicesByType(string $type) {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        try {
            if (!$query = $this->_db->get_where('inventory.device', array('Type' => $type))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->result_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the devices.", $error);
            return $exception;
        }
    }

    /*
     * Argument: An integer with the device id
     * Purpose: Get accessories linked to this device id
     * Return: Returns an array of acessory ids or an array with key exception on error
     * Notes: This function is not in use
     */

    private function GetDeviceHasAccessoryRow(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        try {
            if (!$query = $this->_db->get_where('inventory.Device_has_Accessories', array('Device_Id' => $id))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->result_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the accessories.", $error);
            return $exception;
        }
    }

    /*
     * Argument: A boolean representing availability. 0 or no, 1 for yes
     * Purpose: Get all columns from the device table for devices available for loaning and that are not loaned out, else not available devices
     * Return: Returns an array of device data or an array with key exception on error
     */

    private function GetDevicesByAvailability(bool $isAvailable) {
        if ($isAvailable) {
            return $this->GetAvailableDevices();
        } else {
            return $this->GetUnavailableDevices();
        }
    }

    /*
     * Purpose: Get all columns for devices that can be loaned and are not loaned currently
     * Return: Returns an array of device data or an array with key exception on error
     */

    private function GetAvailableDevices() {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        $query = $this->_db->select('*')
                ->from('inventory.device')
                ->where('TechnicalAvailability = 1 AND LoanAvailability = 1')
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->result_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the available devices.", $error);
            return $exception;
        }
    }

    /*
     * Purpose: Get all columns for device table for devices that are technical unavailable or already loaned.
     * Return: Returns an array of device data or an array with key exception on error
     */

    private function GetUnavailableDevices() {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        $query = $this->_db->select('*')
                ->from('inventory.device')
                ->where('TechnicalAvailability = 0 OR LoanAvailability = 0')
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->result_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the unavailable devices.", $error);
            return $exception;
        }
    }

    public function getDeviceRow() {
        return $this->deviceRow_Id;
    }

    public function getDevicesLoaned() {
        return $this->GetLoanedDevices();
    }

    public function getName() {
        if (isset($this->deviceRow_Id['Name'])) {
            return $this->deviceRow_Id['Name'];
        } else {
            return $this->deviceRow_Id; // will hold error or empty if not set
        }
    }

    public function getDevices_Type() {
        return $this->devices_Type;
    }

    public function getDeviceCount_Type() {
        return $this->deviceCount_Type;
    }

    public function devices_Available() {
        return $this->devices_Available;
    }

    /* UPDATE */

    /*
     * Argument: An array of device data, requires device Id
     * Purpose: Update the deivce barcode.
     * Return: Returns 1 on success or an array with key exception on failure
     * If you have a specific library of devices you can check barcodes against, do a check here.
     */

    public function UpdateDevice($updateDeviceAry) {
        $trans_status;

        if( isset($updateDeviceAry['Barcode']) ) {
            $isValidBarcode = true; 

            if($isValidBarcode) {
                $trans_status = $this->Update($updateDeviceAry);
            } else {
                $trans_status['exception'] = '<div class="exception textCenter">Sorry, '.$updateDeviceAry['Barcode'].' is not a valid device barcode.</div>';
            }
        } else {
           $trans_status = $this->Update($updateDeviceAry);
        }

        return $trans_status;
    }

    public function Update($updateDeviceAry) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = FALSE;

        $trans_status;

         try {
            if (!$this->_db->update('inventory.device', $updateDeviceAry, array('Id' => $updateDeviceAry['Id']))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $trans_status = 1;
            }
            } catch (CustomException $e) {
                $error = $this->_db->error();
                $exception['exception'] = $e->getCustomError("Sorry, could not update this device.", $error);
                $trans_status = $exception;
            }

            return $trans_status;
    }

    /* INSERT */

    /*
     * Argument: Array with data to insert a new device
     * Purpose: Insert a new device to the database. Note that all columns in the device table are required except for notes
     * Return: Returns 1 on success or an array with key exception on failure
     * If you have a specific library of devices you can check barcodes against, do a check here.
     */

    public function InsertDevice($insertDeviceAry) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = FALSE;

        $trans_status;

        $isValidBarcode = true;

        if($isValidBarcode) {
            try {
            if (!$this->_db->insert('inventory.device', $insertDeviceAry)) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $trans_status = 1;
            }
            } catch (CustomException $e) {
                $error = $this->_db->error();
                $exception['exception'] = $e->getCustomError("Sorry, could not insert this device. Please, check the barcode. Also make sure this device has not been added before.", $error);
                $trans_status = $exception;
            }
        } else {
            $trans_status['exception'] = '<div class="exception textCenter">Sorry, '.$insertDeviceAry['Barcode'].' is not a valid device barcode.</div>';
        }



        return $trans_status;
    }

    /* DELETE */

    /*
     * Argument: An intger device id
     * Purpose: Delete a device from the database
     * Return: Returns 1 on success or an array with key exception on failure
     * Notes: Deleting a device, will stop the manage devices from getting the device name,
     * must be revised. Possible solution is for all loans for this device to also be removed.
     * This function is currently not in use. There is no interface for deleting a device. The only options are to change its' barcode or set to unavailable
     */
    /*
      public function DeleteDevice(int $id) {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $trans_status;

      $this->_db->trans_start();

      $this->_db->delete('inventory.Device_has_Accessories', array('Device_Id' => $id));
      $this->_db->delete('inventory.device', array('Id' => $id));

      $this->_db->trans_complete();

      try {
      if( $this->_db->trans_status() === FALSE ) ) {
      $this->_db->db_debug = $db_debug;
      throw new CustomException();
      } else {
      $this->_db->db_debug = $db_debug;
      $trans_status = 1;
      }
      } catch(CustomException $e) {
      $error = $this->_db->error();
      $exception['exception'] = $e->getCustomError("Sorry, could not delete this device.", $error);
      $trans_status = $exception;
      }

      return $trans_status;
      }
     */

    /* OTHERS */

    /*
     * Argument: A String representing the device name
     * Purpose: Filter device data obtained from GetLoanedDevices by device name
     * Return: Returns an array of data for the specific device name, empty, or an array with key exception on error
     */

    public static function filterLoanedDevices(string $deviceName) {
        $loanedDevices = (new self)->GetLoanedDevices();
        $device['empty'] = 0;
        $flag = 0;
        if (isset($loanedDevices['exception'])) {
            $device = $loanedDevices['exception'];
        } else {
            for ($i = 0; $i < sizeOf($loanedDevices); $i++) {
                if ($loanedDevices[$i]['Name'] == $deviceName) {
                    $device = $loanedDevices[$i];
                    $flag = 1;
                }
                 if( $flag ) {
                     break;
                 }
            }
            return  $device; // can be empty
        }
    }

    /*
     * Argument: An integer device id
     * Purpose: Check if the device with the given id is unavailable, using a list of unavailable devices
     * Return: 1 = available, 0 = unavailable. It can return an array with key exception on error
     */

    public static function isAvailable(int $deviceId) {
        $devices = (new self())->getDevicesLoaned();
        if (isset($devices['exception'])) {
            return $devices;
        } else {
            foreach ($devices as $device) {
                if (in_array($deviceId, $device))
                    return 0;
            }
            return 1;
        }
    }

}

// end of file
