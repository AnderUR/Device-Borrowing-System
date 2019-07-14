<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Manage Devices</title>
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/jquery-ui-1.12.1/jquery-ui.min.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/jqueryComponents/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css">

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/foundation.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/foundation642/css/app.css">

  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/stylesheet.css">
  <link rel="stylesheet" href="/LibServices/deviceLoan_assets/css/adminStylesheet.css">
</head>

<body id="bg">

  <?=$header?>

  <div class="loadActivityICO hidden"><img src="/LibServices/deviceLoan_assets/img/activityICO.gif" /></div>

  <div id="manageDevicesCtnr" class="secTitle textCenter">MANAGE DEVICES
    <svg id="connectToManageTitle" height="210" width="100">
      <line x1="11" y1="0" x2="11" y2="80" style="stroke: var(--lighter-gray);stroke-width: 4;"></line>
      <circle cx="11" cy="9" r="9" fill="white"></circle>
    </svg>
  </div>

  <div id="devicesContainer" class="grid-container">

    <svg id="connectToHistory" height="210" width="100">
      <line x1="11" y1="0" x2="11" y2="140" style="stroke: var(--lighter-gray);stroke-width: 4;"></line>
      <circle cx="11" cy="140" r="9" fill="white"></circle>
    </svg>

    <div id="pseudoBorder">

    <div id="selectDeviceCntnr" class="col1Device">
    <a href="ipad"><button class="button">iPad</button></a>
      <hr id="connector" />
      <a href="laptop"><button class="button">Laptop</button></a>
    </div>

      <div id="deviceTabs" class="col2Device">
        <ul class="vertical tabs" data-tabs id="example-tabs">
          <li class="tabs-title"><a href="#panel1v">Add items <div id="addImg">&#43;</div></a></li>
          <li class="tabs-title"><a href="#panel2v">Remove <div id="addImg">&minus;</div></a></li>
          <?php $i = 3;
          foreach ($devices as $device) : ?>
          <li class="tabs-title deviceTab" id=<?=$device['Id']?> ><a href=<?="#panel".$i."v"?>> <?=$device['Name'];?> </a></li>
          <?php $i++;
          endforeach; ?>
        </ul>
      </div>

      <div class="col3Device">
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel" id="panel1v">

            <?php
            if($isAdmin) :

            $attributes = array('data-confirm' => 'Are you sure you want to insert this device?');
            echo form_open('DeviceManage/insertDevice', $attributes); ?>

            <fieldset class="flexContainerCol">
              <div class="subHeadings subHeading">Add Device</div>

              <div class="blueWrapper">

                 <div class="flexContainer">
                   <div class="inputContainer flexChild">
                     <input id="barcode" class="input" type="number" name="Barcode" maxlength="14" placeholder=" " required/>
                     <label class="special" for="barcode">Barcode</label>
                   </div>

                   <div class="flexChildcol">

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
            </fieldset>
            <div id="addDevicebutton" class="textCenter">
                  <button class="button submit" name="submit" value="insertDevice" type="submit">Add Device</button>
            </div>
          </form>

          <?php
          $attributes['data-confirm'] = "Are you sure you want to insert this accessory?";
          echo form_open('DeviceManage/insertAccessory', $attributes); ?>

          <fieldset class="flexContainerCol">
            <div class="subHeadings subHeading">Add Accessory</div>

            <div class="blueWrapper">

               <div class="flexContainer">
                 <div class="inputContainer flexChild">
                   <input id="accessoryName" class="input" type="text" name="Name" placeholder=" " required/>
                   <label class="special" for="accessoryName">Name</label>
                 </div>
                   <div class="inputContainer flexChild">
                   <input id="quantity" class="input" type="number" name="Quantity" maxlength="10" placeholder=" " required/>
                   <label class="special" for="quantity">Quantity</label>
                  </div>

                  <div class="flexChild">

                    <div class="radioSpace flexChild">Scan</div>
                    <label class="boolInputCntnr">
                      <input type="radio" id="ScanRequired1" name="ScanRequired" value="1" required/>
                      <span class="checkmark radio"></span>
                     </label>

                     <div style="white-space: nowrap;" class="radioSpace flexChild">No Scan</div>
                     <label class="boolInputCntnr">
                       <input type="radio" id="ScanRequired0" name="ScanRequired" value="0" required/>
                       <span class="checkmark radio"></span>
                     </label>

                  </div>

             </div>

            </div>
          </fieldset>
          <div id="addDevicebutton" class="textCenter">
                <button class="button submit" name="submit" value="insertAccessory" type="submit">Add accessory</button>
          </div>
        </form>

      <?php else : echo "Sorry, you must be an administrator to access this content."; endif; //end is admin if ?>

      </div> <!--End tab panel 1-->

      <div class="tabs-panel" id="panel2v">

        <div class="flexContainerCol">

          <?php if($isAdmin) : ?>

          <div class="subHeadings">ACCESSORIES</div>
          <?php if( ! isset($noAccessories) ) { ?>
          <p>
            Select the accessories you want to remove from the list below and click remove
          </p>
          <?php
          $attributes['data-confirm'] = "Are you sure you want proceed with this change(s)?";
          echo (form_open('DeviceManage/deleteAccessories', $attributes)
          .'<fieldset id="removeAccessoriesCntnr">');
          ?>
          <div class="accssContainer">
              <?php
            foreach($editAccessories as $accessory) :
              if($accessory['ScanRequired']) {
                $requireScan = '<span data-tooltip aria-haspopup="true" class="has-tip tip-right" title="This accessory must be scanned before loaning it">&nbsp(scan)</span>';
              } else {
                $requireScan = "";
              }
            ?>
            <div class="labelCheckCntnr">
              <div class="flexChild"><?=$accessory['Name'].' '.$requireScan?></div>
              <label class="boolInputCntnr">
                <input type="checkbox" name="Accessories_Id[]" value=<?=$accessory['Id']?> />
                <span class="checkmark"></span>
              </label>
            </div>
          <?php endforeach; ?>
          </div>
        </fieldset>
            <div id="addDevicebutton" class="textCenter">
                  <button class="button submit" value="removeAccessory" type="submit">Remove</button>
            </div>
          </form>
        <?php } else { echo $noAccessories; }

      else : echo "Sorry, you must be an administrator to access this content."; endif; //end is admin if

        ?>
      </div>

    </div> <!--End tab panel 2-->

          <?php $i=3; $j=0;
          foreach ($devices as $device) {
              $availabilityTxt = "Unavailable";
              $availabilityCss = "disabled";
              if ($device['TechnicalAvailability']) {
                  $availabilityTxt = "Available";
                  $availabilityCss = "enabled";
              } ?>

            <div class="tabs-panel" id=<?="panel".$i."v"?>>

              <div class="flexContainerCol">

                <div id="shadowsDevice" class="flexContainer">
                    <div class="flexChild device <?=$availabilityCss?>">
                      <div class="flexContainerCol">
                        <div id="deviceName"><?=$device['Name']?></div>
                        <div id="deviceBarcode"><?=$device['Barcode']?></div>
                      </div>
                    </div>
                    <div id="dueDate" class="flexChild">
                      <div class="flexContainerCol">
                        <div id="dateCntnr">Availability</div>

                          <div>
                            <?=$availabilityTxt?>
                          </div>

                      </div>
                    </div>
                </div>

                <?php if($isAdmin) : ?>

                <div class="subHeadings">DEVICE ACTIONS</div>

                <div class="flexContainer">

                  <?php $data = array('DeviceId' => $device['Id']);
                  $attributes['data-confirm'] = "Are you sure you want to delete all photos for this device? You will not be able to undo this action!";
                  echo form_open('DeviceManage/deleteDevicePhotos', $attributes, $data)?>
                    <input type="submit" name="DeletePhotos" value="Delete all photos"/>
                  </form>

                  <?php
                  $data = array('Id' => $device['Id'], 'TechnicalAvailability' => $device['TechnicalAvailability']);
                  $attributes['data-confirm'] = "This device will be set to available for loaning immediately or unavailable, in which case it won't be available for loaning, continue?";
                  echo form_open('DeviceManage/toggleDevice', $attributes, $data);?>
                    <input class="deviceActionBtn" type="submit" value="Set availability" />
                  </form>

                  <form id="updateBarcode" action="javascript:void(0);">
                    <input type="hidden" name="deviceId" value="<?=$device['Id']?>" />
                    <input class="deviceActionBtn" data-open="revealEditBarcode" type="submit" value="Update barcode" />
                  </form>

                  <form id="editNotes" action="javascript:void(0);">
                    <input type="hidden" name="deviceId" value="<?=$device['Id']?>">
                    <input class="deviceActionBtn" data-open="revealEditNotes" type="submit" value="Notes" aria-controls="revealEditNotes" aria-haspopup="true" tabindex="0">
                  </form>

                </div>

                <div class="subHeadings">ACCESSORIES</div>

                <?php
                $data = array('Id' => $device['Id']);
                $attributes['data-confirm'] = "All selected accessories will be immediately available for loaning with this device. Continue?";
                echo form_open('DeviceManage/linkItems', $attributes, $data);
                ?>

                  <fieldset class="accssContainer">
                    <?php
                    $accessoriesObj = new Accessories((int)$device['Id']);
                    $accessories = $accessoriesObj->getAccessoriesFiltered();

                    if(array_key_exists('hasAccessories', $accessories)) {

                      foreach($accessories['hasAccessories'] as $viewData) {

                        if($viewData['ScanRequired']) {
                          $requireScan = '<span data-tooltip aria-haspopup="true" class="has-tip tip-right" title="This accessory must be scanned before loaning it">&nbsp(scan)</span>';
                        } else {
                          $requireScan = "";
                        }
                    ?>
                        <div class="labelCheckCntnr">
                          <div class="flexChild"><?=$viewData['Name'].' '.$requireScan?></div>
                          <label class="boolInputCntnr">
                            <input type="checkbox" name="Accessories_Id[]" value=<?=$viewData['Id']?> checked />
                            <span class="checkmark"></span>
                          </label>
                        </div>
                    <?php }
                    foreach($accessories['hasNotAccessories'] as $viewData) {

                      if($viewData['ScanRequired']) {
                        $requireScan = '<span data-tooltip aria-haspopup="true" class="has-tip tip-right" title="This accessory must be scanned before loaning it">&nbsp(scan)</span>';
                      } else {
                        $requireScan = "";
                      }
                  ?>
                      <div class="labelCheckCntnr">
                        <div class="flexChild"><?=$viewData['Name'].' '.$requireScan?></div>
                        <label class="boolInputCntnr">
                          <input type="checkbox" name="Accessories_Id[]" value=<?=$viewData['Id']?> />
                          <span class="checkmark"></span>
                        </label>
                      </div>
                  <?php }

                } else if(array_key_exists('allAccessories', $accessories)) {

                      foreach($accessories['allAccessories'] as $viewData) {
                        if($viewData['ScanRequired']) {
                          $requireScan = '<span data-tooltip aria-haspopup="true" class="has-tip" title="You must scan this accessory">(scan)</span>';
                        } else {
                          $requireScan = "";
                        }
                    ?>
                        <div class="labelCheckCntnr">
                          <div class="flexChild"><?=$viewData['Name'].' '.$requireScan?></div>
                          <label class="boolInputCntnr">
                            <input type="checkbox" name="Accessories_Id[]" value=<?=$viewData['Id']?> />
                            <span class="checkmark"></span>
                          </label>
                        </div>
                    <?php }

                  } else if(array_key_exists('empty', $accessories)) {
                    echo ($accessories['empty']);/*no accessories*/
                  } else { echo($accessories); /*error*/ }
                    ?>
                  </fieldset>

              <div class="textCenter addAccessoryBtn">
                    <button class="button submit" name="submit" value="linkItems" type="submit">Save</button>
              </div>

            </form>
            
          <?php endif;  //end is admin if ?>

          </div> <!-- end flexContainerCol -->

            </div>
        <?php $i++; //next tab
          }  ?>
        </div>
      </div> <!--end col3Device -->
  </div> <!-- end pseudoBorder -->
</div> <!--end devicesContainer -->

  <div id="historyTitleCtnr" class="secTitle grid-container"><span id="manageDevicesTitle">DEVICE HISTORY</span>
    <svg id="connectToDeviceTitle" height="210" width="100">
      <line x1="11" y1="0" x2="11" y2="204" style="stroke: var(--lighter-gray);stroke-width: 4;"></line>
      <circle cx="11" cy="9" r="9" fill="white"></circle>
    </svg>
  </div>

  <div id="historyContainer" class="grid-container">

      <div class="flexChildJustify">
        <?php
        $attributes = array('id'=>'rangeHistory', 'class'=>'flexContainer');
        echo form_open('DeviceManage/deviceHistoryRange', $attributes);
        ?>
        <input type="hidden" id="deviceId" name="deviceId" />

        <div id="from" class="flexChildJustify">From: </div>

        <div class="flexChildJustify">
          <div><input class="rangeInput" type="text" name="startDate" required/></div>
        </div>

        <div id="to" class="flexChildJustify">To: </div>

        <div class="flexChildJustify">
          <div><input class="rangeInput" type="text" name="endDate" required/></div>
        </div>

        <div class="flexChildJustify">
          <div> <button id="searchRange" class="button" disabled>Get history</button></div>
        </div>

        </form>
      </div>
      <div>
        <span id="loansCount">Loans: 0</span>
      </div>
    <div id="partialBorder">
      <div id="historyContent">

      </div>

    </div>
  </div>

<div class="small reveal" id="revealEditBarcode" aria-labelledby="revealEditBarcode" data-reveal>
    <div id="editDevicebarcode">

    </div>
</div>

<div class="small reveal" id="revealEditNotes" aria-labelledby="revealEditNotes" data-reveal>
    <div id="editDeviceNotes">
        <form action="../DeviceManage/editNotes" method="POST">
            <fieldset class="flexContainerCol">
                <div id="addDevicebutton" class="textCenter">
                    <div class="subHeadings subHeading">Edit Notes</div>
                    <div class="flexContainerCol">
                        <input id="deviceId_Note" type="hidden" name="deviceId" value="None" />
                        <textarea rows="6" cols="6" id="notesText" type="number" name="Notes"></textarea>
                    </div>
                    <button class="button submit" name="submit" value="updateNotes" type="submit">Submit</button>
                </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/jquery.js"></script>

  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/what-input.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/vendor/foundation.js"></script>
  <script src="/LibServices/deviceLoan_assets/foundation642/js/app.js"></script>
  <script src="/LibServices/deviceLoan_assets/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="/LibServices/deviceLoan_assets/jqueryComponents/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js"></script>
  <script src="/LibServices/deviceLoan_assets/jqueryComponents/jquery.floatThead/dist/jquery.floatThead.min.js"></script>

  <script src="/LibServices/deviceLoan_assets/js/manage.js"></script>
  <script src="/LibServices/deviceLoan_assets/js/global.js"></script>

</body>

</html>
