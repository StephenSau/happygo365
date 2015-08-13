<script type="text/javascript" src="<?php echo RESOURCE_PATH.'/js/search_goods.js';?>" charset="utf-8"></script>
<script src="<?php echo RESOURCE_PATH;?>/js/jquery.datalazyload.js" type="text/javascript"></script>

<?php defined('haipinlegou') or exit('Access Invalid!');?>
<form id="search_form">
  <input name="act" type="hidden" value="xianshi" />
  <input name="op" type="hidden" value="index" />
  <input id="groupbuy_area" name="groupbuy_area" type="hidden" value="<?php echo $_GET['groupbuy_area'];?>"/>
  <input id="groupbuy_class" name="groupbuy_class" type="hidden" value="<?php echo $_GET['groupbuy_class'];?>"/>
  <input id="groupbuy_price" name="groupbuy_price" type="hidden" value="<?php echo $_GET['groupbuy_price'];?>"/>
  <input id="groupbuy_order_key" name="groupbuy_order_key" type="hidden" value="<?php echo $_GET['groupbuy_order_key'];?>"/>
  <input id="groupbuy_order" name="groupbuy_order" type="hidden" value="<?php echo $_GET['groupbuy_order'];?>"/>
</form>
<div class="content">
	<div class="w1210 pdt30">
		<div class="teambuyallsearch">
			<div class="teambuyallsearchtop pr">
				<div class="teambuyallsearchtopmain">
					<ul class="ul-teambuyallsearchtop font-mic">
					<li><a href="javascript:void(0)" onClick="set_groupbuy_class('')"><?php echo $lang['text_no_limit'];?></a></li>
						<?php if(is_array($output['father_class']) && count($output['father_class']) != 0){$sign = 1;?>
						<?php foreach ($output['father_class'] as $tkey=>$val){if ($val['gc_parent_id'] != '0') break;?>
							<li><a <?php echo $_GET['groupbuy_class'] == $val['gc_id']?"class='selected'":'';?> href="javascript:void(0)" onClick="set_groupbuy_class('<?php echo $val['gc_id'];?>')"><?php echo $val['gc_name'];?></a></li>
						<?php }}?>
					</ul>
				</div>
				<a class="a-teambuyallsearchpre" href=""><</a>
				<a class="a-teambuyallsearchnet" href="">></a>
			</div>
			<div class="teambuyallsearchbtm pr" id="teambuyallsearchbtm">
				<ul class="ul-teambuyallsearchbtm font-mic">
					<li>
						<a 	class="<?php echo empty($_GET['groupbuy_order_key'])?'a-teambuyallsearchbtm':'' ?>"
							href="javascript:void(0);" onclick="set_groupbuy_order('');"><?php echo $lang['goods_class_index_default'];?></a>
					</li>					
					<li>
						<a 	<?php if(!empty($_GET['groupbuy_order_key']) && $_GET['groupbuy_order_key'] == 'discount' ){?>
							class="a-teambuyallsearchbtm";
							<?php }else{?>
								class="";
							<?php }?>
							href="javascript:void(0)" onclick="set_groupbuy_order('discount');"><?php echo $lang['goods_class_index_sold'];?></a>
					</li>					
					<li>
						<a 	<?php if(!empty($_GET['groupbuy_order_key']) && $_GET['groupbuy_order_key'] == 'price' ){?>
							class="a-teambuyallsearchbtm";
							<?php }else{?>
								class="";
							<?php }?>
							href="javascript:void(0)" onclick="set_groupbuy_order('price')"><?php echo $lang['goods_class_index_price'];?></a>
					</li>
				</ul>
				<div class="teambuyallsearchselect">
					<!--<select>
						<option><a href="javascript:void(0)" onClick="javascript:dropParam('promotion','groupbuy');" title="<?php echo $lang['goods_class_groupbuy'];?>"><?php echo $lang['goods_class_index_groupbuy'];?></a></option>
						<option><a href="javascript:void(0)" onClick="javascript:dropParam('promotion','xianshi');" title="<?php echo $lang['goods_class_xianshi'];?>"><?php echo $lang['goods_class_index_xianshi'];?></a></option>
						<option><a href="javascript:void(0)" onClick="javascript:dropParam('promotion');" title="<?php echo $lang['goods_class_unlimit'];?>"><?php echo $lang['goods_class_unlimit'];?></a></option>
					</select>-->
				</div>
			</div>
		</div>
		<div class="teambuy pdt20">
			<div class="goodssearchedbtm">
				<ul class="ul-goodssearchedbtm font-mic">
					<?php if(!empty($output['xianshi_item'])){?>
					<?php foreach($output['xianshi_item'] as $key => $value){?>
					<li>
						<a class="a-goodssearchedimg" href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><img src="<?php echo $output['site_url']?>/upload/store/goods/<?php echo $value['store_id'];?>/<?php echo $value['goods_image'];?>" width="218px" height="218px" /></a>
						<div class="goodsshopmess">
							<a class="a-goodssearchedname" style="width:208px;height:40px; overflow:hidden" href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>" title="<?php echo $value['goods_name'];?>"><?php echo $value['goods_name'];?></a>
							<p class="p-goodsprice">乐购价：<span>￥<b><?php echo $value['goods_store_price'];?></b></span></p>
							<div class="goodsshoptime">
								<p class="timelast">
								<?php if(intval($output['xianshi_info']['start_time']) > time()) { ?>
								<!-- 尚未开始 --> 
							   <?php $time = $value['end_time'] - time();?>
								<span><?php echo floor($time/86400);?><?php echo $lang['nc_day'];?><?php echo floor($time%86400/3600);?><?php echo $lang['nc_hour'];?><?php echo floor($time%86400%3600/60);?><?php echo $lang['nc_minute'].$lang['to_start'];?></span>
								<?php } else { ?>
								<!-- 已经开始 --> 
								<?php $time = $value['end_time'] - time();?>
								<span><?php echo floor($time/86400);?><?php echo $lang['nc_day'];?><?php echo floor($time%86400/3600);?><?php echo $lang['nc_hour'];?><?php echo floor($time%86400%3600/60);?><?php echo $lang['nc_minute'].$lang['to_end'];?></span>
								<?php } ?>

								</p>
							</div>							
						</div>
					</li>
					<?php }?>
					<?php }?>
				</ul>
				<div>
					<p class="font-mic" >
						<div class="page">
								<div class="paginations" style="margin:0 auto"><?php echo $output['show_page'];?></div>
						</div>
					</p>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script>
function set_groupbuy_order(order_key) {

    if(order_key == $("#groupbuy_order_key").val()) {
        change_groupbuy_order();
    }
    else {
        $("#groupbuy_order").val('asc');
    }
    $("#groupbuy_order_key").val(order_key);
    submit_search();
}
function set_groupbuy_class(class_id) {
    $("#groupbuy_class").val(class_id);
    submit_search();
}
function set_groupbuy_price(price_range) {
    $("#groupbuy_price").val(price_range);
    submit_search();
} 
function change_groupbuy_order() {
    if($("#groupbuy_order").val() == 'asc') {
        $("#groupbuy_order").val('desc');
    }
    else {
        $("#groupbuy_order").val('asc');
    }
}
function submit_search() {
        $('#search_form').attr('method','get');
        $('#search_form').submit();
}
</script>
