<?php
declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

class Device_loan {
    /* db column variables */

    //private $Id;
    //private $DueDate
    //private $Date_Out
    //private $Date_In;
    //private $ConditionOut_Front;
    //private $ConditionOut_Back;
    //private $ConditionOut_On
    //private $ConditionIn_Front;
    //private $ConditionIn_Back;
    //private $ConditionIn_On
    //private $AccesoriesIn;
    //private $AccessoriesOut;
    //private $EmployeeBarcode_Out;
    //private $EmployeeBarcode_In;
    //private $Notes_Out;
    //private $Notes_In;
    //private $Signature; //uri base64 encoded image. To view it, place it in the <img> src attribute. Note: Not very efficient for mobile devices
    //private $device_Borrower_Id; //Foreign Key
    //private $Device_Id; //Foreign Key

    /* none db variables */

    private $instance;
    private $_db; //for connecting to specific db.
    private $deviceLoan;
    private $loanRange;

    /* Constructors are called dynamically based on argument type or number of arguments passed */

    public function __construct() {
        $this->instance = & get_instance();
        $this->instance->load->library('upload');

        $this->_db = $this->instance->load->database('', TRUE);

        $args = func_get_args(); //get arguments passed to function
        $numArgs = func_num_args(); //get number of argumetns passed to function

        if ($numArgs == 1) {
            if (is_int($args[0])) {
                if (method_exists($this, $func = '__constructInt')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            }
            if (is_array($args[0])) {
                if (method_exists($this, $func = '__constructArray')) {
                    call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
                }
            }
        } else if ($numArgs > 1) {
            if (method_exists($this, $func = '__construct' . $numArgs)) {
                call_user_func_array(array($this, $func), $args);  //Call $func in this class with $args arguments
            }
        }
    }

    public function __constructInt(int $deviceId) {
        $this->deviceLoan = $this->GetLoansByDeviceId($deviceId);
    }

    /*
     * Argument: Array with key Id (loan id) and key transactionType or aray with key Device_Id
     * Purpose: Sets deviceLaon instance variable with one row of db data, loan or return using loan id,
     * or gets the row of data by device id. It can contain an array with key exception if error occurred.
     */

    public function __constructArray($idAry) {
        if (isset($idAry['Id']) && isset($idAry['transactionType'])) {
            $id = (int) $idAry['Id'];
            if ($idAry['transactionType'] == "loan") {
                $this->deviceLoan = $this->GetLoanRowById_Loan($id);
            } else if ($idAry['transactionType'] == "return") {
                $this->deviceLoan = $this->GetLoanRowById_Return($id);
            }
        } else if (isset($idAry['Device_Id'])) {
            $idAry = $idAry['Device_Id'];
            $this->deviceLoan = $this->GetLoanedDeviceData($idAry);
        }
    }

    /*
     * Argument: Takes 3 arguments, two dates (Strings) and a device id (Int)
     * Purpose: Sets the loanRange instance variable with loans for the given device id in the range of dates given.
     * Can contain array with key exception if an error occurred.
     */

    public function __construct3(string $dateStart, string $dateEnd, int $deviceId) {
        $this->loanRange = $this->GetDeviceLoanByRange($dateStart, $dateEnd, $deviceId);
    }

    /* GET */

    /*
     * Argument: Array with key Id (loan id) and key transactionType or aray with key Device_Id
     * Purpose: Gets all loans for the given device id. Used for device history in manage page
     * Return: Array of device loan and borrower data or array with key exception on error
     */

    private function GetLoansByDeviceId(int $deviceId) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        $query = $this->_db->select('dl.Id, EmployeeBarcode_In, EmployeeBarcode_Out, Date_Out, Date_In, Device_Id, device_borrower_Id, db.Id AS borrowerId, FirstName, LastName, Email, Phone')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->where('Device_Id =' . $deviceId)
                ->order_by('Date_Out', 'DESC')
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
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loaned devices.", $error);
            return $exception;
        }
    }

    /*
     * Argument: Integer device id
     * Purpose: Gets device with the given id that is currently loaned out
     * Return: Array of device loan, device, and borrower data or array with key exception on error
     */

    private function GetLoanedDeviceData(int $deviceId) {
        //select the currently loaned device to the $borrowerId
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        $query = $this->_db->select('dl.Id AS LoanId, dl.Device_Id AS DeviceId, dl.device_borrower_Id AS BorrowerId, DueDate,
      Date_Out, Accessories_Out, Notes_Out, d.Name, d.Barcode AS DeviceBarcode, db.Barcode AS BorrowerBarcode, db.FirstName, db.LastName, db.Email, db.Phone')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device AS d', 'd.Id = dl.Device_Id', 'left')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->where('Date_In IS NULL AND LoanAvailability = 0 AND dl.Device_Id = ' . $deviceId)
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
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loaned device.", $error);
            return $exception;
        }
    }

    /*
     * Argument: Integer loan id
     * Purpose: Gets all columns related to the loan out, not the return
     * Return: An array of device loan, device, and borrower data, or an array with key exception on error
     */

    private function GetLoanRowById_Loan(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        $query = $this->_db->select('d.Name, d.Barcode, EmployeeBarcode_Out, DueDate, Date_Out, ConditionOut_Front,
        ConditionOut_Back, ConditionOut_On, Accessories_Out, Notes_Out, Signature, db.Barcode AS BorrowerBarcode, FirstName, LastName, Email, Phone')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->join('inventory.device AS d', 'dl.Device_Id = d.Id')
                ->where('dl.Id =' . $id)
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->row_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loan.", $error);
            return $exception;
        }
    }

    /*
     * Argument: Integer loan id
     * Purpose: Gets all columns related to the returned loans
     * Return: An array of device loan, device, and borrower data, or an array with key exception on error
     */

    private function GetLoanRowById_Return(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;
        $query = $this->_db->select('d.Name, d.Barcode, EmployeeBarcode_In, DueDate, Date_In, ConditionIn_Front,
        ConditionIn_Back, ConditionIn_On, Accessories_In, Notes_In, db.Barcode AS BorrowerBarcode, FirstName, LastName, Email, Phone')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->join('inventory.device AS d', 'dl.Device_Id = d.Id')
                ->where('dl.Id =' . $id)
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->row_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loan.", $error);
            return $exception;
        }
    }

    /*
     * Argument: Integer loan id
     * Purpose: Get all data for a given loan id
     * Return: An array of device loan, device, and borrower data, or an array with key exception on error
     */

    private function GetLoanById(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        $query = $this->_db->select('d.Name, d.Barcode, dl.*, FirstName, LastName, Email, Phone, db.Barcode as BorrowerBarcode')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->join('inventory.device AS d', 'dl.Device_Id = d.Id')
                ->where('dl.Id =' . $id)
                ->get();
        try {
            if (!$query) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $query->row_array();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loan.", $error);
            return $exception;
        }
    }

    /*
     * Argument: Integer loan id
     * Purpose: Helper function used to put together all data necessary to populate the viewLoanTransaction view.
     * Return: An array of device loan, device, and borrower data, or an array with key exception on error
     */

    public static function getLoanTransaction(int $id) {
        $temp = (new self())->GetLoanById($id);
        if (isset($temp['exception'])) {
            return $temp; //holds exception, if any
        } else {
            if (empty($temp['Notes_Out'])) {
                $temp['Notes_Out'] = "None";
            }

            $accessoriesOut = json_decode($temp['Accessories_Out']);
            if (!empty($accessoriesOut)) {

                $accessoriesOut = Accessories::getSelectedAccessories($accessoriesOut);
                if (isset($accessoriesOut['exception'])) {
                    $accessoriesOut = $accessoriesOut['exception'];
                }
            } else {
                $accessoriesOut = '';
            }

            $loan['loan'] = array
                (
                'Name' => $temp['Name'],
                'Barcode' => $temp['Barcode'],
                'EmployeeBarcode_Out' => $temp['EmployeeBarcode_Out'],
                'DueDate' => $temp['DueDate'],
                'Date_Out' => $temp['Date_Out'],
                'ConditionOut_Front' => $temp['ConditionOut_Front'],
                'ConditionOut_Back' => $temp['ConditionOut_Back'],
                'ConditionOut_On' => $temp['ConditionOut_On'],
                'Notes_Out' => $temp['Notes_Out'],
                'Accessories_Out' => $accessoriesOut,
                'Signature' => $temp['Signature'],
                'FirstName' => $temp['FirstName'],
                'LastName' => $temp['LastName'],
                'Email' => $temp['Email'],
                'Phone' => $temp['Phone'],
                'BorrowerBarcode' => $temp['BorrowerBarcode']
            );

            if (!empty($temp['Date_In'])) {

                if (empty($temp['Notes_Out'])) {
                    $temp['Notes_Out'] = "None";
                }

                $accessoriesIn = json_decode($temp['Accessories_In']);
                if (!empty($accessoriesIn)) {

                    $accessoriesIn = Accessories::getSelectedAccessories($accessoriesIn);
                    if (isset($accessoriesIn['exception'])) {
                        $accessoriesIn = $accessoriesIn['exception'];
                    }
                } else {
                    $accessoriesIn = '';
                }

                $loan['return'] = array
                    (
                    'EmployeeBarcode_In' => $temp['EmployeeBarcode_In'],
                    'Date_In' => $temp['Date_In'],
                    'ConditionIn_Front' => $temp['ConditionIn_Front'],
                    'ConditionIn_Back' => $temp['ConditionIn_Back'],
                    'ConditionIn_On' => $temp['ConditionIn_On'],
                    'Notes_In' => $temp['Notes_In'],
                    'Accessories_In' => $accessoriesIn
                );
            } else {
                $loan['return'] = ''; // device has not been returned
            }

            return $loan;
        } // end if else
    }

    /*
     * Argument: Takes 3 arguments, two dates (Strings) and a device id (Int)
     * Purpose: Validates the dates and queries the device loan table in order to populate the history table in the manage view
     * Return: An array of loan data for the given range and id, or an array with key exception on error.
     */

    private function GetDeviceLoanByRange(string $dateStart, string $dateEnd, int $deviceId) {
        try {
            $dateStart = new DateTime($dateStart);
            $dateEnd = new DateTime($dateEnd);
        } catch (Exception $e) {
            $exception['exception'] = '<div class="exception textCenter">' . $e->getMessage() . '</div>';
            return $exception;
        }

        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        $query = $this->_db->select('dl.Id, EmployeeBarcode_In, EmployeeBarcode_Out, Date_Out, Date_In, Device_Id, device_borrower_Id, db.Id AS borrowerId, FirstName, LastName, Email, Phone')
                ->from('inventory.device_loan AS dl')
                ->join('inventory.device_borrower AS db', 'dl.device_borrower_Id = db.Id', 'left')
                ->where('Device_Id =', $deviceId)
                ->where('date(Date_Out) >=', $dateStart->format('Y-m-d'))
                ->where('date(Date_Out) <=', $dateEnd->format('Y-m-d'))
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
            $exception['exception'] = $e->getCustomError("Sorry, could not get the loan history.", $error);
            return $exception;
        }
    }

    public function getDeviceLoan() {
        return $this->deviceLoan;
    }

    public function getLoanRange() {
        return $this->loanRange;
    }

    /* DELETE */

    /*
     * Argument: A intenger loan id
     * Purpose: Delete the loan with the given id
     * Return: 1 on success or an array with key exception on error
     * Notes: This function is currently not in use. There is also no interface for deleting loans.
     */

    public function DeleteDeviceLoanRow(int $id) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        try {
            if (!$this->_db->delete('inventory.device_loan', array('Id' => $id))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return 1;
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not delete this loan.", $error);
            return $exception;
        }
    }

    /* OTHERS */

    /*
     * Argument: An Integer borrower barcode
     * Purpose: Helper function used to populate the borrow form view with devices and user data
     * Return: An array with key borrower and devices
     */

    public static function loanFormData(int $borrowerBarcode) {
        $borrowerObj = new Device_borrower($borrowerBarcode);
        $data['borrower'] = $borrowerObj->getBorrower();
        $deviceObj = new Device(true); //return 'available' devices
        $data['devices'] = $deviceObj->devices_Available();

        return $data;
    }

    /*
     * Argument: Takes a String and an array for directory key words such as ConditionIn_Back
     * Purpose: Used to create the directories for saving images in the server.
     * Return: A string path for the directory for saving images for 'today'
     * Notes: The directory path for the images is saved in the db
     */

    private static function setImgDirectories(string $deviceName, $dir) {
        $deviceDir = str_replace(' ', '', $deviceName);
        $subDirName_Today = date("Y-m-d");
        $uploadPath = './uploads/devicesImg/' . $deviceDir . '/' . $dir['loanorreturn'] . '/' . $subDirName_Today . '/';

        //Create directory for saving images. A device may be returned the same day, which means the directory might already exist, don't create again if so.

        if (!is_dir($uploadPath)) { //Check to avoid mkdir(): File exist warning
            $dir1Bool = mkdir($uploadPath . $dir['back'] . '/', 0777, true); //where 0777 is for access permission, true is for recursive, for creating a directory tree
            $dir2Bool = mkdir($uploadPath . $dir['front'] . '/', 0777);
            $dir3Bool = mkdir($uploadPath . $dir['on'] . '/', 0777);

            try {
                if (!$dir1Bool && !$dir2Bool && !$dir3Bool) {
                    self::removeDir($uploadPath); //if error, remove all directories created
                    throw new CustomException();
                } else {
                    return $uploadPath;
                }
            } catch (CustomException $e) {
                $exception['exception'] = $e->getCustomError("Sorry, failed to save the images.");
                return $exception;
            }
        } else {
            return $uploadPath;
        }
    }

    /*
     * Argument: Takes a String for the upload path for iamges and an array for directory key words such as ConditionIn_Back
     * Purpose: Used to upload the images sent by the borrow and return loans
     * Return: Returns an array with exact path for the images in the server or an array with key exception on error
     * Notes: If there is an error, the image path created won't be removed. They are reused for the loans of that day.
     * There is no option for removing one folder at a time, only for removing all together with images in one go in the manage devices view for the selected device
     */

    private function uploadDeviceImages(string $uploadPath, $dir) {
        $filePaths = array();
        //Create config for file insertion
        $config['upload_path'] = $uploadPath . $dir['back'] . '/';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = 20000;
        $config['max_width'] = 4000;
        $config['max_height'] = 4000;
        $config['file_name'] = 'back_' . date("Y-m-d") . '_.png'; //Rename dynamically

        $this->instance->upload->initialize($config);

        try {

            //insert file
            if (!$this->instance->upload->do_upload($dir['back'])) {
                $error = $this->instance->upload->display_errors();
                throw new CustomException($error);
            } else {
                $filePaths['back'] = $this->instance->upload->data('full_path'); //full path of saved image
                //create new config for next image
                $config['upload_path'] = $uploadPath . $dir['front'] . '/';
                $config['file_name'] = 'front_' . date("Y-m-d") . '_.png'; //Rename dynamically

                $this->instance->upload->initialize($config);

                //insert file
                if (!$this->instance->upload->do_upload($dir['front'])) {
                    $error = $this->instance->upload->display_errors();
                    unlink($filePaths['back']); //remove back image
                    throw new CustomException($error);
                } else {
                    $filePaths['front'] = $this->instance->upload->data('full_path'); //full path of saved image
                    //create new config for next image
                    $config['upload_path'] = $uploadPath . $dir['on'] . '/';
                    $config['file_name'] = 'on_' . date("Y-m-d") . '_.png'; //Rename dynamically
                    $this->instance->upload->initialize($config);

                    //insert file
                    if (!$this->instance->upload->do_upload($dir['on'])) {
                        $error = $this->instance->upload->display_errors();
                        //remove back and front images
                        unlink($filePaths['back']);
                        unlink($filePaths['front']);
                        throw new CustomException($error);
                    } else {
                        $filePaths['on'] = $this->instance->upload->data('full_path'); //full path of saved image
                    }
                }//End second upload
            } //End first

            return $filePaths;
        } catch (CustomException $e) {
            $exception['exception'] = $e->getUploadError("Sorry, there was a problem saving the images.");
            return $exception;
        }
    }

    /*
     * Argument: A string of to the absolute path where the image was saved
     * Purpose: Creates the image relative path where the image was saved to insert or update images for the loan
     * Return: Path to be used as relative path, starting at upload folder
     */

    private static function sanitizeImgPath($imgPath) {
        $newPath = explode('uploads', $imgPath);
        return ( 'uploads' . $newPath[1] );
    }

    /*
     * Argument: A string of a directory path
     * Purpose: Remove all directories and associated files
     * Notes: Used to remove directories created if an error occurs during their creation.
     * Also used for deleting directories and images for a given device by the use of a button in the manage devices view
     */

    public static function removeDir($dir) {
        if (is_dir($dir)) { //base case. Returns false if it is not a directory or the directory doesn't exist
            foreach (glob("$dir*", GLOB_MARK) as $file) { //appends slash for new directory to be added after
                self::removeDir($file);
            }
            rmdir($dir); //runs second. Must not be viewing the folder, else won't remove it
        } else if (is_file($dir)) { //runs first. Returns false if the file is not a regular file or it doesn't exist
            unlink($dir);
        }
    }

    /* INSERT */

    /*
     * Argument: Array of loan data
     * Purpose: Insert loan upon submition of the borrow form
     * Return: The id of the newly inserted row of data
     * Notes: The id is used to populate the loan success view
     */

    private function Insert($insertDeviceAry) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = false;

        try {
            if (!$this->_db->insert('inventory.device_loan', $insertDeviceAry)) { //Requires foreign key device_Borrower_Id and Device_Id
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                return $this->_db->insert_id();
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, failed to insert this loan.", $error);
            return $exception;
        }
    }

    /* UPDATE */

    /*
     * Argument: Array of loan data to update, requires loan id
     * Purpose: Update the loan with the given id after submitting the return form
     * Return: 1 on success or array with key exception on failure
     */

    private function Update($updateAry) {
        $db_debug = $this->_db->db_debug;
        $this->_db->db_debug = FALSE;

        $trans_status;

        try {
            if (!$this->_db->update('inventory.device_loan', $updateAry, array('Id' => $updateAry['Id']))) {
                $this->_db->db_debug = $db_debug;
                throw new CustomException();
            } else {
                $this->_db->db_debug = $db_debug;
                $trans_status = 1;
            }
        } catch (CustomException $e) {
            $error = $this->_db->error();
            $exception['exception'] = $e->getCustomError("Sorry, could not update this loan.", $error);
            $trans_status = $exception;
        }

        return $trans_status;
    }

    //Tests if the insert query succeeds or fails. Example using transaction with codeigniter
    /*  public function testInsertDeviceLoan($insertDeviceAry)
      {
      $db_debug = $this->_db->db_debug;
      $this->_db->db_debug = false;

      $this->_db->trans_start(true); //Query will be rolled back
      $this->_db->insert('inventory.device_loan', $insertDeviceAry);
      $this->_db->trans_complete();

      $trans_status;

      if ($this->_db->trans_status() === false) {
      $trans_status = 0;
      } else {
      $trans_status = 1;
      }

      $this->_db->db_debug = $db_debug;

      return $trans_status;
      }
     */

    /*
     * Argument: Takes an array of loan borrow data
     * Purpose: Insert loan to db. Delegates accessories validation, paths creation, and image upload to other functions
     * Return: Id of loan inserted or an array with key exception on error
     * Notes: DueDate is set here. There is no interface for setting due dates
     */
    public function InsertDeviceLoan($loanPost) {
        $canLoan = Device::isAvailable((int) $loanPost['DeviceId']);
        if (isset($canLoan['exception'])) {
            $data = $canLoan['exception'];
        } else if ($canLoan) {

            $deviceObj = new Device((int) $loanPost['DeviceId']);
            $deviceRow = $deviceObj->getDeviceRow();

            if (isset($deviceRow['exception'])) {
                $data = $deviceRow;
            } else {
                //validate accessories, returns json string
                $accessories = Accessories::validateAccessories($loanPost);

                if (isset($accessories['exception'])) {
                    $data = $accessories;
                } else {

                    $dir = array(
                        'loanorreturn' => "loan",
                        'front' => "ConditionOut_Front",
                        'back' => "ConditionOut_Back",
                        'on' => "ConditionOut_On"
                    );
                    $uploadPath = self::setImgDirectories($deviceRow['Name'], $dir); // create directories, return upload path

                    if (isset($uploadPath['exception'])) {
                        $data['exception'] = $uploadPath['exception'];
                    } else {

                        $filePaths = $this->uploadDeviceImages($uploadPath, $dir); //inserts photos and returns the path of those inserted files
                        $filePaths;
                        if (isset($filePaths['exception'])) {
                            $data['exception'] = $filePaths['exception'];
                        } else {

                            $conditionFront = self::sanitizeImgPath($filePaths['front']);
                            $conditionBack = self::sanitizeImgPath($filePaths['back']);
                            $conditionOn = self::sanitizeImgPath($filePaths['on']);

                            $todayDate = new DateTime();

                            $dueDate = new DateTime();
                            $dueDate->add(new DateInterval('P3D')); //adds 3 days from today

                            $insertBorrowFormAry = array(
                                'Date_Out' => $todayDate->format('Y-m-d h:i:s'),
                                'DueDate' => $dueDate->format('Y-m-d h:i:s'),
                                'Signature' => $loanPost['Signature'],
                                'EmployeeBarcode_Out' => $loanPost['EmployeeBarcode_Out'],
                                'Accessories_Out' => $accessories,
                                'ConditionOut_Front' => $conditionFront, //$filePaths['front'],
                                'ConditionOut_Back' => $conditionBack, //$filePaths['back'],
                                'ConditionOut_On' => $conditionOn, //$filePaths['on'],
                                'Notes_Out' => $loanPost['NotesOut'],
                                'Device_Id' => $loanPost['DeviceId'],
                                'Device_Borrower_Id' => $loanPost['borrowerId']
                            );

                            $data = $this->Insert($insertBorrowFormAry); // may hold exception
                        }
                    }
                }
            }
        } else {
            $data['exception'] = '<div class="exception textCenter">This device is already on loan.</div>';
        }

        return $data;
    }

//end method

    /*
     * Argument: Takes an array of loan return data
     * Purpose: Update loan in db. Delegates accessories validation, paths creation, and image upload to other functions
     * Return: Id of updated loan or an array with key exception on error
     */

    public function UpdateDeviceLoan($updateDeviceAry) {
        $deviceObj = new Device((int) $updateDeviceAry['DeviceId']);
        $deviceRow = $deviceObj->getDeviceRow();

        if (isset($deviceRow['exception'])) {
            $data = $deviceRow;
        } else {
            //validate accessories, returns json string
            $accessories = Accessories::validateAccessories($updateDeviceAry);

            if (isset($accessories['exception'])) {
                $data = $accessories;
            } else {

                $dir = array(
                    'loanorreturn' => "return",
                    'front' => "ConditionIn_Front",
                    'back' => "ConditionIn_Back",
                    'on' => "ConditionIn_On"
                );

                $uploadPath = self::setImgDirectories($deviceRow['Name'], $dir); // create directories, return upload path
                if (isset($uploadPath['exception'])) {
                    $data['exception'] = $uploadPath['exception'];
                } else {

                    $filePaths = $this->uploadDeviceImages($uploadPath, $dir); //inserts photos and returns the path of those inserted files
                    if (isset($filePaths['exception'])) {
                        $data['exception'] = $filePaths['exception'];
                    } else {

                        $accessories = array();
                        if (array_key_exists('Accessories_Id', $updateDeviceAry)) { //accessories may not have been selected, must check
                            foreach ($updateDeviceAry['Accessories_Id'] as $accessoryId) {
                                $accessories[] = $accessoryId;
                            }
                        }
                        $accessories = json_encode($accessories);

                        $conditionFront = self::sanitizeImgPath($filePaths['front']);
                        $conditionBack = self::sanitizeImgPath($filePaths['back']);
                        $conditionOn = self::sanitizeImgPath($filePaths['on']);

                        $dateIn = new DateTime();

                        $updateAry = array(
                            //'DueDate' => 
                            'Id' => $updateDeviceAry['Id'],
                            'Date_In' => $dateIn->format("Y-m-d h:i:s"),
                            'Accessories_In' => $accessories,
                            'ConditionIn_Front' => $conditionFront,
                            'ConditionIn_Back' => $conditionBack,
                            'ConditionIn_On' => $conditionOn,
                            'EmployeeBarcode_In' => $updateDeviceAry['EmployeeBarcode_In'],
                            'Notes_In' => $updateDeviceAry['NotesIn'],
                        );

                        $data = $this->Update($updateAry); //may contain exception
                    }
                }
            }
        }
        return $data;
    }

//end method

    /* Argument: An Id of the returned device
     * Purpose: Renew the device that has just been returned, by using the laon Id, including the pictures taken during the return
     * Return: Array of data inserted or array with key exception on error
     */
    public function renew(int $loanId) {
        $loanData = (new self())->GetLoanById($loanId);

        if (isset($loanData['exception'])) {
            $data = $loanData;
        } else {
            //It's possible this device may have been set to unavailable admin-side while it was loaned.
            $canLoan = Device::isAvailable((int) $loanData['Device_Id']);
            if (isset($canLoan['exception'])) {
                $data = $canLoan;
            } else if (!$canLoan) {
                $data['exception'] = '<div class="exception textCenter">Sorry, cannot renew this device. Please open a new loan.</div>';
            } else {
                $todayDate = new DateTime();
                $isScanned = Device_loan::isScanned($loanData['Barcode']);

                if ($isScanned) { //device was scanned
                    $dueDate = new DateTime();
                    $dueDate->add(new DateInterval('P3D')); //adds 3 days from today

                    $insertBorrowFormAry = array(
                        'Date_Out' => $todayDate->format('Y-m-d h:i:s'),
                        'DueDate' => $dueDate->format('Y-m-d h:i:s'),
                        'Signature' => $loanData['Signature'],
                        'EmployeeBarcode_Out' => $loanData['EmployeeBarcode_In'],
                        'Accessories_Out' => $loanData['Accessories_In'],
                        'ConditionOut_Front' => $loanData['ConditionIn_Front'], //$filePaths['front'],
                        'ConditionOut_Back' => $loanData['ConditionIn_Back'], //$filePaths['back'],
                        'ConditionOut_On' => $loanData['ConditionIn_On'], //$filePaths['on'],
                        'Notes_In' => $loanData['Notes_In'],
                        'Device_Id' => $loanData['Device_Id'],
                        'Device_Borrower_Id' => $loanData['device_borrower_Id']
                    );
                    $insertedId = $this->Insert($insertBorrowFormAry); // may hold exception
                    if (isset($insertedId['exception'])) {
                        $data = $insertedId;
                    } else {
                        $data['Id'] = $insertedId;
                    }
                } else {
                    $data['NotLoaned'] = 'NotLoaned';
                }
            }
        }
        return $data;
    }

//end of method
    /*
     * Argument: An integer loan id
     * Purpose: Data processing after a loan has been submitted in order to send email confirmation and load the success view in the controller
     * Return: An array of loan data
     */
    public static function loanSuccess(int $loanId) {
        $loan['Id'] = (int) $loanId;
        $loan['transactionType'] = "loan";
        $deviceLoanObj = new self($loan);

        $loan = $deviceLoanObj->getDeviceLoan();

        if (isset($loan['exception'])) {
            $errorData['error'] = $loan['exception'];
            $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
            return $errorData;
        } else {

            $accessories = json_decode($loan['Accessories_Out']);
            if (!empty($accessories)) {
                $data['accessories'] = Accessories::getSelectedAccessories($accessories);

                if (isset($data['accessories']['exception'])) {
                    $data['accessoriesError'] = $data['accessories']['exception'];
                }
            } else {
                $data['accessoriesEmpty'] = "No accessories were loaned with this device.";
            }

            if (empty($loan['Notes_Out'])) {
                $loan['Notes_Out'] = "None";
            }
            $dateOut = new DateTime($loan['Date_Out']);
            $loan['Date_Out'] = $dateOut->format("m/d/Y");
            $dueDate = new DateTime($loan['DueDate']);
            $loan['DueDate'] = $dueDate->format("m/d/Y");

            $data['url'] = base_url() . 'index.php/deviceBorrow/';
            $data['loanOut'] = $loan;

            return $data;
        }
    }

    /*
     * Argument: A string with the device name
     * Purpose: Check if the device is loaned and get its accessories if it is.
     * Return: An array with key loan if the device was loaned, or an array with key error
     */

    public static function DeviceReturn(string $device) {
        $device = Device::filterLoanedDevices($device); //get device data by Name

        if (isset($device['exception'])) {
            $errorData['error'] = $device['exception'];
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            return $errorData;
        } else if (!isset($device['Id']) || isset($device['empty'])) {
            $errorData['error'] = '<div class="exception textCenter">This device is not loaned in our system. If you believe this is an error, please try again.'
                    . ' If the error persists, please make sure there is no paper form filed for this loan. If there is none, use a paper form to assist the patron. Please contact your supervisor immediately about this loan.</div>';
            $errorData['url'] = base_url() . 'index.php/deviceBorrow/DeviceBorrow_returnDevice';
            return $errorData;
        } else {
            $data['loan'] = (new self())->GetLoanedDeviceData((int) $device['Id']);

            if (isset($data['loan']['exception'])) {
                $errorData['error'] = $data['loan']['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                return $errorData;
            } else {
                $accessoriesId = json_decode($data['loan'][0]['Accessories_Out']);
                if (!empty($accessoriesId)) { //has accessories
                    $data['accessories'] = Accessories::getSelectedAccessories($accessoriesId); //returns 0 on error. Should always return accessories
                    if (isset($data['accessories']['exception'])) {
                        $errorData['error'] = $data['accessories']['exception'];
                        $errorData['url'] = base_url() . 'index.php/deviceBorrow/DeviceBorrow_returnDevice';
                        return $errorData;
                    } else {
                        return $data;
                    }
                } else {
                    $data['empty'] = "No accessories were loaned for this device.";
                    return $data;
                }
            }
        }
        // }
    }

    /**
     * If you use a specific library to scan devices, use it here.
     */
    public static function isScanned($barcode) {
        if (is_numeric($barcode)) {
            if (true) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

}

//end of file
