<script type="text/javascript">
$(document).ready(function(){
    var state = <?php echo empty($output['complain_info']['complain_state'])?0:$output['complain_info']['complain_state'];?>;
    if(state <= 10) {
        $("#state").addClass('buyercomplaintstep1');
    }
    if(state == 20 ){
        $("#state").addClass('buyercomplaintstep2');
        $("#state_appeal").addClass('buyercomplaintstep2');
    }
    if(state == 30 ){
        $("#state").addClass('buyercomplaintstep3');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('buyercomplaintstep3');
    }
    if(state == 40 ){
        $("#state").addClass('buyercomplaintstep4');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('green');
        $("#state_handle").addClass('buyercomplaintstep4');
    }
    if(state == 99 ){
        $("#state").addClass('buyercomplaintstep5');
        $("#state_appeal").addClass('green');
        $("#state_talk").addClass('green');
        $("#state_handle").addClass('green');
        $("#state_finish").addClass('buyercomplaintstep5');
    }
});
</script>

<div class="buyercomplaintlist">
	<h3 class="sellertlefttitle"><?php echo $lang['complain_progress'];?></h3>
	<div class="buyercomplaintstep">
		<!-- buyercomplaintstep2 这个样式为每一步相应的样式，例：这是第2步 -->
		<ul  id="state"class="ul-buyercomplaintstep">
			<li id="state_new" class="li-buyercomplaintstep1"><?php echo $lang['complain_state_new'];?></li>
			<li id="state_appeal"  class="li-buyercomplaintstep2"><?php echo $lang['complain_state_appeal'];?></li>
			<li id="state_talk"  class="li-buyercomplaintstep3"><?php echo $lang['complain_state_talk'];?></li>
			<li id="state_handle"  class="li-buyercomplaintstep4"><?php echo $lang['complain_state_handle'];?></li>
			<li id="state_finish"  class="li-buyercomplaintstep5"><?php echo $lang['complain_state_finish'];?></li>
		</ul>
	</div>
</div>							
<div class="buyercomplaintlist">
	<h3 class="sellertlefttitle"><?php echo $lang['order_detail'];?></h3>
	<div class="buyercomplaintgoods">
		<ul class="ul-buyercomplaintgoods">
			<li>
				<h4 class="h4-buyercomplaint"><?php echo $lang['order_message'];?></h4>
				<p><?php echo $lang['order_sn'].$lang['nc_colon'];?>
					<?php 
					if(($output['complain_info']['member_status'] == 'accuser'&&intval($output['complain_info']['complain_type']) === 1)||($output['complain_info']['member_status'] == 'accused'&&intval($output['complain_info']['complain_type'])===2)) {
					?>
					<a href="<?php echo SiteUrl;?>/index.php?act=member&op=show_order&order_id=<?php echo $output['order_info']['order_id'];?>"  target="_blank"> <?php echo $output['order_info']['order_sn'];?> </a>
					<?php } else { ?>
					<a href="<?php echo SiteUrl;?>/index.php?act=store&op=show_order&order_id=<?php echo $output['order_info']['order_id'];?>" target="_blank"> <?php echo $output['order_info']['order_sn'];?> </a>
					<?php } ?>
				</p>
				<p><?php echo $lang['order_datetime'].$lang['nc_colon'];?><?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']);?></p>
				<p><?php echo $lang['order_price'].$lang['nc_colon'];?><span class="spanyello"><?php echo $lang['currency'].$output['order_info']['order_amount'];?></span></p>
					<?php if(!empty($output['order_info']['voucher_price'])) { ?>
						<p><?php echo $lang['order_voucher_price'].$lang['nc_colon'];?>
							<span class="spanyello">
							<?php echo $lang['currency'].$output['order_info']['voucher_price'].'.00';?>
							</span>
						</p>
						<p><?php echo $lang['order_voucher_sn'].$lang['nc_colon'];?>
							<span class="spanyello">
							<?php echo $output['order_info']['voucher_code'];?>
							</span>
						</p>
					<?php } ?>
				<p><?php echo $lang['order_state'].$lang['nc_colon'];?><span class="spanblue"><?php echo $output['order_info']['order_state_text'];?></span></p>
			</li>									
			<li>
				<h4 class="h4-buyercomplaint"><?php echo $lang['order_seller_message'];?></h4>
				<p><?php echo $lang['order_shop_name'].$lang['nc_colon'];?>
					<a href="<?php echo SiteUrl;?>/index.php?act=show_store&id=<?php echo $output['order_info']['store_id'];?>"  target="_blank"> <?php echo $output['order_info']['store_name'];?> </a>
				</p>
				<!--<p>城市：上海</p>
				<p>联系电话：020-1234567</p>-->
			</li>
			<li>
				<h4 class="h4-buyercomplaint"><?php echo $lang['order_buyer_message'];?></h4>
				<p><?php echo $lang['order_buyer_name'].$lang['nc_colon'];?><?php echo $output['order_info']['buyer_name'];?></p>
					<!--<p>城市：上海</p>
				<p>联系电话：020-1234567</p>-->
			</li>
		</ul>
	</div>
</div>
