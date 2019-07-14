<?php

/*
 * Notes: This project was made with the intention of using an external API or separate detabase to get user data and employee data.
 * The borrower table only has the instance variables below as columns. There is no employee table. There is no interface for inserting users.
 */

declare (strict_types = 1);

defined('BASEPATH') or exit('No direct script access allowed');

class Device_borrower
{
    /* db columns variables */

    //private $Id;
    //private $Email;
    //private $Phone;
    //private $Barcode;
    //private $FirstName;
    //private $LastName;

    /* none db variables */

    private $borrower;
    private $instance;
    private $_db; //for connecting to specific db.

    /* Constructors are called dynamically based on argument type or number of arguments passed */

    public function __construct()
    {
        $this->instance = &get_instance();
        $this->_db = $this->instance->load->database('', TRUE);

        $args = func_get_args(); //get arguments passed to function
        $numArgs = func_num_args(); //get number of argumetns passed to function

        if ($numArgs == 1) {
            if (is_int($args[0])) {
                if (method_exists($this, $func = '__constructInt')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            }
        } else if ($numArgs > 1) {
            if (method_exists($this, $func = '__construct' . $numArgs)) {
                call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
            }
        }
    }

    /** GETTERS **/

    public function __constructInt(int $barcode)
    {
        $this->borrower = $this->GetBorrowerByBarcode($barcode);
    }

    /*
     * Argument: Integer borrower barcode
     * Purpose: Get all data for a borrower with the given barcode.
     * Return: Array of borrower data or array with key Exception
     */

    private function GetBorrowerByBarcode(int $barcode)
    {
        $db_debug = $this->_db->db_debug;
        $this->_db->debug = FALSE;

        try {
            if (!$query = $this->_db->get_where('inventory.device_borrower', array('Barcode' => $barcode))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $borrowerData = $query->row_array();

                $data = array(
                    'firstName' => $borrowerData['FirstName'],
                    'lastName' => $borrowerData['LastName'],
                    'id' => $borrowerData['Id'],
                    'email' => $borrowerData['Email'],
                    'phone' => $borrowerData['Phone'],
                    'borrowerBarcode' => $borrowerData['Barcode']
                );

                return $data;
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the borrower.", $error);
            return $exception;
        }
    }

    public function GetDeviceBorrowers()
    {
        $query = $this->_db->get('inventory.device_borrower');
        return $query->result_array();
    }

    public function getBorrower()
    {
        return $this->borrower;
    }

    /**UPDATE**/

    /*
     * Argument: Array of borrower data. Requires user barcode
     * Purpose: Updates the borrower table with the given array
     * Return: 1 or array with key exception on failure
     */

    public function UpdateDeviceBorrower($updateBorrowerAry)
    {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = FALSE;

        $trans_status;

        try {
            if (!$this->_db->update('inventory.device_borrower', $updateBorrowerAry, 'Barcode =' . $updateBorrowerAry['Barcode'])) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $trans_status = 1;
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not update the borrower.", $error);
            $trans_status = $exception;
        }

        return $trans_status;
    }

    /**DELETE**/

    /*
     * Argument: Integer borrower barcode
     * Purpose: Delete the borrower of the given barcode
     * Return: 1 or array with key exception on failure
     * Notes: This function is not used as there is no interface to delete the borrower
     */
    /*
      public function DeleteDeviceBorrowerRow(int $barcode)
      {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = FALSE;

      $trans_status;

      try {
      if( ! $this->_db->delete('inventory.device_borrower', array('Barcode' => $barcode)) ) {
      $this->_db->db_debug = $db_debug;
      throw new CustomException();
      } else {
      $this->_db->db_debug = $db_debug;
      $trans_status = 1;
      }
      } catch(CustomException $e) {
      $error = $this->_db->error();
      $exception['exception'] = $e->getCustomError("Sorry, could not update the borrower.", $error);
      $trans_status = $exception;
      }

      return $trans_status;
      }
     */

    /**Insert**/

    /*
     * Argument: Array of borrower data
     * Purpose: Insert a new user to the database. All columns for the borrower are required
     * Return: 1 or array with key exception on failure
     * Notes: This function is used only in validateUser. There is not a full user inteface for inserting new users. 
     * The loan form allows for inserting email and password. You can modify it so that name and barcode can be inserted as well. 
     */

    public function InsertDeviceBorrower($insertBorrowerAry)
    {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = FALSE;

        $trans_status;

        try {
            if (!$this->_db->insert('inventory.device_borrower', $insertBorrowerAry)) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $trans_status = 1;
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not insert the borrower.", $error);
            $trans_status = $exception;
        }

        return $trans_status;
    }

    /**Others**/

    /*
     * Argument: Integer borrower barcode
     * Purpose: Validate the borrower before loaning. Use your own user validation library as necessary. Default: no library is used.
     * Return: 1 on success or array with key exception on error
     */

    public static function validateUser(int $barcode)
    {
        $validationStatus['status'] = 1;

        /* Your validation here, which sets $validationStatus */
        $validation = true;
        if ($validation) {
            $self = new self($barcode);

            if (isset($self->borrower['exception'])) {
                $validationStatus = $self->borrower;
            } else if (empty($self->borrower['borrowerBarcode'])) { //no borrower in db
                $insertBorrower = array(
                    'Barcode' => $barcode,
                    'Email' => '',
                    'Phone' => '',
                    'FirstName' => '',
                    'LastName' => ''
                );

                $validationStatus = $self->InsertDeviceBorrower($insertBorrower);

                if (isset($validationStatus['exception'])) {
                    $validationStatus['exception'];
                }
            }
        }

        return $validationStatus;
    }
}

//end of file
