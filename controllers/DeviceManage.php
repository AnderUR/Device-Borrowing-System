<?php

/**
 * This controller handles device, accessories and loan management using any required classes to do so,
 * such as setting device availability, adding new devices, adding new accessories, deleting device images,
 * updating device barcode, linking accessories to devices, removing accessories, and viewing loan transactions.
 */
defined('BASEPATH') or exit('No direct script access allowed');

class DeviceManage extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/New_York');

        Server::require_login();

        if( !(new Webuser())->is_supervisor() ) {
            $this->session->set_flashdata('message', "You do not have sufficient access to manage devices.");
            redirect('auth/login');
        }
    }

    public function index()
    {
        redirect("DeviceManage/laptop");
    }

    /**
     * Loads the manageDevices view with data for the iPad device type.
     * Note the spelling for the device type as it must be used exactly as typed
     */

    public function ipad()
    {
        $deviceType = 'iPad';

        $devicesObj = new Device($deviceType);
        $ipads = $devicesObj->getDevices_Type();
        if (isset($ipads['exception'])) {
            $errorData['error'] = $ipads['exception'];
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        } else {
            $webUserObj = new WebUser();

            $headerData['employee'] = $webUserObj->getUsername();
            $headerData['locationText'] = anchor('DeviceManage/iPad', 'Device Borrowing System', 'class="titleLink"');
            $headerData['whatPage'] = anchor('DeviceBorrow', '&#8592 Loan/Return devices', 'class="titleLink"');
            $headerData['isAdmin'] = $webUserObj->is_admin();

            $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

            $data['isAdmin'] = $headerData['isAdmin'];

            $accessories = Accessories::getAllAccessories();
            if (isset($accessories['exception'])) {
                $errorData['error'] = $accessories['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if (empty($accessories)) {
                $data['noAccessories'] = "There are no accessories to remove";
                $data['devices'] = $ipads;
                $data['editAccessories'] = $accessories;
                $this->load->view('deviceFormViews/manageDevices', $data);
            } else {
                $data['devices'] = $ipads;
                $data['editAccessories'] = $accessories;
                $this->load->view('deviceFormViews/manageDevices', $data);
            }
        }
    }

    /**
     * Loads the manageDevices view with data for the Laptop device type.
     * Note the spelling for the device type as it must be use exactly as typed
     */

    public function laptop()
    {
        $deviceType = 'Laptop';

        $devicesObj = new Device($deviceType);
        $laptops = $devicesObj->getDevices_Type();
        if (isset($laptops['exception'])) {
            $errorData['error'] = $laptops['exception'];
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        } else {
            $webUserObj = new WebUser();

            $headerData['employee'] = $webUserObj->getUsername();
            $headerData['locationText'] = anchor('DeviceManage/iPad', 'Device Borrowing System', 'class="titleLink"');
            $headerData['whatPage'] = anchor('DeviceBorrow', '&#8592 Loan/Return devices', 'class="titleLink"');
            $headerData['isAdmin'] = $webUserObj->is_admin();

            $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

            $data['isAdmin'] = $headerData['isAdmin'];

            $accessories = Accessories::getAllAccessories();
            if (isset($accessories['exception'])) {
                $errorData['error'] = $accessories['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if (empty($accessories)) {
                $data['noAccessories'] = "There are no accessories to remove";
                $data['devices'] = $laptops;
                $data['editAccessories'] = $accessories;
                $this->load->view('deviceFormViews/manageDevices', $data);
            } else {
                $data['devices'] = $laptops;
                $data['editAccessories'] = $accessories;
                $this->load->view('deviceFormViews/manageDevices', $data);
            }
        }
    }

    /**
     * Receives a device Id in order to get its loan history in the manageDevices view.
     * Ajax posts the Id and this function returns the deviceHistory view
     */

    public function deviceHistory()
    {
        $deviceId = $this->input->post();
        if (isset($deviceId['Id']) && is_numeric($deviceId['Id'])) {
            //get loans by deviceId, including user data
            $deviceLoanObj = new Device_loan((int)$deviceId['Id']);
            $loansData = $deviceLoanObj->getDeviceLoan();

            if (isset($loansData['exception'])) {
                echo $loansData['exception'];
            } else if (empty($loansData)) {
                echo '<div class="textCenter">There is no loan history for this device.</div>';
            } else {
                $data['loansData'] = $loansData;
                $data['count'] = sizeOf($loansData); //total loans for this range
                $this->load->view('deviceFormViews/deviceHistory', $data);
            }
        } else {
            echo '<div class="exception textCenter">Sorry, something went wrong trying to get the loan history for this device.</div>';
        }
    }

    /**
     * Takes startDate, endDate and deviceId, passed through ajax post as serialize string
     * Returns a range for the device loan history for the given dates to ajax, to show it in mnageDevices view
     */

    public function deviceHistoryRange()
    {
        $post = $this->input->post();

        if (isset($post)) {
            parse_str($post['data'], $post);
            if (is_numeric($post['deviceId'])) {
                //dates are validated in Device_loan->GetDeviceLoanByRange
                $deviceLoanObj = new Device_loan($post['startDate'], $post['endDate'], (int)$post['deviceId']);
                $loanRange = $deviceLoanObj->getLoanRange();

                if (isset($loanRange['exception'])) {
                    echo $loanRange['exception'];
                } else if (empty($loanRange)) {
                    echo '<div class="exception textCenter">No loans were found for this device in the range: ' . $post['startDate'] . ' to ' . $post['endDate'] . '</div>';
                } else {
                    $data['loansData'] = $loanRange;
                    $data['count'] = sizeOf($loanRange); //total loans for this range
                    $this->load->view('deviceFormViews/deviceHistory', $data);
                }
            } else {
                echo '<div class="exception textCenter">Sorry, There was a problem getting the history of this device.</div>';
            }
        } else {
            echo '<div class="exception textCenter">Error</div>';
        }
    }

    /**
     * This function is triggered from the manageDevices view in the device history section
     * to show borrow and return forms for each item in the table
     */

    public function viewLoanTransactions()
    {
        $this->output->cache(0); // where 0 is the number of minutes for cache expiration

        $post = $this->input->post();

        if (empty($post) || !is_numeric($post['Id'])) {
            $errorData['error'] = '<div class="exception textCenter">Sorry, this id is not valid.</div>';
            $errorData['url'] = base_url() . '/DeviceManage/ipad';
            $this->load->view('deviceFormViews/errorView', $errorData);
        } else {
            $loanId = (int)$post['Id'];

            $loanTransaction = Device_loan::getLoanTransaction($loanId);

            if (isset($loanTransaction['exception'])) {
                $errorData['error'] = $loanTransaction['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                $webUserObj = new Webuser();

                if (empty($loanTransaction['loan']['Accessories_Out'])) {
                    $loanTransaction['loan']['Accessories_Out'] = "No accessories were loaned for this device.";
                } else if (isset($loanTransaction['loan']['Accessories_Out']['exception'])) {
                    $loanTransaction['loan']['Accessories_Out'] = $loanTransaction['loan']['Accessories_Out']['exception'];
                }

                if (!empty($loanTransaction['return'])) {
                    if (empty($loanTransaction['return']['Accessories_In'])) {
                        $loanTransaction['return']['Accessories_In'] = "No accessories were returned for this device.";
                    } else if (isset($loanTransaction['return']['Accessories_In']['exception'])) {
                        $loanTransaction['return']['Accessories_In'] = $loanTransaction['return']['Accessories_In']['exception'];
                    }

                    $webUserObj->setUserByBarcode($loanTransaction['return']['EmployeeBarcode_In']);
                    if ($webUserObj->getUsername() !== "Guest") {
                        $loanTransaction['employeeName_return'] = $webUserObj->getUsername();
                    } else {
                        $loanTransaction['employeeName_return'] = '<div class="exception textCenter">This user is not in our database.</div>';
                    }
                } else {
                    $loanTransaction['return'] = '<div class="exception textCenter">This device has not been returned for this transaction.</div>';
                }

                $dueDate = new DateTime($loanTransaction['loan']['DueDate']);
                $loanTransaction['loan']['DueDate'] = $dueDate->format("m/d/Y");
                $dateOut = new DateTime($loanTransaction['loan']['Date_Out']);
                $loanTransaction['loan']['Date_Out'] = $dateOut->format("m/d/Y");

                $webUserObj->setUserByBarcode($loanTransaction['loan']['EmployeeBarcode_Out']);
                if ($webUserObj->getUsername() !== "Guest") {
                    $loanTransaction['employeeName_loan'] = $webUserObj->getUsername();
                } else {
                    $loanTransaction['employeeName_loan'] = '<div class="exception textCenter">This user is not in our database.</div>';
                }

                $data['url'] = $_SERVER['HTTP_REFERER'];
                $data['loan'] = $loanTransaction;
                $webUserObj = new WebUser();

                $headerData['employee'] = $webUserObj->getUsername();
                $headerData['locationText'] = anchor('/DeviceManage/iPad', 'Device Borrowing System', 'class="titleLink"');
                $headerData['whatPage'] = "Loan Transaction";

                $data['header'] = $this->load->view('deviceFormViews/header', $headerData, TRUE);

                $this->load->view('deviceFormViews/DeviceManage_LoanTransactions', $data);
            }
        }
    }

    // end of method

    /**
     * Insert a new device. Note that devices' inserted name is based on its type and the amount of devices of this type in the database
     * For example, if there is only one iPad, the next iPad being inserted will be named iPad2.
     */

    public function insertDevice()
    {
        $insertPost = $this->input->post();

        if (isset($insertPost['submit'])) {

            if ($insertPost['submit'] == 'insertDevice') {

                $type = $insertPost['Type'];

                $deviceObj = new Device($type);
                $deviceCount = $deviceObj->getDeviceCount_Type();

                if (isset($deviceCount['exception'])) {
                    $errorData['error'] = $deviceCount['exception'];
                    $errorData['url'] = $_SERVER['HTTP_REFERER'];
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else if (!is_numeric($insertPost['Barcode'])) {
                    $errorData['error'] = '<div class="exception textCenter">Incorrect device barcode.</div>';
                    $errorData['url'] = $_SERVER['HTTP_REFERER'];
                    $this->load->view('deviceFormViews/errorView', $errorData);
                } else {

                    $deviceCount = $deviceCount + 1; //add one to the total count, which will be the num for the new device
                    $insertDeviceAry = array(
                        'Name' => $type . " " . $deviceCount,
                        'Barcode' => $insertPost['Barcode'],
                        'Type' => $type
                    );

                    $trans_status = $deviceObj->InsertDevice($insertDeviceAry);

                    if (isset($trans_status['exception'])) {
                        $errorData['error'] = $trans_status['exception'];
                        $errorData['url'] = $_SERVER['HTTP_REFERER'];
                        $this->load->view('deviceFormViews/errorView', $errorData);
                    } else if ($trans_status === 1) {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }
        } else {
            $errorData['error'] = '<div class="exception textCenter">Nothing to insert. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    //end method

    /**
     * Insert a selected set of accessories. An array is received from the view.
     */

    public function insertAccessory()
    {
        $insertPost = $this->input->post();

        if (isset($insertPost['submit'])) {
            $insertDeviceAry = array(
                'Name' => $insertPost['Name'],
                'Quantity' => $insertPost['Quantity'],
                'ScanRequired' => $insertPost['ScanRequired']
            );

            $accessory = new Accessories();
            $trans_status = $accessory->insertAccessory($insertDeviceAry);

            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if ($trans_status === 1) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $errorData['error'] = '<div class="exception textCenter">Nothing to insert. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    /**
     * Handles attaching accessories to devices which are selected in the manageDevices view for the selected device
     */

    public function linkItems()
    {
        $linkPost = $this->input->post();
        if (array_key_exists('Accessories_Id', $linkPost)) { //If at least one accessory was checked in the manageDevices view
            $linkAry = array(); //for insert_batch of device_Id and accessories_Id, since there is only one device_id received, copies need to be created
            foreach ($linkPost['Accessories_Id'] as $accessoryId) {
                $linkAry[] = array(
                    'device_Id' => $linkPost['Id'],
                    'Accessories_Id' => $accessoryId
                );
            }
            $trans_status = Accessories::linkItems($linkAry);

            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else if (array_key_exists('Id', $linkPost)) { //no checked accessories in manageDevices view for this device - remove all of them from device_has_accessories
            $deviceId = (int)$linkPost['Id'];

            $accessories = new Accessories();
            $trans_status = $accessories->DeleteHasAccessoryByDevice($deviceId);

            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    /**
     * Receives accessories to remove. Devices will no longer be linked to these accessories, but the accessories ids will still be present in the loan records
     */

    public function deleteAccessories()
    {
        $accessoriesId = $this->input->post();
        if (!empty($accessoriesId)) {
            $trans_status = Accessories::DeleteSelectedAccessories($accessoriesId);

            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if ($trans_status === 1) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $errorData['error'] = '<div class="exception textCenter">Something went wrong. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    /**
     * Receives the device id for which uploaded pictures will be removed.
     * Note that the entire file structure is removed starting at the given device name
     * If pictures still show in the LoanTransaction view after the folder is removed, it is caused by browser caching.
     */

    public function deleteDevicePhotos()
    {
        $deviceId = $this->input->post();
        //get device name by id
        if (isset($deviceId['DeviceId']) && is_numeric($deviceId['DeviceId'])) {
            $deviceObj = new Device((int)$deviceId['DeviceId']);
            $device = $deviceObj->getName();

            if (isset($device['exception'])) {
                $errorData['error'] = $device['exception'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if (empty($device)) {
                $errorData['error'] = "Incorrect Id received. Please try again.";
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                $device = str_replace(' ', '', $device);
                $removePhotos = './uploads/devicesImg/' . $device . '/';
                Device_loan::removeDir($removePhotos);

                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $errorData['error'] = "Incorrect Id received.";
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    // end of function

    /**
     * Sets the technical availability of a device. Technical unavailable devices cannot be loaned.
     */

    public function toggleDevice()
    {
        $togglePost = $this->input->post();
        if (isset($togglePost['TechnicalAvailability']) && is_numeric($togglePost['Id'])) {
            $TechAvailability = $togglePost['TechnicalAvailability'];

            if ($TechAvailability) {
                $TechAvailability = 0; //unavailable
            } else {
                $TechAvailability = 1; //available
            }
            $deviceObj = new Device();
            $updateDevice = array('Id' => $togglePost['Id'], 'TechnicalAvailability' => $TechAvailability);

            $trans_status = $deviceObj->UpdateDevice($updateDevice);
            //Add error handler
            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if ($trans_status === 1) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $errorData['error'] = '<div class="exception textCenter">Something went wrong. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    /**
     * Updates a devices' barcode. Devices cannot be removed.
     * If you do not want a device to be used again, set it to unavailable.
     */

    public function updateBarcode()
    {
        $post = $this->input->post();
        if (isset($post) && is_numeric($post['Id']) && is_numeric($post['Barcode'])) {
            $updateAry = array(
                'Id' => $post['Id'],
                'Barcode' => $post['Barcode']
            );
            $deviceObj = new Device();

            $trans_status = $deviceObj->UpdateDevice($updateAry);
            if (isset($trans_status['exception'])) {
                $errorData['error'] = $trans_status['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else if ($trans_status === 1) {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $errorData['error'] = '<div class="exception textCenter">Something went wrong. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        }
    }

    public function getNotes()
    {
        $devicePost = $this->input->post();

        if (!isset($devicePost['deviceId'])) {
            echo 0;
        } else {
            $deviceObj = new Device((int)$devicePost['deviceId']);
            $deviceData = $deviceObj->getDeviceRow();

            if (isset($deviceData['exception'])) {
                echo 'error';
            } else {
                if ($deviceData['Notes'] == '') {
                    echo $deviceData['Name'];
                } else {
                    echo $deviceData['Notes'];
                }
            }
        }
    }

    public function editNotes()
    {
        $notesPost = $this->input->post();

        if (!isset($notesPost['deviceId'])) {
            $errorData['error'] = '<div class="exception textCenter">No notes were received. Please try again.</div>';
            $errorData['url'] = $_SERVER['HTTP_REFERER'];
            $this->load->view('deviceFormViews/errorView', $errorData);
        } else {
            $deviceObj = new Device();
            $data = array(
                'Id' => $notesPost['deviceId'],
                'Notes' => $notesPost['Notes']
            );

            $updateStatus = $deviceObj->Update($data);

            if (isset($updateStatus['exception'])) {
                $errorData['error'] = $updateStatus['exception'];
                $errorData['url'] = $_SERVER['HTTP_REFERER'];
                $this->load->view('deviceFormViews/errorView', $errorData);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function documentation()
    {
        $this->load->view('deviceFormViews/documentation');
    }
}

//end of file
