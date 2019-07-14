<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Device Forms</title>

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/formStylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">
</head>

<body id="bg">

  <?=$header?>

  <img id="innerCntnrTopImg" src="/LibServices/deviceLoan_assets/img/headergraphic.png" />
  <div class="successInnerContainer">
    <a href="<?=$url?>" class="button backBtn">Home</a>

  <div class="flexContainerCol">
      <div id="shadowsDevice" class="flexContainer">
          <div class="flexChild device">
            <div class="flexContainerCol">
              <div id="deviceName"><?=$loan['loan']['Name']?></div>
            </div>
          </div>
          <div id="dueDate" class="flexChild">
            <div class="flexContainerCol">
              <div id="dueDateCntnr">Due Date</div>
              <div class="textCenter" id="dateCntnr"><?=$loan['loan']['DueDate']?></div>
            </div>
          </div>
      </div>
      <div class="subHeadings secTitle">LOAN <?=$loan['loan']['Date_Out']?></div>
      <div>Employee on duty: <?=$loan['employeeName_loan']. " ".$loan['loan']['EmployeeBarcode_Out']?></div>

      <div class="subHeadings">ACCESSORIES</div>
        <div id="accssContainer">
          <?php if( is_array($loan['loan']['Accessories_Out']) ) {
            foreach($loan['loan']['Accessories_Out'] as $accessory) {
            ?>
            <div class="labelCheckCntnr">
              <div class="flexChild flexContainer"><?=$accessory['Name']?></div>
            </div>
          <?php } } else  { echo $loan['loan']['Accessories_Out']; }
          ?>
        </div>
  </div>

  <div class="subHeadings">PHOTOS</div>
  <div class="flexContainer">
    <div class="flexChild customLabel">Front</div>
    <div class="flexChild customLabel">Back</div>
    <div class="flexChild customLabel">Home Screen</div>
  </div>
  <div id="conditionContainer" class="flexContainer">

    <div data-open="revealPhoto" class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['loan']['ConditionOut_Front']?>" />
    </div>

    <div data-open="revealPhoto" class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['loan']['ConditionOut_Back']?>" />
    </div>

    <div data-open="revealPhoto"  class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['loan']['ConditionOut_On']?>" />
    </div>

  </div>
<div class="subHeadings">NOTES</div>
  <div class="notesCntnr">
    <div class="flexContainer">
      <div style="width:100%" class="labelCheckCntnr"><?=$loan['loan']['Notes_Out']?></div>
    </div>
  </div>

  <!--<div class="subHeadings">LOAN DATE</div>
    <div class="flexChild">
      $loan['loan']['Date_Out']?>
  </div>-->

  <div  id="patronContainer">
    <div class="flexContainer">
      <div class="flexChildcol">
        <div style="margin-top: 0;" class="subHeadings">TO</div>
        <div><?=$loan['loan']['FirstName']. " " .$loan['loan']['LastName']. " (" .$loan['loan']['BorrowerBarcode']. ")". "&nbsp&nbsp" . $loan['loan']['Email'] . "&nbsp&nbsp" . $loan['loan']['Phone'] ?></div>
      </div>

    </div>
    <br/>
    <div class="flexContainer"><img src=<?=$loan['loan']['Signature']?> width="80%" /></div>
  </div>

  <div class="flexContainerCol">

    <?php if( is_array($loan['return']) ) { ?>
    <div class="subHeadings secTitle">RETURN <?=$loan['return']['Date_In']?></div>
    <div>Employee on duty: <?=$loan['employeeName_return']. " ". $loan['return']['EmployeeBarcode_In']?></div>

    <div class="subHeadings">ACCESSORIES</div>
      <div id="accssContainer">
        <?php if( is_array($loan['return']['Accessories_In']) ) {
          foreach($loan['return']['Accessories_In'] as $accessory) {
          ?>
          <div class="labelCheckCntnr">
            <div class="flexChild flexContainer"><?=$accessory['Name']?></div>
          </div>
        <?php } } else  { echo $loan['return']['Accessories_In']; } ?>
      </div>
  </div>

  <div class="subHeadings">PHOTOS</div>
  <div class="flexContainer">
    <div class="flexChild customLabel">Front</div>
    <div class="flexChild customLabel">Back</div>
    <div class="flexChild customLabel">Home Screen</div>
  </div>
  <div id="conditionContainer" class="flexContainer">

    <div data-open="revealPhoto" class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['return']['ConditionIn_Front']?>" />
    </div>

    <div data-open="revealPhoto" class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['return']['ConditionIn_Back']?>" />
    </div>

    <div data-open="revealPhoto" class="photoContainer flexChild">
      <img class="imagePlaceholder" src="<?='../../'.$loan['return']['ConditionIn_On']?>" />
    </div>

  </div>

  <div class="notesCntnr">
    <div class="subHeadings">NOTES</div>
    <div class="flexContainer">
      <div style="width:100%;" class="labelCheckCntnr"><?=$loan['return']['Notes_In']?></div>
    </div>
  </div>

  <!--<div class="flexContainerCol">
    <div class="subHeadings">ON</div>
    <div><?=$loan['return']['Date_In']?></div>
  </div>-->

<?php } else { echo $loan['return']; } ?>

</div>

<div class="reveal" id="revealPhoto" aria-labelledby="revealPhoto" data-reveal>
    <div id="photoRevealContainer">
      <img src="" /> <!--filled with js-->
    </div>
</div>

<script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>
<script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
<script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
<script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>

<script>/*Image placeholder reveal*/
	//Shows actual image sizeOf
	$(".photoContainer").on('click', function() {
		let photo = $(this).children('img');
		let photoSrc = photo.attr('src');
		//Set reveal content to the photo placeholder image source
		$("#photoRevealContainer").children('img').attr('src', photoSrc);
	});
</script>


</body>

</html>
