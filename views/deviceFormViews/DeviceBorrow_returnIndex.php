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

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />

    <div class="innerContainer">
        <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
            <button type="submit" id="toUrlBtnDisabled" class="button" name="href-disabled" value="/DeviceBorrow/DeviceBorrow_returnDevice" disabled>Return a device</button>
            <button type="submit" class="button toUrlBtn" name="href" value="/DeviceBorrow">Loan a device</button>
            <button type="submit" class="button toUrlBtn" name="href" value="/DeviceManage">Manage devices</button>
          </form>
        
        <?php echo form_open('/DeviceBorrow/DeviceBorrow_Return'); ?>
        
      <div class="textCenter"><h5>Enter the device number (one or two degits). Ex. <b>1</b> for iPad 1, or <b>10</b> for iPad 10</h5></div>

      <div class="flexContainer">
        <?php for($i = 0; $i < sizeOf($devices); $i++) :?>
        <div class="inputContainer flexChildJustify">
          <div class="blueContainer">

              <input class="input deviceNameInput" id="<?=$devices[$i]?>" type="number" name="<?=$devices[$i]?>" maxlength="2" placeholder=" "/>
              <label class="special" for="<?=$devices[$i]?>"><?=$devices[$i]?></label>

              <div class="textCenter">
                <button id="submit-<?=$devices[$i]?>" class="button submit" type="submit">Search</button>
              </div>

          </div>
        </div>
        <?php endFor; ?>
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
