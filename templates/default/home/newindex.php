
	<!--焦点图-->
	<div id="indexBanner" class="w100 pr of_h">
		<div class="bannerWrap pa">
			<ul class="bannerList slides">
                <?php if(!empty($output['tpl']['home-lb']) && is_array($output['tpl']['home-lb'])) { ?>
                    <?php foreach($output['tpl']['home-lb'] as $k2=>$v2) { ?>
						<?php if($k2 == 0){;?>
                        <li class="fl"><a href="<?php echo $v2['img_link'] ?>" target="blank"><img width="1920" height="464" src="<?php echo SiteUrl.'/'.$v2['img_url'] ;?>" alt=""></a></li>
						<?php }else{;?>
                        <li class="fl" style="display:none;"><a href="<?php echo $v2['img_link'] ?>" target="blank"><img width="1920" height="464" src="<?php echo SiteUrl.'/'.$v2['img_url'] ;?>" alt=""></a></li>
						<?php ;}?>
                    <?php } } ?>
            </ul>
		</div>
	</div>
	<!--焦点图-->

	<!--主体内容-->
	<div id="containerWrap" class="bc_f2f2f2 w100">
		<div class="container">
		
			<!--热门精选-->
			<div class="hotChoice">
				<h2 class="color_999 font16">
					<strong class="black font24">热门精选 /</strong>
					海品乐购在全球 为你精挑细选
				</h2>
				<ul class="of_h">
                    <?php if(!empty($output['tpl']['home-hot'])) { ?>
                    <?php foreach($output['tpl']['home-hot'] as $k4=>$v4) { ?>
					<li class="fl pr">
						<a href="<?php echo SiteUrl.'/goods-'.$v4['goods_info']['goods_id'].'-'.$v4['goods_info']['store_id'].'.html'; ?>" target="blank">
							<p class="pic fl"><img width="197" height="197" src="<?php echo cthumb($v4['goods_info']['goods_image'],'max',$v4['goods']['store_id']);?>" alt=""></p>
							<p class="detail fl">
								<span class="num font24 fw_b color_666">TOP <?php echo $k4 + 1; ?></span>
								<span class="title font14 black"><?php echo $v4['goods_info']['goods_name'] ?></span>
								<span class="sale fw_b color_f60 font16"><i>￥</i><?php echo $v4['goods_info']['goods_store_price'] ?></span>
								<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v4['goods_info']['market_price'] ?></span>
							</p>
						</a>
						<span class="revamp_icon hotIcon">HOT</span>
					</li>
                    <?php } } ?>


				</ul>
			</div>
			<!--热门精选-->

			<!--广告区-->
			<div class="adv"></div>
			<!--广告区-->
            <?php if(!empty($output['tpl']['home-xianshi']) && is_array($output['tpl']['home-xianshi'])) {?>
			<!--限时特卖-->
			<div class="limitBuy">
				<h2 class="color_999 font16">
					<strong class="black font24">限时特卖 /</strong>
					每日严选 品牌折扣限时抢
				</h2>
				<ul>

                    <?php foreach($output['tpl']['home-xianshi'] as $k5=>$v5) { ?>
					<li>
						<a href="<?php echo SiteUrl.'/goods-'.$v5['goods_info']['goods_id'].'-'.$v5['goods_info']['store_id'].'.html'; ?>">
							<p class="pic">
								<img width="588" height="334" src="<?php echo SiteUrl.'/'.$v5['img_url'] ;?>" alt="<?php echo $v5['img_alt'] ?>">
							</p>
							<p class="detail bc_white">
								<span class="time pa bc_black white font14 fw_b ta_c lh31" data_time="<?php echo strtotime("+ ".$v5['word_value']."day")?>">限时特卖：9天14小时9分10秒</span>
								<span class="title black	 font14"><?php echo $v5['goods_info']['goods_name'] ?></span>
								<span class="price of_h w100">
									<span class="sale fl font24 fw_b color_f60"><i>￥</i><?php echo $v5['goods_info']['goods_store_price'] ?></span>
									<span class="market fl td_lt color_999 lh24"><i>市场价：￥</i><?php echo $v5['goods_info']['market_price'] ?></span>
								</span>
							</p>
						</a>
						<span class="revamp_icon limitIcon">限时特卖</span>
						<span class="revamp_icon buyIcon"></span>
					</li>

                    <?php }  ?>

				</ul>
			</div>
			<!--限时特卖-->
            <?php } ?>

			<!--下期预告-->
            <?php if(!empty($output['tpl']['home-next']) && is_array($output['tpl']['home-next'])) { ?>
            <div class="nextAnnounce">
				<h2 class="nextTitle color_999 font16">
					<strong class="black font24">下期预告 /</strong>
					<?php $num =  $output['tpl']['home-next-day'][0]['word_value'];
                           echo date("Y-m-d H:m:s",strtotime("+ ".$num."day"));
                    ?>开售
				</h2>
				<ul>
                    <?php foreach($output['tpl']['home-next'] as $k6=>$v6) { ?>
					<li>
						<a href="<?php echo SiteUrl.'/goods-'.$v6['goods_info']['goods_id'].'-'.$v6['goods_info']['store_id'].'.html'; ?>" target="blank">
							<p class="pic">
								<img width="298" height="259" src="<?php echo cthumb($v4['goods_info']['goods_image'],'max',$v4['goods_info']['store_id']);?>" alt="">
							</p>
							<p class="detail">
								<span class="title font15 lh18 color_333 fw_b"><?php echo $v6['goods_info']['goods_name']?></span>
								<span class="sale sale font21 lh26 color_f60 fw_"><i>￥</i>???</span>
								<span class="market color_999 td_lt lh14"><i>市场价：￥</i><?php echo $v6['goods_info']['market_price']; ?></span>
							</p>
						</a>
					</li>
                    <?php  } ?>

				</ul>
			</div>
            <?php } ?>
			<!--下期预告-->

			<!--广告区-->
			<div class="adv"></div>
			<!--广告区-->

			<!--楼层-->
			<div class="floorWrap">
				<div class="floorList firstFloor">
					<h3>
						<strong>1F</strong>
						<span>母婴用品</span>
					</h3>
					<ul class="floorNav">
                        <?php if($output['tpl']['home-first-menu'] && is_array($output['tpl']['home-first-menu'])){?>
                        <?php foreach($output['tpl']['home-first-menu'] as $k8=>$v8) { ?>
						<li class="<?php if($k8 == 0) {?>active <?php } ?>"><?php echo $v8['word_value'] ?></li>
                        <?php }} ?>

					</ul>
					<div class="floorContent">
						<div class="floorSidebar fl">
                            <?php if(!empty($output['tpl']['home-first-img']) && is_array($output['tpl']['home-first-img'])) {?>
                            <?php foreach($output['tpl']['home-first-img'] as $k10=>$v10) { ?>
							<div class="adv">
								<a href="<?php echo $v10['img_link'] ?>" target="blank"><img <?php if($k10 == 0){ ?> width="239" height="341" <?php }else{ ?>width="239" height="116" <?php } ?> src="<?php echo SiteUrl.'/'.$v10['img_url'] ;?>" alt=""></a>
							</div>
                            <?php }} ?>

							<div class="categoryNav">
								<ul>	
									<li>					
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=228'; ?>">奶粉</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=450'; ?>" target="_blank">惠氏</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=451'; ?>" target="_blank">雅培</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=452'; ?>" target="_blank">雀巢</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=453'; ?>" target="_blank">爱他美</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=478'; ?>" target="_blank">Nutrilon荷兰牛栏</a>
										</div>
										<span class="revamp_icon milk_icon"></span>
									</li>
									<li>						
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=414'; ?>">纸尿片</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=446'; ?>" target="_blank">花王 </a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=448'; ?>" target="_blank">大王 </a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=449'; ?>" target="_blank">尤妮佳</a>
										</div>
										<span class="revamp_icon paper_icon"></span>
									</li>
                                    <!--
									<li>						
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=229'; ?>">婴幼用品</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=454'; ?>" target="_blank">nuk</a>
										</div>
										<span href="" target="_blank" class="revamp_icon baby_icon"></span>
									</li>
									-->
								</ul>
							</div>
						</div>
						<div class="catContent fl pr">
							<ul>
                                <?php if(!empty($output['tpl']['home-1F-g']) && is_array($output['tpl']['home-1F-g'])) { ?>
                                <?php foreach($output['tpl']['home-1F-g'] as $k9=>$v9) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v9['goods_info']['goods_id'].'-'.$v9['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v9['goods_info']['goods_image'],'max',$v9['goods_info']['store_id']);?>" alt=""  width="200"></p>
										<p class="detail">
											<span class="title font15 color_666"><?php echo $v9['goods_info']['goods_name']  ?></span>
											<span class="sale color_f60 font16"><i>￥</i><?php echo $v9['goods_info']['goods_store_price'] ?></span>
											<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v9['goods_info']['market_price'] ?></span>
										</p>
									</a>
								</li>
                                <?php } } ?>

							</ul>
							<ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-1F-2g']) && is_array($output['tpl']['home-1F-2g'])) { ?>
                                <?php foreach($output['tpl']['home-1F-2g'] as $k11=>$v11) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v11['goods_info']['goods_id'].'-'.$v11['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v11['goods_info']['goods_image'],'max',$v11['goods_info']['store_id']);?>" alt=""  width="200"></p>
										<p class="detail">
											<span class="title font15 color_666"><?php echo  mb_substr($v11['goods_info']['goods_name'],0,10,'UTF-8') ?></span>
											<span class="sale color_f60 font16"><i>￥</i><?php echo $v11['goods_info']['goods_store_price'] ?></span>
											<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v11['goods_info']['market_price'] ?></span>
										</p>
									</a>
								</li>
                                <?php } } ?>

							</ul>
							<ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-1F-3g']) && is_array($output['tpl']['home-1F-3g'])) { ?>
                                <?php foreach($output['tpl']['home-1F-3g'] as $k12=>$v12) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v12['goods_info']['goods_id'].'-'.$v12['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v12['goods_info']['goods_image'],'max',$v12['goods_info']['store_id']);?>" alt="<?php echo $v12['goods_info']['goods_name'] ?>"  width="200"></p>
										<p class="detail">
											<span class="title font15 color_666"><?php echo mb_substr($v12['goods_info']['goods_name'],0,10,'UTF-8') ?></span>
											<span class="sale color_f60 font16"><i>￥</i><?php echo $v12['goods_info']['goods_store_price'] ?></span>
											<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v12['goods_info']['market_price'] ?></span>
										</p>
									</a>
								</li>
                                <?php }} ?>

							</ul>
							<ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-1F-4g']) && is_array($output['tpl']['home-1F-4g'])) { ?>
                                <?php foreach($output['tpl']['home-1F-4g'] as $k13=>$v13) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v13['goods_info']['goods_id'].'-'.$v13['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v13['goods_info']['goods_image'],'max',$v13['goods_info']['store_id']);?>" alt="<?php echo $v13['goods_info']['goods_name'] ?>"  width="200"></p>
										<p class="detail">
											<span class="title font15 color_666"><?php echo mb_substr($v13['goods_info']['goods_name'],0,10,'UTF-8') ?></span>
											<span class="sale color_f60 font16"><i>￥</i><?php echo $v13['goods_info']['goods_store_price']; ?></span>
											<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v13['goods_info']['market_price'] ?></span>
										</p>
									</a>
								</li>
                                <?php }} ?>

							</ul>
						</div>
					</div>

					<!--1F品牌菜单-->
					<div class="brandNav bc_white">
						<h4 class="fl">品牌</h4>
						<ul class="fl">
                            <?php if($output['tpl']['home-first-brand'] && is_array($output['tpl']['home-first-brand'])) {?>
                            <?php foreach($output['tpl']['home-first-brand'] as $k11=>$v11) { ?>
							<li class="fl"><a href="<?php echo $v11['word_link'] ?>" target="blank"><img width="114" height="49" src="<?php echo ATTACH_BRAND.'/'.$v11['brand_info']['brand_pic'];?>" alt=""></a></li>
                            <?php } }?>
                        </ul>
					</div>
					<!--品牌菜单-->
				</div>

				<div class="floorList secondFloor">
					<h3>
						<strong>2F</strong>
						<span>美容美妆</span>
					</h3>
					<ul class="floorNav">
                        <?php if(!empty($output['tpl']['home-second-menu']) && is_array($output['tpl']['home-second-menu'])) { ?>
                        <?php foreach($output['tpl']['home-second-menu'] as $k14=>$v14) { ?>
						<li <?php if($k14 == 0) {?> class="active" <?php } ?>><?php echo $v14['word_value'] ?></li>
                        <?php }} ?>
					</ul>
					<div class="floorContent">
						<div class="floorSidebar fl">
                            <?php if(!empty($output['tpl']['home-second-img']) && is_array($output['tpl']['home-second-img'])) {?>
                                <?php foreach($output['tpl']['home-second-img'] as $k19=>$v19) { ?>
                                    <div class="adv">
                                        <a href="<?php echo $v19['img_link'] ?>" target="blank"><img <?php if($k19 == 0){ ?> width="239" height="341" <?php }else{ ?>width="240" height="116" <?php } ?> src="<?php echo SiteUrl.'/'.$v19['img_url'] ;?>" alt=""></a>
                                    </div>
                                <?php }} ?>
							<div class="categoryNav">
								<ul>	
									<li>					
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=416'; ?>">唇妆</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=475'; ?>" target="_blank">爱丽小屋</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=476'; ?>" target="_blank">VDL</a>
										</div>
										<span class="revamp_icon milk_icon"></span>
									</li>
									<li>						
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=396'; ?>">BB霜</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=462'; ?>" target="_blank">谜尚 </a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=463'; ?>" target="_blank">It's Skin</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=464'; ?>" target="_blank">HolikaHolika</a>
										</div>
										<span class="revamp_icon paper_icon"></span>
									</li>
									<li>						
										<div class="title"><a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=402'; ?>">唇部护理</a></div>
										<div class="catList">
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=467'; ?>" target="_blank">VOV</a>
											<a href="<?php echo SiteUrl.'/index.php?act=search&cate_id=468'; ?>" target="_blank">The Face Shop</a>
										</div>
										<span href="" target="_blank" class="revamp_icon baby_icon"></span>
									</li>
								</ul>
							</div>
						</div>
						<div class="catContent fl pr">
							<ul>
                                <?php if(!empty($output['tpl']['home-2F-g']) && is_array($output['tpl']['home-2F-g'])) { ?>
                                <?php foreach($output['tpl']['home-2F-g'] as $k15=>$v15) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v15['goods_info']['goods_id'].'-'.$v15['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v15['goods_info']['goods_image'],'max',$v15['goods_info']['store_id']);?>" alt="<?php echo $v15['goods_info']['goods_name'] ?>"  width="200"></p>
										<div class="detail">
											<div class="detailWrap">
												<span class="title font15 color_666"><?php echo $v15['goods_info']['goods_name'] ?></span>
												<span class="sale color_f60 font16"><i>￥</i><?php echo $v15['goods_info']['goods_store_price'] ?></span>
												<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo  $v15['goods_info']['market_price'] ?></span>
											</div>
										</div>
									</a>
								</li>
                                <?php }} ?>

							</ul>
							<ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-2F-2g']) && is_array($output['tpl']['home-2F-2g'])) { ?>
                                <?php foreach($output['tpl']['home-2F-2g'] as $k16=>$v16) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v16['goods_info']['goods_id'].'-'.$v16['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v16['goods_info']['goods_image'],'max',$v16['goods_info']['store_id']);?>" alt="<?php echo $v16['goods_info']['goods_name'] ?>"  width="200"></p>
										<div class="detail">
											<div class="detailWrap">
												<span class="title font15 color_666"><?php echo $v16['goods_info']['goods_name'] ?></span>
												<span class="sale color_f60 font16"><i>￥</i><?php echo $v16['goods_info']['goods_store_price'] ?></span>
												<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo  $v16['goods_info']['market_price'] ?></span>
											</div>
										</div>
									</a>
								</li>
                                <?php }} ?>

							</ul>
                            <ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-2F-3g']) && is_array($output['tpl']['home-2F-3g'])) { ?>
                                <?php foreach($output['tpl']['home-2F-3g'] as $k17=>$v17) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v17['goods_info']['goods_id'].'-'.$v17['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v17['goods_info']['goods_image'],'max',$v17['goods_info']['store_id']);?>" alt="<?php echo $v17['goods_info']['goods_name'] ?>"  width="200"></p>
										<div class="detail">
											<div class="detailWrap">
												<span class="title font15 color_666"><?php echo $v17['goods_info']['goods_name'] ?></span>
												<span class="sale color_f60 font16"><i>￥</i><?php echo $v17['goods_info']['goods_store_price'] ?></span>
												<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo $v17['goods_info']['market_price'] ?></span>
											</div>
										</div>
									</a>
								</li>
                                <?php }} ?>

							</ul>
                            <ul style="display:none;">
                                <?php if(!empty($output['tpl']['home-2F-4g']) && is_array($output['tpl']['home-2F-4g'])) { ?>
                                <?php foreach($output['tpl']['home-2F-4g'] as $k18=>$v18) { ?>
								<li class="white bc_white fl">
									<a href="<?php echo SiteUrl.'/goods-'.$v18['goods_info']['goods_id'].'-'.$v18['goods_info']['store_id'].'.html'; ?>" target="blank">
										<p class="pic"><img src="<?php echo cthumb($v18['goods_info']['goods_image'],'max',$v18['goods_info']['store_id']);?>" alt="<?php echo $v18['goods_info']['goods_name'] ?>"  width="200"></p>
										<div class="detail">
											<div class="detailWrap">
												<span class="title font15 color_666"><?php echo $v18['goods_info']['goods_name'] ?></span>
												<span class="sale color_f60 font16"><i>￥</i><?php echo $v18['goods_info']['goods_store_price'] ?></span>
												<span class="market color_999 td_lt"><i>市场价：￥</i><?php echo  $v18['goods_info']['market_price'] ?></span>
											</div>
										</div>
									</a>
								</li>
                                <?php }} ?>

							</ul>
						</div>
					</div>


                    <!--2F品牌菜单-->
                    <div class="brandNav bc_white">
                        <h4 class="fl">品牌</h4>
                        <ul class="fl">
                            <?php if($output['tpl']['home-second-brand'] && is_array($output['tpl']['home-second-brand'])) {?>
                                <?php foreach($output['tpl']['home-second-brand'] as $k20=>$v20) { ?>
                                    <li class="fl"><a href="<?php echo $v20['word_link'] ?>" target="blank"><img width="114" height="49" src="<?php echo ATTACH_BRAND.'/'.$v20['brand_info']['brand_pic'];?>" alt=""></a></li>
                                <?php } }?>
                        </ul>
                    </div>
                    <!--品牌菜单-->

				</div>
		
			</div>
			<!--楼层-->
		</div>
	</div>
	<!--主体内容-->
	<!--固定导航-->
	<div class="fixedNavWrap pf bc_white">
	    <div class="fixedNavCon of_h">
	        <div class="fixedLog fl"><a href="#" target="_blank"><img src="<?php echo TEMPLATES_PATH;?>/images/newindex/fixed_logo.png" alt="" /></a></div>
	        <div class="searchWrap fl">
	            <form action="index.php" method="get" class="fl">
	                <input name="act" id="search_act" value="search" type="hidden">
	                <div class="searchTxt">
	                    <span class="revamp_icon"></span>
	                    <input type="text" value="" placeholder="请输入你想要买的商品…" name="keyword" id="fixed_search" class="w100 search_input" />
	                </div>
	                <input type="submit" value="搜索" id="fixed_subBtn" class="font14 white bc_black fl lh29 subBtn_input ff_yh   " >
	            </form>
	            <div class="shopCart pr fl lh29"><a href="<?php echo SiteUrl.'/';?>index.php?act=cart" target="_blank" class="black"><span class="revamp_icon ta_c lh14 white"><?php echo intval($output['goods_num']); ?></span>我的购物车</a></div>
	        </div>
	    </div>
	</div>
	<!--固定导航-->



