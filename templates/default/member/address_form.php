<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="eject_con">
  <div class="adds">
    <div id="warning_error" style="font-size: 16px;color: red;padding-left: 50px;margin-top:10px"></div>
    <form method="post" action="index.php?act=member&op=address" id="address_form" target="_parent">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="id" value="<?php echo $output['address_info']['address_id'];?>" />
        <ul class="fill_in_content fill_in_content1 fill_in_content_address">
			<li>
			  <p class="title"><span>*</span><?php echo $lang['member_address_receiver_name'].$lang['nc_colon'];?></p>
			  <p class="fill_in">
			    <input type="text" class="inputtxt8" name="true_name" maxlength="20" id="consignee" class="text1 formvalidatedata" value="<?php echo $output['address_info']['true_name']?$output['address_info']['true_name']:''; ?>"/>
			    <span class="field_message explain"> <span class="field_notice"></span>
			    <label class="error field_notice" generated="true"></label>
			    </span></p>
			</li>
			<li>

			  <p class="title"><span>*</span><?php echo $lang['member_address_location'].$lang['nc_colon'];?></p>
			  <p class="fill_in"> <span id="region" class="group_validate">
			    
			    <input type="hidden" name="area_id" id="areaid_hidden" value="<?php echo $output['address_info']['area_id'];?>" class="area_ids"/>
			    <input type="hidden" name="area_info" id="area_info" value="<?php echo $output['address_info']['area_info'];?>" class="area_names" />
		
				<input type="hidden" name="city_id" value="<?php echo $output['address_info']['city_id'] ?>"  id="city_id" />
                <?php if(!empty($output['address_info']['area_id'])){?>
			    	<span><?php echo $output['address_info']['area_info'];?>	    	
			    	</span>
		            <input type="button" value="<?php echo $lang['nc_edit'];?>" class="edit_region" />
		            <select class="formvalidatedata" style="display:none;"> </select>
		            <?php }else{?>
		            <select class="formvalidatedata"> </select>
		        <?php }?>
		        	<span style="font-weight:normal;" class="field_message explain"><span class="field_notice"></span>
			    	<label class="error field_notice" generated="true"></label>
			    	</span>
		        	
			   </p>
			</li>
			<li>
			  <p class="title"><span>*</span><?php echo $lang['member_address_address'].$lang['nc_colon'];?></p>
			  <p class="fill_in">
			    <input class="inputtxt8" type="text" name="address" id="address" maxlength="80" class="text1 formvalidatedata" style="width:302px;" value="<?php echo $output['address_info']['address']?$output['address_info']['address']:''; ?>"/>
			    <span class="field_message explain"><span class="field_notice"><?php echo $lang['cart_step1_true_address'];?></span>
			    <label class="error field_notice" generated="true"></label>
			    </span></p>
			</li>
			<li>
			  <p class="title"><?php echo $lang['member_address_zipcode'].$lang['nc_colon'];?></p>
			  <p class="fill_in">
			    <input class="inputtxt8" type="text" name="zip_code" id="zipcode" class="text1" maxlength="6" value="<?php echo $output['address_info']['zip_code']?$output['address_info']['zip_code']:''; ?>"/>
			    <span class="field_message explain"><span class="field_notice"></span>
			    <label class="error field_notice" generated="true"></label>
			    </span></p>
			</li>
			<li>
			  <p class="title"><?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?></p>
			  <p class="fill_in">
			    <input class="inputtxt8" type="text" name="tel_phone" id="phone_tel" maxlength="15" class="text1 formvalidatedata" value="<?php echo $output['address_info']['tel_phone']?$output['address_info']['tel_phone']:''; ?>"/>
			  </p>
			</li>  
			<li>
			  <p class="title"><span>*</span><?php echo $lang['member_address_mobile_num'].$lang['nc_colon'];?></p>
			  <p class="fill_in">
			    <input class="inputtxt8" type="text" id="mob_phone" name="mob_phone" maxlength="11" class="text1 formvalidatedata" value="<?php echo $output['address_info']['mob_phone']?$output['address_info']['mob_phone']:''; ?>"/>
			    <span class="field_message explain"> <span class="field_notice"><?php echo $lang['cart_step1_telphoneormobile'];?></span>
			    <label class="error field_notice" generated="true"></label>
			    </span> </p>
			</li>
		</ul>
			<div class="clear"></div>
			<div class="submit" style="margin:0 auto 20px 120px">
				<!--<a class="submit inputsub6 padding1" href="javascript:void(0);" onclick="submitaddress()"><?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?> </a>-->
				<input id="submitadd" class="btn inputsub6" type="submit" value="<?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?>"/>
			</div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
var SITE_URL = "<?php echo SiteUrl; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').validate({
    	debug  : true,
    	submitHandler:function(form){
    		if($('#city_id').val() == ""){
    			$('#city_id').val($('select[class="valid"]').eq(1).val());
    		}
    		// form.submit();
    		ajaxpost('address_form', '', '', 'onerror');
    	},
       	errorLabelContainer : $("#warning_error"),
       	errorElement : "p",
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {		
               $('#warning').show();
           }else{
           	   $('#warning').hide();
           }
        },
        rules : {
            true_name : {
                required : true
            },
            area_id : {
                required : true,
                min   : 1,
                checkarea : true
            },
            address : {
                required : true
            },
			zip_code : {
				digits : true,
				minlength : 6,
				maxlength : 6
			},
            tel_phone : {
                required : check_phone,
                minlength : 6,
				maxlength : 20
            },
            mob_phone : {
                required : check_phone,
                minlength : 6,
				maxlength : 20,
                digits : true
            }
        },
        messages : {
            true_name : {
                required : '<?php echo $lang['member_address_input_receiver'];?>'
            },
            area_id : {
                required : '<?php echo $lang['member_address_choose_location'];?>',
                min  : '<?php echo $lang['member_address_choose_location'];?>',
                checkarea  : '<?php echo $lang['member_address_choose_location'];?>'
            },
            address : {
                required : '<?php echo $lang['member_address_input_address'];?>'
            },
			zip_code : {
				digits : '<?php echo $lang['member_address_zip_code'];?>',
				minlength : '<?php echo $lang['member_address_zip_code']?>',
				maxlength : '<?php echo $lang['member_address_zip_code']?>'
			},
            tel_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<?php echo $lang['member_address_phone_rule'];?>'
            },
            mob_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
				maxlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
                digits : '<?php echo $lang['member_address_wrong_mobile'];?>'
            }
        },
        groups : {
            phone:'tel_phone mob_phone'
        }
    });
});
function check_phone(){
    return ($('[name="tel_phone"]').val() == '' && $('[name="mob_phone"]').val() == '');
}
// function check_card(){
// 	var _card = $.trim($("#card").val());
	
// 	if(_card == "" || (_card != "" && !/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/.test(_card))){
		
// 		return true;
// 	}else{
// 		return false;
// 	}

// }

</script>