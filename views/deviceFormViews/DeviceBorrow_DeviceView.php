<div class="flexContainerCol">
<input type="hidden" name = "DueDate" value = "<?=$dueDate?>" />
  <div id="shadowsDevice" class="flexContainer">
      <div class="flexChild device">
        <div class="flexContainerCol">
          <div id="deviceName"><?=$name?></div>
          <div id="deviceBarcode"><?=$barcode?></div>
        </div>
      </div>
      <div id="dueDate" class="flexChild">
        <div class="flexContainerCol">
          <div id="dateCntnr"><?=$dueDate?></div>
          <div id="dueDateCntnr">Due Date</div>
        </div>
      </div>
  </div>
  <div class="subHeadings">ACCESSORIES</div>
  <div id="accssContainer">
    <?php
    if(array_key_exists('hasAccessories', $accessories)) {

       foreach($accessories['hasAccessories'] as $accessory) {
         if($accessory['ScanRequired']) {
           $requireScan = '<span data-tooltip aria-haspopup="true" class="has-tip tip-right" title="This accessory must be scanned before loaning or returning it">&nbsp(scan)</span>';
         } else {
           $requireScan = "";
         }
         ?>

        <div class="labelCheckCntnr">
          <div class="flexChild"><?=$accessory['Name'].' '.$requireScan?></div>
          <label class="boolInputCntnr">
            <input type="checkbox" name="Accessories_Id[]" value=<?=$accessory['Id']?> checked />
            <span class="checkmark"></span>
          </label>
        </div>
    <?php } } else if(array_key_exists('empty', $accessories)) { echo ($accessories['empty']." Notify to your supervisor if you believe there are accessories attached to this device.");} ?>
  </div>

</div>

<div class="submitContainer textCenter">
  <button id="deviceAccessoriesBtn" class="button submit continue" type="button">Continue</button>
</div>
