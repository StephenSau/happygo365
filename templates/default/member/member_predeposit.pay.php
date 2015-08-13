<div class="eject_con gselector">
  <div id="warning"></div>
  <form method="post" action="index.php?act=predeposit&op=rechargepay&id=<?php echo $_GET['id']; ?>" id="rechargepay_form">
    <input type="hidden" name="form_submit" value="ok"/>
    <dl style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:30px">
      <dt style="float:left"><?php echo $lang['predeposit_payment'].$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['payment_info']['payment_name']; ?>
        <input type="hidden" id="payment_sel" name="payment_sel" value="<?php echo $output['info']['pdr_payment']; ?>"/>
      </dd>
    </dl>
    <?php if ($output['info']['pdr_payment'] == 'offline'){?>

    <dl style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:30px">
      <dt style="float:left"><?php echo $lang['predeposit_recharge_huikuanname'].$lang['nc_colon']; ?></dt>
      <dd>
        <input  name="huikuan_name" type="text" id="huikuan_name" value="<?php echo $output['info']['pdr_remittancename']; ?>" maxlength="10" class="w100 text"/>
      </dd>
    </dl>
    <dl style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:45px">
      <dt style="float:left"><?php echo $lang['predeposit_recharge_huikuanbank'].$lang['nc_colon']; ?></dt>
      <dd>
        <input  name="huikuan_bank" type="text" id="huikuan_bank" value="<?php echo $output['info']['pdr_remittancebank']; ?>" maxlength="20" class="w200 text"/>
      </dd>
    </dl>
    <dl style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:45px">
      <dt style="float:left"><?php echo $lang['predeposit_recharge_huikuandate'].$lang['nc_colon']; ?></dt>
      <dd>
        <input type="text" class="text"  name="huikuan_date" id="huikuan_date" value="<?php echo @date('Y-m-d',$output['info']['pdr_remittancedate']); ?>"/>
      </dd>
    </dl>
    <?php }?>
    <dl style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:45px">
      <dt style="float:left"><?php echo $lang['predeposit_recharge_memberremark'].$lang['nc_colon'];?></dt>
      <dd>
        <textarea name="memberremark" rows="3" class="w300"  maxlength="150"><?php echo $output['info']['pdr_memberremark'];?></textarea>
      </dd>
    </dl>
    <dl class="bottom" style="height:50px;line-height:50px;font-size:14px;font-weight:bold;padding-left:170px;padding-top:40px;">
      <dt style="float:left">&nbsp;</dt>
      <dd>
        <input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>" />
      </dd>
    </dl>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#huikuan_date').datepicker({dateFormat: 'yy-mm-dd'});
	jQuery.validator.addMethod("notempty", function(value, element, param) {
		var payment_sel = $("#payment_sel").val();
		if(payment_sel == 'offline' && $.trim(value) == ''){
			return false;
		}else{
			return true;
		}
	}, "");
	$('#rechargepay_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        rules : {
            huikuan_name :{
            	notempty   :true
            },
            huikuan_bank : {
            	notempty   :true
            },
            huikuan_date : {
            	notempty   :true
            }
        },
        messages : {
            huikuan_name :{
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuannamenull_error'];?>'
            },
            huikuan_bank : {
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuanbanknull_error'];?>'
            },
            huikuan_date : {
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuandatenull_error'];?>'
            }
        }
    });
});
</script>