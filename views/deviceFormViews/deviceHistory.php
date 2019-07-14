<span style="display: none;" id="count"><?=$count?></span> <!--Used by js in ajax to populate count-->

<div class="table-wrapper">
  <table class="stickyHeader">
    <thead>
      <tr>
        <th>Loan Date</th> <th>Return Date</th> <th>Borrower</th> <th>Loan/Return Form</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($loansData as $loan) {
        $dateIn = ($loan['Date_In'] != null ? $loan['Date_In'] : "In-loan Status");
      ?>
      <tr>
        <td><div><?=$loan['Date_Out']?></div></td>
        <td><div><?=$dateIn?></div></td>
        <td><div><?=$loan['FirstName']. " " .$loan['LastName']?></div></td>
        <td>
          <div>
            <?php
            $hidden = array('Id' => $loan['Id']);
            echo form_open('/DeviceManage/viewLoanTransactions', '', $hidden); ?>
              <button style="color: var(--lib-orange); font-size: 18px;" type="submit">View</button>
            </form>
          </div>
        </td> <!--Open in a new page, printing can be possible there-->
      </tr>
    <?php
        } ?>
    </tbody>
  </table>
</div>
