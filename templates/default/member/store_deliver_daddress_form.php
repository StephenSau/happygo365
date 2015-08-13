<?php defined('haipinlegou') or exit('Access Invalid!');?>



<div id="warning"></div>

<form method="post" action="index.php?act=deliver&op=daddress" id="address_form" target="_parent">

  <input type="hidden" name="form_submit" value="ok" />

  <input type="hidden" name="id" value="<?php echo $output['address_info']['address_id'];?>" />

  <ul>

    <li style="padding:10px 0 0 30px;">

      <span style="padding-left:12px"><i style="color:red;">*</i><?php echo $lang['store_daddress_receiver_name'].$lang['nc_colon'];?></span>

      <input type="text" class="text" name="seller_name" value="<?php echo $output['address_info']['seller_name'];?>"/>

    </li>

    <li style="padding:10px 0 0 30px;line-height:24px;">

      <span style="float:left;"><i style="color:red;">*</i><?php echo $lang['store_daddress_location'].$lang['nc_colon'];?></span>

      <div id="region">

        <input type="hidden" value="<?php echo $output['address_info']['city_id'];?>" name="city_id" id="city_id">

        <input type="hidden" name="area_id" id="area_id" value="<?php echo $output['address_info']['area_id'];?>" class="area_ids" />

        <input type="hidden" name="area_info" id="area_info" value="<?php echo $output['address_info']['area_info'];?>" class="area_names" />

        <?php if(!empty($output['address_info']['area_id'])){?>

        <span><?php echo $output['address_info']['area_info'];?></span>

        <input type="button" value="<?php echo $lang['nc_edit'];?>" class="edit_region" />

        <select style="display:none;margin-left:4px;">

        </select>

        <?php }else{?>

        <select>

        </select>

        <?php }?>

      </div>

    </li>

    <li style="padding:10px 0 0 30px;line-height:20px;">

      <span style="float:left;"><i style="color:red;">*</i><?php echo $lang['store_daddress_address'].$lang['nc_colon'];?></span>

      <input class="text" type="text" name="address" style="width: 466px;float:left;margin-left:4px" value="<?php echo $output['address_info']['address'];?>"/>

      <p><?php echo $lang['store_daddress_not_repeat'];?></p>

    </li>

    <li style="padding:10px 0 0 30px;">

      <span style="padding-left:30px;"><?php echo $lang['store_daddress_zipcode'].$lang['nc_colon'];?></span>

      <input type="text" class="text" name="zip_code" maxlength="6" value="<?php echo $output['address_info']['zip_code'];?>" />

    </li>

    <li style="padding:10px 0 0 30px;line-height:20px;">

      <span style="float:left;"><i style="color:red;">*</i><?php echo $lang['store_daddress_phone_num'].$lang['nc_colon'];?></span>

      <input type="text" class="text" name="tel_phone" value="<?php echo $output['address_info']['tel_phone'];?>" style="float:left;margin-left:4px;" />

      <em style="float:left">（电话号码和手机号码二选一）</em>

      <p><?php echo $lang['store_daddress_area_num'];?> - <?php echo $lang['store_daddress_phone_num'];?> - <?php echo $lang['store_daddress_sub_phone'];?></p>

    </li>

    <li style="padding:10px 0 0 30px;">

      <span><i style="color:red;">*</i><?php echo $lang['store_daddress_mobile_num'].$lang['nc_colon'];?></span>

      <input type="text" class="text" name="mob_phone" value="<?php echo $output['address_info']['mob_phone'];?>"/>

    </li>

    <li style="padding:10px 0 0 30px;">

      <span style="padding-left:30px"><?php echo $lang['store_daddress_company'].$lang['nc_colon'];?></span>

      <input type="text" class="text" name="company" value="<?php echo $output['address_info']['company'];?>"/>

    </li>

    <li style="padding:10px 0 0 30px;">

      <span style="padding-left:30px;"><?php echo $lang['store_daddress_content'].$lang['nc_colon'];?></span>

      <input class="text" style="width: 466px;" type="text" name="content" value="<?php echo $output['address_info']['content'];?>"/>

    </li>

    <li style="text-align:center;padding-top:20px;">

      <span>&nbsp;</span>

      <input type="submit" class="submit" value="<?php if($output['type'] == 'add'){?><?php echo $lang['store_daddress_new_address'];?><?php }else{?><?php echo $lang['store_daddress_edit_address'];?><?php }?>" />

    </li>

  </ul>

</form>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script> 

<script type="text/javascript">

  var SITE_URL = "<?php echo SiteUrl; ?>";

  $(document).ready(function(){

   regionInit("region");

   $('#address_form').validate({

     submitHandler:function(form){
      if($('#city_id').val() == ""){
         $('#city_id').val($('select[class="valid"]').eq(1).val());
      }
     

      ajaxpost('address_form', '', '', 'onerror');

    },

    errorLabelContainer: $('#warning'),

    invalidHandler: function(form, validator) {

     var errors = validator.numberOfInvalids();

     if(errors)

     {

       $('#warning').show();

     }

     else

     {

       $('#warning').hide();

     }

   },

   rules : {

    seller_name : {

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

    seller_name : {

      required : '<?php echo $lang['store_daddress_input_receiver'];?>'

    },

    area_id : {

      required : '<?php echo $lang['store_daddress_choose_location'];?>',

      min  : '<?php echo $lang['store_daddress_choose_location'];?>',

      checkarea  : '<?php echo $lang['store_daddress_choose_location'];?>'

    },

    address : {

      required : '<?php echo $lang['store_daddress_input_address'];?>'

    },

    zip_code : {

      digits : '<?php echo $lang['store_daddress_zip_code'];?>',

      minlength : '<?php echo $lang['store_daddress_zip_code']?>',

      maxlength : '<?php echo $lang['store_daddress_zip_code']?>'

    },

    tel_phone : {

      required : '<?php echo $lang['store_daddress_phone_and_mobile'];?>',

      minlength: '<?php echo $lang['store_daddress_phone_rule'];?>',

      maxlength: '<?php echo $lang['store_daddress_phone_rule'];?>'

    },

    mob_phone : {

      required : '<?php echo $lang['store_daddress_phone_and_mobile'];?>',

      minlength: '<?php echo $lang['store_daddress_wrong_mobile'];?>',

      maxlength: '<?php echo $lang['store_daddress_wrong_mobile'];?>',

      digits : '<?php echo $lang['store_daddress_wrong_mobile'];?>'

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

</script>