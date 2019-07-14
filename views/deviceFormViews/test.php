<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Test</title>
  
  <link rel="stylesheet" href="/LibServices/assets/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/assets/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/assets/deviceLoan_assets/css/stylesheet.css">
</head>

<body id="bg">


      
      <!--RENEW THIS LOAN-->
      <div class="blueContainer darkGrayText">
                      <span>Renewing this device will loan it with all the data that was used in the return. 
                          If you need to make a change, submit a new loan by going back to home. </span>
    <?php 
        $attributes = array('data-confirm' => "Are you sure you want to proceed with this renewal?");
        $hidden = array('barcode' => '1');
        echo form_open('/DeviceBorrow/test', $attributes, $hidden); ?>   
            <div class="textCenter">
              <button style="margin-bottom: 20px;" type="submit" class="submit button" name="loanId" value="1">Renew</button>
            </div>
        </form>
                  </div>

  <script src="/LibServices/assets/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
  <script src="/LibServices/assets/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/assets/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/assets/deviceLoan_assets/foundation642/js/app.js"></script>
  
  <script src="/LibServices/assets/deviceLoan_assets/js/returnSuccess.js"></script>
  
</body>

</html>
