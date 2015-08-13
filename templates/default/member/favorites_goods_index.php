<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="buyercenterright font-mic">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
				<?php include template('member/member_submenu');?>
		</div>
		<div class="buyerdongsearch"> 
		<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
			<div class="collectallchoice">
				<p><input id="all" class="checkall" type="checkbox"><?php echo $lang['nc_select_all'];?></p>
				<p><a href="javascript:void(0);"uri="index.php?act=member_favorites&op=delfavorites&type=goods" name="fav_id" confirm="<?php echo $lang['nc_ensure_del'];?>" nc_type="batchbutton"><?php echo $lang['nc_del'];?></a></p>
				<!--
				<span><?php echo $lang['favorite_view_mode'].$lang['nc_colon'] ;?></span> 
				<a href="index.php?act=member_favorites&op=fglist&show=list" style="margin-right:10px;" title="<?php echo $lang['favorite_view_mode_list'];?>"><?php echo $lang['favorite_view_mode_list'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=pic" style="margin-right:10px;" title="<?php echo $lang['favorite_view_mode_pic'];?>"><?php echo $lang['favorite_view_mode_pic'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=store" title="<?php echo $lang['favorite_view_mode_shop'];?>"><?php echo $lang['favorite_view_mode_shop'];?></a>
				-->
			</div>
		<?php }?>
		</div>
	</div>
	<div class="collectshops">
		<table class="table-collectshops">
			<thead>
				<tr>
					<th width="5%"> </th>
					<th width="35%">商品名称</th>
					<th width="15%">店铺信息</th>
					<th width="10%">价格</th>
					<th width="10%">收藏时间</th>
					<th width="10%">收藏人气</th>
					<th width="15%">操作</th>
				</tr>
			</thead>
			<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
			<tbody>
			<?php foreach($output['favorites_list'] as $key=>$favorites){?>
				<tr>
					<td><input type="checkbox" class="checkitem" value="<?php echo $favorites['goods']['goods_id'];?>"/></td>
					<td>
						<a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><img src="<?php echo thumb($favorites['goods'],'tiny');?>" onload="javascript:DrawImage(this,60,60);" /></a>
							<b>
							
							<a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><?php echo $favorites['goods']['goods_name'];?></a></b>
							<p><span><?php echo $lang['favorite_selled'].$lang['nc_colon'] ;?><em><?php echo $favorites['goods']['salenum'];?></em><?php echo $lang['piece'];?></span><span>(<em><?php echo $favorites['goods']['commentnum'];?></em><?php echo $lang['favorite_number_of_consult'] ;?>)</span></p>
						</a>
					</td>
					<td>
						<span class="span-collectshopsnew"> 
							<p>
							<?php if(!empty($output['store_favorites']) && in_array($favorites['goods']['store_id'],$output['store_favorites'])){ ?>
							<span class="goods-favorite" title="<?php echo $lang['favorite_collected_store'];?>"><i class="have">&nbsp;</i></span>
							<?php }else{?>
							<a href="javascript:collect_store('<?php echo $favorites['goods']['store_id'];?>','store','')" class="goods-favorite" title="<?php echo $lang['favorite_collect_store'];?>" nc_store="<?php echo $favorites['goods']['store_id'];?>"> <i class="add">&nbsp;</i></a>
							<?php }?>
							</p>
							<p class="store-seller"><?php echo $lang['favorite_store_owner'].$lang['nc_colon'];?><a href="index.php?act=member_snshome&mid=<?php echo $favorites['goods']['member_id'];?>"><?php echo $favorites['goods']['member_name'];?></a><a target="_blank" href="index.php?act=home&op=sendmsg&member_id=<?php echo $favorites['goods']['member_id'];?>" class="message" title="<?php echo $lang['nc_message'];?>"></a>
							</p>
							<p><?php echo $lang['favorite_message'].$lang['nc_colon'];?>
							<?php if(!empty($favorites['goods']['store_qq'])){?>
							<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $favorites['goods']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $favorites['goods']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $favorites['goods']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
							<?php }?>
							<?php if(!empty($favorites['goods']['store_ww'])){?>
							<a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $favorites['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $favorites['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
							<?php }?>
							</p>
		  </span>
					</td>
					<td><?php echo ncPriceFormat($favorites['goods']['goods_store_price']);?></td>
					<td><?php echo date("Y-m-d",$favorites['fav_time']);?></td>
					<td><span class="span-collectshopsnum"><?php echo $favorites['goods']['goods_collect'];?></span></td>
					<td>
					<a class="a-collectshopsdone" href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');" class="ncu-btn2"><?php echo $lang['nc_del_&nbsp'];?></a>
					</td>
				</tr>
			<?php }?>				
			</tbody>
			<?php }else{?>
			<tbody>
			  <tr>
				<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?><span></td>
			  </tr>
			</tbody>
			<?php }?>
		</table>
		
	</div>
	<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
	 <div class="store">
		<div class="pagination-store"><?php echo $output['show_page'];?></div>
	</div>
	  <?php }?>
	</div>





<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/sns.js" charset="utf-8"></script>