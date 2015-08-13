<link href="<?php echo TEMPLATES_PATH;?>/css/home_cart.css" rel="stylesheet" type="text/css">

<style type="text/css">

#navBar {

	display: none !important;

}

</style>

<ul class="flow-chart flow-ch">

  <li class="step a1" title="<?php echo $lang['cart_index_ensure_order'];?>"></li>

  <li class="step b1" title="<?php echo $lang['cart_index_ensure_info'];?>"></li>

  <li class="step c2" title="<?php echo $lang['cart_index_buy_finish'];?>"></li>

</ul>

<form method="post" id="order_form" name="order_form" action="index.php?act=show_groupbuy&op=groupbuy_order">

  <input name="group_id" type="hidden" value="<?php echo $output['groupbuy_info']['group_id'];?>"/>

  <input name="spec_id" type="hidden" value="<?php echo $output['spec_id'];?>" />

  <input name="quantity" type="hidden" value="<?php echo $output['quantity'];?>"/>

  <div class="content margin1">

    <?php include template('home/groupbuy_shipping');?>

<script type="text/javascript">

function postscript_activation(tt){

    if (!tt.name)

    {

        tt.value    = '';

        tt.name = 'order_message';

    }



}

</script>

    <div class="cart-title">

      <h2><?php echo $lang['cart_step1_selected_goods'];?></h2>

    </div>

    <table class="buy-table">

      <thead>

        <tr>

          <th><?php echo $lang['cart_index_store_goods'];?>

            </th>

          <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?>

            </th>
            
              <th class="w120"><?php echo $lang['cart_index_ems'];?>

            </th>

          <th class="w120"><?php echo $lang['cart_index_amount'];?>

            </th>

          <th class="w120"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?>

            </th>

        </tr>

      </thead>

      <tbody class="table-buywantadd">

        <tr>

          <th colspan="20" class="td-buywantshopsname"><?php echo $lang['cart_step1_store'].$lang['nc_colon'];?><a href="<?php echo ncUrl(array('act'=>'show_store','id'=>$output['groupbuy_info']['store_id']), 'store');?>" target="_blank"><?php echo $output['groupbuy_info']['store_name']; ?></a></th>

        </tr>

        <tr>

          <td class="w70 td-buywantgoodsname td-bl">

             <a class="a-buyerordername" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$output['groupbuy_info']['group_id'],'id'=>$output['groupbuy_info']['store_id']), 'groupbuy');?>" target="_blank"><img src="<?php echo gthumb($output['groupbuy_info']['group_pic'],'small'); ?>" alt="<?php echo $output['groupbuy_info']['goods_name']; ?>" onload="javascript:DrawImage(this,60,60);"/>

             <b><?php echo $output['groupbuy_info']['goods_name']; ?></b>

              <?php if(!empty($output['spec_text'])) { ?>

              <?php echo $output['spec_text'];?>

              <?php } ?>

             </a>

          </td>

          <td><?php echo sprintf( "%01.2f ",$output['groupbuy_info']['groupbuy_price']); ?></td>
          
            <td class="tc">

			<?php if($total_ems >= 50):?>

				<?php echo $total_ems; ?>

			<?php else:?>

					0.00

			<?php endif;?> 

		</td>

          <td><?php echo $output['quantity']; ?><?php echo $lang['cart_index_jian'];?></td>

          <td><em><?php echo sprintf( "%01.2f ",(($output['groupbuy_info']['groupbuy_price'] * $output['quantity'])+$output['total_ems'])); ?></em></td>

        </tr>

      </tbody>

      <tfoot>

        <tr class="buywantshopssentmess">

          <td class="tl" colspan="2"><label><?php echo $lang['cart_step1_message_to_seller'].$lang['nc_colon'];?></label>

            <input type="text" class="w400 text" id="postscript" onclick="postscript_activation(this);" value="<?php echo $lang['cart_step1_message_advice'];?>" /></td><td class="tc"><?php echo $lang['cart_step1_transport_fee'];?>

          </td>

          <td class="tr" colspan="2"></td>

        </tr>

      </tfoot>

    </table>

    <div class="cart-bottom cart-bottom1">

      <div class="confirm-popup confirm-popup1">

        <div class="confirm-box buywantshopsmonycosttext">

          <dl>

            <dt><?php echo $lang['cart_step2_prder_price'];?></dt>

            <dd class="cart-goods-price-b">￥<em id="order_amount"><?php echo sprintf( "%01.2f ",(($output['groupbuy_info']['groupbuy_price'] * $output['quantity'])+$output['total_ems'])); ?></em></dd>

          </dl>

          <dl>

            <dt><?php echo $lang['cart_step2_prder_trans_to'];?></dt>

            <dd id="confirm_address"></dd>

          </dl>

          <dl>

            <dt><?php echo $lang['cart_step2_prder_trans_receive'];?></dt>

            <dd id="confirm_buyer"></dd>

          </dl>

        </div>

      </div>

      <div class="cart-buttons">

        <a href="javascript:void($('#order_form').submit());" class="cart-button"><?php echo $lang['cart_step1_finish_order_to_pay'];?></a>

      </div>

    </div>

    </div>

</form>

