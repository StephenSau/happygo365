<?php defined('haipinlegou') or exit('Access Invalid!');?>



<div class="buyercenterright font-mic">

	<div class="buyerdoneintop mb30">

		<div class="buyerdongtitle pr">

			<?php include template('member/member_submenu3');?>

		</div>

		<form method="get" action="index.php">

			<div class="buyerdongsearch">

			<input type="hidden" name="act" value="predeposit" />

			<input type="hidden" name="op" value="cashlist" />

				<ul class="ul-buyerdongsearch">

					<li class="li-ovflow fl">

						<p><?php echo $lang['predeposit_cashsn'].$lang['nc_colon'];?></p>

						<span><input type="text" class="inputtxt6"name="sn_search" value="<?php echo $_GET['sn_search'];?>"></span>

					</li>									

					<li class="fr">

						<p><?php echo $lang['predeposit_paystate'].$lang['nc_colon']; ?></p>

						<span>

							<select class="select1 font-mic" id="paystate_search" name="paystate_search">

								<option value="0"><?php echo $lang['nc_please_choose'];?></option>

								<?php if (is_array($output['cashpaystate']) && count($output['cashpaystate'])>0){?>

								<?php foreach ($output['cashpaystate'] as $k=>$v){?>

								<option value="<?php echo $k+1; ?>" <?php if($_GET['paystate_search'] == $k+1 ) { ?>selected="selected"<?php } ?>><?php echo $v;?></option>

							<?php }?>

							<?php }?>

							</select>

						</span>

						<span><input type="submit" value="<?php echo $lang['nc_search'];?>" class="inputsub4"></span>

					</li>									

					<li class="fr">

						<p><?php echo $lang['predeposit_payment'].$lang['nc_colon']; ?></p>

						<span>

							<select class="select1 font-mic" name="payment_search" id="payment_search">

								<option value=""><?php echo $lang['nc_please_choose'];?></option>

								<?php if (is_array($output['payment_array']) && count($output['payment_array'])>0){?>

								<?php foreach ($output['payment_array'] as $k=>$v){?>

								<option value="<?php echo $k;?>" <?php if($_GET['payment_search'] == $k) { ?>selected="selected"<?php } ?> title="<?php echo $v['payment_info'];?>"><?php echo $v['payment_name'];?></option>

							<?php }?>

							<?php }?>

							</select>

						</span>

					</li>

				</ul>

			</div>

		</form>

	</div>

	<div class="buyrecharge">

		<table class="table-buyrecharge">

			<thead>

				<tr>

					<th width="20%"><?php echo $lang['predeposit_cashsn']; ?></th>

					<th width="20%"><?php echo $lang['predeposit_addtime']; ?></th>

					<th width="15%"><?php echo $lang['predeposit_payment']; ?></th>

					<th width="20%"><?php echo $lang['predeposit_cash_price']; ?>(<?php echo $lang['currency_zh']; ?>)</th>

					<th width="10%"><?php echo $lang['predeposit_paystate']; ?></th>

					<th width="15%"><?php echo $lang['nc_handle'];?></th>

				</tr>

			</thead>

			<tbody>

			<?php  if (count($output['cash_list'])>0) { ?>

			<?php foreach($output['cash_list'] as $val) { ?>

				<tr>

					<td><?php echo $val['pdcash_sn'];?></td>

					<td><?php echo @date('Y-m-d',$val['pdcash_addtime']);?></td>

					<td><?php echo $output['payment_array'][$val['pdcash_payment']]['payment_name'];?></td>

					<td><?php echo $val['pdcash_price'];?></td>

					<td><?php echo $output['cashpaystate'][$val['pdcash_paystate']]; ?></td>

					<td>

					<!--<a class="a-buyrechargedone" href="javascript:void(0);" dialog_id="my_address_edit" dialog_width="550" dialog_title="<?php echo $lang['member_address_edit_address'];?>" nc_type="dialog" uri="index.php?act=predeposit&op=cashinfo&id=<?php echo $val['pdcash_id'];?>"><?php echo $lang['nc_view']; ?></a>-->
					<a href="index.php?act=predeposit&op=cashinfo&id=<?php echo $val['pdcash_id']; ?>"><?php echo $lang['nc_view']; ?></a>
						

					<?php if ($val['pdcash_paystate'] == 0){?>
						|
					  <a href="javascript:drop_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=predeposit&op=cashdel&id=<?php echo $val['pdcash_id']; ?>');" class="ncu-btn2 mt5"><?php echo $lang['nc_del_&nbsp'];?></a>

					  <?php }?>

					</td>

				</tr>

			<?php }?>

			<?php } else {?>

			<tr>

				<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

			</tr>

			<?php } ?>

			</tbody>

		</table>

	

	<?php  if (count($output['cash_list'])>0) { ?>

		<div class="store">

			<div class="pagination-store"><?php echo $output['show_page'];?></div>

		</div>

	<?php } ?>

	</div>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script> 

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>