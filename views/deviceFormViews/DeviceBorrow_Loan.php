<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Device Loan Form</title>

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/app.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/sigPadCustom.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">

</head>

<body id="bg">

  <?=$header?>

  <div class="loadActivityICO hidden"><img src="/LibServices/deviceLoan_assets/img/activityICO.gif" /></div>


  <div id="formBg">

      <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
      <fieldset class="innerContainer">

          <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
                <button type="submit" class="backBtn button" name="href" value="<?=$url?>">Cancel</button>
          </form>

            <?php
            $attributes = array('id' => 'loanForm');
            $hidden = array('borrowerId' => $borrower['id'], 'fName' => $borrower['firstName'], 'lName' => $borrower['lastName'], 'borrowerBarcode' => $borrower['borrowerBarcode']);
            echo form_open_multipart('/DeviceBorrow/DeviceBorrow_Loan', $attributes, $hidden);
            ?>
          <!--BORROWER -->
          <div class="secContainer flexContainer">
            <div>
              <img class="secTitleImg flexChild" src="/LibServices/deviceLoan_assets/img/tiles.png"/>
            </div>
            <div class="secTitle flexChild">
              FOR BORROWER
            </div>
          </div>

          <ul id="borrowerAccordion" class="accordion" data-accordion data-slide-speed="150" data-allow-all-closed="true" disabled>

            <li class="accordion-item is-active" data-accordion-item>
              <a href="#" class="accordion-title">AGREEMENT</a>
              <div class="accordion-content" data-tab-content>
                <?=$devicePolicy?>
              </div>
            </li>

            <li class="accordion-item" data-accordion-item>
              <a href="contactAccordion" class="accordion-title">CONTACT DETAILS</a>
              <div id="contactAccordion" class="accordion-content" data-tab-content>

              <div class="flexContainer contactFillCtnr">
                  <div class="inputContainer flexChild">
                    <input id="barcode" class="input" type="number" name="barcode" value="<?=$borrower['borrowerBarcode']?>" placeholder=" " maxlength="14" required/>
                    <label class="special" for="barcode">Barcode</label>
                  </div>
                </div>

                <div class="flexContainer contactFillCtnr">
                  <div class="inputContainer flexChild">
                    <input id="name" class="input" type="text" name="fName" value="<?=$borrower['firstName']?>" placeholder=" " required/>
                    <label class="special" for="name">First</label>
                  </div>
                  <div class="inputContainer flexChild">
                    <input id="name" class="input" type="text" name="lName" value="<?=$borrower['lastName']?>" placeholder=" " required/>
                    <label class="special" for="name">Last</label>
                  </div>
                </div>

                <div class="flexContainer contactFillCtnr">
                  <div class="inputContainer flexChild">
                    <input id="phone" class="input" type="number" name="Phone" value="<?=$borrower['phone']?>" maxlength="10" placeholder=" " required/>
                    <label class="special" for="phone">Phone</label>
                  </div>

                  <div class="inputContainer flexChild">
                    <input id="email" class="input" type="email" name="Email" value="<?=$borrower['email']?>" placeholder=" " required/>
                    <label class="special" for="email">Email</label>
                  </div>
                </div>

                <div class="submitContainer textCenter">
                  <button id="contactContinueBtn" class="button submit continue" type="button">Continue</button>
                </div>

              </div>
            </li>

          </ul>

          <!--OFFICE USE -->
          <div class="secContainer flexContainer">
            <div>
              <img class="secTitleImg flexChild" src="/LibServices/deviceLoan_assets/img/tiles.png"/>
            </div>
            <div class="secTitle flexChild">
              FOR OFFICE USE
            </div>
          </div>

          <ul id="officeUseAccordion" class="accordion" data-accordion data-slide-speed="150" data-allow-all-closed="true" disabled>

            <li class="accordion-item" data-accordion-item>
              <a href="conditionAccordion" class="accordion-title">DEVICE & CONDITION</a>
              <div id="conditionAccordion" class="accordion-content" data-tab-content>

                <div class="flexContainerCol">
                    <div class="blueWrapper">
                      <p class="darkGrayText">Select the device you are loaning from the dropdown and check it out by scanning it</p>
                     <select name="DeviceId" id="deviceSelect">
                       <?php foreach($devices as $device) { ?>
                         <option class="<?=$device['Type']?>" value="<?=$device['Id']?>"><?=$device['Name']?></option>
                       <?php }?>
                     </select>
                   </div>
                </div>
                <br>
                <p class="darkGrayText">Take pictures of the device to be loaned by tapping the images below. Please, <b>retake blurry pictures</b>, using the retake button when the camera opens.</b><br>
                If the device is an iPad and it has the type of case that does not include a front cover, take the case off before taking the pictures.</p>
                <ul class="darkGrayText">
                  <li>About iPad pictures:</li>
                    <ul>
                      <li><b>Front:</b> Take a picture of the iPad with the screen off.</li>
                      <li><b>Back:</b> Take a picture of the back of the iPad</li>
                      <li><b>Home Screen:</b> Turn on the iPad and take a picture of the home screen</li>
                    </ul>
                  <li>About Laptop pictures:</li>
                  <ul>
                    <li><b>Front:</b> Take a picture of the top of the laptop</li>
                    <li><b>Back:</b> Take a picture of the bottom of the laptop</li>
                    <li><b>Home Screen:</b> Open the laptop and a take a picture of the entire laptop, which will include trackpad, palmrest, keyboard and screen. The screen must be turned on showing the home screen.</li>
                  </ul>
                </ul>
                <br>
                <div class="flexContainer">
                  <div class="flexChild customLabel">Front</div>
                  <div class="flexChild customLabel">Back</div>
                  <div class="flexChild customLabel">Home Screen</div>
                </div>

                <div id="conditionContainer" class="flexContainer">

                  <div class="photoContainer flexChild">
                    <input id="frontPic" class="photoInput" type="file" name="ConditionOut_Front" accept="image/*" capture="environment">
                    <label for="frontPic"><img id="photoFront" class="photo" src="/LibServices/deviceLoan_assets/img/photoFront.png" /></label>
                  </div>

                  <div class="photoContainer flexChild">
                    <input id="homePic" class="photoInput" type="file" name="ConditionOut_On" accept="image/*" capture="environment">
                    <label for="homePic"><img id="photoHome" class="photo" src="/LibServices/deviceLoan_assets/img/photoHome.png" /></label>
                  </div>

                  <div class="photoContainer flexChild">
                    <input id="backPic" class="photoInput" type="file" name="ConditionOut_Back" accept="image/*" capture="environment">
                    <label for="backPic"><img id="photoBack" class="photo" src="/LibServices/deviceLoan_assets/img/photoBack.png" /></label>
                  </div>

                </div>

                <label class="darkGrayText" for="deviceNotes">Notes for this device (optional)</label>
                <textarea id="deviceNotes" name="NotesOut"></textarea>

                <div class="submitContainer textCenter">
                  <button id="deviceScan" class="button submit continue" type="button">Continue</button>
                </div>

              </div>
            </li>

            <li class="accordion-item" data-accordion-item>
              <a href="device_accessories" class="accordion-title">DEVICE & ACCESSORIES</a>
              <div id="device_accessories" class="accordion-content" data-tab-content></div>
            </li>

            <li class="accordion-item" data-accordion-item>
              <a href="employeeAccordion" class="accordion-title">EMPLOYEE</a>
              <div id="employeeAccordion" class="accordion-content" data-tab-content>
                <p>
                  I, the employee assisting this patron, have made sure that the patron understands the device loaning process, including:
                  <ul>
                    <li>They read and understood the agreement.</li>
                    <li>They are aware of how and when to return this device.</li>
                    <li>They have been informed that the condition of the device needs to be maintained.</li>
                  </ul>
                </p>
                <div class="flexContainerCol">
                  <div class="blueContainer darkGrayText">
                      <span>Employee: <?=$employeeName?></span>
                      <input class="textCenter" type="text" name="EmployeeBarcode_Out" value="<?=$employeeBarcode?>"maxlength="14" required/>
                  </div>
                </div>
              </div>
            </li>

          </ul>

          <div class="textCenter">
            <button id="borrowFormSubmit" class="button submit" name="loan" type="submit" disabled>Submit Loan</button>
          </div>

      </fieldset>
    </form>
  </div>

  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>

  <script src="/LibServices/deviceLoan_assets/signature_pad/docs/js/signature_pad.umd.js"></script>
  <script src="/LibServices/deviceLoan_assets/signature_pad/docs/js/app.js"></script>

  <script src="/LibServices/deviceLoan_assets/js/deviceBorrowLoan.js"></script>
  <script src="/LibServices/deviceLoan_assets/js/global.js"></script>

</body>

</html>
