<?php defined('haipinlegou') or exit('Access Invalid!');?>



<div class="buyercenterright font-mic">

	<div class="buyerdoneintop mb20">

		<div class="buyerdongtitle pr">

			<?php include template('member/member_submenu3');?>

		</div>

	</div>

	<div class="buyrecharge">

	<form method="post" id="cash_form" action="index.php?act=predeposit&op=predepositcash">

	<input type="hidden" name="form_submit" value="ok" />

		<table class="table-topup">

			<tbody>

				<tr>

					<th><i>*</i><?php echo $lang['predeposit_payment'].$lang['nc_colon']; ?></th>

					<td>

					<?php if (is_array($output['payment_array']) && count($output['payment_array'])>0){?>

						<select class="select4" name="payment_sel" id="payment_sel">

							<option value="">

							<?php echo $lang['nc_please_choose']; ?></option>

							<?php foreach ($output['payment_array'] as $k=>$v){?>

							<option value="<?php echo $v['payment_code'];?>" title="<?php echo $v['payment_info'];?>"><?php echo $v['payment_name'];?>

							</option>

						<?php }?>

						</select>

					<?php }?>

					</td>

				</tr>

				<!--提现金额-->

				<tr>

					<th><i>*</i><?php echo $lang['predeposit_cash_price'].$lang['nc_colon']; ?></th>

					<td>

						<input type="text" class="inputtxt5" name="price" id="price" maxlength="10"><?php echo $lang['currency_zh'];?>

						<p class="p-topup"><b><?php echo $lang['predeposit_cash_price_tip'].$lang['nc_colon']; ?><?php echo $output['member_array']['available_predeposit']; ?>&nbsp;&nbsp;<?php echo $lang['currency_zh']; ?></b></p>

					</td>

				</tr>

				<!--收款银行-->

				<tr class="_offline">

					<th><i>*</i><?php echo $lang['predeposit_cash_shoukuanbank'].$lang['nc_colon']; ?></th>

					<td>

						<input type="text" class="inputtxt10" name="shoukuan_bank" id="shoukuan_bank" maxlength="20">

					</td>

				</tr>

				<!--收款人-->

				<tr class="_offline">

					<th><i>*</i><?php echo $lang['predeposit_cash_shoukuanname'].$lang['nc_colon'];?></th>

					<td>

						<input type="text" class="inputtxt10" name="shoukuan_name" id="shoukuan_name" maxlength="10">

					</td>

				</tr>

				<!--收款账号-->

				<tr>

					<th><i>*</i><?php echo $lang['predeposit_cash_shoukuanaccount'].$lang['nc_colon'];?></th>

					<td>

						<input type="text" class="inputtxt10" name="account" type="text" id="account" maxlength="20">

						<p class="p-topup">线上方式为例如支付宝的账号，线下方式为银行账号</p>

					</td>

				</tr>

				<tr>

					<th><?php echo $lang['predeposit_memberremark'].$lang['nc_colon'];?></th>

					<td><textarea class="textarea2"  name="memberremark" rows="3" maxlength="150"></textarea></td>

				</tr>

				<tr>

					<th> </th>

					<td><input type="submit" value="提交" class="inputsub5" value="<?php echo $lang['nc_submit']; ?>"></td>

				</tr>

			</tbody>

		</table>

		</form>

	</div>

</div>



<script type="text/javascript">

$(function(){

	//线下内容显示与隐藏

	showofflinetr();

	$("#payment_sel").change(function(){ showofflinetr(); });

	//表单验证

	jQuery.validator.addMethod("notempty", function(value, element, param) {

		var payment_sel = $("#payment_sel").val();

		if(payment_sel == 'offline' && $.trim(value) == ''){

			return false;

		}else{

			return true;

		}

	}, "");

	$('#cash_form').validate({

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

            shoukuan_name :{

            	notempty   :true

            },

            shoukuan_bank : {

            	notempty   :true

            },

            account      : {

	        	required  : true

	        }

        },

        messages : {

        	payment_sel      : {

                required:  '<?php echo $lang['predeposit_cash_add_paymentnull_error']; ?>'

            },

            price		: {

            	required  :'<?php echo $lang['predeposit_cash_add_pricenull_error']; ?>',

            	number    :'<?php echo $lang['predeposit_cash_add_pricemin_error']; ?>',

            	min    	  :'<?php echo $lang['predeposit_cash_add_pricemin_error']; ?>'

            },

            shoukuan_name :{

            	notempty   :'<?php echo $lang['predeposit_cash_add_shoukuannamenull_error']; ?>'

            },

            shoukuan_bank : {

            	notempty   :'<?php echo $lang['predeposit_cash_add_shoukuanbanknull_error']; ?>'

            },

            account      : {

	        	required  : '<?php echo $lang['predeposit_cash_add_shoukuanaccountnull_error']; ?>'

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