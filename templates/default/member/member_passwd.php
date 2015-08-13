<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="buyercenterright font-mic">
  <div class="buyerdoneintop mb30">
  <div class="buyerdongtitle pr"><?php include template('member/member_submenu3');?></div>
  
    <div class="buychange">
    <form method="post" id="password_form" name="password_form" action="index.php?act=home&op=passwd">
      <input type="hidden" name="form_submit" value="ok"  />
      <div class="passwordbuychange">
        <table class="table-passwordbuychange">
          <tr>
            <th><?php echo $lang['home_member_your_password'].$lang['nc_colon'];?></th>
            <td>
              <input class="inputtxt8 inputword" type="password" maxlength="40" name="orig_password" id="orig_password">
              <label for="orig_password" generated="true" class="error" style="color:red;"></label>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang['home_member_new_password'].$lang['nc_colon'];?></th>
            <td>
              <input class="inputtxt8 inputword" type="password" maxlength="40" name="new_password" id="new_password">
              <label for="new_password" generated="true" class="error" style="color:red;"></label>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang['home_member_ensure_password'].$lang['nc_colon'];?></th>
            <td>
              <input class="inputtxt8 inputword" type="password" maxlength="40" name="confirm_password" id="confirm_password">
              <label for="confirm_password" generated="true" class="error" style="color:red;"></label>
            </td>
          </tr>
          <tr>
            <th> </th>
            <td><input class="inputsub6 font-mic" type="submit" value="<?php echo $lang['home_member_submit'];?>"></td>
          </tr>
        </table>
      </div>
    </form>
    </div>

</div>
</div>
<script type="text/javascript">
$(function(){
    $('#password_form').validate({
         submitHandler:function(form){
            ajaxpost('password_form', '', '', 'onerror') 
        },   
        rules : {
            orig_password : {
                required : true
            },
            new_password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            confirm_password : {
                required   : true,
                equalTo    : '#new_password'
            }
        },
        messages : {
            orig_password : {
                required : '<?php echo $lang['home_member_old_password_null'];?>'
            },
            new_password  : {
                required   : '<?php echo $lang['home_member_new_password_null'];?>',
                minlength  : '<?php echo $lang['home_member_password_range'];?>'
            },
            confirm_password : {
                required   : '<?php echo $lang['home_member_ensure_password_null'];?>',
                equalTo    : '<?php echo $lang['home_member_diffent_password'];?>'
            }
        }
    });
});
</script> 
