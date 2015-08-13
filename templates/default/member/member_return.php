<?php defined('haipinlegou') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

 

	<div class="buyerdoneintop mb30">

		<div class="buyerdongtitle pr">

			<?php include template('member/member_submenu3');?>

		</div>

		<form method="get" action="index.php">

        <input type="hidden" name="act" value="member_refund" />

        <input type="hidden" name="op" value="index" />

		<div class="buyerdongsearch">

			<ul class="ul-buyerdongsearch">

				<li class="li-ovflow fl">

					<p><?php echo $lang['return_order_add_time'].$lang['nc_colon'];?></p>

					<span><input type="text" class="inputtxt7" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /></span>

					<p>-</p>

					<span> <input id="add_time_to" type="text" class="inputtxt7"  name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /></span>

				</li>

				<li class="li-ovflow fl">

					<p>

						<select class="select1 font-mic" name="type">

							<option value="order_sn" <?php if($_GET['type'] == 'order_sn'){?>selected<?php }?>><?php echo $lang['return_order_ordersn']; ?></option>

							<option value="return_sn" <?php if($_GET['type'] == 'return_sn'){?>selected<?php }?>><?php echo $lang['return_order_returnsn']; ?></option>

							<option value="store_name" <?php if($_GET['type'] == 'store_name'){?>selected<?php }?>><?php echo $lang['return_store_name']; ?></option>

					  </select>

				  </p>

					<span><input type="text" class="inputtxt6" name="key" value="<?php echo trim($_GET['key']); ?>" /></span>

				</li>									

				<li class="fr">

				<!--

					<p>订单状态</p>

					<span>

					<select class="select1 font-mic" name="state_type">

					<option value="all" <?php echo $_GET['state_type']=='all'?'selected':''; ?>><?php echo$lang['member_order_all'];?></option>

					<option value="order_pay" <?php echo $_GET['state_type']=='order_pay'?'selected':''; ?>><?php echo $lang['member_order_wait_pay'];?></option>

					<option value="order_pay_confirm" <?php echo $_GET['state_type']=='order_pay_confirm'?'selected':''; ?>><?php echo $lang['member_order_wait_confirm'];?></option>

					<option value="order_no_shipping" <?php echo $_GET['state_type']=='order_no_shipping'?'selected':''; ?>><?php echo $lang['member_order_wait_ship'];?></option>

					<option value="order_shipping" <?php echo $_GET['state_type']=='order_shipping'?'selected':''; ?>><?php echo $lang['member_order_shipped'];?></option>

					<option value="order_finish" <?php echo $_GET['state_type']=='order_finish'?'selected':''; ?>><?php echo $lang['member_order_finished'];?></option>

					<option value="order_cancal" <?php echo $_GET['state_type']=='order_cancal'?'selected':''; ?>><?php echo $lang['member_order_canceled'];?></option>

					<option value="order_refer" <?php echo $_GET['state_type']=='order_refer'?'selected':''; ?>><?php echo $lang['member_order_refer']; ?></option>

					<option value="order_confirm" <?php echo $_GET['state_type']=='order_confirm'?'selected':''; ?>><?php echo $lang['member_order_confirm']; ?></option>

					</select>

					</span>

					-->

					<span><input type="submit"value="<?php echo $lang['nc_search'];?>" class="inputsub4"></span>

				</li>

			</ul>

		</div>

	</div>

	<div class="buyerrefund">

		<table class="table-buyerrefund">

			<thead>

				<tr>

					<th width="15%"><?php echo $lang['return_order_ordersn'];?></th>

					<th width="15%"><?php echo $lang['return_order_returnsn'];?></th>

					<th width="20%"><?php echo $lang['member_order_store_name'];?></th>

					<th width="15%"><?php echo $lang['return_order_return'];?></th>

					<th width="15%"><?php echo $lang['return_order_add_time'];?></th>

					<th width="10%"><?php echo $lang['return_state'];?></th>

					<th width="10%"><?php echo $lang['nc_handle'];?></th>

				</tr>

			</thead>

			<tbody>

			 <?php if (is_array($output['return_list']) && !empty($output['return_list'])) { ?>

			 <?php foreach ($output['return_list'] as $key => $val) { ?>

				<tr>

					<td><a href="index.php?act=member&op=show_order&order_id=<?php echo $val['order_id']; ?>" target="_blank"><?php echo $val['order_sn'];?></a></td>

					<td><?php echo $val['return_sn']; ?></td>

					<td>

						<a class="a-buyerrefundname" href="index.php?act=show_store&id=<?php echo $val['store_id']; ?>" target="_blank" title="<?php echo $val['store_name']; ?>"><?php echo $val['store_name']; ?></a>

						<!--<span class="span-buyerrefundname">李小美</span>-->

					</td>

					<!--

					<td><span class="span-buyerrefundprice"><em class="goods-price" title="<?php echo $val['return_paymentname']; ?>"><?php echo $val['order_return'];?></em></span></td>-->

					<td><strong><?php echo $val['return_goodsnum'];?></strong></td>

					<td><?php echo date("Y-m-d H:i:s",$val['add_time']);?></td>

					<td>
						<?php
						if($val['return_state']==1){ ?>
								<span class="span-buyerrefundcg" style="color:#CFA71B"><?php echo $output['state_array'][$val['return_state']]; ?></span>
						<?php }elseif($val['return_state']==2){ ?>
								<span class="span-buyerrefundcg" style="color:#56AA05"><?php echo $output['state_array'][$val['return_state']]; ?></span>
						<?php }elseif($val['return_state']==3){ ?>
 								<span class="span-buyerrefundcg" style="color:#ED1C24"><?php echo $output['state_array'][$val['return_state']]; ?></span>
						<?php }else{ ?>
								<span class="span-buyerrefundcg" style="color:#333333"><?php echo $output['state_array'][$val['return_state']]; ?></span>
						<?php }
						?>
						
					</td>

					<td>

					<a class="a-buyerrefunddone" href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['nc_view'];?>" dialog_id="member_order_return" dialog_width="400" uri="index.php?act=member_return&op=view&return_id=<?php echo $val['return_id']; ?>"> <?php echo $lang['nc_view'];?> </a>

					</td>

					

				</tr>

			<?php }?>

			 </tbody>

			<?php }else{?>

			<tbody>

				<tr>

					<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

				</tr>

			</tbody>

			<?php } ?>

		</table>

	</div>

<?php  if (count($output['cash_list'])>0) { ?>

	<div class="store">

		<div class="pagination-store"><?php echo $output['show_page'];?></div>

	</div>

<?php } ?>

	

</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 

<script type="text/javascript">

	$(function(){

	    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});

	    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});

	});

</script> 

