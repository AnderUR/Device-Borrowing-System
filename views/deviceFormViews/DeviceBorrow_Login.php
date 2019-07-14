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
  
  <style>
    .boolInputCntnr {
      padding-right: 10px;
    }
  </style>
  
</head>

<body id="bg">
    
 <?=$header?>

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
  
    <div class="innerContainer">
        <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
            <button type="submit" class="button toUrlBtn" name="href" value="/DeviceBorrow/DeviceBorrow_returnDevice">Return a device</button>
            <button type="submit" id="toUrlBtnDisabled" class="button" name="href-disabled" value="/DeviceBorrow" disabled>Loan a device</button>
            <button type="submit" class="button toUrlBtn" name="href" value="/DeviceManage">Manage devices</button>
        </form>
        
       <?php echo form_open($postToUrl); 
       $barcodeVal = set_value('BorrowerBarcode');
      if( empty (set_value('BorrowerBarcode') )  )  $barcodeVal =  '' ;
      ?>
        
      <?= validation_errors(); ?>
      <div class="flexContainerCol">
        <div class="blueContainer darkGrayText">
            
            <span>Borrower, enter your barcode number</span>
            <input class="textCenter" type="number" name="BorrowerBarcode" maxlength="14" value="<?=$barcodeVal?>" required autofocus/>
        
            <div class="flexChildJustify">
              <label class="boolInputCntnr">iPad
                <input type="radio" name="Type" value="iPad" required/>
                <span class="checkmark radio"></span>
               </label>

               <label class="boolInputCntnr">Laptop
                 <input type="radio" name="Type" value="Laptop" required/>
                 <span class="checkmark radio"></span>
               </label>
            </div>
            
        </div>
      </div>
      <div class="textCenter">
        <button id="loginBorrow" class="button submit" type="submit">Login</button>
      </div>
          </form>

    </div>
  
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>
  
  <script src="/LibServices/deviceLoan_assets/js/global.js"></script>

</body>

</html>
