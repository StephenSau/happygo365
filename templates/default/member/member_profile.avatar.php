<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="buyercenterright font-mic">
  <div class="buyerdoneintop mb30">
  <div class="buyerdongtitle pr">
  <?php include template('member/member_submenu3');?>
</div>
<form method="post" enctype="multipart/form-data" id="profile_form" action="index.php?act=home&op=avatar">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />
  <div class="ncu-form-style left avatar_show">
    <dl>
      <dd><?php echo $output['avatarflash']; ?> </dd>
    </dl>
  </div>
</form>
</div>
</div>
