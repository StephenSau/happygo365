<div id="warning" class="warning" style="display:none;"></div>

<div class="buywantsite">

  <div class="buywantsitetop">

    <h3><?php echo $lang['cart_step1_receiver_address'];?></h3>

    <p>

       <!-- <a class="a-newbuywantsite" href="JavaScript:void(0);" id="span_newaddress" onclick="shownewaddress();"><?php echo $lang['cart_step1_new_address']; ?></a>

        <a href="index.php?act=member&op=address" target="_blank" class="a-managebuywantsite"><?php echo $lang['cart_step1_manage_receiver_address'];?></a>
		-->
		<a href="index.php?act=member&op=address" target="_blank" class="a-managebuywantsite"><?php echo $lang['cart_step1_manage_receiver_address'];?></a>
    </p>

  </div>

  <div id="addressone_model" style="display:none;">

    <ul class="receive_add address_item">

      <li class="goto"><?php echo $lang['cart_step1_receiver_jsz'];?></li>

      <li address="" buyer=""></li>

    </ul>

  </div>

  <div id="addresslist" class="buywantsitemain buy_main_bottom" style="position:relative; top:0;left:0;">
    <?php foreach((array)$output['address_list'] as $k=>$val){ ?>

    <ul class="receive_add address_item <?php if ($k == 0) echo 'selected_address'; ?> buywantsitemain_ul_bor">

      <li class="goto">

        <?php if ($k == 0) echo $lang['cart_step1_receiver_jsz'];else echo '&nbsp;';?>

      </li>

      <li class="goto_address_a" address="<?php echo $val['area_info']; ?>&nbsp;&nbsp;<?php echo $val['address']; ?>" buyer="<?php echo $val['true_name']; ?>&nbsp;&nbsp;<?php if($val['mob_phone']) echo $val['mob_phone']; else echo $val['tel_phone']; ?>" >
        <input id="address_<?php echo $val['address_id']; ?>" type="radio" city_id="<?php echo $val['city_id']?>" name="address_options" value="<?php echo $val['address_id']; ?>" <?php if ($k == 0) echo 'checked'; ?>/>
        <span>&nbsp;&nbsp;<?php echo $val['area_info']; ?>&nbsp;&nbsp;<?php echo $val['address']; ?>&nbsp;&nbsp; <?php echo $val['true_name']; ?><?php echo $lang['cart_step1_receiver_shou'];?>&nbsp;&nbsp;
        <?php if($val['mob_phone']) echo $val['mob_phone']; else echo $val['tel_phone']; ?></span>	
    		<a class="df_addr" data-id="<?php echo $val['address_id'];?>" style="display:<?php echo $k ==0 ? "block":"none";?>"><?php echo $k == 0 ? "默认地址" : "设置默认地址"?></a>
    		<a class="xiu_addr" style="display:<?php echo $k ==0 ? "block":"none";?>" href="javascript:void(0);" dialog_id="my_address_edit" dialog_width="740" dialog_title="编辑地址" nc_type="dialog" uri="index.php?act=member&op=address&type=edit&id=<?php echo $val['address_id'];?>">修改本地址</a>
    </li>
<!--last-->	  
	   <!-- <li class="goto_address_b" address="<?php echo $val['area_info']; ?>&nbsp;&nbsp;<?php echo $val['address']; ?>" buyer="<?php echo $val['true_name']; ?>&nbsp;&nbsp;<?php if($val['mob_phone']) echo $val['mob_phone']; else echo $val['tel_phone']; ?>"  id="goto_address_b">
             <input id=" " type="radio" />
             <span>&nbsp;&nbsp;<?php echo $val['area_info']; ?>&nbsp;&nbsp;<?php echo $val['address']; ?>&nbsp;&nbsp; <?php echo $val['true_name']; ?><?php echo $lang['cart_step1_receiver_shou'];?>&nbsp;&nbsp;
             <?php if($val['mob_phone']) echo $val['mob_phone']; else echo $val['tel_phone']; ?></span>    
           </li> -->
	 
    </ul>

    <?php } ?>

    <input type="hidden" id="chooseaddressid" name="chooseaddressid" value='<?php echo $output['address_list'][0]['address_id'];?>'/>
	
  </div>
  <div class="use_new_addr"><a href="javascript:void(0)"  nc_type="dialog" dialog_title="新增地址" dialog_id="my_address_add" uri="index.php?act=member&amp;op=address&amp;type=add" dialog_width="740" title="新增地址">使用新地址</a></div>
</div>

  <?php if(is_array($output['cart_array']) and !empty($output['cart_array'])) {?>

  <div class="cart-title">

    <h2><?php echo $lang['cart_step1_order_info'];?></h2>

  </div>

  <table class="buy-table">

    <thead>

      <tr>

        <th><?php echo $lang['cart_index_store_goods'];?>

          </th>

        <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?>

          </th>

        <th class="w120"><?php echo $lang['cart_index_amount'];?>

          </th>        

		    <th class="w120"><?php echo $lang['cart_index_ems'];?>

          </th>

        <th class="w120"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?>

          </th>

        <!--<th class="w120"><?php echo $lang['cart_index_handle'];?>

          </th>-->

      </tr>

    </thead>

    <tbody class="table-buywantadd">

      <tr>

        <th colspan="20" class="td-buywantshopsname"><?php echo $lang['cart_step1_store'].$lang['nc_colon'];?><a target="_blank" href="<?php  if (isset($output['shop_array'][$output['cart_array'][0]['store_id']]) ) {echo 'index.php?'.$output['shop_array'][$output['cart_array'][0]['store_id']];} else { echo ncUrl(array('act'=>'show_store','id'=>$output['cart_array'][0]['store_id']),'store',$output['cart_array'][0]['store_domain']); };?>"><?php echo $output['cart_array'][0]['store_name']; ?></a></th>

      </tr>

      <?php foreach($output['cart_array'] as $v) { ?>

      <tr id="cart_item_<?php echo $v['spec_id'];?>">

        <td class="w70 td-buywantgoodsname td-bl">

          <a class="a-buyerordername" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['goods_id']), 'goods');?>" target="_blank">

            <img src="<?php echo thumb($v,'tiny');?>" alt="<?php echo $v['goods_name']; ?>" onload="javascript:DrawImage(this,60,60);" />

            <b><?php echo $v['goods_name']; ?></b><?php echo $v['cart_spec_info']; ?>

          </a>

        </td>

        <td><?php echo $v['spec_goods_price']; ?></td>

        <td class="tc"><?php echo $v['goods_num']; ?></td>

        <td class="tc">

			<?php if($cart_array[0]['total_ems'] > 50):?>

				<?php echo $v['ems']; ?>

			<?php else:?>

					0.00

			<?php endif;?> 

		</td>

    <td>

			<span class="span-buyerprice">

				<em id="item<?php echo $v['cart_id']; ?>_subtotal">

					<?php if($output['cart_array'][0]['total_ems'] > 50):?>

						<?php echo $v['goods_all_price']; ?>

					<?php else:?>

						<?php echo $v['spec_goods_price']; ?>

					<?php endif;?>

				</em>

			</span>

    </td>
<!--
    <td class="td-br">

        <a class="a-buywantadd" href="javascript:collect_goods('<?php echo $v['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a>|<a class="a-buywantadd" href="javascript:void(0)" onclick="drop_cart_item(<?php echo $v['store_id']; ?>, <?php echo $v['spec_id']; ?>);"><?php echo $lang['cart_index_del'];?></a>

      </td>
-->
      </tr>

      

      <?php if ($v['goods_transfee_charge']==0){?>

      <?php if ($v['py_price']==0 && $v['kd_price']==0 && $v['es_price']==0 && $v['transport_id'] == 0){?>

      <?php }else{?>

      <?php $if_free = false;?>

      <?php if ($v['transport_id'] > 0){?>

      <?php $g_tid .=','.$v['transport_id'];?>

      <?php $g_num .=','.$v['goods_num'];?>

      <?php }else{?>

      <?php $g_trans .= ','.$v['py_price'].'_'.$v['kd_price'].'_'.$v['es_price'];?>

      <?php }?>

      <?php }?>

      <?php }?>

      <?php }?>

    </tbody>

<tfoot>
      <tr class="buywantshopssentmess">
		
        <td  class="tl buy-mess-td" colspan="6" >
		<div style="  width:663px; float:left;padding-top:27px;  padding-left:28px; height:75px; border-right:1px solid #E8E8E8;">
			<script type="text/javascript">function postscript_activation(tt){if (!tt.name){tt.value = '';tt.name = 'order_message';}}</script>
			  <label><?php echo $lang['cart_step1_message_to_seller'].$lang['nc_colon'];?>
				<input type="text" id="postscript" onclick="postscript_activation(this);" value="<?php echo $lang['cart_step1_message_advice'];?>" maxlength="200" class="text  " style="width:550px;" />
			  </label>
          <?php }?>
		 </div>
		 <div style="width:380px; float:left; padding-top:27px;  padding-left:40px;  position:relative; top:7px; left:0;">
		<i class="transport-ico"></i><span>
		 

		 
		  <?php if($output['bundling'] == 1){?>
         <?php echo floatval($output['bl_freight']) == 0?$lang['cart_step1_transport_fee']:$lang['cart_index_freight'].$lang['nc_colon']."&nbsp;".$lang['currency'].ncPriceFormat($output['bl_freight']);?></span>
		 
          <?php }else{?>
          <span><?php if ($if_free === false){?>
          <?php echo $lang['cart_step1_transport_type'].$lang['nc_colon'];?>
          <?php }?><?php if ($if_free !== false){?><?php echo $lang['cart_step1_transport_fee'];?><?php }else{?>
          <select name="transport_type"></select><?php }?></span><?php }?>
		  
          <em id="trans_total" class="cart-goods-price ml5 mr20"><?php echo $output['bundling'] == 1?(floatval($output['bl_freight']) == 0?'':ncPriceFormat($output['bl_freight'])):'';?></em>
		  </div>
		</td>
		
       

      </tr>
</tfoot>

  </table>

<input id="store_goods_price" type="hidden" value="<?php echo $output['store_goods_price'];?>" />

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/goods_cart.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/member.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/address.js" charset="utf-8"></script>

<script type="text/javascript">

//上传证件begin



$("#true_name").blur(function()

{

	//if( this.value=="" || ( this.value !=""  && ! /^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[a-zA-Z0-9])*$/.test(this.value) ) )

	if( this.value=="" || ( this.value !=""  && ! /^[\u4E00-\u9FA5\uF900-\uFA2D]*$/.test(this.value) ) )

	{

		$(this).next('span').addClass('error').text('请输入真实名字！');

		

	}else

	{

		$(this).next('span').removeClass().text('');

	}

})



//上传证件end
$(".selected_address").children().eq(0).addClass("buywantsitemain_gb");
$(".selected_address").siblings().find('#goto_address_a').addClass("selected_address_dis");

var SITE_URL = "<?php echo SiteUrl; ?>";

$(function(){
    /*地址选择的默认值*/
    $('.address_item').live('click',function(){
      var checked_address_radio = $(this).find("input[name='address_options']");
      var _next = $(this).find('li').eq(1);

      $(this).parent().find(".goto").removeClass("buywantsitemain_gb").html('&nbsp;');
      $(this).parent().find(".df_addr").hide();
      $(this).parent().find(".xiu_addr").hide();

      $(this).find(".df_addr").show();
      $(this).find(".xiu_addr").show()
      $(this).children().first().html('<?php echo $lang['cart_step1_receiver_jsz'];?>');
      $(this).children().first().addClass('buywantsitemain_gb');

      $(checked_address_radio).attr('checked', true);

      $('.address_item').removeClass('selected_address');

      $(this).addClass('selected_address');
  
      $("#chooseaddressid").val($(checked_address_radio).val());

      getTransport();

      $('#confirm_address').html($(_next).attr('address'));

      $('#confirm_buyer').html($(_next).attr('buyer'));

    });

    $('.voucheritem').live('click',function(){

        $(this).find("input[name='voucher_id']").attr('checked',true);

        $('.voucheritem').removeClass('selected_voucher');

        $(this).addClass('selected_voucher');

        getallprice();

    });



    <?php if (empty($output['address_list']) && $store_info['store_id']!=20){?>
      shownewaddress();

  <?php };?>

	$('select[name="transport_type"]').bind('change',function(){

		var value = $(this).val().split('|');

		$('#trans_total').html(value[1]);

		getallprice();

	});



	function getTransport(){

        var _select = $('.selected_address').find('li').eq(1);

        $('#confirm_address').html($(_select).attr('address'));
		
        $('#confirm_buyer').html($(_select).attr('buyer'));



        <?php if (!isset($g_tid) && !isset($g_trans)){?>

	    	$('select[name="transport_type"]').each(function(){

				var value = $(this).val().split('|');

				$('#trans_total').html(value[1]);

	    	});

			getallprice();

			return false;

        <?php }?>

		    var url = SITE_URL + '/index.php?act=cart&op=calc_buy&rand='+Math.random();

		    var area_id = $('input[name="address_options"]:checked').attr('city_id');

		    var hash = "<?php echo encrypt(implode('-',array(trim($g_tid,','),trim($g_num,','),trim($g_trans,','))),MD5_KEY.'CART');?>";

	      $.getJSON(url, {'hash':hash,'area_id':area_id}, function(data){

	    	if (data == null) return false;



    		var str = '';

    		if(typeof(data['py']) != 'undefined'){ str += '<option value="py|'+data['py']+'"><?php echo $lang['transport_type_py'];?> '+data['py']+'</option>';}

    		if(typeof(data['kd']) != 'undefined'){ str += '<option value="kd|'+data['kd']+'"><?php echo $lang['transport_type_kd'];?> '+data['kd']+'</option>';}

    		if(typeof(data['es']) != 'undefined'){ str += '<option value="es|'+data['es']+'">EMS '+data['es']+'</option>';}

    		if (str != ''){

    			$('select[name="transport_type"]').html(str);

    		}else{

    			$('select[name="transport_type"]').html('');

    		}

	    	$('select[name="transport_type"]').each(function(){

				var value = $(this).val().split('|');

				$('#trans_total').html(value[1]);

	    	});

			getallprice();

	    });

	}

	getTransport();



});



function getallprice(){

	var order_amount = 0;

    <?php if(!$output['rule_shipping_free']) { ?>

	if ($('#trans_total').html() != ''){

		order_amount += parseFloat($('#trans_total').html());

	}

    <?php } ?>

    order_amount += parseFloat($("#store_goods_price").val());

	var voucher = parseFloat($(".selected_voucher").children(".pay").children(".money").attr('value'));

	if(voucher > 0){

		order_amount = order_amount - voucher;

	}

	$('#order_amount').html(number_format(order_amount,2));

}


/*新增地址*/
function shownewaddress(){
  var msg = "";
	var addr_id = $("input[name='address_options']:checked").val();
  $.getJSON("index.php?act=ajax&op=bindinfo",function(data){
    if(data.status == 'succ'){
      msg = "<?php echo $lang['cart_step1_new_address'];?>";
    }else{
      msg = "完善个人资料";
    }

    ajax_form('newaddressform',msg, SITE_URL + '/index.php?act=cart&op=newaddress&addr_id='+addr_id,740);
  })
  

  return false;

}
function shownewpnum(){
	ajax_form('newpnumform','<?php echo $lang['cart_step1_new_pnum'];?>', SITE_URL + '/index.php?act=cart&op=newpnum',740);
    return false;
}



</script>