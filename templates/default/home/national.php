<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style>
body{overflow-x : hidden;}
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

.embassy{position:relative;height:457px;margin : 0 auto;width:100%;overflow: hidden;}
.embassy img {position: absolute;left:50%;margin-left: -960px;}
.koreaBg {background: url("templates/default/images/banner_bg/korea_bg.jpg") center 457px repeat transparent;}

</style>
<script type="text/javascript">
<?php echo $output['style_js'];?>
$(window).load(function(){
	$('.flexslider').flexslider();
});
</script>

<div class="content koreaBg" style="width:100%;">
		<div class="maxad embassy" id="banner">
			<!--script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=382"></script>-->
			<a><img style="width:1920px;height:457px" border="0" src="templates/default/images/banner_bg/korea_banner.jpg" alt=""></a>
		</div>
		<div class="w1210">
			<!--国家馆商品分类begin-->
			<!--
			<div class="koreatj">
				<ul class="ul-koreatj font-mic">
					<?php if( is_array($output['father_class'])){$num = 1;?>
					
					<?php foreach($output['father_class'] as $key => $value){?>
					<?php if($num < 6){?>
					<li <?php if($key == 0){echo "class='nomargin-l'";}?>>
						<img src="upload/gc_image/<?php echo $value['gc_image'];?>">
						<a href="<?php echo SiteUrl;?>/index.php?act=national&id=2&a=#<?php echo (int)$value['gc_id']?>"><b><?php echo $value['gc_name']?></b>首尔</a>
					</li>					
					<?php }$num++;}}?>
				</ul>
			</div>
			-->
			<!--国家馆商品分类end-->
			
			<!--商品列表-->
			<!--
			<?php if(is_array($output['show_goods_class']) && count($output['show_goods_class']) != 0){$sign = 1;$n = 0; foreach ($output['show_goods_class'] as $key=>$val){if ($val['gc_parent_id'] != '0') break;?>
			<?php if($sign < 6){?>
			<div class="goodslist pdt40">
				<div class="goodslisttitle">
					<h2><?php echo $val['gc_name']?><span>（首尔）</span><a href="#" name="<?php echo (int)$val['gc_id'];?>"></a></h2>
				</div>
				<div class="goodslistin">
					<div class="goodslistinleft fl">
					<?php foreach($output['goods_newest'] as $keys=>$vals){?>
									<?php if(!empty($vals)){?>
										<?php foreach($vals as $k=>$v){?>
											<?php if($k < 1){?>
												<?php if($val['gc_id'] == $keys){?>
												<div class="goodslistintj fl">
													<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 384+$n;?>"></script>
												</div>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								<?php }?>
						<div class="goodslistinmain">
								<ul class="ul-goodslistinmain">
									<?php if(!empty($output['goods_list'])){?>
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
									<?php }else{?>
										<span></span>
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
			<!--商品-->
			<?php if(!empty($output['goods_list'])){?>
			<div class="hgggoods">
				<ul class="ul-hgggoods">
					<?php foreach($output['goods_list'] as $k=>$v){?>
						<li>
							<div class="sellergoodslistblck">
                       <a class="a-hgggoodsimg" target="_blank" href="index.php?act=goods&goods_id=<?php echo $v['goods_id']?>"><img src="<?php echo cthumb($v['goods_image'],'max',$v['store_id']);?>"></a>                
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
		</div>
		
	</div>