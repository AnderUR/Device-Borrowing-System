<!DOCTYPE>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Device Borrow Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>

  <body>

    <table align="center" width="500px">
      <tr>
        <td>
          <table>
            <tr>
              <td>
                <img src="DBS_Logo.png" alt="DBS Logo" height="100%" width="100%" style="display: block;"/>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <tr align="center">
        <td>
          <table cellspacing="0" cellpadding="20">
            <tr>
              <td bgColor="#3085b5">
                <span style="font-size: 20px; color: white;"><?=$loan['Name']?></span>
              </td>
              <td align="center" bgColor="#444444">
                <span style="color: white;"><?=$dueText?></span>
                <br>
                <span style="font-size: 20px; color: white;"><?=$loan['DueDate']?></span>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <tr align="center">
        <td>
          <table width="50%" cellspacing="4" cellpadding="20">
            <tr align="center">
              <td>
                ACCESSORIES
              </td>
            </tr>

            <?php
          if( isset($accessoriesError) ) : ?>
            <tr align="center">
              <td style="padding: 6px;">
                No information for accessories was provided. Please contact us if you have questions.
              </td>
            </tr>
            <?php
          elseif ( isset($accessoriesEmpty) ) : ?>
            <tr align="center">
              <td style="padding: 6px;">
                <?=$accessoriesEmpty;?>
              </td>
            </tr>
            <?php
          else :
            $i = 0;
            foreach($accessories as $accessory) :
              $i++;
              if($i !== sizeOf($accessories)) :
            ?>
            <tr align="center">
              <td style="padding: 6px; border-top: 2px solid lightgray;">
                <?=$accessory['Name']?>
              </td>
            </tr>
            <?php
            else : ?>
            <tr align="center">
              <td style="padding: 6px; border-top: 2px solid lightgray; border-bottom: 2px solid lightgray;">
                <?=$accessory['Name']?>
              </td>
            </tr>
            <?php
            endif;
            endforeach; 
            endif; 
            ?>
          </table>
          <table width="100%" cellpadding="20">
            <tr>
              <td style="padding-left: 0px;">
                NOTES
              </td>
            </tr>
            <tr>
              <td style="padding: 0px;">
                <?=$loan['Notes'];?>
              </td>
            </tr>

            <tr>
              <td style="padding-left: 0px; padding-right: 0px;">
                <table width="100%" cellpadding="10">
                  <tr>
                    <td style="padding-left: 0px;">
                      LOAN TO
                    </td>
                    <td style="padding-right: 0px;">
                      ON
                    </td>
                  </tr>
                  <tr>
                    <td style="padding-left: 0px;">
                      <?=$loan['FirstName']. " " .$loan['LastName']. " (" .$loan['BorrowerBarcode']. ")"?>
                    </td>
                    <td style="padding-right: 0px;">
                        <?php 
                        if (isset ($loan['Date_Out']) ) :
                            echo $loan['Date_Out'];
                        else : echo $loan['Date_In'];
                        endif;
                        ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table width="100%" bgColor="#2E697A" cellpadding="10" style="color: white;">
            <tr align="center">
              <td>
                CONTACT US about loan questions or concerns
              </td>
            </tr>
            <tr align="center">
              <td>
                  Great Name 000.000.000 ROOM <a style="color: #ffa918" href="mailto:dbs@dbs.com?subject=Device Loan Question">dbs@dbs.comu</a>
                </td>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

  </body>
</html>
