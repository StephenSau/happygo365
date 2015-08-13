<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css">
	<!--焦点图-->
	<div id="indexBanner" class="w100 pr of_h category">
		<div class="bannerWrap pa">
			<ul class="bannerList slides">
                <?php if(is_array($output['top_adv'])&&!empty($output['top_adv'])){ ?>
                    <?php foreach($output['top_adv'] as $item=>$val){ ?>
				<li class="fl"><a href="<?php echo $val['block_value']; ?>" target="blank"><img src="<?php echo SiteUrl."/upload/goodsclass/".$val['img_url']; ?>" alt=""></a></li>
			        <?php } ?>
			     <?php } ?>
            </ul>
		</div>
	</div>
	<!--焦点图-->

	<!--主体内容-->
	<div id="containerWrap" class="bc_f2f2f2 w100">
		<div class="container categoryCon">
		<!--分类页-->
			<div class="topSale">
				<a name="hot_cate"></a>
				<h2 class="ta_c font24">最值得购买的<strong class="fw_b">热销商品</strong></h2>
				<ul class="of_h">
                    <?php if(is_array($output['hot_goods'])&&!empty($output['hot_goods'])){ ?>
                     <?php foreach($output['hot_goods'] as $k=>$v){ ?>
					<li class="pr bc_white fl">
						<a href="<?php echo SiteUrl.'/goods-'.$v['goods_id'].'-'.$v['store_id'].'.html'; ?>" target="_blank">
							<div class="pic">
								<img src="<?php echo thumb(['store_id'=>$v['store_id'],'goods_images'=>$v['goods_image']],'mid');?>" alt="" width = "234">
							</div>
							<div class="detail">
								<div class="title black font18"><?php echo $v['goods_name']; ?></div>
								<div class="sale color_f60 font16"><i>￥</i><?php echo $v['goods_store_price'] ?></div>
								<div class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v['market_price']; ?></div>
							</div>
						</a>
                        <?php if(isset($_SESSION['is_login'])&&!empty($_SESSION['is_login'])){ ?>
                            <a target="_blank" class="buy pa bc_f60 lh31 white ta_c" href="<?php echo SiteUrl;?>/index.php?act=buynow&buynow_spec_id=<?php echo $v['spec_id']; ?>&buynow_quantity=1" title="<?php echo $lang['goods_index_now_buy'];?>">立即购买</a>
                        <?php } ?>
					</li>
                        <?php } ?>
                    <?php } ?>
				</ul>
				<div class="adv">
                    <?php if(is_array($output['mid_adv'])&&!empty($output['mid_adv'])){ ?>
                    <?php foreach($output['mid_adv'] as $k=>$v){ ?>
					<a href="<?php echo $v['block_value']; ?>" target="_blank">
                        <img src="<?php echo SiteUrl."/upload/goodsclass/".$v['img_url']; ?>" alt="">
                    </a>
                    <?php } ?>
                    <?php } ?>
				</div>
			</div>
		<!--分类页-->
	<!-- 品牌与最新上架  -->
            <?php foreach($output['goods_class_info'] as $k=>$v){ ?>
			<div class="powderWrap">
				<a name="cate_<?php echo $k ;?>"></a>
				<div class="powderBrand">
					<h3 class="black font24 ta_c"><strong class="fw_b"><?php echo $v['class_name']['gc_name']; ?></strong></h3>
					<ul class="of_h">
					 <?php if(is_array($v['brand'])&&!empty($v['brand'])){ ?>
                        <?php foreach($v['brand'] as $key=>$val){ ?>
						<li class="fl bc_white ta_c">
							<div style="  display: table; height:100%; width:100%"><div style="display: table-cell; vertical-align: middle; text-align: center;">
							<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id='.$val['gc_id']; ?>" target="_blank">
								<div class="pic"><img width="180" src="<?php if(is_array($v['brand_list'])&&!empty($v['brand_list']))foreach($v['brand_list'] as $item){ if($item['brand_name']==$val['gc_name']){ echo ATTACH_BRAND.'/'.$item['brand_pic']; }} ?>" alt=""></div>
								<div class="detail">
									<div class="title font20 black font_16"><?php echo $val['gc_name']; ?></div>
									<div class="slogan color_999 font14"><?php echo $val['brand_spec']; ?></div>
								</div>

							</a>
							</div></div>
						</li>
                        <?php } ?>
                        <?php } ?> 
					</ul>
				</div>

				<div class="powderZoon">
					<h4 class="font24 black"><?php echo $v['class_name']['gc_name']; ?>专区</h4>
					<ul class="of_h">
                        <?php if(is_array($v['new_goods_info'])&&!empty($v['new_goods_info'])){ ?>
                        <?php foreach($v['new_goods_info'] as $key=>$val){ ?>
						<li class="fl bc_white pr">
							<a href="<?php echo SiteUrl.'/goods-'.$val['goods_id'].'-'.$val['store_id'].'.html'; ?>" target="#">
								<div class="pic"><img src="<?php echo thumb(['store_id'=>$val['store_id'],'goods_images'=>$val['goods_image']],'small'); ?>" alt=""></div>
								<div class="detail">
									<div class="title black font_14"><?php echo $val['goods_name']; ?></div>
									<div class="sale color_f60 font16 relative"><i>￥</i><?php echo $val['goods_store_price']; ?></div>
									<div class="market color_999 td_lt "><i>市场价：￥</i><?php echo $val['market_price']; ?></div>
								</div>
							</a>
                            <?php if(isset($_SESSION['is_login'])&&!empty($_SESSION['is_login'])){ ?>
							<a href="<?php echo SiteUrl;?>/index.php?act=buynow&buynow_spec_id=<?php echo $val['spec_id']; ?>&buynow_quantity=1" target="#" class="buy pa bc_f60 lh25 white ta_c">立即购买</a>
						    <?php } ?>
                        </li>
                      <?php } ?>
                      <?php } ?>
                      <li class="fl bc_ pr bc_fff4f0"><a href="<?php echo SiteUrl;?>/index.php?act=search&cate_id=<?php echo $k;?>" target=""><img src="<?php echo TEMPLATES_PATH ;?>/images/newindex/powder_more.jpg" alt=""/></a></li>
					</ul>
				</div>
				<!--广告-->
				<div class="adv">
                    <a href="<?php echo $v['adv_url']; ?>" target="_blank">
                        <?php if(!empty($v['adv_image'])){ ?>
                    <img src="<?php echo SiteUrl."/upload/goodsclass/".$v['adv_image']; ?>" alt="">
                        <?php } ?>
                    </a>
                </div>
			</div>
			<?php } ?>

			<!--浮动客服-->
			<dl class="categoryService pf a_white font16 lh34 fw_b ta_c">
				<dd class="bc_ff4e88"><a href="#hot_cate">热销商品</a></dd>
				<?php if(!empty($output['goods_class_info']) && is_array($output['goods_class_info'])){?>
				<?php foreach($output['goods_class_info'] as $k=>$v){ ?>
						<dd class="bc_ff4e88"><a href="#cate_<?php echo $k;?>"><?php echo $v['class_name']['gc_name']; ?>专区</a></dd>
					<?php };?>
				<?php };?>
				<dd class="back bc_black pr">返回<span class="revamp_icon"></span></dd>
			</dl>
			<!--浮动客服-->
		</div>
	</div>
	<!--主体内容-->

	<script src="<?php echo RESOURCE_PATH ;?>/js/jquery.flexslider-min.js"></script>
	<script src="<?php echo RESOURCE_PATH ;?>/js/jquery.newindex.js"></script>