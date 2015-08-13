<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style>
.adv{width:1920px;height:400px;border-bottom:2px #eee solid;}
.products{width:1000px;height:auto;margin:50px auto;}
.products p{padding:3px 0px;margin:5px 0px;text-algin:left;font-weight:bold;font-size:18px;border-bottom:4px solid #aa0600;}
.good{width:245px;height:340px;border:1px #ccc solid; float:left;}
.normal{margin:0 3px;}
.good img{width:245px;height:250px;}
.good span{text-algin:justify;}
.left{float:left;}
.right{float:right;}

/*国家馆*/
.hgggoods{font-family: 'Microsoft Yahei';padding-top: 30px;}
.ul-hgggoods{overflow: hidden;margin-left: -20px;}
.ul-hgggoods li{float: left;margin-left: 20px;margin-bottom: 20px; background-color: #ffffff;width:287px;height:390px;}
.ul-hgggoods li:hover .sellergoodslistblck{border-color: #ff6c00;}
.a-hgggoodsimg{display: block;overflow: hidden;}
.a-hgggoodsimg img{width: 278px;height: 278px;}
.ul-hgggoods li:hover .a-sellergoodslistname{color: #ff6c00;}
</style>
<link href="<?php echo TEMPLATES_PATH;?>/css/shop.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.flexslider-min.js"></script> 
<script type="text/javascript">
<?php echo $output['style_js'];?>
$(window).load(function(){
	$('.flexslider').flexslider();
	
	//banner图调整
	var bannerWidth = document.body.clientWidth;
	$("#banner").css({"position":"relative","width":bannerWidth+"px",'overflow':'hidden'});
	$("#banner a img").css({"position":"absolute","left":"50%","margin-left":"-960px"});
});
</script>
<div class="content" style="width:1210px;">
		<div class="maxad" id="banner">
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=383"></script>
		</div>
		<div class="w1210">
			<div class="koreatj">
				<!--迪拜馆商品分类begin-->
					<!--
					<ul class="ul-koreatj font-mic">
						<?php if( is_array($output['father_class'])){$num = 1;?>
						<?php foreach($output['father_class'] as $key => $value){?>
						<?php if($num < 6){?>
						<li <?php if($key == 0){echo "class='nomargin-l'";}?>>
							<img src="upload/gc_image/<?php echo $value['gc_image'];?>">
							<a href="<?php echo SiteUrl;?>/index.php?act=national&id=2&a=#<?php echo (int)$value['gc_id']?>"><b><?php echo $value['gc_name']?></b>迪拜</a>					
							<a href="<?php echo SiteUrl;?>/index.php?act=dubai&id=2&a=#<?php echo (int)$value['gc_id']?>"><b><?php echo $value['gc_name']?></b>迪拜</a>
						</li>					
						<?php }$num++;}}?>
					</ul>
					-->
				<!--迪拜馆商品分类begin-->
			</div>
			<!--商品-->
			<!--
			<?php if(is_array($output['show_goods_class']) && count($output['show_goods_class']) != 0){$sign = 1;$n = 0; foreach ($output['show_goods_class'] as $key=>$val){if ($val['gc_parent_id'] != '0') break;?>
			<?php if($sign < 6){?>
			<div class="goodslist pdt40">
				<div class="goodslisttitle">
					<h2><?php echo $val['gc_name']?><span>（迪拜）</span><a href="#" name="<?php echo (int)$val['gc_id'];?>"></a></h2>
				</div>
				<div class="goodslistin">
					<div class="goodslistinleft fl">
					<?php foreach($output['goods_newest'] as $keys=>$vals){?>
									<?php if(!empty($vals)){?>
										<?php foreach($vals as $k=>$v){?>
											<?php if($k < 1){?>
												<?php if($val['gc_id'] == $keys){?>
												<div class="goodslistintj fl">											
													<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 390+$n;?>"></script>
												</div>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
						<div class="goodslistinmain">
							<ul class="ul-goodslistinmain">
							<?php foreach($output['goods_list'] as $keys=>$vals){?>
									<?php if(!empty($vals)){?>
										<?php foreach($vals as $k=>$v){?>
											<?php if($k < 8){?>
												<?php if($val['gc_id'] == $keys){?>
													<li <?php if(3>$k){?>class="boder-dr boder-db"<?php }elseif(3==$k || 7==$k){?>class="boder-db"<?php }else{?>class="boder-dr"<?php }?>>
														<a class="a-goodslistinimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>"><img src="<?php ?>/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" width="150px" height="150px" /></a>
														<a class="a-gooodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>" title="<?php echo $v['goods_name'];?>" style="width:150px;height:25px; overflow:hidden"><?php echo $v['goods_name'];?></a>
														<p class="p-gooslistprice"><span>￥<?php echo $v['goods_store_price'];?></span></p>
													</li>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
							</ul>
						</div>
					
					</div>
					<div class="goodslistinright2">
					<?php foreach($output['goods_rank'] as $keys=>$vals){?>
						<?php if(!empty($vals)){?>
							<?php foreach($vals as $k=>$v){?>
								<?php if($k < 2){?>
									<?php if($val['gc_id'] == $keys){?>
										<div class="goodslistinrightad2 <?php if($k==0){echo 'mb14';}?>"><a href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>"><img src="<?php ?>/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" width="270px" height="225px" /></a></div>
									<?php }?>
								<?php }?>
							<?php }?>
						<?php }?>
					<?php }?>
					</div>
				</div>
			</div>
			<?php };$sign++;$n++;}};?>
			-->
			<?php if(!empty($output['goods_list'])){?>
			<div class="hgggoods">
				<ul class="ul-hgggoods">
					<?php foreach($output['goods_list'] as $k=>$v){?>
						<li>
							<div class="sellergoodslistblck">
								<a class="a-hgggoodsimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>"><img src="<?php echo cthumb($v['goods_image'],'max',$v['store_id']);?>"></a> 
								<div class="sellergoodslistmess">
									<a class="a-sellergoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>"><?php echo $v['goods_name'];?></a>
									<p class="p-sellergoodslistprice">
										￥
										<b><?php echo $v['goods_store_price']?></b>
									</p>
								</div>
							</div>
						</li>	
					<?php }?>						
				</ul>
				<div class="page"><?php echo $output['page'];?></div>
			</div>
			<?php }?>
			<!--商品-->
		</div>
	</div>