<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Device Loan Success</title>

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">
</head>

<body id="bg">
    
 <?=$header?>

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
  <div class="successInnerContainer">
      
        <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
            <button type="submit" class="backBtn button" name="href" value="<?=$url?>">Home</button>
        </form>  
        
  <div class="flexContainerCol">
      <div id="shadowsDevice" class="flexContainer">
          <div class="flexChild device">
            <div class="flexContainerCol">
              <div id="deviceName"><?=$loanOut['Name']?></div>
            </div>
          </div>
          <div id="dueDate" class="flexChild">
            <div class="flexContainerCol">
              <div id="dueDateCntnr">Due Date</div>
              <div class="textCenter" id="dateCntnr"><?=$loanOut['DueDate']?></div>
            </div>
          </div>
      </div>
      <div class="subHeadings">ACCESSORIES</div>
        <div id="accssContainer">
          <?php
          if( isset($accessoriesError) ) {
            echo $accessoriesError;
          } else if( isset($accessoriesEmpty) ) {
            echo $accessoriesEmpty;
          } else {
            foreach($accessories as $accessory) {
            ?>
            <div class="labelCheckCntnr">
              <div class="flexChild flexContainer"><?=$accessory['Name']?></div>
            </div>
          <?php } } ?>
        </div>
  </div>
  <div id="notesCntnr">
  <div class="subHeadings">NOTES</div>
  <div class="flexContainer">
    <div style="width:100%;" class="labelCheckCntnr"><?=$loanOut['Notes_Out']?></div>
  </div>
</div>

  <div id="patronContainer">
  <div class="flexContainer">
    <div class="flexChildcol">
      <div style="margin-top: 0;" class="subHeadings">LOAN TO</div>
      <div><?=$loanOut['FirstName']. " " .$loanOut['LastName']. " (" .$loanOut['BorrowerBarcode']. ")"?></div>
    </div>
    <div class="flexChildcol">
      <div style="margin-top: 0;" class="subHeadings">ON</div>
      <div><?=$loanOut['Date_Out']?></div>
    </div>
  </div>
  <br/>
  <div class="flexContainer"><img src=<?=$loanOut['Signature']?> width="80%" /></div>
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
    
  </div>

  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>
  
</body>

</html>
