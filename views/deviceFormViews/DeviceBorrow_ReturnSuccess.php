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
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">
</head>

<body id="bg">

 <?=$header?>

  <div class="loadActivityICO hidden"><img src="/LibServices/deviceLoan_assets/img/activityICO.gif" /></div>

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
  <div class="successInnerContainer">

      <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
        <button type="submit" class=" backBtn button" name="href" value="<?=$url?>">Home</button>
      </form>

  <div class="flexContainerCol">
      <div id="shadowsDevice" class="flexContainer">
          <div class="flexChild device">
            <div class="flexContainerCol">
              <div id="deviceName"><?=$loan['Name']?></div>
            </div>
          </div>
          <div id="dueDate" class="flexChild">
            <div class="flexContainerCol">
              <div id="dueDateCntnr">Was Due</div>
              <div class="textCenter" id="dateCntnr"><?=$loan['DueDate']?></div>
            </div>
          </div>
      </div>
      <div class="subHeadings">ACCESSORIES</div>
        <div id="accssContainer">
          <?php if( isset($accessories) ) {
            foreach($accessories as $accessory) {
            ?>
            <div class="labelCheckCntnr">
              <div class="flexChild flexContainer"><?=$accessory['Name']?></div>
            </div>
          <?php } } else if( isset($accessoriesEmpty) ) {
            echo $accessoriesEmpty;
          } else {
            echo $accessoriesError;
          } ?>
        </div>
  </div>
  <div id="notesCntnr">
  <div class="subHeadings">NOTES</div>
  <div class="flexContainer">
    <div style="width:100%;" class="labelCheckCntnr"><?=$loan['Notes_In']?></div>
  </div>
</div>

  <div  id="patronContainer">
  <div class="flexContainer">
    <div class="flexChildcol">
      <div style="margin-top: 0;" class="subHeadings">LOAN TO</div>
      <div><?=$loan['FirstName']. " " .$loan['LastName']. " (" .$loan['BorrowerBarcode']. ")"?></div>
    </div>
    <div class="flexChildcol">
      <div style="margin-top: 0;" class="subHeadings">RETURNED ON</div>
      <div><?=$loan['Date_In']?></div>
    </div>
  </div>
  <br/>
</div>

<div id="footer">
    <p>CONTACT US about loan questions or concerns</p>
    <p>Great Name Here
      <span style="font-size:12px;">
        &#9865
      </span>
      000.000.0000
      <span style="font-size:12px;">
        &#9865
      </span>
        ROOM
      <span style="font-size:12px;">
        &#9865
      </span>
        dbs@dbs.com
    </p>
  </div>

<!--RENEW LOAN-->
    <div style="margin-top: 3em;" class="blueContainer darkGrayText">
        <span>
          Renewing this loan will use the data from the return that you just submited. If you want to change anything, such as accessories, or notes, you need to submit a new loan form.
        </span>
      <?php
        //$attributes = array('data-confirm' => "Are you sure you want to proceed with this renewal?");
        //$hidden = array('barcode' => $loan['Barcode']);
        //echo form_open('../DeviceBorrow/renew', $attributes, $hidden); ?>
      <form id="renewLoan" action="../DeviceBorrow/renew" method="POST">
        <div class="textCenter">
            <input type="hidden" name="barcode" value="<?=$loan['Barcode']?>"/>
            <input type="hidden" name="loanId" value="<?=$loan['loanId']?>"/>
          <button style="margin-bottom: 20px;" type="submit" class="submit button" name="renew" value="renew">Renew</button>
        </div>
     </form>
     </div>

  </div>

  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>

  <script src="/LibServices/deviceLoan_assets/js/returnSuccess.js"></script>

</body>

</html>
