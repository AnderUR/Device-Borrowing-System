<?php

/**
 * This controller handles the loan process and its requirements using any necessary classes to do so,
 * such as checking device availability, form validation for all transactions and user validation.
 */
defined('BASEPATH') or exit('No direct script access allowed');

class DeviceBorrow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/New_York');

        $this->load->library('form_validation');

        Server::require_login();

        if( !(new Webuser())->is_supervisor() ) {
            $this->session->set_flashdata('message', "You do not have sufficient access to loan or return devices.");
            redirect('auth/login');
        }
    }

    /**
     * This is the sign in page for a borrower to enter their barcode to be validated and get all relevant borrower data for the loan form.
     */
    public function index() {
        $loginPost = $this->input->post();
        $data['postToUrl'] = "DeviceBorrow"; //for deviceborrow_login to know where to post

        $webUserObj = new WebUser();

        $headerData['employee'] = ucwords(strtolower($webUserObj->getUsername()));
        $headerData['locationText'] = "Device Borrowing System";

        if ($this->form_validation->run('validateBarcode') == FALSE) {
            $headerData['whatPage'] = "Device Loan Login";
            $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

            $this->load->view('deviceFormViews/DeviceBorrow_Login', $data);
        } else {
            if (isset($loginPost['BorrowerBarcode']) && is_numeric($loginPost['BorrowerBarcode'])) {
                $validationStatus = Device_borrower::validateUser((int) $loginPost['BorrowerBarcode']);

                if (isset($validationStatus['exception'])) {
                    $errorData['error'] = '<div class="exception textCenter">' . $validationStatus['exception'] . '</div>';
                    $errorData['url'] = $_SERVER['HTTP_REFERER'];
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else {
                    $data = Device_loan::loanFormData((int)$loginPost['BorrowerBarcode']); //get borrower data and devices

                    if (isset($data['borrower']['exception']) || isset($data['devices']['exception'])) {

                        $exception = ( isset($data['borrower']['exception']) ) ? $data['borrower']['exception'] : $data['devices']['exception'];

                        $errorData['error'] = $exception;
                        $errorData['url'] = $_SERVER['HTTP_REFERER'];
                        $this->load->view('deviceFormViews/errorView', $errorData);
                    } else if (empty($data['devices'])) {
                        $errorData['error'] = '<div class="exception textCenter">There are no devices to loan. If you believe this is a error, please use a paper form for this loan then contact your supervisor immediately.</div>';
                        $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                        $this->load->view('deviceFormViews/errorView', $errorData);
                    } else {
                        $data['employeeBarcode'] = $webUserObj->getBarcode();
                        $data['employeeName'] = ucwords(strtolower($webUserObj->getUsername()));

                        if ($loginPost['Type'] === "iPad") {
                            $data['devicePolicy'] = $this->load->view('deviceFormViews/iPadPolicy', NULL, TRUE);
                        } else if ($loginPost['Type'] === "Laptop") {
                            $data['devicePolicy'] = $this->load->view('deviceFormViews/laptopPolicy', NULL, TRUE);
                        }

                        $headerData['whatPage'] = "Device Loan Form";
                        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                        $data['url'] = base_url() . "index.php/DeviceBorrow";
                        $this->load->view('deviceFormViews/DeviceBorrow_Loan', $data);
                    }
                }
            }
        }
    }

//end of function

    /**
     * Loads the form for loaning devices. Two posts come here: From login comes barcode, and from this function comes loan post.
     * run method will return true if all validation rules applied successfully. It will begin validation on POST.
     * The validation uses the form_validation.php file in config folder
     */

    public function DeviceBorrow_Loan() {
        $loanPost = $this->input->post();

        $webUserObj = new WebUser();

        $headerData['employee'] = ucwords(strtolower($webUserObj->getUsername()));
        $headerData['locationText'] = "Device Borrowing System";

        if (($this->form_validation->run('DeviceBorrow_Loan') == FALSE) && (!empty($loanPost) )) {
            $deviceObj = new Device((int) $loanPost['DeviceId']);
            $deviceRow = $deviceObj->getDeviceRow();

            if (isset($deviceRow['exception'])) {
                $errorData['error'] = $deviceRow['exception'];
                $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                $data['employeeName'] = ucwords(strtolower($webUserObj->getUsername()));

                if (isset($loanPost['Accessories_Id'])) { //use posted accessories to repopulate the loan form accessories
                    $hasAccessories = Accessories::getSelectedAccessories($loanPost['Accessories_Id']);

                    if (isset($hasAccessories['exception'])) {
                        $errorData['error'] = $hasAccessories['exception'];
                        $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                        $this->load->view('deviceFormViews/errorView', $errorData);
                    } else {
                        //$data['devicePolicy'] = $this->load->view('deviceFormViews/DeviceBorrow_LoanPolicy', NULL, TRUE);
                        $data['deviceData'] = $deviceRow;
                        $data['hasAccessories'] = $hasAccessories;
                        $data['url'] = base_url() . "index.php/DeviceBorrow";
                        $headerData['whatPage'] = "Device Loan Validate";
                        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                        $this->load->view('deviceFormViews/DeviceBorrow_LoanValidate', $data);
                    }
                } else {
                    //$data['devicePolicy'] = $this->load->view('deviceFormViews/DeviceBorrow_LoanPolicy', NULL, TRUE);
                    $data['deviceData'] = $deviceRow;
                    $data['empty'] = "No accessories are attached to this device. If you believe this an error, try again. If the error persists, leave notes for this device or use a paper form for this loan, then contact your supervisor immediately.";
                    $data['url'] = base_url() . "index.php/DeviceBorrow";
                    $headerData['whatPage'] = "Device Loan Validate";
                    $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                    $this->load->view('deviceFormViews/DeviceBorrow_LoanValidate', $data);
                }
            }
        } //if validation succeeded
        else if (!empty($loanPost)) {
            $borrowerData = array(
                'FirstName' => $loanPost['fName'],
                'LastName' => $loanPost['lName'],
                'Email' => $loanPost['Email'],
                'Phone' => $loanPost['Phone'],
                'Barcode' => $loanPost['borrowerBarcode']
            );

            $borrowerObj = new Device_borrower();
            $updateTransaction = $borrowerObj->UpdateDeviceBorrower($borrowerData);

            if (isset($updateTransaction['exception'])) {
                $errorData['error'] = $updateTransaction['exception'];
                $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                $deviceLoanObj = new Device_loan();
                $insertedId = $deviceLoanObj->InsertDeviceLoan($loanPost);

                if (isset($insertedId['exception'])) {
                    $errorData['error'] = $insertedId['exception'];
                    $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else {
                    /*Loan Success and Email*/

                    $deviceLoanObj = new Device_loan();
                    $data = $deviceLoanObj->loanSuccess((int) $insertedId);

                    if( isset($data['error']) ) {
                        $this->load->view('deviceFormViews/errorView', $data, TRUE);
                    } else {
                        $headerData['whatPage'] = "Device Loan Success";
                        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                        $this->load->view('deviceFormViews/DeviceBorrow_LoanSuccess.php', $data); // NOTE: this view uses key loanOut

                        /*Email user*/
                        $data['loanOut']['Notes'] = $data['loanOut']['Notes_Out'];
                        $data['loan'] = $data['loanOut'];
                        $data['dueText'] = "Due date";
                        $message = $this->load->view('deviceFormViews/emailTemplate', $data, TRUE); //NOTE: this view uses key loan
                        $this->notify->emailDeviceLoanReceipt($data['loanOut']['Email'], $message, "Device Loan Receipt");
                    }
                    /*
                    //If you wish to gain access to the success page by uri using loan id,
                    //uncomment the redirect in this function and the LoanSuccess controller function.
                    //Changes will be required, such as error handling, to the LoanSuccess function

                    $loan['Id'] = (int) $insertedId;
                    $loan['transactionType'] = "loan";
                    $deviceLoanObj = new Device_loan($loan);
                    $loan = $deviceLoanObj->getDeviceLoan();

                    if (isset($loan['exception'])) {
                        $errorData['error'] = $loan['exception'];
                        $errorData['url'] = base_url() . 'index.php/deviceBorrow/';
                        $this->load->view('deviceFormViews/errorView', $errorData);
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

                        $headerData['whatPage'] = "Device Loan Success";
                        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                        $this->load->view('deviceFormViews/DeviceBorrow_LoanSuccess.php', $data);

                    }*/
                }
                // redirect(base_url().'index.php/DeviceBorrow/DeviceBorrow_LoanSuccess/'. $insertedId);
            }
        } else {
            redirect(base_url() . 'index.php/deviceBorrow/'); //NOTE: when 3 wrong iamges are selected, no error is shown. Unknown error
        }
    }

//end function

    /*
     * Use the device Id passed by ajax with id #deviceScan and return device & accessories view to be used in the borrowForm view
     */

    public function DeviceBorrow_DeviceView() {
        $deviceId = (int) $this->input->post('deviceId');

        $deviceObj = new Device($deviceId);
        $accessoriesObj = new Accessories();

        $deviceAry = $deviceObj->getDeviceRow();
        if (isset($deviceAry['exception'])) {
            echo $deviceAry['exception'];
        } else {
            /** Check the barcode against library you use for scanning device barcodes, if any. Return true device was scanned */ 
            $isScanned = Device_loan::isScanned($deviceAry['Barcode']); 

            $isAvailabletoLoan = Device::isAvailable($deviceAry['Barcode']);
            
            if ($isScanned) {

                if ($isAvailabletoLoan) {
                    $dueDate = new DateTime();
                    $dueDate->add(new DateInterval('P3D')); //adds 3 days from today

                    $accessoriesAry = $accessoriesObj->GetHasAccessories($deviceId);

                    if (isset($accessoriesAry['exception'])) {
                        echo $accessoriesAry['exception'];
                    } else {
                        $data['id'] = $deviceAry['Id'];
                        $data['barcode'] = $deviceAry['Barcode'];
                        $data['name'] = $deviceAry['Name'];
                        $data['accessories'] = $accessoriesAry;
                        $data['dueDate'] = $dueDate->format("m/d/Y");
                        $data['dueTime'] = $dueDate->format("h:i:s");
                    }
                    echo ( $this->load->view('deviceFormViews/DeviceBorrow_DeviceView', $data, TRUE) );
                } else {
                    echo ( "NotLoaned" );
                }
            } else {
                echo("NotValid");
            }
        }
    }

    /**
     * Loads the return view for choosing a device to return.
     * No need to acquire user data for this process as it is already part of the loan record.
     * The device name must be exactly spelled as in the device class
     */

    public function DeviceBorrow_returnDevice() {
        //NOTE: Possible improvement: place devices type in their own table
        $devices = ["iPad", "Laptop"];
        $data['devices'] = $devices;

        $webUserObj = new WebUser();

        $headerData['employee'] = $webUserObj->getUsername();
        $headerData['locationText'] = "Device Borrowing System";
        $headerData['whatPage'] = "Device Return";

        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

        $this->load->view('deviceFormViews/DeviceBorrow_returnIndex', $data);
    }

    /**
     * Loads the return form for the selected device in the return view.
     * The device name must be exactly spelled as in the return controller function
     */

    public function DeviceBorrow_Return() {
        $deviceNum = $this->input->post();

        $webUserObj = new WebUser();

        $headerData['employee'] = ucwords(strtolower($webUserObj->getUsername()));
        $headerData['locationText'] = "Device Borrowing System";

        if (!empty($deviceNum['iPad']) && is_numeric($deviceNum['iPad']) && ( $deviceNum['iPad'] < 100 )) { //NOTE: As a rule, it's assumed there is no more than 99 of a given device
            //NOTE: Future improvement: create table of device types
            $num = ltrim($deviceNum['iPad'], "0");
            $device = "iPad " . $num;
            $data = Device_loan::DeviceReturn($device);

            if (isset($data['error'])) {
                $this->load->view('deviceFormViews/errorView', $data);
            } else if (isset($data['loan'])) {
                $headerData['whatPage'] = "Device Return";
                $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                $data['employeeBarcode'] = $webUserObj->getBarcode();
                $data['employeeName'] = ucwords(strtolower($webUserObj->getUsername()));

                $dueDate = new DateTime($data['loan'][0]['DueDate']);
                $data['loan'][0]['DueDate'] = $dueDate->format("m/d/Y");

                $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/DeviceBorrow_Return', $data);
            } else {
                $data = "Sorry, an unexpected error has occurred. If the error persists, leave notes for this device or use a paper form for this loan, then contact your supervisor immediately.";
                $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/errorView', $data);
            }
        } else if (!empty($deviceNum['Laptop']) && is_numeric($deviceNum['Laptop']) && ( $deviceNum['Laptop'] < 100 )) { //NOTE: As a rule, it's assumed there is no more than 99 of a given device

            $num = ltrim($deviceNum['Laptop'], "0");
            $device = "Laptop " . $num;
            $data = Device_loan::DeviceReturn($device);

            if (isset($data['error'])) {
                $this->load->view('deviceFormViews/errorView', $data);
            } else if (isset($data['loan'])) {
                $headerData['whatPage'] = "Device Return";
                $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                $data['employeeBarcode'] = $webUserObj->getBarcode();
                $data['employeeName'] = ucwords(strtolower($webUserObj->getUsername()));

                $dueDate = new DateTime($data['loan'][0]['DueDate']);
                $data['loan'][0]['DueDate'] = $dueDate->format("m/d/Y");

                $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/DeviceBorrow_Return', $data);
            } else {
                $data = "Sorry, an unexpected error has occurred. If the error persists, leave notes for this device or use a paper form for this loan, then contact your supervisor immediately.";
                $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/errorView', $data);
            }
        } else {
            redirect(base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice');
        }
    } //end borrowForm_return method

    public function DeviceBorrow_ReturnValidate() {
        $returnPost = $this->input->post();

        $webUserObj = new WebUser();

        $headerData['employee'] = $webUserObj->getUsername();
        $headerData['locationText'] = "Device Borrowing System";

        if (($this->form_validation->run('DeviceBorrow_Return') == FALSE ) && ( isset($returnPost['Id']) )) {
            $headerData['whatPage'] = "Device Return Validate";
            $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);
            $data['employeeName'] = ucwords(strtolower($webUserObj->getUsername()));

            if (!empty($returnPost['Accessories_Id'])) { //has accessories
                $data['accessories'] = Accessories::getSelectedAccessories($returnPost['Accessories_Id']); //returns array with key exception on error. Should return accessories if Accessories_Id is set correctly
                if (isset($data['accessories']['exception'])) {
                    $errorData['error'] = $data['accessories']['exception'];
                    $errorData['url'] = base_url() . 'index.php/deviceBorrow/DeviceBorrow_returnDevice';
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else {
                    $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                    $this->load->view('deviceFormViews/DeviceBorrow_ReturnValidate', $data);
                }
            } else {
                $data['empty'] = "No accessories were loaned with this device.";
                $data['url'] = base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/DeviceBorrow_ReturnValidate', $data);
            }
        } else if (isset($returnPost['Id'])) { //update the loan
            $deviceLoanObj = new Device_loan();
            $update = $deviceLoanObj->UpdateDeviceLoan($returnPost);

            if (isset($update['exception'])) {
                $errorData['error'] = $update['exception'];
                $errorData['url'] = base_url() . 'index.php/deviceBorrow/DeviceBorrow_returnDevice';
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                //all below code can be removed, up to the redirect to ReturnSuccess, if you wish to gain access to the success page by uri using loan id,
                //uncomment the redirect and the ReturnSuccess controller function.
                //Changes will be required, such as error handling, to the ReturnSuccess function
                $loan['Id'] = (int) $returnPost['Id'];
                $loan['transactionType'] = "return";
                $deviceLoanObj = new Device_loan($loan);
                $loan = $deviceLoanObj->getDeviceLoan();

                if (isset($loan['exception'])) {
                    $errorData['error'] = $loan['exception'];
                    $errorData['url'] = base_url() . 'index.php/deviceBorrow/DeviceBorrow_returnDevice';
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else {
                    $accessories = json_decode($loan['Accessories_In']);
                    if (!empty($accessories)) {
                        $data['accessories'] = Accessories::getSelectedAccessories($accessories);
                        if (isset($data['accessories']['exception'])) {
                            $data['accessoriesError'] = $data['accessories']['exception'];
                        }
                    } else {
                        $data['accessoriesEmpty'] = "No accessories were returned with this device.";
                    }

                    if (empty($loan['Notes_In'])) {
                        $loan['Notes_In'] = "None";
                    }
                    $dateIn = new DateTime($loan['Date_In']);
                    $loan['Date_In'] = $dateIn->format('m/d/Y');
                    $dueDate = new DateTime($loan['DueDate']);
                    $loan['DueDate'] = $dueDate->format("m/d/Y");

                    $loan['loanId'] = $returnPost['Id'];

                    $headerData['whatPage'] = "Device Return Success";
                    $data['url'] = base_url() . 'index.php/deviceBorrow/';
                    $data['loan'] = $loan;
                    $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                    $this->load->view('deviceFormViews/DeviceBorrow_ReturnSuccess.php', $data);

                    /* Email user */
                    $data['loan']['Notes'] = $loan['Notes_In'];
                    $data['dueText'] = "Was due on";
                    $message = $this->load->view('deviceFormViews/emailTemplate', $data, TRUE);
                    $this->notify->emailDeviceLoanReceipt($loan['Email'], $message, "Device Return Receipt");
                }
                //redirect(base_url().'index.php/DeviceBorrow/DeviceBorrow_ReturnSuccess/'. $returnPost['Id']);
            }
        } else {
            redirect(base_url() . 'index.php/DeviceBorrow/DeviceBorrow_returnDevice'); //NOTE: when 3 wrong images are selected, no error is shown. Unknown error
        }
    }

    public function renew() {
        $renewPost = $this->input->post();

        if ( ! isset($renewPost['loanId']) ) {
            $errorData['error'] = '<div class="exception textCenter">Sorry, cannot renew this device. An error occurred receiving the required data. Open a new loan instead. Please contact your supevisor immediately if you believe this is an error.</div>';
            $errorData['url'] = base_url() . '/DeviceBorrow';
            $this->load->view('deviceFormViews/errorView', $errorData);
        } else {
            $webUserObj = new WebUser();

            $headerData['employee'] = ucwords(strtolower($webUserObj->getUsername()));
            $headerData['locationText'] = "Device Borrowing System";

            $obj = new Device_loan();
            $loanId = $obj->renew($renewPost['loanId']);

            if (isset($loanId['exception'])) {
                $errorData['error'] = $loanId['exception'];
                $errorData['url'] = base_url() . '/DeviceBorrow';
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if ( isset($loanId['NotLoaned']) ) {
                echo ("NotLoaned");
            } else {
                $deviceLoanObj = new Device_loan();
                    $data = $deviceLoanObj->loanSuccess((int) $loanId['Id']);

                    if( isset($data['error']) ) {
                        $this->load->view('deviceFormViews/errorView', $data, TRUE);
                    } else {

                        $headerData['whatPage'] = "Device Loan Success";
                        $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                        $this->load->view('deviceFormViews/DeviceBorrow_LoanSuccess.php', $data); // NOTE: this view uses key loanOut

                        /*Email user*/
                        $data['loanOut']['Notes'] = $data['loanOut']['Notes_Out'];
                        $data['loan'] = $data['loanOut'];
                        $data['dueText'] = "Due on";
                        $message = $this->load->view('deviceFormViews/emailTemplate', $data, TRUE); //NOTE: this view uses key loan
                        $this->notify->emailDeviceLoanReceipt($data['loanOut']['Email'], $message, "Device Loan Receipt");
                }
            }
        }
    }

    /** Used for checking availability of this device. Returns 0 or 1 for javascript handler */
    public function isScanned() {
        $barcode = $this->input->post('barcode');
        echo( Device_loan::isScanned($barcode) );
    }

   /**
    * This method was created to add compatibility with iOS' webview. When using webview, links will not open in safari.
    */
   public function helperRedirect() {
        $redirectTo = $this->input->post('href');
        redirect($redirectTo);
    }

    /** Get loans for user, if any, by user barcode, and load the correct views for returning the device */
    /*    public function return_borrowerBarcode() {
      $barcode = $this->input->post();
      $data['postToUrl'] = "DeviceBorrow/DeviceBorrow_returnDevice";
      if ($this->form_validation->run('validateBarcode') == FALSE) {
      $this->load->view('deviceFormViews/borrowForm_Login', $data);
      } else {
      $borrowerObj = new Device_borrower((int)$barcode['BorrowerBarcode']);
      $borrower = $borrowerObj->getBorrower();

      //Check user is in our db
      if( $borrower === 0 ) {
      $errorData['error'] = "Sorry, a database error has occurred, near line 138 DeviceBorrow. Please try again. If the error persists, please use a paper form for this loan, then contact your supervisor immediately.";
      $this->load->view('deviceFormViews/errorView', $errorData);
      }
      else if ( !isset($borrower['id'])  ) {
      $errorData['error'] = "This user is not in our database. If you believe this is an error, try again or use a paper form to return the device. Make sure to scan it and contact your supervisor about this issue immediately.";
      $this->load->view('deviceFormViews/errorView', $errorData);
      } else {
      $loanObj = new Device_loan((int)$borrower['id']);
      $loan = $loanObj->getDeviceLoan();

      if( $loan === 0 ) {
      $errorData['error'] = "Sorry, a database error has occurred, near line 146 DeviceBorrow. Please try again. If the error persists, please use a paper form for this loan, then contact your supervisor immediately.";
      $this->load->view('deviceFormViews/errorView', $errorData);
      } else if ( empty($loan) ) {
      $errorData['error'] = "This user has no devices to return. If you believe this is an error, try again or use a paper form to return the device. Make sure to scan it and contact your supervisor about this issue immediately.";
      $this->load->view('deviceFormViews/errorView', $errorData);
      } else {
      if(sizeOf($loan) == 1) {
      echo("1");
      //if loans == 1 --> validation + load view borrowForm_return
      }
      else if (sizeOf($loan) > 1) {
      echo(">1");
      //if loans> 1 --> load view with many in $data
      } else {
      var_dump($loan);
      echo "failed unknown line 163 or so";
      }
      }
      }
      } // end if else validation
      } // end method
     */

    /** The success page when a device loan is submitted. */
    /*
      public function DeviceBorrow_LoanSuccess() {
      $post = $this->uri->segment(3, 0); //second parameter is default in case of failure

      if( ! is_numeric($post) && $post === 0) {
      $errorData['error'] = "Sorry, a database error has occurred, near line 290 DeviceBorrow. Please try again. If the error persists, please use a paper form for this loan, then contact your supervisor immediately.";
      $errorData['url'] = base_url().'index.php/deviceBorrow/';
      $this->load->view('deviceFormViews/errorView', $errorData);
      } else {
      $loan['Id'] = (int)$post;
      $loan['transactionType'] = "loan";
      $deviceLoanObj = new Device_loan($loan);
      $loan = $deviceLoanObj->getDeviceLoan();

      if( ! empty($loan)) {
      $accessories = json_decode($loan['Accessories_Out']);
      if( ! empty($accessories) ) {
      $data['accessories'] = Accessories::getSelectedAccessories($accessories);
      if( $data['accessories'] === 0 ) {
      $data['accessoriesError'] = "Could not get the inserted accessories for this device. Reload this page. If the error persists, make a note of loaned accessories and contact your supervisor immediately.";
      }
      } else {
      $data['accessoriesEmpty'] = "No accessories were loaned for this device. If you believe this an error, try again. If the error persists, leave notes for this device or use a paper form for this loan, then contact your supervisor immediately.";
      }

      if( empty($loan['Notes_Out']) ) {
      $loan['Notes_Out'] = "None";
      }

      $data['loanOut'] = $loan;
      $this->load->view('deviceFormViews/DeviceBorrow_LoanSuccess.php', $data);
      } else {
      $errorData['error'] = "Could not get the inserted loan. Please try again. DeviceBorrow_LoanSuccess near line 304";
      $errorData['url'] = base_url().'index.php/deviceBorrow/';
      $this->load->view('deviceFormViews/errorView', $errorData);
      }
      }
      }
     */

    /*
      public function DeviceBorrow_ReturnSuccess() {
      $post = $this->uri->segment(3, 0); //second parameter is default in case of failure

      if( ! is_numeric($post) && $post === 0) {
      $errorData['error'] = "Sorry, a database error has occurred, near line 290 DeviceBorrow. Please try again. If the error persists, please use a paper form for this loan, then contact your supervisor immediately.";
      $errorData['url'] = base_url().'index.php/deviceBorrow/DeviceBorrow_returnDevice';
      $this->load->view('deviceFormViews/errorView', $errorData);
      } else {
      $loan['Id'] = (int)$post;
      $loan['transactionType'] = "return";
      $deviceLoanObj = new Device_loan($loan);
      $loan = $deviceLoanObj->getDeviceLoan();

      if( ! empty($loan)) {
      $accessories = json_decode($loan['Accessories_In']);
      if( ! empty($accessories) ) {
      $data['accessories'] = Accessories::getSelectedAccessories($accessories);
      if( $data['accessories'] === 0 ) {
      $data['accessoriesError'] = "Could not get the inserted accessories for this device. Reload this page. If the error persists, make a note of loaned accessories and contact your supervisor immediately.";
      }
      } else {
      $data['accessoriesEmpty'] = "No accessories were loaned for this device. If you believe this an error, try again. If the error persists, leave notes for this device or use a paper form for this loan, then contact your supervisor immediately.";
      }

      if( empty($loan['Notes_In']) ) {
      $loan['Notes_In'] = "None";
      }

      $data['loan'] = $loan;
      $this->load->view('deviceFormViews/DeviceBorrow_ReturnSuccess.php', $data);
      } else {
      $errorData['error'] = "Could not get the inserted loan. Please try reload the page to try again. DeviceBorrow_LoanSuccess near line 304";
      $this->load->view('deviceFormViews/errorView', $errorData);
      }
      }
      }
     */

} //End of file