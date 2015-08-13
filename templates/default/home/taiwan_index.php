<?php defined('haipinlegou') or exit('Access Invalid!');?>
<!-- 添加专有样式文件 -->
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/common_museum.css" /> 

<div class="taiwan_museum_container" >
	<div class="taiwan_museum_ben" >
<!-- 添加Banner广告图 -->
		<div class="taiwan_museum_ben_di"><p><img src="templates/default/images/banner_bg/taiwan_museum_benner.jpg"></p></div>
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
			<div class="taiwan_museum_r"><a href="index.php?act=taiwan&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div class="taiwan_explosion_product">
			<ul>
            <?php foreach($topgoods as $key=>$val){?>
				<li>
						<a class="taiwan_exp_num"><i><?php echo ($key+1)?></i></a>
						<p class="taiwan_exp_p"><img src="<?php echo cthumb($val['goods_image'],'max',$val['store_id']);?>" width="207" height="264" title=""></p>
						<div class="taiwan_exp_div">
							<div class="taiwan_exp_info">
								<p class="taiwan_exp_tit"><?php echo $val['goods_name']?></p>
								<p class="taiwan_exp_detailed"><?php echo $val['goods_keywords']?></p>
							</div>
							<div class="taiwan_exp_money">
								<p>售价&nbsp;&nbsp;¥<a><?php echo $val['spec_goods_price']?></a></p>
								<del>市场价￥<?php echo $val['spec_market_price']?></del>

								<a class="taiwan_museum_car" target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']?>">&nbsp;&nbsp;</a>
							</div>
						</div>
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
			<div class="taiwan_museum_r"><a href="index.php?act=taiwan&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div class="taiwan_new_product">
			<ul>
            	  <?php foreach($newgoods as $key=>$val){?>
				<li>
					<a class="taiwan_new_num"><i>NEW</i></a>
					<p class="taiwan_new_p"><img src="<?php echo cthumb($val['goods_image'],'max',$val['store_id']);?>" width="108" height="138" title=""></p>
					<div class="taiwan_new_div">
						<p class="taiwan_new_tit"><?php echo $val['goods_name']?></p>
						<p class="taiwan_new_detailed"><?php echo $val['goods_keywords']?></p>
							<div class="taiwan_new_money">
								<p>售价&nbsp;&nbsp;¥<a><?php echo $val['spec_goods_price']?></a></p>
								<del>市场价￥<?php echo $val['spec_market_price']?></del>
								<a class="taiwan_new_car" target="_blank" href="index.php?act=goods&goods_id=<?php echo $val['goods_id']?>"></a>
							</div>
					</div>
				</li>
                <?php }?>	
			</ul>	
		</div>
         <?php }?>
         
         <div class="taiwan_ad_1"></div>
         
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
			<div class="taiwan_museum_r"><a href="index.php?act=taiwan&op=list">MORE&nbsp;&nbsp;></a></div>
		</div>
		<div  class="taiwan_common_pro">
			<ul>
				<?php 
				if($val){
				foreach($val as $valsec){
				?>
			
				<li>
					<a class="taiwan_common_num"><i><?php echo round(($valsec['spec_goods_price']/$valsec['spec_market_price']*10),2);?>折</i></a>
					<p class="taiwan_common_p"><img src="<?php echo cthumb($valsec['goods_image'],'max',$valsec['store_id']);?>" width="145" height="185" title=""></p>
					<div class="taiwan_common_div">
						<p class="taiwan_common_tit"><?php echo $valsec['goods_name']?></p>
						<p class="taiwan_common_detailed"><?php echo $valsec['goods_keywords']?></p>
							<div class="taiwan_common_money">
								<p>售价&nbsp;&nbsp;¥<a><?php echo $valsec['spec_goods_price']?></a></p>
								<del>市场价￥<?php echo $valsec['spec_market_price']?></del>
								<a class="taiwan_common_car" target="_blank" href="index.php?act=goods&goods_id=<?php echo $valsec['goods_id']?>"></a>
							</div>
					</div>
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
			<div class="guild_subMenu_a"><a href="#anchor_point_<?php echo $k?>"><?php echo $key?></a></div>
            <?php $k++;}}?>
            
			<div class="guild_subMenu_c" id="guild_subMenu_top"><a href="javascript:;">&nbsp;&nbsp;</a></div>
		</div>
</div></div>

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