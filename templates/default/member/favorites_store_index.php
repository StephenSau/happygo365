<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link href="<?php echo RESOURCE_PATH;?>/js/jcarousel/skins/tango/skin.css" rel="stylesheet" type="text/css">
<style type="text/css">
.jcarousel-skin-tango { background-color: #F4F9FD; border: solid 1px #AED2FF;}
.jcarousel-skin-tango a { background-color: #FFF; width: 120px; height: 120px; display: inline-block; border: solid 1px #C4D5E0; }
.jcarousel-skin-tango .jcarousel-clip-horizontal { width: 660px !important; height: 130px !important;}
.jcarousel-skin-tango .jcarousel-item { height: 130px !important;}
.jcarousel-skin-tango .jcarousel-container-horizontal { width: 660px !important;}
</style>

<div class="buyercenterright font-mic">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
				<?php include template('member/member_submenu3');?>
		</div>
		<div class="buyerdongsearch"> 
		<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
			<div class="collectallchoice">
				<p><input id="all" class="checkall" type="checkbox"><?php echo $lang['nc_select_all'];?></p>
				<p><a href="javascript:void(0);"uri="index.php?act=member_favorites&op=delfavorites&type=goods" name="fav_id" confirm="<?php echo $lang['nc_ensure_del'];?>" nc_type="batchbutton"><?php echo $lang['nc_del'];?></a></p>
				<!--<span><?php echo $lang['favorite_view_mode'].$lang['nc_colon'] ;?></span> 
				<a href="index.php?act=member_favorites&op=fglist&show=list" class="list" title="<?php echo $lang['favorite_view_mode_list'];?>"><?php echo $lang['favorite_view_mode_list'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=pic" class="onpic" title="<?php echo $lang['favorite_view_mode_pic'];?>"><?php echo $lang['favorite_view_mode_pic'];?></a>
				<a href="index.php?act=member_favorites&op=fglist&show=store" class="store" title="<?php echo $lang['favorite_view_mode_shop'];?>"><?php echo $lang['favorite_view_mode_shop'];?></a>
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
					<th width="35%"><?php echo $lang['favorite_store_name'];?></th>
					<th width="15%"><?php echo $lang['favorite_store_new_goods'];?></th>
					<th width="20%"><?php echo $lang['favorite_popularity'];?></th>
					<th width="10%"><?php echo $lang['favorite_popularity'];?></th>
					<th width="15%"><?php echo $lang['favorite_handle'];?></th>
				</tr>
			
			<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
			<tbody>
			 <?php foreach($output['favorites_list'] as $key=>$favorites){?>
				<tr>
					<td><input class="checkitem" type="checkbox" value="<?php echo $favorites['goods']['goods_id'];?>"></td>
						<td>
							<a class="a-collectshopsname"href="index.php?act=show_store&id=<?php echo $favorites['store']['store_id'];?>" target="_blank"><img src="<?php echo $favorites['store']['store_logo'];?>" onload="javascript:DrawImage(this,60,60);"/>						 
							<b>
								<?php echo $favorites['store']['store_name'];?>
							</b>
							<?php echo $favorites['store']['area_info'];?>
							</a>
						</td>
					
					<td>
					<span class="span-collectshopsnew">
					<a href="javascript:get_store_goods('<?php echo $favorites['store']['store_id'];?>','<?php echo $favorites['store']['goods_count'];?>');" ><?php echo $lang['favorite_new_goods'];?>(<?php echo $favorites['store']['goods_count'];?>)<i id="store-arrow-<?php echo $favorites['store']['store_id'];?>">&nbsp;</i></a>
					</span>
					</td>
					<td><?php echo date("Y-m-d",$favorites['fav_time']);?></td>
					<td><span class="span-collectshopsnum"><?php echo $favorites['store']['store_collect'];?></span></td>
					<td>
						<a class="a-collectshopsdone" href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');"><?php echo $lang['nc_del_&nbsp'];?></a>
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
			<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
				<tfoot>
				  <tr>
					
					
					
				  </tr>
				</tfoot>
			<?php }?>			
		</table>
	</div>
	<div class="store">
			<div class="pagination-store"><?php echo $output['show_page']; ?></div>
	</div>
</div>



<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/sns.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jcarousel/jquery.jcarousel.min.js"></script> 
<script type="text/javascript">
function get_store_goods(store_id,goods_count){
	if(goods_count < 1) return ;
	var store=$("#store-goods-"+store_id);
	var store_arrow=$("#store-arrow-"+store_id);
	var store_display=store.css("display");
	var store_goods=store.find("ul").html();
	if(store_goods == '') {
		store.find("ul").html('<li><img src="<?php echo TEMPLATES_PATH;?>/images/loading.gif" alt="loading..." ></li>');
		store.show();
		store_arrow.attr("class","arrow-up");
		var ajaxurl = 'index.php?act=member_favorites&op=store_goods&store_id='+store_id;
		var store_goods = $.ajax({url: ajaxurl,async: false}).responseText;
		store.find("ul").html(store_goods);
		store.find("ul").jcarousel({visible: 5});
	}else{
		if(store_display == 'none') {
			store_arrow.attr("class","arrow-up");
			store.show();
		}else{
			store_arrow.attr("class","arrow-down");
			store.hide();
		}
	}
}
</script>
