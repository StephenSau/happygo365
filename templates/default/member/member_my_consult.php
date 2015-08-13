<?php defined('haipinlegou') or exit('Access Invalid!');?>


<div class="buyercenterright font-mic">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
			<?php include template('member/member_submenu3');?>
		</div>
	</div>
	
	<div class="buyerconsult">
		<ul class="ul-buyerconsult">
			<?php  if (count($output['list_consult'])>0){ ?>
			<?php foreach($output['list_consult'] as $consult){?>
			<li>
				<p class="p-buyerconsulttile">
					<span>
					<?php echo $lang['store_consult_list_consult_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$consult['consult_addtime']);?>
					</span>
					<a href="index.php?act=goods&goods_id=<?php echo $consult['goods_id']; ?>" target="_blank"><?php echo $consult['cgoods_name'];?>
					</a>
				</p>
				<div class="buyerconsultmain">
					<h4></h4>
					<div class="buyerconsultmainbox pr">
					<p><?php echo $lang['store_consult_list_consult_content'].$lang['nc_colon'];?></strong><span class="gray"><?php echo nl2br($consult['consult_content']);?></p>
					<i class="i-commaup"></i>
					<i class="i-commadown"></i>
					<?php if($consult['consult_reply'] != ""){?>
					  <tr>
						<td class="tl bdl"></td>
						<td class="tl"><strong><?php echo $lang['store_consult_list_reply_time'].$lang['nc_colon'];?></strong><span class="gray"><?php echo nl2br($consult['consult_reply']);?></span><span class="ml10 goods-time">(<?php echo date("Y-m-d H:i:s",$consult['consult_reply_time']);?>)</span></td>
						<td class="bdr"></td>
					  </tr>
					<?php }?>
					</div>
				</div>
			</li>
			<?php }?>			
		</ul>
	</div>
	<?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php }?>
	</div>
	 <?php  if (count($output['list_consult'])>0){ ?>
	<div class="store">
		<div class="pagination-store"><?php echo $output['show_page'];?></div>
	</div>
	<?php }?>
	
</div>