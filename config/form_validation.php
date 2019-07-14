<?php
$config = array(
        'DeviceBorrow_Loan' => array(
                array(
                        'field' => 'borrowerId',
                        'label' => 'borrowerId',
                        'rules' => 'required|integer'
                ),
                array(
                        'field' => 'DueDate'
                ),
                array(
                        'field' => 'Signature',
                        'label' => 'Borrower Signature',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'Phone',
                        'label' => 'Borrower Phone Number',
                        'rules' => 'required|integer|exact_length[10]'
                ),
                array(
                        'field' => 'Email',
                        'label' => 'Borrower Email',
                        'rules' => 'required|valid_email'
                ),
                array(
                        'field' => 'fName'
                ),
                array(
                        'field' => 'lName'
                ),
                array(
                        'field' => 'borrowerBarcode'
                ),
                array(
                        'field' => 'DeviceId',
                ),
                array(
                        'field' => 'EmployeeBarcode_Out',
                        'label' => 'Employee Barcode',
                        'rules' => 'required|integer|max_length[14]'
                )
        ),
        'DeviceBorrow_Return' => array(
                array(
                        'field' => 'Id',
                        'label' => 'Loan Id',
                        'rule' => 'required|integer'
                ),
                array(
                        'field' => 'DueDate'
                ),
                array(
                        'field' => 'borrowerId'
                ),
                array(
                        'field' => 'Phone'
                ),
                array(
                        'field' => 'Email'
                ),
                array(
                        'field' => 'fName'
                ),
                array(
                        'field' => 'lName'
                ),
                array(
                        'field' => 'BorrowerBarcode'
                ),
                array(
                        'field' => 'DeviceId',
                        'label' => 'Device Id',
                        'rule' => 'required|integer'
                ),
                array(
                        'field' => 'DeviceName'
                ),
                array(
                        'field' => 'DeviceBarcode'
                ),
                array(
                        'field' => 'NotesOut'
                ),
                array(
                        'field' => 'NotesIn'
                ),
                array(
                        'field' => 'EmployeeBarcode_In',
                        'label' => 'Employee Barcode',
                        'rules' => 'required|integer|max_length[14]'
                )
        ),
        'validateBarcode' => array(
                array(
                        'field' => 'BorrowerBarcode',
                        'label' => 'Barcode',
                        'rules' => 'required|integer|max_length[14]'
                )
        )
);
