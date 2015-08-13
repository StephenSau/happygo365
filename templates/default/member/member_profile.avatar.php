<?php defined('haipinlegou') or exit('Access Invalid!');?><style>.left{float:left;}.avatar_show{margin:80px;}.member_avatar{margin:100px 0 0 100px;}</style>
<div class="buyercenterright font-mic">
  <div class="buyerdoneintop mb30">
  <div class="buyerdongtitle pr">
  <?php include template('member/member_submenu3');?>
</div><!--
<form method="post" enctype="multipart/form-data" id="profile_form" action="index.php?act=home&op=avatar">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />
  <div class="ncu-form-style left avatar_show">
    <dl>
      <dd><?php echo $output['avatarflash']; ?> </dd>
    </dl>
  </div>    <div class="member_avatar left">	<img src="<?php if ($output['member_info']['member_avatar']!='') { echo ATTACH_AVATAR.DS.$output['member_info']['member_avatar']; } else { echo ATTACH_COMMON.DS.$GLOBALS['setting_config']['default_user_portrait']; } ?>" width="120" height="120" alt="" nc_type="avatar" />  </div>  <div style="clear:both;"></div>
</form>--><form method="post" enctype="multipart/form-data" id="profile_form" action="index.php?act=home&op=avatar">  <input type="hidden" name="form_submit" value="ok" />  <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />  <div class="ncu-form-style">    <dl><dd><img src="<?php if ($output['member_info']['member_avatar']!='') { echo ATTACH_AVATAR.DS.$output['member_info']['member_avatar']; } else { echo ATTACH_COMMON.DS.$GLOBALS['setting_config']['default_user_portrait']; } ?>" width="120" height="120" alt="" nc_type="avatar" /></dd>    </dl>    <dl>      <dd><?php echo $output['avatarflash']; ?> </dd>    </dl>  </div></form>
</div>
</div><script type="text/javascript">function updateavatar() {window.location='index.php?act=home&op=avatar&avatar=1';}</script> 

