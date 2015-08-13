<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

<link href="<?php echo TEMPLATES_PATH;?>/css/home_cart.css" rel="stylesheet" type="text/css">

<style type="text/css">

#navBar {

	display: none !important;

}

</style>

<script type="text/javascript" >

$(function() {

$(".tabs-nav > li > a").mouseover(function(e) {

	if (e.target == this) {

		var tabs = $(this).parent().parent().children("li");

		var panels = $(this).parent().parent().parent().parent().children(".tabs-panel");

		var index = $.inArray(this, $(this).parent().parent().parent().find("a"));

	if (panels.eq(index)[0]) {

		tabs.removeClass("tabs-selected")

			.eq(index).addClass("tabs-selected");

		panels.addClass("tabs-hide")

			.eq(index).removeClass("tabs-hide");

		}

	}

}); 

});

</script>

<ul class="flow-chart flow-ch">

  <li class="step a1" title="<?php echo $lang['cart_index_ensure_order'];?>"></li>

  <li class="step b1" title="<?php echo $lang['cart_index_ensure_info'];?>"></li>

  <li class="step c2" title="<?php echo $lang['cart_index_buy_finish'];?>"></li>

</ul>

<div class="content margin2">

  <form action="index.php?act=payment&order_id=<?php echo $output['order']['order_id']; ?>" method="POST" id="goto_pay" >

    <div class="buygoodsallmess font-mic">

      <div class="buygoodsallmesstop">

        <p class="p-buygoodsallmesstopdp fl"><?php echo $lang['cart_step1_store'].$lang['nc_colon'];?><a href=""><?php echo $output['order']['store_name']; ?></a></p>

        <p class="p-buygoodsallmesstopdp fl"><?php echo $lang['cart_step2_store_user'].$lang['nc_colon'];?>

<a target="_blank" href="index.php?act=home&op=sendmsg&member_id=<?php echo $output['order']['seller_id']; ?>" class="message" title="<?php echo $lang['nc_message'];?>"><?php echo $output['order']['seller_name']; ?></a></p>

        <p class="fr"><?php echo $lang['cart_step2_time'].$lang['nc_colon'];?><?php echo date('Y-m-d:H:i:s',$output['order']['add_time']);?></p>

      </div>

      <div class="buygoodsallmessmain">

        <p><?php echo @implode('+',$output['goods_name']);?></p>

        <a href="index.php?act=member&op=show_order&order_id=<?php echo $output['order']['order_id']; ?>" target="_blank"><?php echo $lang['cart_step2_order_info'];?></a>

        <span class="order-price" title="<?php echo $lang['cart_step2_prder_price'];?>" id="order_amount"><b><?php echo ncPriceFormat($output['order']['order_amount']); ?></b></span>

      </div>

    </div>

	

    <?php if (empty($output['online_array']) && empty($output['offine_array'])){?>

    <div class="cart-title">

      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?>

        <?php if (C('payment')){?>

        <a href="index.php?act=home&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a>

        <?php }else{?>

        <?php echo $lang['cart_step2_paymentnull_4'];?>

        <?php }?>

        <?php echo $lang['cart_step2_paymentnull_3'];?></div>

    </div>

    <?php } else {?>

    <div class="buypadways font-mic">

      <h3><?php echo $lang['cart_step2_choose_method_to_pay'];?></h3>

      <div class="buypadwaysin">

        <?php if(!empty($output['offine_array'])){?>

        <!--<h4><?php echo $lang['cart_step2_offline_pay'];?></h4>-->

          <?php foreach($output['offine_array'] as $val){ ?>

          <p><input type="radio" id="payment_<?php echo $val['payment_code']; ?>" name="payment_id" value="<?php echo $val['payment_id']; ?>" extendattr="<?php echo $val['payment_code']; ?>"/>

          <img alt="<?php echo $val['payment_name']; ?>-<?php echo $val['payment_info']; ?>" title="<?php echo $val['payment_name']; ?>-<?php echo $val['payment_info']; ?>" src="api/payment/<?php echo $val['payment_code']; ?>/logo.gif" onload="javascript:DrawImage(this,125,50);"  />          

          <?php echo $val['payment_info']; ?>

          </p>

          <?php } ?>

        <?php }?>

        <div class="tabs-panel <?php if (!empty($output['offine_array'])){}else{?>tabs-hide<?php }?>">

          <div id="paymessagediv" style="display:none;" class="cart-paymessage"> <?php echo $lang['cart_step2_paymebankinfo'];?>

            <p>

              <span><?php echo $lang['pay_bank_user'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[user]" maxlength="30" value="" class="text w90">            

            </p>

            <p>

              <span><?php echo $lang['pay_bank_bank'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[bank]" maxlength="40" value="" class="text w200">            

              <?php echo $lang['pay_bank_bank_tips'];?>

            </p>

            <p>

              <span><?php echo $lang['pay_bank_account'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[account]" maxlength="30" value="" class="text w200">            

            </p>

            <p>

              <span><?php echo $lang['pay_bank_num'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[num]" maxlength="10" value="" class="text w60">            

            </p>

            <p>

              <span><?php echo $lang['pay_bank_order'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[order]" maxlength="20" value="" class="text w200">            

            </p>

            <p>

              <span><?php echo $lang['pay_bank_date'].$lang['nc_colon'];?></span>            

                <input type="text" name="offline[date]" maxlength="12" value="" class="text w90">            

            </p>

            <p>

              <span><?php echo $lang['pay_bank_extend'].$lang['nc_colon'];?></span>            

                <textarea name="offline[extend]" rows="2" class="textarea w200"></textarea>            

            </p>

          </div>

        <?php } ?>

        <div class="clear"></div>

        </div>

        <?php if(!empty($output['online_array'])){?>

        <!--<h4><?php echo $lang['cart_step2_online_pay'];?></h4>-->

          <?php foreach($output['online_array'] as $val) { ?>

          <p><input id="payment_<?php echo $val['payment_code']; ?>" type="radio" name="payment_id" value="<?php echo $val['payment_id']; ?>" extendattr="<?php echo $val['payment_code']; ?>"/>

          <img src="api/payment/<?php echo $val['payment_code']; ?>/logo.gif" alt="<?php echo $val['payment_name']; ?>-<?php echo $val['payment_info']; ?>" title="<?php echo $val['payment_name']; ?>-<?php echo $val['payment_info']; ?>" onload="javascript:DrawImage(this,125,50);" />

          <?php echo $val['payment_info']; ?>

          </p>

          <?php } ?>

        <?php }?>

      <p class="p-buypadwaysbtn"><a href="javascript:check();" class="cart-button a-cart-button font-mic submit1"><?php echo $lang['cart_step2_ensure_pay'];?></a></p> 

    </div>

  </form>

</div>

<div class="contad pdt40">

          <a href="">

            <img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img4.jpg">

          </a>

        </div>

</div>


<!--
<div class="gotop">

    <a class="a-gotop" href="javascript:void(0);"></a>

  </div>
-->
<script type="text/javascript">

function check(){

	var flag = false;

	$.each($("input[name='payment_id']"),function(i,n){

		if($(n).attr('checked')){

			flag = true;

			return false;

		}

	});

	if(flag){

		var code = $('input:radio[name="payment_id"]:checked').attr('extendattr');

		if(code=="offline" && ($('input[name="offline[user]"]').val() == '' || $('input[name="offline[account]"]').val() == '' || $('input[name="offline[num]"]').val() == '')){

			alert("<?php echo $lang['cart_step2_paymessage_nullerror']; ?>");

		}else{
			ajax_form('newtipsform','<?php echo $lang['cat_tips'];?>', SITE_URL + '/index.php?act=cart&op=tips',740);
   		
			//$('#goto_pay').submit();

		}

	}else{

		alert('<?php echo $lang['cart_step2_choose_pay_method'];?>');

	}

}



function showmsgdiv(){

	var code = $('input:radio[name="payment_id"]:checked').attr('extendattr');

	if(code=="offline"){

		$("#paymessagediv").show();	

	}else{

  		$("#paymessagediv").hide();

	}

}

$(function(){

	$('input:radio[name="payment_id"]').bind('change',showmsgdiv);

	$('#refresh').click(function(){

		$('#order_amount').load('index.php?act=cart&op=order_amout&order_id=<?php echo $output['order']['order_id']; ?>');

	});

});

// 回到顶部

  var agotop=$(".a-gotop");

  agotop.on({

    click:function(){

      $('html,body').animate({scrollTop:0},'slow');

    }

  });



</script>