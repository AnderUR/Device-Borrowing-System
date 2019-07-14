<?php
if (isset($isAdmin)) :
  $manageUsers = '<div>' . anchor('auth/index', "Manage users") . '</div>';
else :
  $manageUsers = '';
endif;
?>
<div>
  <img style="width: 396px; height: 88px;" src="/LibServices/deviceLoan_assets/img/logotop.png" width="100px" />
  <div id="titleTop" class="logoTitle">
    <span><?= $locationText ?></span>
  </div>
  <div id="titleBottom" class="logoTitle">
    <span><?= $whatPage ?></span>
  </div>

  <ul id="employee" class="dropdown menu" data-dropdown-menu>
    <li>
      <a href="#"><?= $employee ?></a>
      <ul class="menu">
        <li>
          <?php echo form_open('/housekeeper/logout'); ?>
          <button id="logoutBtn" type="submit" name="href" value="/housekeeper/logout">Log out</button>
          </form>
        </li>
        <li>
          <?= $manageUsers; ?>
        </li>
        <li>
          <?= anchor('DeviceManage/documentation', '<img src="/LibServices/deviceLoan_assets/img/helpICO" />'); ?>
        </li>
      </ul>
    </li>
  </ul>
</div>