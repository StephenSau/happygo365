<div class="cart-bottom cart-bottom1">

  <div class="confirm-popup confirm-popup1">

    <div class="confirm-box buywantshopsmonycosttext">
    
		<?php if($output['is_invitation']==1){?>
		 <dl>
         <!--大客户-->
 		<dt><?php echo $big_customer;?></dt>
        <dt><?php echo $lang['is_invitation'];?></dt>

        <dd class="cart-goods-price-b"><?php echo C('predeposit_consume_discount')?>折</dd>
				<dd class="cart-goods-price-b">折扣结束时间：<?php echo $predeposit_consume_month_endtime;?></dd>
      </dl>
		<?php }?>


      <dl>

        <dt class="car-good-price-dt"><?php echo $lang['cart_step2_prder_price2'];?></dt>

        <dd class="cart-goods-price-b">￥<em id="order_amount"></em></dd>

      </dl>

      <dl>

        <dt class="con-addr-dt"><?php echo $lang['cart_step2_prder_trans_to'];?></dt>

        <dd id="confirm_address"></dd>

      </dl>

      <dl>

        	<dt class="con_buyer_dt"><?php echo $lang['cart_step2_prder_trans_receive'];?></dt> 

        <dd id="confirm_buyer"></dd>

      </dl>

    </div>

  </div>

  <div class="buywantpaybtn" style="margin-top:10px;">

   

    <a href="javascript:void(0)" id='submitToPay' class="inputsub5" style="background-color:#ff6c00;color:#fff"><?php echo $lang['cart_step1_finish_order_to_pay'];?></a> 

     <?php if($output['type'] != 'groupbuy'){?>

    <a href="index.php?act=cart" class="a-buywantpaygobtn" ><?php echo $lang['cart_step1_back_to_cart'];?></a>

    <?php }?>

  </div>

</div>

<script>

$(function(){

	$('#submitToPay').click(function(){

		ifsubmit = true;
<?php if ($store_info['store_id']!=20){?>
		if ($('input[name="address_options"]:checked').val() == null){

		    <?php if ($row['member_name']=='www'){?>

				alert('hahah');return false;

			<?php }else if(empty($output['address_list'])){?>
					
						shownewaddress();
				
			<?php }else{ ?>

				alert('<?php echo $lang['cart_step1_please_set_address'];?>');

			<?php }?>

			return false;

		}
	<?php }else{?>
	
		shownewpnum();
		return false;
		
	<?php }?>
		$('select[nc_type="sel_transport"]').each(function(){

			if($(this).val() == '' || $(this).val() == null){

				alert('<?php echo $lang['cart_step1_transport_none'];?>');

				ifsubmit = false;

			}

		});

		

		//增加身份验证

		$("input.check").each(function()

		{

			if($(this).val()=="")

			{

				$(this).next('span').addClass('error').text('不能为空');

			}else{

				$(this).next('span').removeClass('error').text('');

			}

		})

		var numError = $('form .error').length;

		if(numError)

		{

			ifsubmit = false;

		}

		if (ifsubmit == true){

			$('#order_form').submit();

		}

	});

});

</script>