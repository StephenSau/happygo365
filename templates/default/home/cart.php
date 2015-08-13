<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/goods_cart.js" charset="utf-8"></script>
<style>
   .chk_all{vertical-align: middle}
    .span_all{vertical-align: middle}
</style>

<link href="<?php echo TEMPLATES_PATH;?>/css/home_cart.css" rel="stylesheet" type="text/css">

<ul class="flow-chart flow-ch">

  <li class="step a1" title="<?php echo $lang['cart_index_ensure_order'];?>"></li>

  <li class="step b2" title="<?php echo $lang['cart_index_ensure_info'];?>"></li>

  <li class="step c2" title="<?php echo $lang['cart_index_buy_finish'];?>"></li>

</ul>

<div class="content margin1"  style="width:1210px;">

        <?php 

		      if(is_array($output['cart_array']) and !empty($output['cart_array'])) {?>

        <?php	foreach($output['cart_array'] as $val) {?>

        <div class="cart-title">

          <h2><?php echo $lang['cart_step1_order_info_conf'];?></h2>

        </div>

        <table class="buy-table">

          <thead>

            <tr>
                <th>
                  <input type="checkbox" class="chk_all" checked="true" />
                  <input type="hidden" id="checkedall" value="" />
                  <span class="span_all">全选</span>
                </th>
              <th><?php echo $lang['cart_index_store_goods'];?>

                </th>

              <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?>

                </th>

              <th class="w120"><?php echo $lang['cart_index_amount'];?>

                </th>

			        <th class="w120"><?php echo $lang['cart_index_ems'];?>

                </th>

              <th class="w120"><?php echo $lang['cart_index_sum'];?>

                </th>              

              <th class="w120"><?php echo $lang['cart_index_handle'];?>

                </th>

            </tr>

          </thead>

          <tbody class="table-buywantadd">

            <tr>

              <th colspan="20" class="td-buywantshopsname"><?php echo $lang['cart_step1_store'].$lang['nc_colon'];?><a href="<?php if (isset($output['shop_array'][$val[0]['store_id']]) ) {echo 'index.php?'.$output['shop_array'][$val[0]['store_id']];} else { echo ncUrl(array('act'=>'show_store','id'=>$val[0]['store_id']),'store',$val[0]['store_domain']); };?>"><?php echo $val[0]['store_name']; ?></a></th>

            </tr>

            <?php foreach($val as $k=>$v) { ?>

            <tr id="cart_item_<?php echo $v['spec_id'];?>">
                <td>
                    <input class="goods_chk" type="checkbox" name="goods[]" value="<?php echo $v['goods_id']; ?>" checked />
                    <input type="hidden" id="<?php echo $v['goods_id'] ?>" value="<?php if($val[0]['total_ems'] > 50):?>

                    <?php echo number_format(ncPriceFormat($v['goods_total_price']),2); ?>

                    <?php else:?>

                      <?php echo number_format(ncPriceFormat($v['goods_all_prices']),2); ?>

                    <?php endif;?>" />
                </td>

              <td class="w70 td-buywantgoodsname td-bl">

                <!--<a class="a-buyerordername" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['goods_id']), 'goods');?>" target="_blank">-->

                <a class="a-buyerordername" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" target="_blank">

                  <img src="<?php echo thumb($v,'tiny');?>" alt="<?php echo $v['goods_name']; ?>" onload="javascript:DrawImage(this,60,60);" />

                  <b><?php echo $v['goods_name']; ?></b><?php echo $v['spec_info']; ?>

                </a>

              </td>

              <td class="salePrice"><?php echo $v['goods_store_price']; ?></td>

			        <td>

                <div class="buynumberbox">

                  <div class="buynumber">

                  <input id="input_item_<?php echo $v['cart_id']; ?>" value="<?php echo $v['goods_num']; ?>" orig="<?php echo $v['goods_num']; ?>" mold="" changed="<?php echo $v['goods_num']; ?>" onkeyup="change_quantity(<?php echo $v['store_id']; ?>, <?php echo $v['cart_id']; ?>, <?php echo $v['spec_id']; ?>, this , '<?php echo $v['ems']; ?>','<?php echo $v['goods_id'] ?>');" class="inputnum" type="text"  style=" *float: left;"/>

                  <a href="JavaScript:void(0);" onclick="decrease_quantity(<?php echo $v['cart_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="a-buynumberl">-</a>          

                  <a href="JavaScript:void(0);" onclick="add_quantity(<?php echo $v['cart_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="a-buynumberr" >+</a>

                  </div>

                </div>

              </td>

			        <td>

      				<?php if($val[0]['total_ems'] >= 50 ):?>

      				<span data-tax="<?php echo $v['ems'] ;?>" class="ems_<?php echo $v['goods_id']; ?> ems" id="ems_<?php echo $v['cart_id']?>"><?php echo ncPriceFormat($v['ems']*$v['goods_store_price']*$v['goods_num']); ?></span>

      			    <?php else:?>

      				<span data-tax="<?php echo $v['ems'] ;?>" id="ems_<?php echo $v['cart_id']?>" class="ems">0.00</span>

      				<?php endif;?>

      			  </td>

      			  <td>

      				<span class="span-buyerprice" >

      					<em id="item<?php echo $v['cart_id']; ?>_subtotal" class="subtotal">

      						<?php if($val[0]['total_ems'] > 50):?>

      							<?php echo number_format(ncPriceFormat($v['goods_total_price']),2); ?>

      						<?php else:?>

      							<?php echo number_format(ncPriceFormat($v['goods_all_prices']),2); ?>

      						<?php endif;?>

      					</em>

      				</span>

      			  </td>

      			  <td class="td-br">

                <a class="a-buywantadd" href="javascript:collect_goods('<?php echo $v['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a>|<a class="a-buywantadd" href="javascript:void(0)" onclick="drop_cart_item(<?php echo $v['store_id']; ?>, <?php echo $v['spec_id']; ?>);"><?php echo $lang['cart_index_del'];?></a>

              </td>

            </tr>

            <?php } ?>

          </tbody>

        </table>

          <?php $mansong = $output['mansong'][$val[0]['store_id']];?>

          <?php if($mansong['mansong_flag']) { ?>

        <dl class="cart-discount">

          <dt><?php echo $lang['cart_step1_youhui'];?><i></i></dt>

          <dd>          

              <?php foreach($mansong['mansong_rule'] as $rule) { ?>

            <?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>

                <?php if(!empty($rule['discount'])) { ?>

                <?php echo $lang['nc_comma'].$lang['nc_reduce'];?><?php echo ncPriceFormat($rule['discount']);?><?php echo $lang['nc_yuan'].$lang['nc_cash'];?>

                <?php } ?>

                <?php if(!empty($rule['shipping_free'])) { ?>

                <?php echo $lang['nc_comma'].$lang['nc_shipping_free'];?>

                <?php } ?>

                <?php if(!empty($rule['gift_name'])) { ?>

                <?php echo $lang['nc_comma'].$lang['nc_gift'];?><a href="<?php echo $rule['gift_link'];?>" target="_blank"><?php echo $rule['gift_name'];?></a>

                <?php } ?>

              <?php } ?>

          </dd>

        </dl>

          <?php }?>

<div class="mt14 buywantpayoff buywantpayoff-h">
	
      <a class="buywant-set inputsub5" href="index.php?act=cart&op=step1&store_id=<?php echo $v['store_id']; ?>">结算<!--<?php echo $lang['cart_index_input_ensure_order'];?> --></a>

	  <?php if($output['is_invitation']==1){?>            
	 <div class="burwan-discount-time">
		<a><?php echo $big_customer;?></a>
		<a><?php echo $lang['is_invitation'];?></a>
		<a class="burwan-a-bold"><?php echo C('predeposit_consume_discount')?>折</a> 
		&nbsp;&nbsp;
		<a>折扣结束时间：</a>
		<a class="burwan-a-think"><?php echo $predeposit_consume_month_endtime;?></a>
		
	 </div><?php }?>
     <div class="buywantp-p">
		
	 
          <?php echo $lang['cart_index_goods_sumary2'];?>:
          <a class="bur-yelow total_price"  id="cart<?php echo $v['store_id']; ?>_total">￥<?php echo number_format($val[0]['store_goods_all_price'],2);?></a>
          &nbsp;+&nbsp;
		  
		  <?php if($val[0]['total_ems'] < 50):?>
<?php echo $lang['cart_index_goods_ems'];?>:
      		<?php else:?>
      			<?php echo $lang['cart_index_goods_ems'];?>:
      		<?php endif;?>

          <a class="bur-yelow total_ems" id="cart<?php echo $v['store_id']; ?>_ems">￥<?php if($val[0]['total_ems'] > 50):?>
      					<?php echo  number_format(ncPriceFormat($val[0]['total_ems']),2); ?>
      				<?php else:?>
      					0.00
      				<?php endif;?></a>
          &nbsp;=&nbsp;
          <?php if($val[0]['store_all_price'] < 50):?>
				<?php echo $lang['cart_index_goods_sumary']."";?>:
				<?php else:?>
				<?php echo $lang['cart_index_goods_sumary'];?>:
		<?php endif;?>
          <a class="bur-yelow total_amount" id="cart<?php echo $v['store_id']; ?>_amount">￥<?php if($val[0]['total_ems'] > 50):?>
					<?php echo number_format($val[0]['store_all_price'],2); ?><?php else:?>
					<?php echo number_format($val[0]['store_goods_all_price'],2); ?>
					<?php endif;?>
					</a>
        
     </div>
    
</div>
<!--

        <div class="buywantpayoff mb14 mt14">
        <?php if(!$output['is_invitation']==1){?>      
          <p>
 			<?php echo $big_customer;?>
		    <?php echo $lang['is_invitation'];?>
			<span>
				<b>
					<?php echo C('predeposit_consume_discount')?>折                  
				</b>
				折扣结束时间： <?php echo $predeposit_consume_month_endtime;?>
			</span>
		  </p>
	<?php }?>       
		  <p>
		    <?php echo $lang['cart_index_goods_sumary2'];?>:
			<span>
				<b id="cart<?php echo $v['store_id']; ?>_total" class="total_price"><?php echo $val[0]['store_goods_all_price'];?></b></span></p>
			
			<p>
				<?php if($val[0]['store_all_price'] < 50):?>
				<?php echo $lang['cart_index_goods_sumary']."";?>:
				<?php else:?>
				<?php echo $lang['cart_index_goods_sumary'];?>:
				<?php endif;?>
				<span>	
					<b id="cart<?php echo $v['store_id']; ?>_amount" class="total_amount">	
					<?php if($val[0]['total_ems'] > 50):?>
					<?php echo $val[0]['store_all_price']; ?><?php else:?>
					<?php echo $val[0]['store_goods_all_price']; ?>
					<?php endif;?>
					</b>
				</span>
			</p> <p>
			<?php if($val[0]['total_ems'] < 50):?>
<?php echo $lang['cart_index_goods_ems'];?>:
      		<?php else:?>
      			<?php echo $lang['cart_index_goods_ems'];?>:
      		<?php endif;?>
      		<span>
      			<b id="cart<?php echo $v['store_id']; ?>_ems" class="total_ems">
      				<?php if($val[0]['total_ems'] > 50):?>
      					<?php echo  ncPriceFormat($val[0]['total_ems']); ?>
      				<?php else:?>
      					0.00
      				<?php endif;?>
      			</b>
      		</span>
      	  </p>
          </div>

-->
 
 <!--
          <div class="buywantpaybtn">

         <a href="index.php?act=cart&op=step1&store_id=<?php echo $v['store_id']; ?>" class="inputsub5" style="background-color:#ff6c00;color:#fff"><?php echo $lang['cart_index_input_ensure_order'];?></a>

         <a href="index.php" class="a-buywantpaygobtn"><?php echo $lang['cart_index_continue_shopping'];?></a>

          </div>
		  -->
		  

       


        <?php } } ?>

        <div class="contad pdt40">

          <a href="">

            <img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img4.jpg">

          </a>

        </div>

    <div class="full_module" >

      <!-- <div id="content" class="infocontent">

        <div id="top" class="infolist"></div>

        <span class="ad_middle"> 

        <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=16"></script> 

        </span><span class="ad_middle"> 

        <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=17"></script> 

        </span><span class="ad_middle"> 

        <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=18"></script> 

        </span><span class="ad_middle"> 

        <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=19"></script> 

        </span>

        <div id="bottom" class="infolist"></div>

      </div>

      <div class="clear"> </div>

      <p><a href="<?php echo SiteUrl;?>/index.php?act=store_adv&op=adv_buy"><?php echo $lang['cart_i_want_to_be_here']; ?></a></p> -->

    </div>

</div>

<!--

<div class="gotop">

    <a class="a-gotop" href="javascript:void(0);"></a>

</div>

-->

  <script type="text/javascript">

  // 回到顶部

  /*

  var agotop=$(".a-gotop");

  agotop.on({

    click:function(){

      $('html,body').animate({scrollTop:0},'slow');

    }

  });

  */
  //购买清单全选
$('.chk_all').click(function(){
    var checked=$(this).attr("checked");
    if(checked){
        $(this).parents(".buy-table ").find(".goods_chk").each(function(){
            $(this).attr("checked",true)
        })
        $("#checkedall").val("all");
    }else{
        $(this).parents(".buy-table ").find(".goods_chk").each(function(){
          $(this).attr("checked",false);
        })
        $("#checkedall").val("unall");
    }

    $(this).parents(".buy-table ")
          .find(".goods_chk")
          .eq(0)
          .parents("tr")
          .find(".inputnum").trigger("keyup");
})

 
$('.goods_chk').click(function(){
        //单个商品选择
        var aGoodsChk =  $(this).parents(".buy-table ").find(".goods_chk").length;
        var checkNum =  $(this).parents(".buy-table ").find(".goods_chk:checked").length;
        var aChkAll = $(this).parents(".buy-table ").find(".chk_all");
        if(checkNum == aGoodsChk){
          aChkAll.attr("checked",true);
          $("#checkedall").val("all");
        }else if(checkNum == 0){
          aChkAll.attr("checked",false);
          $("#checkedall").val("unall");
        }else{
          aChkAll.attr("checked",false);
           $("#checkedall").val("");
        }

        $(this).parents("tr").find(".inputnum").trigger("keyup");
})

  function toDecimal(x) {
      var f = parseFloat(x);
      if (isNaN(f)) {
          return;
      }
      f = Math.round(x*100)/100;
      return f;
  }

   //提交订单
      $('.inputsub5').click(function(){
         var href=$(this).attr("href");
         var goods_id='';
          $('.goods_chk').each(function(){
              var checked=$(this).attr("checked");
              if(checked) {
                  goods_id += ',' + $(this).val();
              }
          })
       goods_id=goods_id.replace(/^,/g,'');
          var url=href+'&goods_id='+goods_id;
          window.location.href=url;
          return false;
      })
  </script>

