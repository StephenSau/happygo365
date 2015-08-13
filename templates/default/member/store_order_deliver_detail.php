<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style type="text/css">
#container .layout {
	background: none !important;
	min-height: 100px!important;
}
</style>
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
<?php if ($output['order_info']['order_sn'] != ''){?>
<div class="order-num"><?php echo $lang['member_change_order_no'].$lang['nc_colon'];?><span><?php echo $output['order_info']['order_sn']; ?></span></div>
	<div class="logistics-dynamic">
		<div class="rsc-title"><?php echo $lang['member_show_express_ship_dstatus'];?></div>
		<div class="logistics-dynamic-content">
			<div class="tips">
				<span><?php echo $lang['member_change_order_no'].$lang['nc_colon'];?><em><?php echo $output['order_info']['order_sn']; ?></em></span>
				<span><?php echo $lang['member_order_time'].$lang['nc_colon'];?><em><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></em></span>
			</div>
			<!--
			<ul>
				<li><label>2014-12-13 14:50:20</label><span>卖家已发货</span></li>
				<li><label>2014-12-13 14:50:20</label><span>【广东东莞凤岗分部】的收件员【上海铭铂】已收件</span></li>
				<li><label>2014-12-13 14:50:20</label><span>【广东东莞凤岗分部】的收件员【上海铭铂】已收件</span></li>
				<li><label>2014-12-13 14:50:20</label><span>【广东东莞凤岗分部】的收件员【上海铭铂】已收件</span></li>
				<li><label>2014-12-13 14:50:20</label><span>【广东东莞凤岗分部】的收件员【上海铭铂】已收件</span></li>
				<li class="last-msg"><label>2014-12-13 14:50:20</label><span>【广东东莞凤岗分部】的收件员【上海铭铂】已收件</span></li>
			</ul>
			-->
			<div class="tips-more">
				<span><?php echo $lang['member_show_expre_type'];?></span>
				<span><?php echo $lang['member_show_express_ship_code'].$lang['nc_colon'];?><em><?php echo $output['order_info']['shipping_code']; ?></em></span>
				<span><?php echo $lang['member_show_expre_company'].$lang['nc_colon'];?><a target="_blank" href="<?php echo $output['e_url'];?>"><?php echo $output['e_name'];?></a></span>
			</div>
		</div>
	</div>
	<div class="order-details-msg">
		<div class="rsc-title"><?php echo $lang['member_show_order_info'];?></div>
		<div class="order-details-msg-content">
			<ul>
			<?php if(is_array($output['order_goods_list']) and !empty($output['order_goods_list'])) {
			    foreach($output['order_goods_list'] as $val) {
			?>
				<li>
					<a target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>"><img src="<?php echo thumb($val,'tiny'); ?>" onload="javascript:DrawImage(this,60,60);"/></a>
					<span class="owt"><?php echo $val['goods_name']; ?></span>
					<em><?php echo $val['goods_price']; ?> X <?php echo $val['goods_num']; ?></em>
				</li>
			<?php }}?>
			</ul>
			<div class="tips-more">
				<span><?php echo $lang['member_show_receive_info'].$lang['nc_colon'];?><em><?php echo $output['order_info']['area_info'];?>&nbsp;<?php echo $output['order_info']['address'];?>&nbsp;<?php echo $output['order_info']['zip_code']?>&nbsp;<?php echo $output['order_info']['true_name'];?>&nbsp;<?php echo $output['order_info']['tel_phone'];?>&nbsp;<?php echo $output['order_info']['mob_phone']; ?></em></span>
				<span><?php echo $lang['member_show_deliver_info'].$lang['nc_colon'];?><em><?php echo $output['daddress_info']['area_info']; ?>&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;<?php echo $output['daddress_info']['zip_code'];?>&nbsp;<?php echo $output['daddress_info']['seller_name'];?>&nbsp;<?php echo $output['daddress_info']['tel_phone'];?>&nbsp;<?php echo $output['daddress_info']['mob_phone'];?></em></span>
			</div>
		</div>
	</div>
</div>
<?php }else{?>
    <div class="logistics-dynamic">
	  <i>&nbsp;</i><span><?php echo $lang['nc_common_result_null'];?></span>
	</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 

<script>
$(function(){
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout: 1,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'bottom',
		offsetX: 5,
		offsetY: 0,
		allowTipHover: false
	});
      var_send = '<li><?php echo date("Y-m-d H:i:s",$output['order_info']['shipping_time']); ?>&nbsp;&nbsp;<?php echo $lang['member_show_seller_has_send'];?></li>';
	$.getJSON('index.php?act=member&op=get_express&e_code=<?php echo $output['e_code']?>&shipping_code=<?php echo $output['shipping_code']?>&t=<?php echo random(7);?>',function(data){
		if(data){
			data = var_send+data;
			$('#express_list').html(data).next().css('display','');
		}else{
			$('#express_list').html(var_send);
		}
	});
});
</script>