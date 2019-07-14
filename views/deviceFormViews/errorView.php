<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Error</title>

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">

</head>

<body id="bg">
  <div>
    <img style="width: 396px; height: 88px;" src="/LibServices/deviceLoan_assets/img/logotop.png" width="100px" />
    <div id="titleTop" class="logoTitle">
      <span>Device Borrowing System</span>
    </div>
    <div id="titleBottom" class="logoTitle">
      <span>Error</span>
    </div>
  </div>

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
  <div class="innerContainer">
    <span><?= $error; ?></span><br><br>

    <?php echo form_open('/DeviceBorrow/helperRedirect'); ?>
        <div class="textCenter">
            <button type="submit" class=" backBtn button" name="href" value="<?=$url?>">Try again</button>
        </di>
     </form>

  </div>

  <script src="/LibServices/deviceLoan_assets/js/global.js"></script>

</body>

</html>
