<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="right-seller-content">
	<div class="p-sellerorder-status">
			<div class="p-sellerorder-status-tit">
				<p>
					<a><?php echo $lang['member_order_state'].$lang['nc_colon'];?></a>
					<a class="p-seller-bold"><?php echo strip_tags($output['order_info']['state_info']); ?></a> 
					<a>订单号：</a>
					<a class="p-seller-bold"><?php echo $output['order_info']['order_sn']; ?></a>
					<a>下单时间：</a>
					<a class="p-seller-bold"><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></a>
				</p>
			</div>
			<div class="p-sellerorder-status-mes" >
				<!--
					<p class="mes-bold">2015-05-21&nbsp;&nbsp;14:15:32&nbsp;&nbsp;海关申报成功</p>
					<p>2015-05-21&nbsp;&nbsp;14:15:32&nbsp;&nbsp;随便</p>
					<p>2015-05-21&nbsp;&nbsp;14:15:32&nbsp;&nbsp;申报成功</p>
					<p>2015-05-21&nbsp;&nbsp;14:15:32&nbsp;&nbsp;申报成功</p>
					<p>2015-05-21&nbsp;&nbsp;14:15:32&nbsp;&nbsp;交易完成</p>
				-->
			</div>
	</div>
<!--	
	<p class="p-sellerordertop mb20">
		<span></span>
		<span class="ml50">订单号：<?php echo $output['order_info']['order_sn']; ?> </span>
		<span class="ml50">下单时间：<?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></span>
	</p>
-->	

<div class="sellerorderitem">
		<h3 class="sellertlefttitle"><?php echo $lang['member_show_order_info'];?></h3>
		<div class="sellerorderorder">
			<table class="table-sellerorderorder">
				<thead>
					<tr>
						<th width="40%">商品名称</th>
						<th width="50%">数量</th>
						<th width="10%">价格</th>
					</tr>
				</thead>
				<tbody>
				 <?php if(is_array($output['order_goods_list']) and !empty($output['order_goods_list'])) {
						foreach($output['order_goods_list'] as $val) {
				?>
					<tr>
						<td>
							<a target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>" class="a-buyerordername">
								<a target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>"><img src="<?php echo thumb($val,'tiny'); ?>" />
								<b>
								<?php echo $val['goods_name']; ?></a>
								</b>
							<!--	</br>
								<?php echo str_replace(':', $lang['nc_colon'], $val['spec_info']); ?> -->
							</a>
						</td>
						<td><?php echo $val['goods_num']; ?></td>
						<td><span class="spanyello"><?php echo $lang['currency'];?><?php echo $val['goods_price']; ?></span></td>
					</tr>
					<?php }  }  ?>
				</tbody>
			</table>
			<p class="table-sellerorde-p">
				<a><?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?></a><a class="table-seller-intime"><?php echo $output['order_info']['payment_name']; ?>&nbsp;</a>
				<a><?php echo $lang['member_order_time'].$lang['nc_colon'];?></a><a class="table-seller-time"><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></a>
				<a><?php echo $lang['member_show_order_pay_time'].$lang['nc_colon'];?></a><a class="table-seller-time"><?php if(!empty($output['order_info']['payment_time'])) echo date("Y-m-d H:i:s",$output['order_info']['payment_time']); ?></a>
				<a class="table-sellerorde-frm table-ding">¥<?php echo $output['order_info']['order_amount']; ?></a><a class="table-sellerorde-fr"><?php echo $lang['member_order_sum'].$lang['nc_colon'];?></a>
				
<?php if(!empty($output['order_info']['shipping_fee']) && $output['order_info']['shipping_fee'] != '0.00'){ ?>
			<a class="table-sellerorde-frm table-cms">¥<?php echo $output['order_info']['shipping_fee']; ?></a><a class="table-sellerorde-fr ">邮费：</a>		
			 <?php }else{?>
				<a class="table-sellerorde-free"><?php echo $lang['nc_common_shipping_free'];?></a>
<?php }?>	
			</p>
			<!-- <div class="table-footer-mes">
				<p >
					<a href="">申请售后</a>&nbsp;&nbsp;<a href="">评论商品</a>&nbsp;&nbsp;
					
						<a class="table-footer-red" href="">分享订单</a>&nbsp;&nbsp;
						
				</p>
			</div> -->
<!-- ******** -->
				<div style="overfolw:hidden; height:28px; padding-top:12px;">
				<p class="bdsharebuttonbox" data-tag="share_1" style="height:40px; float:right;padding-right: 18px">
			
					<a class="bds_more" data-cmd="more" style="font-size:14px; background:none;padding-left:0;margin:0; color:#e94a06; font-weight:bold">分享订单</a>
					
				</p>
				</div>
				<script>
					window._bd_share_config = {
						common : {
							bdText : "<?php echo $val['goods_name']; ?>--海品乐购--精选海外正品--乐享品质生活 ",	
							bdDesc : '海品乐购  精选海外正品 乐享品质生活',	
							bdUrl : "http://<?php echo $_SERVER['HTTP_HOST'] ?>/index.php?act=goods&goods_id=<?php echo $val['goods_id']?>", 	
							bdPic : "<?php echo thumb($val,'tiny'); ?>"
						},
						share : [{
							"bdSize" : 16
						}]
						
					}
					with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
				</script>
<!-- ********* -->


			<!--
			 <p class="p-sellerorderorderprice">
			 <?php if(!empty($output['order_info']['shipping_fee']) && $output['order_info']['shipping_fee'] != '0.00'){ ?>
				<?php echo $lang['store_show_order_tp_fee'];?>邮费 : <span><?php echo $lang['currency'];?><?php echo $output['order_info']['shipping_fee']; ?></span> 
			 <?php }else{?>
				<?php echo $lang['nc_common_shipping_free'];?>
			<?php }?>
			<?php echo $lang['member_order_sum'].$lang['nc_colon'];?><span class="spanyello"><?php echo $lang['currency'];?><?php echo $output['order_info']['order_amount']; ?></span></p>
			<?php if($val['refund_state']>0 && $val['refund_amount']>0) { ?>
				  &nbsp;&nbsp;<?php echo $lang['member_order_refund'].$lang['nc_colon'];?><b><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?></b>
			<?php } ?>
			<?php if($output['order_info']['payment_name']) { ?>
			<p class="p-sellerorderorder">
				<?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?><?php echo $output['order_info']['payment_name']; ?>
			</p>
			<?php } ?> 
			<?php if($output['order_info']['pay_message']) { ?>
			<p class="p-sellerorderorder">
				<?php echo $lang['member_show_order_pay_message'].$lang['nc_colon'];?>
				<?php $tmp = unserialize($output['order_info']['pay_message']);?>
				<?php if (is_array($tmp)){?>
				<?php if ($tmp['user']) echo $lang['pay_bank_user'].' '.stripslashes($tmp['user']);?>
				<?php if ($tmp['bank']) echo $lang['pay_bank_bank'].' '.stripslashes($tmp['bank']);?>
				<?php if ($tmp['account']) echo $lang['pay_bank_account'].' '.stripslashes($tmp['account']);?>
				<?php if ($tmp['num']) echo $lang['pay_bank_num'].' '.stripslashes($tmp['num']);?>
				<?php if ($tmp['date']) echo $lang['pay_bank_date'].' '.stripslashes($tmp['date']);?>
				<?php if ($tmp['order']) echo $lang['pay_bank_order'].' '.stripslashes($tmp['order']);?>
				<?php if ($tmp['extend']) echo $lang['pay_bank_extend'].' '.stripslashes($tmp['extend']);?>
				<?php }else{?>
				<?php echo $output['order_info']['pay_message']; ?>
			</p>
			<?php } ?>
			<?php } ?>	
			  
					<p class="p-sellerorderorder">
						<?php echo $lang['member_order_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?>
					</p>
					<?php if($output['order_info']['payment_time']) { ?>
					<p class="p-sellerorderorder">
						<?php echo $lang['member_show_order_pay_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['order_info']['payment_time']); ?>
					</p>
					<?php } ?>
					
					<?php if($output['order_info']['shipping_time']) { ?>
					<p class="p-sellerorderorder">
						<?php echo $lang['member_show_order_send_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['order_info']['shipping_time']); ?>
					</p>
					<?php } ?>
					<?php if($output['order_info']['finnshed_time']) { ?>
					<p class="p-sellerorderorder">
						<?php echo $lang['member_show_order_finish_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$output['order_info']['finnshed_time']); ?>
					</p>
					<?php } ?>  
					-->     
		</div>
	</div>
	<div class="sellerorderitem">
		<h3 class="sellertlefttitle"><?php echo $lang['member_show_order_shipping_info'];?></h3>
		<div class="sellerorderseller">
			<ul class="ul-sellerorderseller">
				<li><?php echo $lang['member_show_order_receiver'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['order_info']['true_name']; ?></span></li>
				<li><?php echo $lang['member_address_zipcode'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['order_info']['zip_code']; ?></span></li>
				<li><?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['order_info']['tel_phone']; ?></span></li>
				<li><?php echo $lang['member_address_mobile_num'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['order_info']['mob_phone']; ?></span></li>
				<?php if($output['order_info']['shipping_code']) { ?>
					<li><?php echo $lang['member_show_order_shipping_no'].$lang['nc_colon'];?>
					<span class="spanblack"><?php echo $output['order_info']['shipping_code']; ?>&nbsp;</span></li>
					<?php } ?>
					<?php if($output['order_info']['order_message']) { ?>
					<li><?php echo $lang['member_show_order_buyer_message'].$lang['nc_colon'];?>
					<span class="spanblack"><?php echo $output['order_info']['order_message']; ?>&nbsp;</span></li>
				<?php } ?>
				<li><?php echo $lang['member_show_order_receive_address'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['order_info']['area_info']; ?><?php echo $output['order_info']['address']; ?></span></li>
			</ul>
		</div>
	</div> 
    
    <!--物流追踪-->
    <?php if($output['routreorder']){?>
    <div class="sellerorderitem">
		<h3 class="sellertlefttitle"><?php echo $lang['member_show_order_shipping_code'];?></h3>
		<div class="sellerorderseller">
			<?php foreach($output['routreorder'] as $val){
			echo '发生地：'.$val['accept_address'].'&nbsp;&nbsp;&nbsp;<br/>信息：'.$val['remark'].'&nbsp;&nbsp;&nbsp;<br/>时间：'.$val['accept_time'].'<br/><br/>';
			
			}?>
		</div>
	</div> 
    <?php }?>
    <!--END追踪-->

	<div class="sellerorderitem">
		<h3 class="sellertlefttitle"><?php echo $lang['member_show_order_seller_info'];?>
		<?php if($order_info['examine'] == 0 && $order_info['order_state']>=20):?>
		<!--	<a style="float:right;font-size:16px;margin-right:20px;" href="index.php?act=store&op=order_declaration&order_id=<?php echo $order_goods_list[0]['order_id'];?>" >提交报关请求</a>-->
		<?php elseif($order_info['examine'] == 1&& $order_info['order_state']>=20):?>
			<!--<span style="float:right;font-size:16px;color:#ff6c00;padding-right:20px;">报关申请已成功提交，请耐心等候！！</span>-->
		<?php elseif($order_info['examine'] == 2 &&  $order_info['order_state']>=20):?>
			
			<?php if($order_info['deliver'] == 0 && $order_info['send_examine'] == 1 && $order_info['entry_examine']==2 ):?>
				<a style="float:right;font-size:16px;margin-right:20px;" href="index.php?act=store&op=deliver&id=<?php echo $order_goods_list[0]['order_id']; ?>" >提交发货请求</a>
			<?php elseif($order_info['deliver'] == 1 && $order_info['send_examine'] == 1 && $order_info['entry_examine']==2):?>
				<a style="float:right;font-size:16px;margin-right:20px;" href="javascript:void(0);" >已提交发货请求</a>			
			<?php elseif($order_info['deliver'] == 2 && $order_info['send_examine'] == 1 && $order_info['entry_examine']==2):?>
				<a style="float:right;font-size:16px;margin-right:20px;" href="javascript:void(0);" >已发货</a>
			<?php endif;?>
			
		<?php endif;?>
		</h3>
		<div class="sellerorderseller">
			<ul class="ul-sellerorderseller">
				<li><?php echo $lang['member_evaluation_store_name'].$lang['nc_colon'];?>
					<span class="spanblack">
						<?php echo $output['store_info']['store_name']; ?>
					</span>
				</li>
				<li>
				<?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?>
					<span class="spanblack">
						<?php echo $output['store_info']['store_tel']; ?>
					</span>
				</li>
				<li><?php echo $lang['member_address_location'].$lang['nc_colon'];?><span class="spanblack"><?php echo $output['store_info']['area_info']; ?></span></li>
				<li>QQ<?php echo $lang['nc_colon'];?><span class="spanblack"><?php echo $output['store_info']['store_qq']; ?></span></li>
			</ul>
		</div>
	</div>                
	
	<!--促销活动-->
	<div class="sellerorderitem">
		<h3 class="sellertlefttitle"><?php echo $lang['nc_promotion'];?></h3>
		<div class="sellerordersales">
		<?php if(!empty($output['order_info']['group_id'])){ ?>
        <span style="color:red"><?php echo $lang['nc_groupbuy'];?></span> <?php echo $output['group_name'];?> <a href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$output['order_info']['group_id'],'id'=>$output['order_info']['store_id']), 'groupbuy');?>" target="_blank"><?php echo $lang['nc_groupbuy_view'];?></a>
        <?php } ?>
        <?php if(!empty($output['order_info']['xianshi_id'])){ ?>
        <span style="color:red"><?php echo $output['order_info']['xianshi_explain'];?></span>
        <?php } ?>
        <?php if(!empty($output['order_info']['mansong_id'])){ ?>
        <span style="color:red"><?php echo $output['order_info']['mansong_explain'];?></span>
        <?php } ?>
        <?php if(!empty($output['order_info']['bundling_id'])){ ?>
        <span style="color:red"><?php echo $output['order_info']['bundling_explain'];?></span>
        <?php } ?>
		</div>
	</div>
	

    
    
    
    
    
		<?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
		<div class="sellerorderitem">
			<h3 class="sellertlefttitle"><?php echo $lang['member_show_order_handle_history'];?></h3>
			<div class="sellerorderhistory">
				<ul class="ul-sellerorderhistory">
					<?php foreach($output['refund_list'] as $val) { ?>
					<li>
					<?php if($val['operator'] == '') { ?>
						<?php echo $lang['member_show_system'];?>
					<?php } else { echo $val['operator']; } ?>
					<i><?php echo $lang['member_show_order_at'];?></i><?php echo date("Y-m-d H:i:s",$val['log_time']); ?> <b><?php echo $lang['member_show_order_cur_state'].$lang['nc_colon'];?></b><span class="spanblue"><?php echo $val['order_state']; ?></span><b><?php echo $lang['member_show_order_next_state'].$lang['nc_colon'];?></b><span class="spanyello"><?php echo $val['change_state']; ?></span>
					<?php if($val['state_info']!='') {  ?>
					<?php echo $lang['member_show_order_reason'].$lang['nc_colon'];?><span class="reason"><?php echo $val['state_info']; ?></span>
					<?php } ?>
					</li>                            
					<?php } ?>
				</ul>
			</div>
		</div>
	<?php } ?>
	 <?php if(is_array($output['order_log']) and !empty($output['order_log']) and $output['order_info']['seller_id'] == $_SESSION['member_id']) { ?>
		<div class="sellerorderitem">
			<h3 class="sellertlefttitle">操作历史</h3>
			<div class="sellerorderhistory">
				<ul class="ul-sellerorderhistory">
				 <?php foreach($output['order_log'] as $val) { ?>
					<li>
					<?php if($val['operator'] == '') { ?>
					<?php echo $lang['member_show_system'];?>
					<?php } else { echo $val['operator']; } ?>
					<i><?php echo $lang['member_show_order_at'];?></i>
					
					<?php echo date("Y-m-d H:i:s",$val['log_time']); ?> <b><?php echo $lang['member_show_order_cur_state'].$lang['nc_colon'];?></b>
					
					<span class="spanblue"><?php echo $val['order_state']; ?></span>
					
					<b><?php echo $lang['member_show_order_next_state'].$lang['nc_colon'];?></b><span class="spanyello"><?php echo $val['change_state']; ?></span>
					
					<?php if($val['state_info']!='') {  ?>
					<?php echo $lang['member_show_order_reason'].$lang['nc_colon'];?><span class="reason"><?php echo $val['state_info']; ?></span>
					<?php } ?>
					</li>                            
					<?php } ?>
				</ul>
			</div>
		</div>
	 <?php } ?>
	
	
		<?php if(is_array($output['return_list']) and !empty($output['return_list'])) { ?>
		<h3><?php echo $lang['member_order_return'];?></h3>
		<ul>
		<?php foreach($output['return_list'] as $val) { ?>
		<li><span><?php echo date("Y-m-d H:i:s",$val['add_time']); ?></span> <a href="index.php?act=return&type=return_sn&key=<?php echo $val['return_sn']; ?>"><?php echo $val['return_sn'];?></a> <?php echo $val['goods_name'];?> <span><?php echo $val['spec_info']; ?></span> <?php echo $lang['member_show_order_amount'].$lang['nc_colon'];?><strong><?php echo $val['goods_returnnum']; ?></strong> </li>
		<?php } ?>
		</ul>
		<?php } ?>
	
	
</div>