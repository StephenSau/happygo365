<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="buyercenterright font-mic">
<div class="buyerdoneintop mb20">
  <div class="buyerdongtitle pr">
    <?php include template('member/member_submenu3');?>
  </div>
</div>
  <div class="buyrecharge">
  <form method="post" id="recharge_form" action="index.php?act=predeposit">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table-topup">
      <tbody>
        <tr>
          <th><i>*</i><?php echo $lang['predeposit_payment'].$lang['nc_colon']; ?></th>
          <td>
          <?php if (is_array($output['payment_array']) && count($output['payment_array'])>0){?>
            <select class="select4" name="payment_sel" id="payment_sel">
              <option value=""><?php echo $lang['nc_please_choose']; ?></option>
              <?php foreach ($output['payment_array'] as $k=>$v){?>
               <option value="<?php echo $v['payment_code'];?>" title="<?php echo $v['payment_info'];?>"><?php echo $v['payment_name'];?></option>
              <?php }?>
            </select>
          <?php }?>
          </td>
        </tr>
        <tr>
          <th><i>*</i><?php echo $lang['predeposit_recharge_price'].$lang['nc_colon']; ?></th>
          <td><input class="inputtxt5" type="text" name="price" id="price" maxlength="10"><?php echo $lang['currency_zh'];?> </td>
        </tr>
        <tr>
          <?php if (is_array($output['payment_array']) && count($output['payment_array'])>0){?>
          <?php foreach ($output['payment_array'] as $k=>$v){?>
          <?php if ($v['payment_code'] == 'offline'){?>
          <td><h3 class="_offline"><?php echo $v['payment_info'];?> </h3></td>
          <?php }?>
          <?php }?>
          <?php }?>
        </tr>
        <tr class="_offline">
          <th><i>*</i><?php echo $lang['predeposit_recharge_huikuanname'].$lang['nc_colon']; ?></th>
          <td><input name="huikuan_name" type="text" id="huikuan_name" maxlength="10" class="inputtxt5" /></td>
        </tr>
        <tr class="_offline">
          <th><i>*</i><?php echo $lang['predeposit_recharge_huikuanbank'].$lang['nc_colon']; ?></th>
          <td><input name="huikuan_bank" type="text" id="huikuan_bank" maxlength="20" class="inputtxt5" /></td>
        </tr>
        <tr class="_offline">
          <th><i>*</i><?php echo $lang['predeposit_recharge_huikuandate'].$lang['nc_colon']; ?></th>
          <td><input name="huikuan_date" type="text" id="huikuan_date" class="inputtxt5" /></td>
        </tr>
        <tr>
          <th><i>*</i><?php echo $lang['predeposit_recharge_memberremark'].$lang['nc_colon']; ?></th>
          <td><textarea class="textarea2" name="memberremark" rows="3" maxlength="150"></textarea></td>
        </tr>
        <tr>
          <th> </th>
          <td><input class="inputsub5" type="submit" value="提交"></td>
        </tr>
      </tbody>
    </table>
    </form>
  </div>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" ></script> 
<script type="text/javascript">
$(function(){
	$('#huikuan_date').datepicker({dateFormat: 'yy-mm-dd'});
	showofflinetr();
	$("#payment_sel").change(function(){ showofflinetr(); });
	jQuery.validator.addMethod("notempty", function(value, element, param) {
		var payment_sel = $("#payment_sel").val();
		if(payment_sel == 'offline' && $.trim(value) == ''){
			return false;
		}else{
			return true;
		}
	}, "");
	$('#recharge_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        rules : {
        	payment_sel      : {
	        	required  : true
	        },
        	price      : {
	        	required  : true,
	            number    : true,
	            min       : 0.01
            },
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
        	payment_sel      : {
                required:  '<?php echo $lang['predeposit_recharge_add_paymentnull_error']?>'
            },
            price		: {
            	required  :'<?php echo $lang['predeposit_recharge_add_pricenull_error']; ?>',
            	number    :'<?php echo $lang['predeposit_recharge_add_pricemin_error']; ?>',
                min    	  :'<?php echo $lang['predeposit_recharge_add_pricemin_error']; ?>'
            },
            huikuan_name :{
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuannamenull_error']; ?>'
            },
            huikuan_bank : {
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuanbanknull_error']; ?>'
            },
            huikuan_date : {
            	notempty   :'<?php echo $lang['predeposit_recharge_add_huikuandatenull_error']; ?>'
            }
        }
    });
});
function showofflinetr(){
	var payment_sel = $("#payment_sel").val();
	if(payment_sel == 'offline'){
		$("._offline").show();
	}else{
		$("._offline").hide();
	}
}
</script>