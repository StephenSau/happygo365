<?php defined('haipinlegou') or exit('Access Invalid!');?>


<div class="buyercenterright font-mic" style="text-align:center;">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
		 <?php include template('member/member_submenu3');?>
		</div>
		<div class="buyerdongsearch">
			<div class="collectallchoice">
			<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
				<p><input type="checkbox" id="all" class="checkall"/><label for="all">全选</label></p>
				<p><a href="javascript:void(0);" class="ncu-btn1" uri="index.php?act=member_favorites&op=delfavorites&type=goods" name="fav_id" confirm="<?php echo $lang['nc_ensure_del'];?>" nc_type="delete_collect"><span><?php echo $lang['nc_del'];?></span></a></p>
				<!--  展示方式 
				<div class="model-switch-btn" style="float:left;">
				<span><?php echo $lang['favorite_view_mode'].$lang['nc_colon'] ;?></span> 
				<a href="index.php?act=member_favorites&op=fglist&show=list" style="margin-right:10px;" title="<?php echo $lang['favorite_view_mode_list'];?>"><?php echo $lang['favorite_view_mode_list'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=pic" style="margin-right:10px;" title="<?php echo $lang['favorite_view_mode_pic'];?>"><?php echo $lang['favorite_view_mode_pic'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=store" title="<?php echo $lang['favorite_view_mode_shop'];?>"><?php echo $lang['favorite_view_mode_shop'];?></a>
				</div>
				-->
			 <?php }?>
			</div>
		</div>
	</div>
	<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
	<div class="collectgoods">
		<ul class="ul-collectgoods">
		 <?php foreach($output['favorites_list'] as $key=>$favorites){?>
			<li>
				<div class="collectgoodsborder pr">
					<a class="a-collectgoodsimg" href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><img src="<?php echo thumb($favorites['goods'],'small');?>" onload="javascript:DrawImage(this,160,160);" /></a>
					<div class="collectgoodsmess">

						<a class="a-collectgoodsname" href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank" >
						<?php echo mb_strcut($favorites['goods']['goods_name'],0,30,'utf-8');?>...
						</a>
						<p class="p-collectgoodsprice">￥<b><?php echo ncPriceFormat($favorites['goods']['goods_store_price']);?></b>
						<input type="checkbox" class="checkitem" style="float:right" value="<?php echo $favorites['goods']['goods_id'];?>"/>
						</p>
						<!--
						收藏人气
						<p>
						<?php echo $lang['favorite_popularity'].$lang['nc_colon'];?><?php echo $favorites['goods']['goods_collect'];?>
						</p>
						出售件数
						<p>
						<?php echo $lang['favorite_selled'].$lang['nc_colon'] ;?><?php echo $favorites['goods']['salenum'];?><?php echo $lang['piece'];?>
						</p>
						分享
						<a href="javascript:void(0)"  nc_type="sharegoods" data-param='{"gid":"<?php echo $favorites['goods']['goods_id'];?>"}' class="sns-share" title="<?php echo $lang['favorite_snsshare_goods'];?>">
						<?php echo $lang['nc_snsshare'];?></a>
						-->	
						
						
					</div>
					
					<a class="i-collectgoodsdele" href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');" title="<?php echo $lang['nc_del'];?>"></a>
				</div>
			</li>
			<?php }?>
		</ul>
	
	<?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span style="font-size:18px;font-weight:bold;"><?php echo $lang['no_record'];?><span></td>
      </tr>
    <?php }?>
	</div>
	 <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
        <div class="store">
			<div class="pagination-store"><?php echo $output['show_page'];?></div>
		</div>
      <?php }?>

</div>


<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/sns.js" charset="utf-8"></script>