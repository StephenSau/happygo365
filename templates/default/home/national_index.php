<?php defined('haipinlegou') or exit('Access Invalid!');?>
<!-- 添加专有样式文件 -->
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/common_museum.css" /> 

<div class="taiwan_museum_container" >
	<div class="taiwan_museum_ben" >
<!-- 添加Banner广告图 -->
		<div class="taiwan_museum_ben_di"><p><img src="templates/default/images/banner_bg/korea_museum_benner.jpg"></p></div>
	</div>
 
<!-- 这里暂时不显示start -->
<!-- <div class="taiwan_museum_navbar" >
	<ul>
		<li class="taiwan_museum_navbar_1"></li>
		<li class="taiwan_museum_navbar_2"></li>
		<li class="taiwan_museum_navbar_3"></li>
		<li class="taiwan_museum_navbar_4"></li>
	</ul>
</div> -->
<!-- 这里暂时不显示end -->
<div class="taiwan_museum_main">
	<div class="taiwan_museum_explosion">
    
    <?php if($topgoods){?>
		<div class="taiwan_museum_submenu">
			<div class="taiwan_museum_l">
				<a name="anchor_point_1" id="anchor_point_1" class="anchor_point"></a>
				<a class="taiwan_museum_submenu_exp">爆款
				<a class="taiwan_museum_submenu_exp_en">TODAY'S HOST</a>
				</a>
			</div>
			<div class="taiwan_museum_r"><a href="index.php?act=national&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div class="taiwan_explosion_product">
			<ul>
            <?php foreach($topgoods as $key=>$val){?>
				<li>
					<a target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']?>" title="<?php echo $val['goods_name'];?>">
						<span class="taiwan_exp_num"><i><?php echo ($key+1)?></i></span>
						<p class="taiwan_exp_p"><img src="<?php echo cthumb($val['goods_image'],'max',$val['store_id']);?>" width="264" height="264" title=""></p>
						<div class="taiwan_exp_div">
							<div class="taiwan_info">
								<p class="taiwan_exp_tit"><?php echo (mb_strlen($val['goods_name'],'utf-8')>32) ? mb_substr($val['goods_name'],0,32,"utf-8")."..." : $val['goods_name'];?></p>
								<!-- <p class="taiwan_exp_detailed"><?php echo mb_substr($val['goods_keywords'],0,20,"utf-8")."...";?></p> -->
							</div>
							<div class="taiwan_exp_money">
								<p>售价&nbsp;&nbsp;¥<span><?php echo $val['spec_goods_price']?></span></p>
								<del>市场价￥<?php echo $val['spec_market_price']?></del>

								<span class="taiwan_museum_car">&nbsp;&nbsp;</span>
							</div>
						</div>
					</a>
				</li>
			<?php }?>	
			</ul>
		</div>
        <?php }?>
        
		<!-- 新品 -->
         <?php if($newgoods){?>
		<div class="taiwan_museum_submenu">
			<div class="taiwan_museum_l">
				<a name="anchor_point_2" id="anchor_point_2" class="anchor_point"></a>
				<a class="taiwan_museum_submenu_exp">新品
				<a class="taiwan_museum_submenu_exp_en">NEW ARRIVAL</a>
				</a>
			</div>
			<div class="taiwan_museum_r"><a href="index.php?act=national&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div class="taiwan_new_product">
			<ul>
            	  <?php foreach($newgoods as $key=>$val){?>
				<li>
					<a  target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']?>" title="<?php echo $val['goods_name'];?>">
						<span class="taiwan_new_num"><i>NEW</i></span>
						<p class="taiwan_new_p"><img src="<?php echo cthumb($val['goods_image'],'max',$val['store_id']);?>" width="138" height="138" title=""></p>
						<div class="taiwan_new_div">
							<div class="taiwan_info">
								<p class="taiwan_new_tit"><?php echo (mb_strlen($val['goods_name'],'utf-8')>32) ? mb_substr($val['goods_name'],0,32,"utf-8")."..." : $val['goods_name'];?></p>
								<!-- <p class="taiwan_new_detailed"><?php echo mb_substr($val['goods_keywords'],0,10,"utf-8")."...";?></p> -->
							</div>
							<div class="taiwan_new_money">
								<p>售价&nbsp;&nbsp;¥<span><?php echo $val['spec_goods_price']?></span></p>
								<del>市场价￥<?php echo $val['spec_market_price']?></del>
								<span class="taiwan_new_car"></span>
							</div>
						</div>
					</a>
				</li>
                <?php }?>	
			</ul>	
		</div>
         <?php }?>
         
         <div class="taiwan_ad_1" style="display:none;"></div>
         
         <?php 
			if($parentclassid){
			$y=3;
			foreach($parentclassid as $key=>$val){
		?>  
		
		<div class="taiwan_museum_submenu">
			<div class="taiwan_museum_l">
				<a name="anchor_point_<?php echo $y?>" id="anchor_point_<?php echo $y?>" class="anchor_point"></a>
				<a class="taiwan_museum_submenu_exp"><?php echo $key?>
					<a class="taiwan_museum_submenu_exp_en"></a>
				</a>
			</div>
			<div class="taiwan_museum_r"><a href="index.php?act=national&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div  class="taiwan_common_pro">
			<ul>
				<?php 
				if($val){
				foreach($val as $valsec){
				?>
			
				<li>
					<a target="_blank" href="index.php?act=goods&goods_id=<?php echo $valsec['goods_id']?>" title="<?php echo $valsec['goods_name'];?>">
						<span class="taiwan_common_num"><i><?php echo ($valsec['spec_goods_price'] && $valsec['spec_market_price']>1) ? round(($valsec['spec_goods_price']/$valsec['spec_market_price']*10),1).'折' : '';?></i></span>
						<p class="taiwan_common_p"><img src="<?php echo cthumb($valsec['goods_image'],'max',$valsec['store_id']);?>" width="185" height="185" title=""></p>
						<div class="taiwan_common_div">
							<div class="taiwan_info">
								<p class="taiwan_common_tit"><?php echo (mb_strlen($valsec['goods_name'],'utf-8')>32) ? mb_substr($valsec['goods_name'],0,32,"utf-8")."..." : $valsec['goods_name'];?></p>
								<!-- <p class="taiwan_common_detailed"><?php echo mb_substr($valsec['goods_keywords'],0,10,"utf-8")."...";?></p> -->
							</div>
							<div class="taiwan_common_money">
								<p>售价&nbsp;&nbsp;¥<span><?php echo $valsec['spec_goods_price']?></span></p>
								<del>市场价￥<?php echo $valsec['spec_market_price']?></del>
								<span class="taiwan_common_car"></span>
							</div>
						</div>
					</a>
				</li>
				<?php }}?>
			</ul>
		</div>
         <?php $y++;}}?>
         

		<div class="guild_subMenu">
			<div class="guild_subMenu_a"><a href="#anchor_point_1">爆款</a></div>
			<div class="guild_subMenu_a"><a href="#anchor_point_2">新品</a></div>
            <?php 
			if($parentclassid){
			$k=3;
			foreach($parentclassid as $key=>$val){
			?>
			<div class="guild_subMenu_a"><a href="#anchor_point_<?php echo $k?>"><?php echo mb_substr($key,0,10,"utf-8")."..."?></a></div>
            <?php $k++;}}?>
            
			<div class="guild_subMenu_c" id="guild_subMenu_top"><a href="javascript:;">&nbsp;&nbsp;</a></div>
		</div>
		</div>
	</div>

<script type="text/javascript">

$(function(){
	$(".guild_subMenu").fadeOut(100);
	/*客服到一定的高度慢慢淡出*/
	$(window).bind("scroll",function(){
		if($(window).scrollTop() >= 250){
			$(".guild_subMenu").stop(false,true).fadeIn(100);
		}else{
			$(".guild_subMenu").stop(false,true).fadeOut(100);
		}
	})
	$("#guild_subMenu_top").click(function(){
		$('html,body').animate({'scrollTop':0},200);
	});

	//删掉默认客服
	$(".serviceContact").remove();

});

</script>