<?php defined('haipinlegou') or exit('Access Invalid!');?>
<?php include template('store/goods_header');?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css">

<div class="background clearfix">
  <?php include template('store/goods_top');?>
    <div class="navclass">
    </div>
  <div class="goods_nav">
      <a href="index.php?act=search&cate_id=<?php echo $output['goods_class']['gc_parent_id']; ?>"><b><?php echo $output['goods_class_name'][0]; ?></b>&gt;</a>
      <a href="index.php?act=search&cate_id=<?php echo $output['goods_class']['gc_id']; ?>"><b><?php echo $output['goods_class_name'][1]; ?></b>&gt;</a>
      <a style="color:#FF6400; font-size:14px;" href="index.php?act=search&cate_id=<?php echo $output['goods_class']['gc_id']; ?>"><?php echo $output['goods']['brand_name']?></a>
  </div>
  <div class="content">
		<div class="w1210">
		<!--
			<div class="curmbs">
				-<p>您当前的位置：<a href="">首页</a> > <span>搜索结果</span></p>
			</div>
-->
			<div class="hpgoodsin font-mic">
				<div class="hpgoodstop">
					<div class="hpgoodslook">
						<div class="hpgoodslookleft fl">
							<div class="hpgoodsimg">
								<a href="<?php echo cthumb($output['goods_image'],'max',$output['goods']['store_id']);?>" class="nc-zoom" id="zoom1" rel="position: 'inside' , showTitle: false"><img src="<?php echo cthumb($output['goods_image'],'max',$output['goods']['store_id']);?>"  width="372px" height="372px" alt="" title=""></a>
							</div>
							<div class="hpgoodsbtn">
								<ul class="ul-hpgoodsbtn">
									<?php if(is_array($output['desc_image']) && !empty($output['desc_image'])){?>
									<?php foreach($output['desc_image'] as $key=>$val){
								
									?>
									<?php if($key<1 || !empty($val)){?>
									<li><a href="<?php echo cthumb($val,'max',$output['goods']['store_id']);?>" class="nc-zoom-gallery <?php if($key=='0'){?>hovered<?php }?>" title="" rel="useZoom: 'zoom1', smallImage: '<?php echo cthumb($val,'max',$output['goods']['store_id']);?>' "> <span class="thumb size40"> <i></i> <img src="<?php echo cthumb($val,'max',$output['goods']['store_id']);?>" alt="" onload="javascript:DrawImage(this,60,60);"> </span><b></b> </a></li>
									<?php }?>
									<?php }?>
									<?php }?>
								</ul>
							</div>
						</div>
						<div class="hpgoodslookright">
						    <style type="text/css">
								.summary-name{padding-bottom: 20px;}
								.p-summary-name{font-size: 12px;color: #ff6c00;}
								.summary-gotdata{color: #888888;font-size: 12px;}
								.summary-gotdata span{color: #ff6c00;}
								.span-summary-dutyint{color: #ff6c00;margin-left: 20px;}
								.span-summary-number{color: #ff6c00;margin-left: 20px;}
								.p-sellerpromise-zp{background-position: 0 0;}
								.p-sellerpromise-fh{background-position: 0 -46px;}
								.p-sellerpromise-bz{background-position: 0 -90px;}
								.sellerpromise p a{color: #333333;}
								
								.showinfo{
								  display:none;
								  left:1px;
								  top:1px;
								  width:422px; 
								  min-height:53px; 
								  padding:10px;
								  color:#000000;
								  background:#CCCCCC; 
								  position:fixed; 
								  z-index:999999; 
								  border: 1px solid #5e4e3f;
								  
								 }
							</style>
							<div class="summary-name">
								<h1><?php echo $output['goods']['goods_name']; ?></h1>
								<p>Citize西铁城手表远渡重洋而来，很抱歉中需要维修需要自费前往国内西铁城维修点。</p>
								<?php if($output['store_id'] !=20){?>
									<!-- <p class="p-summary-name">优惠：满199元减20元，满499减50元，全场99元包邮</p> -->
								<?php }?>
							</div>
							<div class="hpgoodslookrightborder">
								<ul class="summary">
										<!-- 限时特卖的时候start -->
										<li style="padding:0;">
											<div class="time_limit_a">
												<div>限时特卖</div>
												<p>距结束：<span>00</span>天<span>00</span>时<span>00</span>分<span>00</span>秒<span>00</span></p>
											</div>
											<div class="time_limit_b">
												<p>
													限时特价：
													<span class="limit_b_num">￥239.00</span>
													海品乐购原价：
													<span class="limit_b_nu">￥99</span>
													<span class="limit_b_jian">直减130</span>
													<del>市场价：￥328.00</del>
												</p>
												<a href="javascript:">关税明细</a>
											</div>
										</li>
										<!-- 限时特卖的时候end -->
										<li class="summary-price">
												<div class="hpgoodsmestitle" style="font-size:12px;"><?php echo $lang['goods_index_goods_price'];?>：</div>
												<div class="hphpgoodsmesmain" style="font-size:26px">
													<div class="p-hpgoodsprice">
														<div class="danjiaWrap">
															<span>￥
			                                                    <?php if($output['store_id'] !=20){?>
			                                                        <b <?php if($output['group_flag'] || ($output['xianshi_flag']&&$output['start_flag'])) echo "class='del'"; ?>nctype="goods_price"><?php echo (($output['goods']['goods_store_price_interval'] == '' || $output['goods']['spec_open'] == '0')? ($output['spec_array'][0]['goods_tax_money']>50)?'<span id="danjia">'.$output['goods']['goods_store_price'].'':'<span id="danjia">'.intval($output['goods']['goods_store_price']).'' : $output['goods']['goods_store_price_interval']); ?></b>
			                                                    <?php }else{?>
			                                                        <b <?php if($output['group_flag'] || ($output['xianshi_flag']&&$output['start_flag'])) echo "class='del'"; ?>nctype="goods_price"><?php echo (($output['goods']['goods_store_price_interval'] == '' || $output['goods']['spec_open'] == '0')? ($output['spec_array'][0]['goods_tax_money']>50)?'<span id="danjia">'.$output['goods']['goods_store_price'].'</span>':'<span id="danjia">'.intval($output['goods']['goods_store_price']).'</span>' : $output['goods']['goods_store_price_interval']); ?></b>
			                                                    <?php }?>
			                                             	</span>
		                                             	</div>
		                                             	<div class="shuijiaWrap">
				                                           <span class="lijianWrap">直减<span id="lijian"><?php echo intval($output['goods']['market_price'] - $output['goods']['goods_store_price']);?></span>
				                                           </span><a class="del-line" style="color: #888888;font-size: 12px;margin-left: 15px;">市场价：￥<del><span id="marketprice"><?php echo intval($output['goods']['market_price'])?></span></del></a>
		                                            	</div>
		                          						
		                                            </div>
		                                           
		                                             <?php  if($output['goods']['goods_store_price_interval'] == '' || $output['goods']['spec_open'] == '0'){?>
		                                             		 <?php  if($output['spec_array'][0]['goods_tax_money']>50){?>
		                                            <input type="hidden" value="<?php echo $output['goods']['goods_store_price']?>" id="yuandanjia"/>
		                                            <input type="hidden" value="<?php echo $output['spec_array'][0]['goods_tax_money']?>" id="yuanshuijia"/>
		                                            		 <?php }else{?>

		                                            <input type="hidden" value="<?php echo $output['goods']['goods_store_price']?>" id="yuandanjia"/>
		                                            <input type="hidden" value="<?php echo $output['spec_array'][0]['goods_tax_money']?>" id="yuanshuijia"/>
		                                           			 <?php }?>
		                                            <?php }else{?>
		                                            <?php if (is_array($output['spec_array']) and !empty($output['spec_array'])) { 
														?>
		                                            <input type="hidden" value="<?php echo $output['spec_array'][0]['spec_goods_price'] ;?>" id="yuandanjia"/>
		                                            <input type="hidden" value="0" id="yuanshuijia"/>
														<?php };?>
		                                            <?php }?>
		                                             <input type="hidden" value="<?php echo $output['goods']['market_price']?>" id="yuanmarketprice"/>
												</div>
												<a href="javascript:" class="tariff_details">关税明细<span>&nbsp;&nbsp;</span></a>
										</li>

										<li class="summary_dl summary_bor">
												<dl>
														<dt>优惠：</dt>
															<dd>
																<p><span>优惠券</span>满199元减20元券，满499减50元券</p>
																<p><span>包邮</span>全场99元包邮</p>
															</dd>
														
												</dl>
										</li>
											

										<li class="summary_dl summary_add ">
												<dl>
														<dt>运费：</dt>
															<dd>
																<p>
																	<select>
																		  <option value ="guangZhou">广州市</option>
																		  <option value ="guangZhou">广州市</option>
																		  <option value ="guangZhou">广州市</option>
																		  <option value ="guangZhou">广州市</option>
																	</select>
																	免运费<em id="nc_kd"></em>
																</p>
																
															</dd>
														<dt style="line-height: 30px;">数量：</dt>
															<dd class="nc-figure-input" style="line-height: 30px;"><a href="javascript:void(0)" class="decrease fl text-hidden">-</a>
																		<input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="fl" style="border-radius:0; text-align: center;">
																		<a href="javascript:void(0)" class="increase fl text-hidden" id="increase">+</a>
																			<span id="totalPirce"></span>
										                                    <input type="hidden" value="<?php echo $output['goods']['spec_goods_storage']; ?>" id="goods_storage" />
																		<em class="fl ml20" style="font-size:12px"><strong nctype="goods_stock" style="display: none;"><?php  if(!empty($output['goods']['spec_goods_storage'])){?>有货<?php }else{?>无货<?php }?></strong>
																		<?php if($goods['store_id']<>20){?>(每单限购1000元)<?php }?>
																		</em> 
															</dd>
												</dl>
										</li>
											
								</ul>
							</div>

						
							<!-- 商品已经下架 -->
							<!-- <div class="hpgoodslookrightnormal">
								<?php if($output['goods']['goods_state'] == '0' && $output['goods']['goods_show'] == '1'){?>
							
								<?php }else{?>
								<dl class="nsg-handle">
								<dt><?php echo $lang['goods_index_is_no_show'];?></dt>
								<dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
								<dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?><a href="<?php echo ncUrl(array('act'=>'show_store','id'=>$output['goods']['store_id']), 'store');?>"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a><?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
								</dl>
								<?php }?>
							</div>
							 -->

						<div>
							<div class="buyandcollect" style="margin-top:105px;width: 350px; float:left;">
								
								<?php if(!empty($output['group_flag'])) { ?>
                                    <?php if(isset($_SESSION['is_login'])&&$_SESSION['is_login']){ ?>
								  <!-- 团购购买--> 
								  <a href="javascript:buy('groupbuy');" class="a-buynow fl text-hidden" title="<?php echo $lang['goods_index_now_buy'];?>">立即购买</a>

								  <?php } ?>
                                    <!-- S 加入购物车弹出提示框 -->
								  <?php } elseif(!empty($output['xianshi_flag']) && !empty($output['start_flag'])) { ?>
                                <?php if(isset($_SESSION['is_login'])&&$_SESSION['is_login']){ ?>
								  <a href="javascript:buy('buynow');" class="a-buynow fl text-hidden" title="<?php echo $lang['goods_index_now_buy'];?>"></a><!-- 立即购买-->
                                <?php } ?>
                                <?php } else { ?>
                                <?php if(isset($_SESSION['is_login'])&&$_SESSION['is_login']){ ?>
								  <a href="javascript:buy('buynow');" id='a-buynow' class="a-buynow fl text-hidden" title="<?php echo $lang['goods_index_now_buy'];?>"></a><!-- 立即购买-->
                                    <?php } ?>
                                    <a href="javascript:buy('');" class="a-collect fl ml10 text-hidden" title="<?php echo $lang['goods_index_add_to_cart'];?>">加入购物车</a>
									
								<div class="ncs_cart_popup">
									<div class="cartWrap">
										<dl>
										  <dt>
											<?php echo $lang['goods_index_cart_success'];?>
											<a class="close" title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs_cart_popup').css({'display':'none'});">x</a></dt>
										  <dd>
											<p class="cartCalc"><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num" style="font-size:14px;font-weight:800">1</strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" style="color:#f60;font-weight:600;margin-left:-2px"></em></p>
											<p class="cartBtn">
											  <input type="submit" class="btn1" name="" value="<?php echo $lang['goods_index_view_cart'];?>" onClick="location.href='<?php echo SiteUrl.DS?>index.php?act=cart'"/>
											  <input type="submit" class="btn2" name="" value="<?php echo $lang['goods_index_continue_shopping'];?>" onClick="$('.ncs_cart_popup').css({'display':'none'});"/>
											</p>
										  </dd>
										</dl>
									</div>
								</div>
							<?php } ?>
							</div>
							<!--分享代码start-->
 
	<div class="bdsharebuttonbox bdsharebuttonbox_font" data-tag="share_1" style=" float:right; width:180px; padding-top: 9px;">	
					<span style="float:left; line-height:28px;">分享到：</span>
					<a class="bds_weixin" data-cmd="weixin"></a>
					<a class="bds_tsina" data-cmd="tsina"></a>
					<a class="bds_tqq" data-cmd="tqq"></a>
					<a class="bds_more" data-cmd="more">更多</a>
	</div>
<!--分享代码end-->

						</div>
				<style>
				 .a-sure a{padding-left:10px;}
				</style>
						</div>
					<div class="clearfix"></div>
					</div>
					<!--
					<div class="hpgoodstj fr">
						<h2 class="hpgoodstjtitle"><span>推荐商品</span></h2>
						<div id="g_fushil"  class="hpgoodstjmian">
						<div id="f_litimg">
						<?php if(is_array($output['hot_collect']) && !empty($output['hot_collect'])){?>
							 <ul class="ul-hpgoodstjmian">
								<?php foreach($output['hot_collect'] as $val){?>
								<li>
									<a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val,'mid');?>" onload="javascript:DrawImage(this,190,190);"></a>
									<!--<a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods');?>"><img src="<?php echo thumb($val,'big');?>" onload="javascript:DrawImage(this,190,190);"></a>
									<p>￥<?php echo $val['goods_store_price']?></p>
								</li>
								<?php }?>
							</ul>
							<?php }?>
						</div>    
					</div>
					<div class="orient">
							<a class="a-orientdown" href="javascript:void(0)" id="fu_top"></a>
							<a class="a-orientup" href="javascript:void(0)" id="fu_down"></a>
						</div>

						</div>	-->

					</div>
					<!--
					<div class="combination pr" <?php if(!$output['mansong_flag']) {?>style="display:none;"<?php }?>>
					<div class="nc-bundling" id="nc-bundling" style="display:none;">
                    </div>
				    </div>
					
					<div class="combination pr">
					<div class="nc-bundling" id="nc-bundling" style="display:none;">
                    </div>
				    </div>
                    -->

              <!--       相关推荐       -->
              	<div class="hpgoods_recommend">
              			<div class="hpgoods_recommend_a">
              					<span>相关推荐</span>
              					购买了该商品的人还买了这些
              			</div>
              			<ul>
              					<li>
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              					<li>
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              					<li>
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              					<li>
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              					<li>
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              					<li class="mrgin_0">
              						<p><img src="http://www.happygo365.com/upload/store/goods/2/2_d19d89b9209b97bf9c8d965958320922.jpg_small.jpg" width="160px" height="160px" alt=""></p>
              						<h2>THEFACESHOP菲诗小铺迪肤适白泥洁净鼻贴膜50G</h2>
              						
              						<span>￥</span>
              						<span class="hpgoods_re_num">183.00</span>
              						<span class="hpgoods_re_man">已有20人评价</span>
              					</li>
              			</ul>
              	</div>
              <!--       相关推荐end    -->

				<div class="hpgoodsmid pdt30">
					<div class="fl">
						<div class="hpgoodsmidleft">
							<h2 class="h3-trademess-left">猜你喜欢</h2>
							<div class="hpgoodsmidin">
							<?php if(is_array($output['hot_sales']) && !empty($output['hot_sales'])){?>
								<ul class="ul-hpgoodsmidin">
								<?php foreach($output['hot_sales'] as $val){?>
									<li>
										<a  href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>">
										<div class="guess_like_box">
											<div class="guess_like_img"><img src="<?php echo thumb($val,'big');?>"  width="70px " heigth="70px" alt="" ></div>
											<div class="guess_like_info">
												<p><?php echo $val['goods_name']?></p>
												￥<span><?php echo $val['goods_store_price']?></span>
											</div>
										</div>
										</a>

									</li>
									<?php }?>
								</ul>
								<?php }?>
							</div>
						</div>
						<div class="hpgoodsmidleft margin_top25" >
							<h2 class="h3-trademess-left">最近浏览</h2>
							<div class="hpgoodsmidin">
								<ul class="ul-hpgoodsmidin">
									<li>
										<a  href="#">
										<div class="guess_like_box">
											<div class="guess_like_img"><img src=" "  width="70px " heigth="70px" alt="" ></div>
											<div class="guess_like_info">
												<p>纸尿片纸尿片纸纸尿片纸尿片尿片</p>
												￥<span>100.00</span>
											</div>
										</div>
										</a>
									</li>
								</ul>
							</div>	
						</div>
					</div>
					<div class="hpgoodsmidright fr">
					<!--
						<div class="hpgoodsmidrighttitle">
							<article id="main-nav-holder">
								<nav id="main-nav">
								<ul id="categorymenu" class="ul-hpgoodsmidrighttitle">
									<li class="li-tabchoice"><a id="tabGoodsIntro" href="#hd1"><?php echo $lang['goods_index_goods_info'];?></a></li>
									<li><a id="tabGoodsRate" href="#hd2"><?php echo $lang['goods_index_goods_consult'];?></a></li>
									<li><a id="tabGoodsTraded" href="#hd3"><?php echo $lang['goods_index_sold_record'];?></a></li>
									<li><a id="tabGuestbook" href="#hd4"><?php echo $lang['goods_index_product_consult'];?></a></li>
									
									<li class="li-tabchoice"><a href="#hd1"><?php echo $lang['goods_index_goods_info'];?></a></li>
									<li><a href="#hd2"><?php echo $lang['goods_index_goods_consult'];?></a></li>
									<li><a href="#hd3"><?php echo $lang['goods_index_sold_record'];?></a></li>
									<li><a href="#hd4"><?php echo $lang['goods_index_product_consult'];?></a></li>
								</ul>
								</nav>
							</article>	
						</div>-->
<!--一定位置固定 -->
<script type='text/javascript' >
			var nt = !1;
			$(window).bind("scroll",
				function() {
				var st = $(document).scrollTop();//取到当前下拉往下滚的高度
				nt = nt ? nt: $("#J_m_nav").offset().top;//获取的导航头部的高度
				// document.title=st;
				var sel=$("#J_m_nav");
				if (nt < st) {
					sel.addClass("nav_fixed");
				} else {
					sel.removeClass("nav_fixed");
				}
			});
</script>	
			<div  id="J_m_nav">
				<div class="trademess-top pr .trademesstop">
					<ul class="ul-trademess-top2">
						<li class="li-trademess-top li-trademess-topon" id="h11"><a href="#hd1" class="h11"><?php echo $lang['goods_index_goods_info'];?></a><span></span></li>
						<li class="li-trademess-top" id="h12"><a href="#hd2" class="h12"><?php echo $lang['goods_index_goods_consult'];?></a><span></span></li>
						<li class="li-trademess-top" id="h13"><a href="#hd3" class="h13"><?php echo $lang['goods_index_sold_record'];?></a><span></span></li>
						<!-- <li class="li-trademess-top" id="h14"><a href="#hd4" class="h14"><?php echo $lang['goods_index_product_consult'];?></a><span></span></li> -->
					</ul>
					<span class="span-trademess-top-left"></span>
					<span class="span-trademess-top-right"></span>
				</div>
			</div>
						<div class="hpgoodsmidrightmain bd">
							<div class="goodsspxq"  id="hd1" name="hd1">
								<?php echo $output['goods']['goods_body']; ?>
							</div>
							<div class="goodssppl">
								<section class="nc-s-c-s4 ncg-comment"  id="hd2" name="hd2">
								  <div class="title hd" style="height: 30px;text-align:30px;color:#555;margin:4px 0 4px 16px;font-size:16px;font-weight:80px;padding-top:10px">
									<!-- <h4><?php echo $lang['goods_index_goods_consult'];?></h4>  -->
								  </div>
								  <div class="goodssppl_pingjia_box">
									<div class="goodssppl_pingjia">
										<?php echo $lang['goods_index_goods_consult'];?>
									</div>
								  </div>
								  <div class="content bd" id="ncGoodsRate" style=" border-top:1px solid #D8D8D8;">
									<table width="100%" border="0" cellpadding="0" cellspacing="0" id="t" class="nc-g-r" style="font-size:12px">
									  <tr>
										<th><p><?php echo $lang['nc_credit_evalstore_type_1'];?><em>5<!--<?php echo $output['store_info']['store_desccredit'];?> --> </em><?php echo $lang['nc_grade'];?></p>
										  <dl class="ncs-rate-column">
											<dt><em style=" right:-32px;<!-- left:  <?php echo $output['store_info']['store_desccredit_rate'] - 4;?>%; -->">
											<!--<?php echo $output['store_info']['store_desccredit'];?> -->5
											</em></dt>
											<dd><?php echo $lang['nc_eval_description_of_grade_1'];?></dd>
											<dd><?php echo $lang['nc_eval_description_of_grade_2'];?></dd>
											<dd><?php echo $lang['nc_eval_description_of_grade_3'];?></dd>
											<dd><?php echo $lang['nc_eval_description_of_grade_4'];?></dd>
											<dd><?php echo $lang['nc_eval_description_of_grade_5'];?></dd>
										  </dl></th>
									</tr>
									</table>
									<!-- 商品评价内容部分 -->
									<div id="goodseval" style="font-size:14px;text-align:left;width:931px"></div>
								  </div>
								</section>
								<!-- 销售记录 -->
								<section class="nc-s-c-s4 ncg-salelog" id="hd3" name="hd3">
								  <div class="title hd"  style="height: 30px;text-align:30px;color:#555;margin:4px 0 4px 16px;font-size:16px;font-weight:80px;padding-top:10px">
									<!-- <h4 class="tooltip"><?php echo $lang['goods_index_sold_record'];?>:</h4> -->
								  </div>
								  <div class="content bd" id="ncGoodsTraded">
					<div class="goodssppl_pingjia_box">
						<div class="goodssppl_pingjia">
							<?php echo $lang['goods_index_sold_record'];?>
						</div>
						
					</div>
									<div class="note">
									  <p><em><?php echo $lang['goods_index_goods_cost_price'];?><strong class="price"><?php echo $output['goods']['goods_store_price'];?></strong><?php echo $lang['goods_index_yuan'];?></em><span class="ml50"><?php echo $lang['goods_index_price_note'];?><!-- 购买的价格不同可能是由于店铺往期促销活动引起的，详情可以咨询卖家 --></span></p>
									</div>
									<!-- 成交记录内容部分 -->
									<div id="salelog_demo" class="ncs-loading" style="width:931px;"> </div>
								  </div>
								</section>
								 <!--
								<section class="nc-s-c-s4 ncg-guestbook" id="hd4" name="hd4">
								  <div class="title hd" style="height: 30px;text-align:30px;color:#555;margin:4px 0 4px 16px;font-size:16px;font-weight:80px;padding-top:10px">
									<h4 class="titbar"><?php echo $lang['goods_index_product_consult'];?>:</h4>
								  </div>
								  
								
								  <div class="content bd" id="ncGuestbook">
									
									<div class="ncg-guestbook">
									  <div id="cosulting_demo" class="ncs-loading" style="font-size:12px;color:red"> </div>
									</div>
								  </div>
								  
								  
								</section>
								-->
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<!--
		<div class="gotop">
		<a class="a-gotop" href="javascript:void(0)"></a>
	</div>
	-->
	</div>
  <?php include template('new_footer');?>
</div>
<form id="groupbuy_form" method="get" action="<?php echo SiteUrl;?>/index.php?act=show_groupbuy&op=groupbuy_buy">
  <input id="act" name="act" type="hidden" value="show_groupbuy" />
  <input id="op" name="op" type="hidden" value="groupbuy_buy" />
  <input id="group_id" name="group_id" type="hidden" value="<?php echo $output['group_info']['group_id'];?>" />
  <input id="groupbuy_spec_id" name="groupbuy_spec_id" type="hidden" />
  <input id="groupbuy_quantity" name="groupbuy_quantity" type="hidden" />
</form>
<form id="buynow_form" method="get" action="<?php echo SiteUrl;?>/index.php?act=buynow">
  <input id="act" name="act" type="hidden" value="buynow" />
  <input id="buynow_spec_id" name="buynow_spec_id" type="hidden"/>
  <input id="buynow_quantity" name="buynow_quantity" type="hidden" value='1' />
</form>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/nc-zoom.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.charCount.js"></script> 
<script src="<?php echo RESOURCE_PATH;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script> 
<script src="<?php echo RESOURCE_PATH;?>/js/sns.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_PATH;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script> 

<script type="text/javascript">
$(function(){
  var page = 1; //当前第几页
  var i = 3; //图片个数
  var $content = $("#f_litimg");//寻找到内容展示区
  var $content_w = $("#g_fushil").height();//内容展示区外围div宽度
  var $len = $("#f_litimg ul li").length;//拿到li的个数
  var page_count = Math.ceil($len/i);  //只要不是整数，就往大的方向取最小的整数
  $("#fu_top").click(function(){
    if( !$content.is(":animated") ){
     if( page == page_count ){
     $content.animate({top:'0px'},"slow"); 
      page=1;
      }else{
       $content.animate({top:'-='+$content_w},"normal");//改变left值，每次换一个版
        page++;
      }
     }
   })
  $("#fu_down").click(function(){
    if( !$content.is(":animated") ){    //判断"视频内容展示区域"是否正在处于动画
     if( page == 1 ){  //已经到第一个版面了,如果再向前，必须跳转到最后一个版面。
    $content.animate({ top : '-='+$content_w*(page_count-1) }, "slow");
    page = page_count;
   }else{
    $content.animate({ top : '+='+$content_w }, "slow");//改变left值，每次换一个版
    page--;
   }
      }
   })
   
   
   //默认选中规格（有的情况下）
   <?php print_r();?>
 	<?php if(is_array($output['goods']['goods_spec'])){ $i=0;?>
	<?php foreach($output['goods']['spec_name'] as $key=>$val){$i++;?>
   			<?php if (is_array($output['goods_spec'][$key]) and !empty($output['goods_spec'][$key])) {?>
				<?php $j=0;foreach($output['goods_spec'][$key] as $k=>$v) {?>
					<?php if($j==0){$j++;?>
						selectSpec(<?php echo $i;?>, "#guige<?php echo $i;?>and<?php echo $k;?>", <?php echo $k;?>);

				<?php }?>
				<?php }?>
			<?php }?>
  <?php }?>
  <?php }?>
 
});
</script>
<script type="text/javascript">
// $("#categorymenu li a").click(function(){
// if($(this).parent("li").has(".li-tabchoice")){
// $(this).parent("li").addClass("li-tabchoice").siblings("li").removeClass("li-tabchoice");
// }})
// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('li-tabchoice');
		$(this).addClass('li-tabchoice');
		
	});
// 商品详情默认情况下显示全部
$('.h11').click(function(){
    $('#h11').addClass('li-trademess-topon');
	$('#h12').removeClass('li-trademess-topon');
	$('#h13').removeClass('li-trademess-topon');
	$('#h14').removeClass('li-trademess-topon');
});
$('.h12').click(function(){
	$('#h12').addClass('li-trademess-topon');
	$('#h11').removeClass('li-trademess-topon');
	$('#h13').removeClass('li-trademess-topon');
	$('#h14').removeClass('li-trademess-topon');
});
$('.h13').click(function(){
	$('#h13').addClass('li-trademess-topon');
	$('#h11').removeClass('li-trademess-topon');
	$('#h12').removeClass('li-trademess-topon');
	$('#h14').removeClass('li-trademess-topon');
});
$('.h14').click(function(){
	$('#h14').addClass('li-trademess-topon');
	$('#h11').removeClass('li-trademess-topon');
	$('#h12').removeClass('li-trademess-topon');
	$('#h13').removeClass('li-trademess-topon');
});
		// 点击评价隐藏其他以及其标题栏
$('#tabGoodsRate').click(function(){
	$('#hd4').css({display:'none'});
	$('#hd3').css({display:'none'});
	$('#hd1').css({display:'none'});
	$('.hd').css({display:'none'});
	$('#ncGoodsRate').css({display:''});
});
$('#tabGoodsTraded').click(function(){
	$('#hd4').css({display:'none'});
	$('#hd1').css({display:'none'});
	$('.hd').css({display:'none'});
	$('#ncGoodsRate').css({display:'none'});
	$('#hd3').css({display:''});
});
$('#tabGuestbook').click(function(){

	$('#hd3').css({display:'none'});
	$('#hd1').css({display:'none'});
	$('.hd').css({display:'none'});
	$('#ncGoodsRate').css({display:'none'});
	$('#hd4').css({display:''});
});
</script>

<script>
// 商品规格选择js部分
var SITE_URL = "<?php if($GLOBALS['setting_config']['enabled_subdomain'] == '1' and $output['store_info']['store_domain']!='') echo "http://".$output['store_info']['store_domain'].'.'.$GLOBALS['setting_config']['subdomain_suffix']; else echo SiteUrl;?>";
var specs = new Array();
var source_goods_price = <?php echo $output['goods']['goods_store_price']; ?>;
<?php if (is_array($output['spec_array']) and !empty($output['spec_array'])) { 
	foreach($output['spec_array'] as $val) {
?>
specs.push(new spec(<?php echo $val['spec_id']; ?>, [<?php echo $val['spec_goods_spec']?>], <?php echo $val['spec_goods_price']; ?>, <?php echo $val['spec_goods_storage']; ?>,<?php echo $val['goods_tax_money']; ?>,<?php echo $val['spec_market_price']; ?>));
<?php
	}
 }
?>
var specQty = <?php if($output['goods']['spec_open'] == 1) echo $output['spec_count']; else echo '0'; ?>;
var defSpec = <?php echo intval($output['spec_array'][0]['spec_id']); ?>;
var goodsspec = new goodsspec(specs, specQty, defSpec);


function buy(type)
{
	var B = false;
	$('ul[nctyle="ul_sign"]').each(function(){
		if(!$(this).find('a').hasClass('hovered')){
	        B = true;
		}
	});
    if (goodsspec.getSpec() == null || B)
    {
        alert('<?php echo $lang['goods_index_pleasechoosegoods']; ?>');
        return;
    }
    var spec_id = goodsspec.getSpec().id;//108
    var quantity = parseInt($("#quantity").val());//1
    if (!quantity>=1)
    {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return;
    }
    max = parseInt($('[nctype="goods_stock"]').text());//9
    if(quantity > max){
    	alert("<?php echo $lang['goods_index_add_too_much'];?>");
    	return;
    }
    switch(type) {
    case 'groupbuy' :
        buynow(spec_id,quantity,"groupbuy");
        break;
    case 'buynow':		
        buynow(spec_id,quantity,'buynow');
        break;
    default:
        add_to_cart(spec_id, quantity);
        break;
    }
}


/* spec对象 */
function spec(id, spec, price, stock,goods_tax_money,spec_market_price)
{
    this.id    = id;
    this.spec  = spec;
    this.price = price;
    this.goods_tax_money = goods_tax_money;
    this.stock = stock;
	this.spec_market_price = spec_market_price;
}
/* goodsspec对象 */
function goodsspec(specs, specQty, defSpec)
{
    this.specs = specs;
    this.specQty = specQty;
    this.defSpec = defSpec;
    <?php for ($i=1; $i<=$output['spec_count'];$i++){?>
    this.spec<?php echo $i?> = null;
    <?php }?>
    if (this.specQty >= 1)
    {
        for(var i = 0; i < this.specs.length; i++)
        {
            if (this.specs[i].id == this.defSpec)
            {
                <?php for ($i=1; $i<=$output['spec_count'];$i++){?>
                this.spec<?php echo $i?> = this.specs[i].spec[<?php echo (intval($i)-1);?>];
                <?php }?>
                break;
            }
        }
    }


    // 取得选中的spec
    this.getSpec = function()
    {
        for (var i = 0; i < this.specs.length; i++)
        {
            <?php for ($i=1; $i<=$output['spec_count'];$i++){?>
            if (this.specs[i].spec[<?php echo (intval($i)-1);?>] != this.spec<?php echo $i?>) continue;
            <?php }?>
            return this.specs[i];
        }
        return null;
    }

}

//加减价格变
function incdec(){
	var quantity=$('#quantity').val();
	var yuandanjia=$("#yuandanjia").val();
	var yuanshuijia=$("#yuanshuijia").val();
	var oTotalPirce = $("#totalPirce");
	var yuanmarketprice=$("#yuanmarketprice").val();
	var totalDanjia = number_format((quantity*yuandanjia),2);
	var totalShuijia = number_format((quantity*yuanshuijia),2);

	if(quantity*yuanshuijia <=50){
		$("#shuijia").html("00.00");
		oTotalPirce.html("总价&yen;"+totalDanjia);
		$("#bold_mly").html(totalDanjia);
		}else{
		$("#shuijia").html("&yen;"+number_format((quantity*yuanshuijia),2));
		oTotalPirce.html("总价&yen;" + (parseFloat(totalDanjia) + parseFloat(totalShuijia)).toFixed(2));
		$("#bold_mly").html((parseFloat(totalDanjia) + parseFloat(totalShuijia)).toFixed(2));
	}

	$("#marketprice").html(number_format((yuanmarketprice),2));
	var marketprice=parseFloat($('#marketprice').html());
    var goodsprice=parseFloat($('#danjia').html());
    $('#lijian').html(number_format((marketprice-goodsprice),2));
}
/*加载执行单价，税价与总价*/
	incdec();
/* 选中某规格 num=1,2 */
function selectSpec(num, liObj, SID)
{
	goodsspec['spec' + num] = SID;
    $(liObj).addClass("hovered");
    $(liObj).parents('li').siblings().find('a').removeClass("hovered");
    var spec = goodsspec.getSpec();
    var sign = 't';

    $('ul[nctyle="ul_sign"]').each(function(){
		if($(this).find('.hovered').html() == null ){
			sign = 'f';
		}
    });
    if (spec != null && sign == 't')
    {

        
		 <?php if($output['store_id'] !=20){?>
		
		if(spec.goods_tax_money>50){
//		$('[nctype="goods_price"]').html('<span id="danjia">'+number_format(spec.price,2)+'</span>(关税：<span id="shuijia">'+number_format(spec.goods_tax_money,2)+'</span>)');
//		}else{
//		$('[nctype="goods_price"]').html('<span id="danjia">'+number_format(spec.price,2)+'</span>(关税：<span id="shuijia">'+number_format(spec.goods_tax_money,2)+'</span>)');
		}
		<?php }else{?>
		if(spec.goods_tax_money>50){

		$('[nctype="goods_price"]').html('<span id="danjia">'+number_format(spec.price,2)+'</span>');
		}else{
		$('[nctype="goods_price"]').html('<span id="danjia">'+number_format(spec.price,2)+'</span>');
		}
		<?php }?>
		$("#marketprice").html(number_format(spec.spec_market_price,2));
		$("#lijian").html(number_format(spec.spec_market_price,2)-number_format(spec.price,2));
		
		$("#yuanmarketprice").val(spec.spec_market_price);
		$("#yuandanjia").val(spec.price);
		$("#yuanshuijia").val(spec.goods_tax_money);

		/*选择规格变化价格*/
		incdec();
		
        //限时折扣价格
        <?php if(!empty($output['xianshi_flag']) && !empty($output['xianshi_goods'])) { ?>
        var discount = <?php echo $output['xianshi_goods']['discount'];?>;
        $('[nctype="xianshi_price"]').html(number_format((spec.price*discount).toFixed(2),2));
        <?php } ?>
        $('[nctype="goods_stock"]').html(spec.stock);
        if(parseInt(spec.stock) == 0){
        	$('[nctype="goods_prompt"]').show().html('<dt><?php echo $lang['goods_index_prompt'];?></dt><dd><em class="no fl"><?php echo $lang['goods_index_understock_prompt'];?></em></dd>');
        }else{
            SP_V = '';
            $('ul[nctyle="ul_sign"]').find('li > .hovered').each(function(i){
				SP_V += $(this).text()+'<?php echo $lang['nc_comma'];?>';
            });
            SP_V = SP_V.substr(0,SP_V.length-1);
        	$('[nctype="goods_prompt"]').show().html('<dt><?php echo $lang['goods_index_prompt'];?></dt><dd><em class="yes fl"><?php echo $lang['goods_index_you_choose'];?>'+SP_V+'</em></dd>');
        }
     }
}

</script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.lazyload.mini.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/newindex.js"></script>
<script type="text/javascript">
    //浮动导航  waypoints.js
    $('#main-nav-holder').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    }); 
    
// 立即购买js
function buynow(spec_id,quantity,type){
<?php if ($_SESSION['is_login'] !== '1'){?>
	//login_dialog();
	window.location = 'index.php?act=login';
<?php }else{?>
    	$("#"+type+"_spec_id").val(spec_id);
    	$("#"+type+"_quantity").val(quantity);
    	$("#"+type+"_form").submit();
<?php }?>
}
$(function(){

    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?act=goods&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data.kd != 'undefined') {$('#nc_kd').html(data.kd);}else{$('#nc_kd').html('');}
	        if(data.py != 'undefined') {$('#nc_py').html(data.py);}else{$('#nc_py').html('');}
	        if(data.es != 'undefined') {$('#nc_es').html(data.es);}else{$('#nc_es').html('');}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    <?php if($output['goods']['goods_state'] == '0' && $output['goods']['goods_show'] == '1'){?>
	$("#nc-bundling").load('index.php?act=goods&op=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>&id=<?php echo $output['goods']['store_id'];?>');
	<?php }?>
  	$("#goodseval").load('index.php?act=goods&op=comments&goods_id=<?php echo $output['goods']['goods_id'];?>&id=<?php echo $output['goods']['store_id'];?>');
	$("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&id=<?php echo $output['goods']['store_id'];?>');
	$("#cosulting_demo").load('index.php?act=goods&op=cosulting&goods_id=<?php echo $output['goods']['goods_id'];?>&id=<?php echo $output['goods']['store_id'];?>');
});
</script> 
<script type="text/javascript">
//收藏分享处下拉操作
	jQuery.divselect = function(divselectid,inputselectid) {
		var inputselect = $(inputselectid);
			$(divselectid).click(function(){
		var ul = $(divselectid+" ul");
			if(ul.css("display")=="none"){
				ul.slideDown("fast");
			}
		});
	$(document).click(function(){
		$(divselectid+" ul").hide();
		});
	};
</script> 
<script type="text/javascript">
$(function(){
	$.divselect("#handle-l");
	$.divselect("#handle-r");
});
</script>
<script type="text/javascript">
// 规格属性
	$.getJSON('index.php?act=goods&op=get_s_a&goods_id=<?php echo $output['goods']['goods_id']; ?>&id=<?php echo $output['goods']['store_id']; ?>', function(data){
		if(data != null){

			<?php if(C('spec_model') == 1){?>
			// 规格
			var SPEC = '';
			if(data['spec_name'] != 'null' &&  data['goods_spec'] != null){
				$.each( data['spec_name'], function(prop_SN, prop_v){
					i++;
					SPEC += '<dl><dt>'+data['spec_name'][prop_SN]+'<?php echo $lang['nc_colon']; ?></dt><dd><ul nctyle="ul_sign">';
					var j=0;
					$.each( data['goods_spec'][prop_SN], function(prop_GS, prop_n){
						P_GS = data['goods_spec'][prop_SN][prop_GS];
						if(typeof(P_GS['name']) == 'undefined' || typeof(P_GS['id']) == 'undefined') return true;
						if(data['goods_col_img'] != 'null' && typeof(data['goods_col_img'][P_GS['name']]) != 'undefined' && data['goods_col_img'][P_GS['name']] != ''){
							SPEC += '<li class="sp-img"><a href="<?php echo SiteUrl.DS.ATTACH_SPEC.DS.$output['goods']['store_id'].DS; ?>'+data['goods_col_img'][P_GS['name']].replace(/_tiny/,"_mid")+'" onClick="selectSpec('+i+', this, '+P_GS['id']+')" class="nc-zoom-gallery" title="'+P_GS['name']+'" rel="useZoom: \'zoom1\', smallImage : \'<?php echo SiteUrl.DS.ATTACH_SPEC.DS.$output['goods']['store_id'].DS; ?>'+data['goods_col_img'][P_GS['name']].replace(/_tiny/, '_mid')+'\'" style=" background-image: url(<?php echo SiteUrl.DS.ATTACH_SPEC.DS.$output['goods']['store_id'].DS;?>'+data['goods_col_img'][P_GS['name']]+');">'+P_GS['name']+'</i></a></li>';
						}else{
							SPEC += '<li class="sp-txt"><a href="javascript:void(0)" onClick="selectSpec('+i+', this, '+P_GS['id']+')" class="">'+P_GS['name']+'<i></i></a></li>';
						}
					});
				  SPEC += '</ul></dd></dl>';
				});
			}
			$('div.nc-spec').html(SPEC);
			$('.nc-zoom-gallery').NCZoom();		// 绑定zoom
			<?php }?>
			
			// 属性
			data['goods_attr'];
			var ATTR = '<?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>'; }?>';
			if(data['goods_attr'] != 'null'){
				for (var pron_A in data['goods_attr']){
					P_A = data['goods_attr'][pron_A];
					if(typeof(P_A['name']) == 'undefined' || typeof(P_A['value']) == 'undefined') continue;
					ATTR += '<li>'+P_A['name']+'<?php echo $lang['nc_colon']; ?>'+P_A['value']+'</li> ';
				}
			}
			$('ul[class="nc-goods-sort"]').html(ATTR);
		}
	});


	
// 百度分享代码
		window._bd_share_config = {
			common : {
				bdText : '<?php echo $output['goods']['goods_name']; ?>',	
				bdDesc : '<?php echo $output['goods']['goods_name']; ?>',	
				bdUrl : "<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>", 	
				bdPic : "<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"
				
			},
			share : [{
				"bdSize" : 16
			}],
		
			
		}

		with(document)[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='<?php echo RESOURCE_PATH;?>/js/share_link.js'];

// var agotop=$(".a-gotop");
	// agotop.click(function(){
			// $('html,body').animate({scrollTop:0},'slow');
		// }
	// );
/*
var MouseEvent = function(e){
 this.x = e.pageX
 this.y = e.pageY
}

var Mouse = function(e){
 var kdheight =  jQuery(document).scrollTop();
 mouse = new MouseEvent(e);
 leftpos = mouse.x+10;
 toppos = mouse.y-kdheight+10; 
}

jQuery(
 function(){
  jQuery(".e1").hover(
   function(e){
    Mouse(e);
    jQuery(".showinfo").css({top:toppos,left:leftpos}).fadeIn(100);
   },function(){
    jQuery(".showinfo").hide();
    }
  )
 }
)*/
// var Mouse = function(e){
 // var kdheight =  $(document).scrollTop();
 // mouse = new MouseEvent(e);
 // leftpos = mouse.x+10;
 // toppos = mouse.y-kdheight+10; 
// }
// $("#ncrecive").mouseover(function(){
	// $(".showinfo").css({top:toppos,left:leftpos}).fadeIn('slow');
// }).mousemove(function(){
	// $(".showinfo").fadeIn('slow');
// }).mouseout(function(){
	// $(".showinfo").fadeOut('slow');
// })

/*$(document).ready(function () {  
	/*var ps = $(".e1").position();  
	$(".showinfo").css("position", "absolute");  
	$(".showinfo").css("left", ps.left + 20); //距离左边距  
	$(".showinfo").css("top", ps.top + 20); //距离上边距  
	$(".e1").mouseenter(function () {  
		$(".showinfo").show();  
	});  
	$(".showinfo").mouseleave(function () {  
		$(".showinfo").hide();  
	});  
})  */

</script>
</body></html>