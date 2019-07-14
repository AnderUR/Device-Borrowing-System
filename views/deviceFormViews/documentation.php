<!DOCTYPE html>

<html>

<head>
  <style>
    body {
      margin: 0;
    }

    #logo {
      display: inline-block;
      width: 396px;
      height: 88px;
    }

    .logoTitle {
      color: white;
    }

    #titleTop {
      font-size: 15px;
      position: absolute;
      top: 5px;
      left: 120px;
    }

    #titleBottom {
      font-size: 17px;
      position: absolute;
      top: 40px;
      left: 170px;
    }

    #bg {
      background-image: url('/LibServices/deviceLoan_assets/img/bgAdmin.png');
      background-repeat: repeat;
      background-size: 100%;
    }

    .grid-container {
      display: grid;
      grid-template-columns: 300px auto;
      grid-column-gap: 20px;
      grid-row-gap: 20px;
      margin: 40px 40px 0 40px;
      background: white;
    }

    .item1 {
      background-color: #393D41;
    }

    #content {
      height: calc(100vh);
      padding-right: 20px;
      overflow: auto;
    }

    #nav {
      list-style: none;
      padding: 0;
      font-size: 20px;
    }

    .hide {
      display: none;
    }

    /*.dropdown {
        cursor: pointer;
      }*/

    li {
      color: navajowhite;
      padding: 0 20px 20px 20px;
      -webkit-user-select: none;
      /* Safari 3.1+ */
      -moz-user-select: none;
      /* Firefox 2+ */
      -ms-user-select: none;
      /* IE 10+ */
      user-select: none;
      /* Standard syntax */
    }

    li a {
      color: navajowhite;
      text-decoration: none;
      margin-left: 10px;
    }

    li div a {
      margin-left: 30px;
    }

    li p {
      font-weight: bold;
      margin: 0 10px 10px 10px;
    }

    img {
      width: 50vw;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    /*Scrollbar*/
    /* width */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      box-shadow: inset 0 0 5px grey;
      border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #444444;
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #0E4E8F;
    }
  </style>
</head>

<body id="bg">

  <div>
    <a href="ipad"><img id="logo" src="/LibServices/deviceLoan_assets/img/logotop.png" width="100px" /></a>
    <div id="titleTop" class="logoTitle">
      <span>Device Borrowing System</span>
    </div>
    <div id="titleBottom" class="logoTitle">
      <span>DBS Documentation</span>
    </div>
  </div>

  <div class="grid-container">

    <div class="item1">

      <ul id="nav">
        <li>
          <a href="#title1">Logo & username</a>
        </li>
        <li>
          <a href="#title2">Borrow index</a>
        </li>
        <li>
          <a href="#title3">Return index</a>
        </li>
        <li>
          <p class="dropdown">Borrow form</p>
          <div>
            <a href="#title4">Borrow form</a><br />
            <a href="#title5">Borrow form validate</a>
          </div>
        </li>
        <li>
          <p class="dropdown">Return form</p>
          <div>
            <a href="#title6">Return form</a><br />
            <a href="#title7">Return validate</a>
          </div>
        </li>
        <li>
          <a href="#title8">Success borrow form</a>
        </li>
        <li>
          <a href="#title9">Success return form</a>
        </li>
        <li>
          <p class="dropdown">Manage page</p>
          <div>
            <a href="#title10">Manage devices</a><br />
            <a href="#title11">Device history</a>
          </div>
        </li>
        <li>
          <a href="#title12">Improvements & known Issues</a>
        </li>
      </ul>

    </div>

    <div id="content">

      <div id="title1" class="item2">
        <h2>Logo</h2>
        <img src="/LibServices/deviceLoan_assets/img/docImgs\Logo_NameSection.png" />
        <p>
          <b>The logo</b> is sectioned into three parts. The logo on the left, the title of the application at the top and the page title at the bottom.<br>
          The titles can be made into links, as done in the DeviceManage/[device] page.
        </p>
        <h2>Username section</h2>
        <p>
          The username is obtained once a user logs in at auth/login. It also serves as a dropdown that contains a log out button,
          which redirects the user back to auth/login, a link to the auth/index page to manage users, such as editing, adding and removing users,
          and finally a link to this documentation in the form of a question mark icon.
        </p>
      </div>

      <div class="item3" id="title2">
        <h2>Borrow index</h2>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loginIndexNoLogo.png" />
        <p>
          Use the <b>Return a device button</b> to go to the return page. Technical note: The borrow page is the index page in the DeviceBorrow controller.<br><br>
          <b>The barcode</b> field has to be at most 14 digits long. If there are any errors with the borrowers' account, the errors will be displayed.<br><br>
          <b>The device checkbox</b> is necessary for acquiring the loan agreement for the selected device. <b>Currently this does not affect the devices shown in the borrow form.</b>
        </p>
      </div>

      <div class="item4" id="title3">
        <h2>Return index</h2>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/returnIndex.png" />
        <p>
          Use the <b>loan a device button</b> to go to the borrow page.<br><br>
          To return a device, enter the corresponding device number in the <b>Laptop or iPad fields</b>. The number is validated but 01 instead of 1, or 011, instead of 11, is permitted.
        </p>
      </div>

      <div class="item5" id="title4">
        <h2>Borrow form</h2>
        <p>
          By default, all sections are disabled, except for the agreement, which is opened automatically once the page loads
        </p>
        <h3>1. Agreement</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/agreement.png" />
        <p>
          <b>The agreement</b> text is populated based on the device selected in the borrow index page.<br><br>
          In order to proceed to the contact section, the user must <b>sign</b> and press the agree button.
          When the agreement is submitted, the contact section is opened automatically.
          It is possible to return to the agreement by selecting the agreement section. However, the signature cannot be re-entered, as it is disabled.
          The student can re-enter the signature by pressing the clear button before submitting the agreement.
        </p>
        <h3>2. Contact details</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loan_contactDetails.png" />
        <p>
          In the <b>contact details</b> section, enter name, phone, email and barcode. This entered data will be saved and reused for this student in the next loan transaction.<br><br>
          The email entered will be used for <b>emailing the student</b> a receipt of this transaction.<br><br>
          Once the <b>continue button</b> is pressed, the device & condition section will open automatically and an alert box will tell the student to return the device to the employee.<br><br>
          Note that the contact section will stay open so that the employee can verify the data entered by the student.
        </p>
        <h3>3. Device & condition</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loan_devicecondition.png" />
        <h4>Select a device from the list</h4>
        <p>
          Currently there is no filter for devices; all available devices will show in this list. Future filtering will need to be tied with the login index select device checkbox.
          Available devices are those that are currently not borrowed in this system and that have not been marked as unavailable in the Manage Devices page.
        </p>
        <h4>Take photos</h4>
        <p>
          Take photos of the device by tapping the front, back or home screen pictures.
          If the device being used uses an old browser version, the employee will need to select to take a photo from a menu before the camera is deployed. Otherwise, the camera will deploy immediately.
        </p>
        <h4>Notes</h4>
        <p>
          Notes are optional. These notes can be used for noting issues with the device, accessories or anything else. They should not be used to describe the physical condition of the device, as that is the purpose of the photos.
        </p>
        <h4>Continue button</h4>
        <p>
          The continue button enables and opens the device & accessories section, but only if the device selected was scanned, otherwise an error alert will appear to remind the employee to check out the device first.<br>
          Note: By default, scanning a device will be true, unless changed in the backend with your specified rules.
        </p>
        <h3>4. Device & accessories</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loan_deviceAccessories.png" />
        <p>
          <b>The device & accessories section</b> is populated based on the selected device in the device & condition section. It shows the device type, number, barcode, due date and accessories, which can be selected from the list to borrow with the device, if any have been attached in the Manage Devices page.<br><br>
          A new device can be selected from the device & condition section and pressing the continue button.
        </p>
        <h3>5. Employee</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loan_employee.png" />
        <p>
          <b>The employee field</b> will be automatically filled with the barcode of the currently signed in user.
          The field can be edited in case the employee is not currently at the desk and someone else is temporarily attending the students.<br><br>
          This field is important as it can be used to speak with the employee who helped the student during the borrow process about any issues they may have risen during or after the process. Such as, employees’ handling of the process and student concerns or disputes.
        </p>
        <h3>6. Submit</h3>
        <p>
          <b>Upon submission</b>, an email is sent to the student using the email that was entered in the contact section.<br>
          In addition, data will be validated. If the validation fails, the borrow validation form will open, otherwise the borrow success page will open.
        </p>
      </div>

      <div class="item6" id="title5">
        <h2>Borrow form validate</h2>
        <p>
          The <b>Borrow validation</b> form appears if there were one or more errors in the borrow from submission. The errors will show at the top of the page and need to be corrected. This form will show again otherwise.<br><br>
          Unlike in the borrow form, this form does not disable the sections and any of them can be selected at any time to open or close them.<br><br>
          This form is virtually the same as the Borrow form, except data entered in the borrow form will be used to populate most of the sections.
        </p>
        <h3>1. Agreement</h3>
        <p>
          In the <b>agreement section</b>, no agreement text is shown again. Only the signature entered in the borrow form will be present.
        </p>
        <h3>2. Contact details</h3>
        <p>
          <b>The contact details</b> section will be filled with the data entered in the borrow form.
        </p>
        <h3>3. Device & condition</h3>
        <p>
          <b>The pictures</b> need to be retaken.<br><br>
          <b>The notes</b> are filled with the data entered in the borrow form.
        </p>
        <h3>4. Device & accessories</h3>
        <p>
          <b>The device & accessories section</b> is filled with data obtained from the borrow form. The device cannot be changed in this form; a new transaction would need to be started.
        </p>
        <h3>5. Employee</h3>
        <p>
          <b>The employee section</b> is filled from the data entered in the borrow form.
        </p>
        <h3>6. Submit</h3>
        <p>
          <b>Upon submission</b>, an email is sent to the student using the email that was entered in the contact section.<br>
          In addition, data will be validated. If the validation fails, the borrow validation form will open again, otherwise the borrow success page will open.
        </p>
      </div>

      <div class="item7" id="title6">
        <h2>Return form</h2>
        <p>
          The Device Condition and Employee sections are disabled when the page loads.
        </p>
        <h3>1. Contact details</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_contactDetails.png" />
        <p>
          <b>The contact details section</b> opens automatically, in order for the employee to quickly review the student returning the device.<br><br>
          Students cannot change their information on return. The reason for this is so that the return process is faster and smoother, as students rarely come with time to spare before their classes, even when told to. In addition, it is not likely their contact will change in the few days the device is borrowed.
        </p>
        <h3>2. Device & accessories</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_deviceAccessories.png" />
        <p>
          <b>The device & accessories section</b> opens automatically to quickly see the device being returned and when it is due.<br><br>
          Accessories can be unselected if they were not returned with the device. If no accessories were borrowed, there will be a message explaining this to the employee.<br><br>
          Continue button will trigger a check to make sure this device was returned if you use your own check in/out scanner. If it was not returned a message will show explaining it needs to be returned.
          If it was returned, the device condition and employee sections will open and the submit button will be enabled.
        </p>
        <h3>3. Device & condition</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_deviceCondition.png" />
        <h4>Take pictures</h4>
        <p>
          Take photos of the device by tapping the front, back or home screen pictures.
          If the device being used uses an old browser version, the employee will need to select take photos from a menu before the camera is deployed. Otherwise, the camera will deploy immediately.
        </p>
        <h4>Borrowed notes</h4>
        <p>
          The notes entered when the device was borrowed. <b>Borrow notes</b> can be used as a reference for adding return notes.
        </p>
        <h4>Return notes</h4>
        <p>
          <b>Return notes</b> are optional. These notes can be used for confirming an accessory was not returned and the student is aware of it, issues with the device reported by the student and more. These notes should not be used to describe the physical condition of the device, as that is the purpose of the photos.
        </p>
        <h3>4. Employee</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_employee.png" />
        <p>
          The checkboxes are for reminders to the employee to take care of those tasks during or after the device is returned. They are not used for any other purpose.<br><br>
          <b>The employee field</b> will be automatically filled with the barcode of the currently signed in user.
          The field can be edited in case the employee is not currently at the desk and someone else is temporarily attending the students.<br><br>
          This field is important as it can be used to speak with the employee who helped the student during the return process about any issues they may have risen during or after. Such as, employees’ handling of the process and student concerns or disputes.
        </p>
        <h3>5. Submit</h3>
        <p>
          <b>Upon submission</b>, an email is sent to the student using the email that was entered in the contact section in the loan form.<br>
          In addition, data will be validated. If the validation fails, the return validation form will open, otherwise the return success page will show.
        </p>
      </div>

      <div class="item8" id="title7">
        <h2>Return form validate</h2>
        <p>
          The <b>return validation</b> form appears if there were one or more errors in the return from submission. The errors will show at the top of the page and need to be corrected. This form will show again otherwise.<br><br>
          Unlike in the return form, this form does not disable the sections and any of them can be selected at any time to open or close them.<br><br>
          This form is virtually the same as the return form, except data entered in the borrow form will be used to populate most of the sections.
        </p>
        <h3>3. Device & condition</h3>
        <p>
          <b>The pictures</b> need to be retaken.<br><br>
          <b>The notes</b> are filled with the data entered in the return form.
        </p>
        <h3>4. Device & accessories</h3>
        <p>
          <b>The device & accessories section</b> is filled with data obtained from the return form.
        </p>
        <h3>5. Employee</h3>
        <p>
          <b>The employee section</b> is filled from the data entered in the return form.
        </p>
        <h3>6. Submit</h3>
        <p>
          <b>Upon submission</b>, an email is sent to the student using the email shown in the contact details section.<br>
          In addition, data will be validated. If the validation fails, the return validation form will open again, otherwise the return success page will open.
        </p>
      </div>

      <div class="item9" id="title8">
        <h2>Success borrow form</h2>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/loan_success.png" />
        <p>
          The <b>success borrow page</b> lists all the information entered by the student and employee in the borrow form, as well as the date the device was loaned and who to contact for details.<br><br>
          This information is emailed to the student.<br><br>
        </p>
      </div>

      <div class="item10" id="title9">
        <h2>Success return form</h2>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_success.png" />
        <p>
          The <b>success return page</b> lists all the information entered by the student and employee in the borrow form, as well as return date and who to contact for details.<br><br>
          This information is emailed to the student
        </p>
        <h3>1. Renew button</h3>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/return_renew.png" />
        <p>
          The <b>renew button</b> re-borrows a device to a student. This will use all the information submitted during the return transaction to fill a new borrow for the student.
          A success borrow form will be presented upon successful renewal.<br><br>
          Currently, the renewal button is disabled. When the button is needed once more, it can be enabled in the DeviceBorrow_ReturnSuccess.php file.
        </p>
      </div>

      <div class="item11">
        <h2 id="title10">Manage Devices</h2>

        <h3>1. Manage devices</h3>
        Only supervisors can make changes in the manage devices. This means that add device, add accessories and remove accessories content is not shown to employees without the required privileges.
        <h4>Add device and accessories</h4>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_add.png" />
        <p>
          <b>Devices are added</b> numerically to the devices list in descending order.<br><br>
          The barcode entered needs to be at least 14 digits. No duplicates are allowed. Devices cannot be removed, but, there is little to no chance of entering an incorrect device.<br><br>
          Currently there are only two types of devices to choose from, iPad or Laptop. <b>You must enter the correct type</b>, since the type is used for cataloguing and more.<br><br>

          <b>To add an accessory, enter</b> a name (bag, case, strap,...), quantity and select scan or no scan.<br><br>
          Quantity refers to how many accessories of this kind are available in total. This is not shown anywhere and was added for future implementation if needed.<br>
          Scan or No scan refers to whether an accessory needs to be scanned in a given scanner. If scan is selected, ‘(scan)’ will show next to the given accessory on all pages that show accessories and a tooltip will be available for it.<br>
        </p>
        <h4>Remove</h4>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_remove.png" />
        <p>
          <b>The remove tab</b> is currently used only to remove accessories.<br><br>
          Removing the selected accessories will delete the accessories from the database and will detach them from all the devices. Loans that were processed with those accessories will not be shown in the loan/return transaction anymore.
        </p>

        <h4>Devices</h4>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_devices.png" />
        <h5>Device actions</h5>
        <p>
          Click the <b>Delete all photos</b> button to remove all photos taken for the given device from the server. This action cannot be undone.<br><br>
          Click the <b>Set availability</b> button to mark a device as available or unavailable. A device may be marked as unavailable if it cannot be loaned for any reason. In the database, this is represented as ‘technical availability’.<br>
          The box that holds the device name will change color when the availability is changed; black is for unavailable and blue for available.
          A device that is unavailable will not show in the loan form.<br><br>
          Click the <b>Update barcode</b> button to edit a devices' barcode in case the device is being replaced for another. If a number is to be taken off circulation altogether, set availability of the device to unavailable.<br><br>
          Click the <b>Notes</b> button to write any notes for a device. The notes are especially useful to explain why a device is set to unavailable.<br><br>
          <b>Select</b> accessories from the list to attach them to the device. These accessories will then be shown in the borrow form for this device.<br><br>
        </p>

        <h3 id="title11">2. Device history</h3>
        <h4>1. History table</h4>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_history.png" />
        <p>
          <b>The history table</b> is populated when a device is selected in the manage devices section. It shows all the loans for the given device. Each specific loan transaction can be viewed by clicking the view button in the loan/return form column.<br><br>
          If a device has not been returned, the return date column will read 'In-loan Status'.<br><br>
          The history for the specific device can be <b>filtered</b> from date to date. If there is no match, there will be an error shown with the dates entered instead of a table.<br><br>
        </p>
        <h4>2. Loan transaction</h4>
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_transaction1.png" />
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_transaction2.png" />
        <img src="/LibServices/deviceLoan_assets/img/docImgs/manage_transaction3.png" />
        <p>
          <b>The loan transaction page</b> shows the loan and return data entered by the student and employee for the clicked loan/return transaction; that is, all loan information available. If the device has not been returned, it will be stated below the return section.<br><br>
          The photos can be clicked to see them in full size.
        </p>
      </div>
      <br><br>
      <hr>
      <div class="item12">
        <h3 id="title12">Improvements and known issues</h3>
        <h4>Possible future improvements</h4>
        <p>
          1. Add delete button to delete a transaction either in the history table for each transaction, or on the loan/return transaction page,
          which will show only for transactions not currently in loan status.<br>
          2. Add delete all loan/return transactions for a device as button in Device Actions, which will delete all transactions except the one currently on loan status.<br>
          3. Add delete for devices. Given the current system structure, it is possible to delete the last device entered for a given device type.
          This could be useful if somehow a device was entered erroneously. <br>
          4. Add indicator icon for devices that have notes next to their name in the device list in the Manage Devices section.<br>
          5. For the loan validation form and return validation form, photos are not repopulated. They need to be retaken. Ideally, they should be repopulated.<br>
        </p>
        <h4>Known issues</h4>
        <p>
          1. There are multiple validations for the signature in the loan form. In the unlikely event that the form is submitted without a signature,
          the loan validation form will state that the signature is needed. With no way to re-enter it, the employee will need to redo the form.<br>
          2. Minor layout fixes are needed in the Manage Devices page for the Manage Devices section in older browsers; specifically for Safari in iPad 1.
          This is because the Manage Devices page was originally intended for admins in desktops with newer browsers.<br>
          3. Sometimes some browsers add a blue highlight on barcodes, regardless of the styles written.
        </p>
        <h4>Notes</h4>
        <p>
          1. If photos for a device are deleted and then a loan/return transaction is viewed from the history table, it is possible that the transaction
          will still show the device pictures. This can happen if the transaction was viewed before. This is a result of browser caching the pictures.
          Clear your browser cache to fix it.
        </p>
      </div>
      <hr><br><br>
    </div>
    <!--end of content-->

    <!--
    <script>
      function toggleThis(el) {
        el.nextElementSibling.classList.toggle("hide");
      }
    </script>
  -->
</body>

</html>
