<?php defined('haipinlegou') or exit('Access Invalid!');?>

<link href="<?php echo TEMPLATES_PATH;?>/css/home_index.css" rel="stylesheet" type="text/css">

<style type="text/css">

.indexnav-total-main { display: block !important;}

.border-all { border: 1px dashed #dfdfdf;}

</style>

<!--[if IE 6]>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/ie6.js" charset="utf-8"></script>

<![endif]-->	

<script src="<?php echo RESOURCE_PATH;?>/js/jquery.KinSlideshow.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.accordion.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/tonjay.js" ></script>

<script src="<?php echo RESOURCE_PATH;?>/js/swfobject_modified.js" type="text/javascript"></script>

<script src="<?php echo RESOURCE_PATH;?>/js/jquery.lazyload.mini.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/index.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.flexslider-min.js" charset="utf-8"></script>
<!-- 首页对联广告 -->

<script src="<?php echo RESOURCE_PATH;?>/js/double_adv.js" type="text/javascript"></script>

<script src="<?php echo RESOURCE_PATH;?>/js/time.js" type="text/javascript"></script>

<!--
<div class="navanchor pr">

	

	<div class="w1210 pr">

		<ul class="ul-navanchor font-mic">

		    <!--<li><a href="index.php?act=xianshi">限时购</a></li>

			<li><a href="index.php?act=show_groupbuy">团购</a></li>

			<li><a href="javascript:void(0);">品牌推荐</a></li>

			<li><a  href="#Anchor1">母婴用品</a></li>

			<li><a  href="#Anchor2">美容美妆</a></li>

			<li><a  href="#Anchor3">食品保健</a></li>

			<li><a  href="#Anchor4">数码家电</a></li>

			<li><a  href="#Anchor5">汽车用品</a></li>

			<li><a  href="#Anchor6">生活用品</a></li>

		</ul>

		<div class="bantj">

			<div id="bantjtitle" class="bantjtitle font-mic">

			   <?php if (!empty($output['show_article']) && is_array($output['show_article'])) {$i = 0;$num=1;?>

				<?php foreach ($output['show_article'] as $key => $val) {$i++;?>

				<span <?php if($num>2){?>style="display:none;"<?php }?> <?php if($key=='notice'){?>id="spanddd" style="border-right:1px solid #dfdfdf;"<?php }?> class="<?php echo $i==1 ? 'span-bantjtitle':'';?>"><?php echo $val['ac_name'];?></span>

			    <?php $num++;}?>

			   <?php }?>

			</div>



			<div class="bantjmain" class="bantjmain">

			 <div class="bantjlist bantj-mszc">

				<p class="font-mic">尊敬的消费者：</p>

<p class="font-mic">

大家好！非常感谢您对海品乐购的关注和支持。依托跨境电商试点资质，我们承诺以最优价格提供海外原装正品，阳光快捷的一站式服务带给您超值体验。</p>

			</div>

			<div class="bantjlist bantj-cxhd" style="display:none;">

			<p class="font-mic" style="line-height:25px; font-size: 14px; ">海关授权网购在广州保税区的全球商品，按行李包裹征收行邮税。</p>

				<p class="font-mic" style="line-height:25px; font-size: 14px; ">1. 大部分商品单笔订单500元内（税额不超过50元）海关免征。</p>

<p class="font-mic" style="line-height:25px; font-size: 14px; ">2. 单笔订单不能超过1000元，单件商品超过1000元不在限制范围。</p>

<p class="font-mic" style="line-height:25px; font-size: 14px; ">3. 目前没有限定每年购物总额。只限自用和馈赠，严禁转卖盈利！</p>

			</div>

			</div>



		</div>



	</div>

	

</div>
-->

	<div class="banner indexbanner">
		<div class="banimg">
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=13"></script>
		</div>
	</div>




	<div class="w1200">

	<!--

	 <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) {$num=1; ?>

		<div class="timelimitgoods">

			<div class="listtitle pr">

				<h2 class="timelimitgoods-t"></h2>

				<a class="a-goodsmore" href="index.php?act=xianshi">全部正在进行的限时购</a>

			</div>

			

			<div class="timelimitgoodsin font-mic">

				<ul class="ul-timelimitgoodsin">

                 <?php foreach($output['xianshi_item'] as $key=>$val) { ?>				

					<li <?php if($num>5){?>style="display:none;"<?php }?>>

						<div class="timelimitborder">

							<a target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>"><img src="<?php echo thumb($val,'small');?>" onload="javascript:DrawImage(this,150,150);" alt="<?php echo $val['goods_name']; ?>" style="height:150px" /></a>

							<h4><a target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>" title="<?php echo $val['goods_name']; ?>"><?php echo mb_strcut($val['goods_name'],0,30,'utf-8'); ?></a></h4>

							<p class="p-goodsprice"><?php echo $lang['index_index_store_goods_price'].$lang['nc_colon'];?><span><b><?php echo $val['goods_store_price']; ?></b></span></p>

							 <?php $time = intval($val['end_time']) - time();?>

							<p class="timelast"><span><?php echo floor($time/86400);?></span>天<span><?php echo floor($time%86400/3600);?></span>时<span><?php echo floor($time%86400%3600/60);?></span>分</p>

						</div>

					</li>

				 <?php }?>

				</ul>

			</div>

		</div>

	 <?php $num++;}?>

		<div class="teambuy pdt40">

			<div class="listtitle pr">

				<h2 class="teambuy-t">团购<span>团购更划算</span></h2>

				<a class="a-goodsmore" href="index.php?act=show_groupbuy">全部正在进行的团购</a>

			</div>

			<?php if(is_array($output['groupbuy_template'])) { ?>

			<?php if(is_array($output['groupbuy_list'])) {$num=1; ?>

			<div class="teambuyin font-mic">

				<ul class="ul-teambuyin">						

					<?php foreach($output['groupbuy_list'] as $key=>$groupbuy) { ?>

					<li <?php if($key==0){?> class="nomargin-l"<?php }?> <?php if($num>3){?>style="display:none;"<?php }?>>

						<div class="teambuyinborder">

							<a title="" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" target="_blank"><img style="width:390px; height:260px;" src="<?php echo gthumb($groupbuy['group_pic'],'mid');?>" alt="" onload="javascript:DrawImage(this,390,260);"></a>

							<div class="teambuymess pr">

								<h4>

									<a title="<?php echo $groupbuy['group_name'];?>" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" target="_blank"><?php echo $groupbuy['group_name'];?></a>

								</h4>

								<p class="p-goodsprice">乐购价:<span><b><?php echo $lang['currency'].$groupbuy['goods_price'];?></b></span></p>

								<p class="p-goodsnumber">已有<span><?php echo $groupbuy['def_quantity']+$groupbuy['virtual_quantity'];?>人</span>团购</p>

								<?php if($groupbuy['state'] == '3') { ?>

								<a class="a-teambuybtn" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" style="cursor:pointer" target="_blank">立即团购</a>

							    <?php }else{?>

								 <a class="a-teambuybtn" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" style="cursor:pointer" target="_blank">团购结束</a>

								<?php }?>

							</div>

						</div>

					</li>

					<?php }?>

				</ul>

			</div>

			<?php $num++;}?>

			<?php }?>

		</div>-->
		
		
		
		
		

		<!--<div class="brandrecommend pdt40">

			<div class="listtitle pr">

				<h2 class="brandrecommend-t">商品推荐</h2>

				<a class="a-goodsmore" href="<?php echo SiteUrl;?>/index.php?act=brand">热销商品</a>

			</div>

			<div class="brandrecommendin font-mic">
			   
			  <ul class="specially">
				<?php if(!empty($output['recommend_best_item']) && is_array($output['recommend_best_item'])) { ?>
				<?php foreach($output['recommend_best_item'] as $val) { ?>
				<li>
				  <dl>
					<dt class="goods-name"><a target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>" title="<?php echo $val['goods_name']; ?>"><?php echo $val['goods_name']; ?></a></dt>
					</dt>
					<dd class="goods-pic"><a target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>"> <span class="thumb size150"><i></i><img src="<?php echo thumb($val,'small');?>" onload="javascript:DrawImage(this,150,150);" alt="<?php echo $val['goods_name']; ?>" /></span></a></dd>
					<dd class="goods-price"> <?php echo $lang['index_index_store_goods_price'].$lang['nc_colon'];?><em><?php echo $val['goods_store_price']; ?></em><?php echo $lang['currency_zh']; ?></dd>
					<dd class="sale"><?php echo $lang['index_index_special_goods'];?></dd>
				  </dl>
				</li>
				<?php } ?>
				<?php } ?>
			  </ul>
				<!--品牌推荐
				<ul class="ul-brandrecommendin">

					<?php if(!empty($output['brand_r']) && is_array($output['brand_r'])){$num=1;?>

					<?php foreach($output['brand_r'] as $key=>$brand_r){?>

					<li <?php if($key==0 || $key==5){?> class="nomargin-l"<?php }else{?>class=" "<?php } ?> <?php if($num>10){?>style="display:none;"<?php }?>>

					<a href="<?php echo ncUrl(array('act'=>'brand','op'=>'list','brand'=>$brand_r['brand_id']));?>" target="_blank">

					<a href="javascript:void(0);">

					<img src="<?php if(!empty($brand_r['brand_pic'])){echo ATTACH_BRAND.'/'.$brand_r['brand_pic'];}else{echo TEMPLATES_PATH.'/images/default_brand_image.gif';}?>" onload="javascript:DrawImage(this,228,152);"  alt="" title="<?php echo $brand_r['brand_name'];?>" />

					</a>

					<p><?php echo $brand_r['brand_name'];?></p>

					</li>

					<?php $num++;}?>

					<?php }?>

				</ul>

			</div>				

		</div>-->

		<!--S首页中部广告位-->
		<div class="goodsaddtop">
		    <!--
			<?php foreach($output['product_list'] as $k=>$v){?>
			    <a class="a-goodsaddtop" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['goods_id']), 'goods'); ?>" style="text-align:center;"><img src="<?php echo thumb($v,'small')?>"></a>
			<?php }?>
			-->
			
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=413"></script>
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=414"></script>
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=415"></script>
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=416"></script>
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=417"></script>
			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=418"></script>
			
			</div>


		<?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])){?>
		<div class="specialoffer m-btm">
			<div class="specialoffer-top pr">
				<h2 class="h2-specialoffer font-mic fl">限时特卖</h2>
				<p class="p-specialoffer-top font-mic">每日严选 品牌折扣限时选</p>
				<span class="span-specialoffer-top">今日30个精选品牌</span>
				<a  target="_blank" class="a-specialoffer-top" href="index.php?act=xianshi">+ 全部限时特价商品</a>
			</div>
			<div class="specialoffermain pr">
				<div class="specialoffermainlist">
					<ul class="ul-specialoffermain" style="width:10000px">
					<?php foreach($output['xianshi_item'] as $key=>$val) { ?>
						<li class="li-specialoffermain" <?php if($num>4){?>style="display:none;"<?php }?>>
							<div class="specialoffer-title">
							    <a class="a-specialoffer-img" target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>"><img src="<?php echo thumb($val,'max');?>" onload="javascript:DrawImage(this,150,150);" alt="<?php echo $val['goods_name']; ?>" style="left:0;height:235px;width:235px;" /></a>
								
								<a class="a-specialoffer-name" target="_blank" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']), 'goods'); ?>" title="<?php echo $val['goods_name']; ?>"><b class="c-red"><?php echo $val['discount'];?>折</b><?php echo $val['goods_name']; ?></a>
								
							</div>
							<div class="specialoffer-price">
								<b class="b-specialoffer-price font-mic c-red"><i>￥</i><?php echo $val['xianshi_price']; ?></b>
								<del class="del-specialoffer-price">原价：<?php echo $val['goods_store_price']; ?></del>
							</div>
							<div class="specialoffer-bytime">
							    <?php $time = intval($val['end_time']) - time();?>

								<!-- <p class="date-specialoffer-bytime c-red">剩：<?php echo floor($time/86400);?>天<?php echo floor($time%86400/3600);?>小时<?php echo floor($time%86400%3600/60);?>分<?php echo floor($time%86400%3600/60/60);?>秒</p> -->
								<p class="date-specialoffer-bytime c-red" data-time="<?php $time = intval($val['end_time']);echo $time?>">剩：<span>00天 00 时 00 分 00 秒</span></p>
								<!--<p class="p-specialoffer-bytime"><span></span><a href="">立即抢购</a>852件已售</p>-->
							</div>
						</li>
						<?php }?>
					</ul>
				</div>
				<!--
				<a class="a-runpre a-specialofferpre" href=""></a>
				<a class="a-runnet a-specialoffernet" href=""></a>
				-->
			</div>
		</div>
		<?php }?>
		

		<div class="contad" style="margin-bottom:20px;">

			<script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=374"></script>

		</div>

		<!--E首页中部广告位-->

		
 <!--产品列表begin-->

		<?php
		if(is_array($output['show_goods_class']) && count($output['show_goods_class']) != 0){
            $sign = 1;
            $n = 0;
		foreach ($output['show_goods_class'] as $tkey=>$val){
		if ($val['gc_parent_id'] != 0) break;

		?>	

		<!--首页分类商品排行 $sign限制限时多少行 78热门-->

		<div class="indexgoodslist floor<?php echo $sign;?> m-btm" <?php if($sign>2){?>style="display:none;"<?php }?>>
			<div class="indexgoodslisttop pr">
				<h2 class="indexgoodstitle"><?php echo $sign;?>F</h2>
				<p class="p-indexgoodstitle font-mic"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" style="color:#ffffff"><?php echo $val['gc_name'];?></a></p>
				<div class="indextab pr">
					<ul class="ul-indextab">
						<li class="li-indextab li-indextabon"><a href="javascript:void(0);">热门</a><span class="i-triangle"></span></li>
						<?php 

						if($val['child'] != ''){

						$d = 0;
						foreach(explode(',',$val['child']) as $k){				

						?>

						<?php if($d < 3){?>
						<!--
						<li class="li-indextab"><a href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						-->
						<li class="li-indextab"><a  href="javascript:void(0);"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						<?php }?>
						<?php $d++;}}?>
					</ul>
				</div>
			</div>
			<div class="indexgoodslistmain">
				<div class="lefttopadd fl">
				    <?php if(!empty($output['father_class'])){?>
					<?php foreach($output['father_class'] as $key=>$value){?>
								
					<?php if($val['gc_id'] == $value['gc_id']){?>

						<a  class="a-lefttopaddtop" href="javascript:void(0);"><img style="width:209px; height:235px;" src="<?php if(!empty($value['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$value['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>" /></a>

					<?php }?>

					<?php }?>

					<?php }?>
					
					<div class="lefttopaddbtm pr">
						<div class="lefttopaddbtmbox">
							<ul>
								<?php if(!empty($output['brand_c_list']) && is_array($output['brand_c_list'])){?>
								<?php foreach($output['brand_c_list'] as $key=>$value){$num=1;?>
								<?php if($val['gc_name'] == $value['brand_class'] && !empty($value['brand_pic']) && in_array($value['brand_name'],array('nuk','大王','花王', 'innisfree','ETUDE HOUSE 爱丽小屋','丽得姿','VDL','爱他美'))){?>
									<li class="li-lefttopaddbtmbox" style="<?php if($num>3){?>display:none;<?php }?>"><a target="_blank" href="/index.php?act=search&brand_id=<?php echo $value['brand_id'];?>"><img src="<?php if(!empty($value['brand_pic'])){ echo ATTACH_BRAND.'/'.$value['brand_pic'];}?>"></a></li>
								<?php $num++;}?>
								<?php }?>
								<?php }?>
							</ul>
						</div>
						<!-- <a  class="a-runpre a-lefttopaddpre" href="javascript:void(0);"></a>
						<a class="a-runnet a-lefttopaddnet" href="javascript:void(0);"></a> -->
					</div>
					
					
					<p class="p-lefttopaddbtm">
					<?php 

						if($val['child'] != ''){

						$d = 0;
						foreach(explode(',',$val['child']) as $k){	
						?>

						<?php if($d < 3){?>
						<a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
					<?php }$d++;}}?>
					</p>
					
				</div>

				<div class="midgoodslist">
					<div class="div-midgoodslist div-midgoodsliston">
					
						<!--<div class="righttopadd fr">
							 <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 408+$sign;?>"></script>
						</div>-->

						<div class="righttopadd fr" style="display:none;">
							<div class="righttopaddbox">
								<ul class="ul-righttopaddbox">
									<li><a target="_blank"   href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
									<li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
									<li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
								</ul>
							</div>
							<a  class="a-runpre a-lefttopaddpre" style="display:none;" href="javascript:void(0);"></a>
							<a  class="a-runnet a-lefttopaddnet" style="display:none;" href="javascript:void(0);"></a>
						</div>
						
						<div class="hot-midgoodslist">
							<ul class="ul-midgoodslist">
					<?php if(is_array($output['goods_list'])){$num=1;?>
						 <?php foreach($output['goods_list'] as $key=>$value){?>
						 <?php if(!empty($value)){?>
						  <?php foreach($value as $k=>$v){?>
                           <?php if($v['goods_state'] != 1 ){?>
							<?php if($val['gc_id'] == $key){ ?>
                            <?php if($num<=10){ unset($output['goods_list'][$key][$k]); } ?>
						<li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
							<a target="_blank"  class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>" style="text-align:center;"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
							<a target="_blank" class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
							<b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
						</li>
					<?php $num++;}?>

						  <?php }?>
						  <?php }?>

						 <?php }?>

						 <?php }?>

						 <?php }?>
					</ul>
						</div>
						
					</div>
					<?php if($val['child'] != ''){$d = 0;
						foreach(explode(',',$val['child']) as $kk){	?>
					<div class="div-midgoodslist">
						<div class="normal-midgoodslist">
						
					<ul class="ul-midgoodslist">
						<?php if(is_array($output['goods_list'])){$num=1;?>
						 <?php foreach($output['goods_list'] as $key=>$value){?>
						 <?php if(!empty($value)){?>
						  <?php foreach($value as $k=>$v){?>
						   <?php if($v['goods_state'] != 1 ){?>
							<?php if($val['gc_id'] == $key && $kk == $v['gc_id']){?>
						<li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
							<a class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
							<a class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
							<b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
						</li>
					<?php $num++;}}}}}}?>
					</ul>
					
						</div>
						
					</div>
					<?php }}?>
				</div>
			</div>
		</div>

<!--       三四层     -->
            <div class="indexgoodslist floor<?php echo $sign;?> m-btm" <?php if($sign>2){?>style="display:none;"<?php }?>>
                <div class="indexgoodslisttop pr">
<!--                    <h2 class="indexgoodstitle">--><?php //echo $sign;?><!--F</h2>-->
                  <!--  <p class="p-indexgoodstitle font-mic"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" style="color:#ffffff"><?php echo $val['gc_name'];?></a></p> -->
                    <div class="indextab pr">
                        <ul class="ul-indextab">
                            <li class="li-indextab li-indextabon"><a href="javascript:void(0);">热门</a><span class="i-triangle"></span></li>
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){

                                    ?>

                                    <?php if($d < 3){?>
                                        <!--
						<li class="li-indextab"><a href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						-->
                                        <li class="li-indextab"><a  href="javascript:void(0);"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
                                    <?php }?>
                                    <?php $d++;}}?>
                        </ul>
                    </div>
                </div>
                <div class="indexgoodslistmain">
                    <div class="lefttopadd fl">
                        <?php if(!empty($output['all_class'])){$a=0;?>

                            <?php foreach($output['all_class'] as $key=>$value){?>

                                <?php if($val['gc_id'] == $value['gc_parent_id']){?>
									<?php if($a==0){?>
                                    <a  class="a-lefttopaddtop" href="<?php echo  $value['gc_url']?>"><img style="width:209px; height:235px;" src="<?php if(!empty($value['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$value['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>" /></a>
									<?php }?>
                                <?php $a++;}?>

                            <?php }?>

                        <?php }?>

                        <div class="lefttopaddbtm pr">
                            <div class="lefttopaddbtmbox">
                                <ul>
                                    <?php if(!empty($output['brand_c_list']) && is_array($output['brand_c_list'])){?>
                                        <?php foreach($output['brand_c_list'] as $key=>$value){$num=1;?>
                                            <?php if($val['gc_name'] == $value['brand_class'] && !empty($value['brand_pic']) && in_array($value['brand_name'],array('nuk','大王','花王', 'innisfree','ETUDE HOUSE 爱丽小屋','丽得姿','VDL','爱他美'))){?>
                                                <li class="li-lefttopaddbtmbox" style="<?php if($num>3){?>display:none;<?php }?>"><a target="_blank" href="/index.php?act=search&brand_id=<?php echo $value['brand_id'];?>"><img src="<?php echo ATTACH_BRAND.'/'.$value['brand_pic'];?>"></a></li>
                                                <?php $num++;}?>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </div>
                            <!-- <a  class="a-runpre a-lefttopaddpre" href="javascript:void(0);"></a>
                            <a class="a-runnet a-lefttopaddnet" href="javascript:void(0);"></a> -->
                        </div>


                        <p class="p-lefttopaddbtm">
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){
                                    ?>

                                    <?php if($d == 0){?>
                                        <a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
                                    <?php }$d++;}}?>
                        </p>

                    </div>

                    <div class="midgoodslist">
                        <div class="div-midgoodslist div-midgoodsliston">

                            <!--<div class="righttopadd fr">
							 <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 408+$sign;?>"></script>
						</div>-->

                            <div class="righttopadd fr" style="display:none;">
                                <div class="righttopaddbox">
                                    <ul class="ul-righttopaddbox">
                                        <li><a target="_blank"   href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                    </ul>
                                </div>
                                <a  class="a-runpre a-lefttopaddpre" style="display:none;" href="javascript:void(0);"></a>
                                <a  class="a-runnet a-lefttopaddnet" style="display:none;" href="javascript:void(0);"></a>
                            </div>

                            <div class="hot-midgoodslist">
                                <ul class="ul-midgoodslist">
                                    <?php if(is_array($output['goods_list'])){$num=1;?>
                                        <?php foreach($output['goods_list'] as $key=>$value){?>
                                            <?php if(!empty($value)){?>
                                                <?php foreach($value as $k=>$v){ echo $num;?>
                                                    <?php if($v['goods_state'] != 1 ){ ?>
                                                        <?php if($val['gc_id'] == $key){?>
                                                            <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                <a target="_blank"  class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>" style="text-align:center;"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                <a target="_blank" class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                            </li>
                                                            <?php $num++;}?>

                                                    <?php }?>
                                                <?php }?>

                                            <?php  }?>

                                        <?php }?>

                                    <?php }?>
                                </ul>
                            </div>

                        </div>
                        <!-- 纸尿片类-->
                        <?php if($val['child'] != ''){$d = 0;
                            foreach(explode(',',$val['child']) as $kk){	?>
                                <div class="div-midgoodslist">
                                    <div class="normal-midgoodslist">

                                        <ul class="ul-midgoodslist">
                                            <?php if(is_array($output['goods_list'])){$num=1;?>
                                                <?php foreach($output['goods_list'] as $key=>$value){?>
                                                    <?php if(!empty($value)){?>
                                                        <?php foreach($value as $k=>$v){?>
                                                            <?php if($v['goods_state'] != 1 ){?>
                                                                <?php if($val['gc_id'] == $key && ($kk == $v['gc_id'] || $kk == $v['gc_parent_id'])){?>
                                                                    <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                        <a class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                        <a class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                        <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                                    </li>
                                                                    <?php $num++;}}}}}}?>
                                        </ul>

                                    </div>

                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
            <!--       奶粉层开始     -->

            <div class="indexgoodslist floor<?php echo $sign;?> m-btm" <?php if($sign>2){?>style="display:none;"<?php }?>>
                <div class="indexgoodslisttop pr">
                    <!--                    <h2 class="indexgoodstitle">--><?php //echo $sign;?><!--F</h2>-->
                    <!--  <p class="p-indexgoodstitle font-mic"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" style="color:#ffffff"><?php echo $val['gc_name'];?></a></p> -->
                    <div class="indextab pr">
                        <ul class="ul-indextab">
                            <li class="li-indextab li-indextabon"><a href="javascript:void(0);">热门</a><span class="i-triangle"></span></li>
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){


                                    ?>

                                    <?php if($d < 3){?>
                                        <!--
						<li class="li-indextab"><a href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						-->
                                        <li class="li-indextab"><a  href="javascript:void(0);"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
                                    <?php }?>
                                    <?php $d++;}}?>
                        </ul>
                    </div>
                </div>
                <div class="indexgoodslistmain">
                    <div class="lefttopadd fl">
                        <?php if(!empty($output['all_class'])){$a=0;?>

                            <?php foreach($output['all_class'] as $key=>$value){?>

                                <?php if($val['gc_id'] == $value['gc_parent_id']){?>
                                    <?php if($a==0){?>
                                        <a  class="a-lefttopaddtop" href="<?php echo  $value['gc_url']?>"><img style="width:209px; height:235px;" src="<?php if(!empty($value['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$value['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>" /></a>
                                    <?php }?>
                                    <?php $a++;}?>

                            <?php }?>

                        <?php }?>

                        <div class="lefttopaddbtm pr">
                            <div class="lefttopaddbtmbox">
                                <ul>
                                    <?php if(!empty($output['brand_c_list']) && is_array($output['brand_c_list'])){?>
                                        <?php foreach($output['brand_c_list'] as $key=>$value){$num=1;?>
                                            <?php if($val['gc_name'] == $value['brand_class'] && !empty($value['brand_pic']) && in_array($value['brand_name'],array('nuk','大王','花王', 'innisfree','ETUDE HOUSE 爱丽小屋','丽得姿','VDL','爱他美'))){?>
                                                <li class="li-lefttopaddbtmbox" style="<?php if($num>3){?>display:none;<?php }?>"><a target="_blank" href="/index.php?act=search&brand_id=<?php echo $value['brand_id'];?>"><img src="<?php echo ATTACH_BRAND.'/'.$value['brand_pic'];?>"></a></li>
                                                <?php $num++;}?>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </div>
                            <!-- <a  class="a-runpre a-lefttopaddpre" href="javascript:void(0);"></a>
                            <a class="a-runnet a-lefttopaddnet" href="javascript:void(0);"></a> -->
                        </div>


                        <p class="p-lefttopaddbtm">
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){
                                    ?>

                                    <?php if($d == 0){?>
                                        <a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
                                    <?php }$d++;}}?>
                        </p>

                    </div>

                    <div class="midgoodslist">
                        <div class="div-midgoodslist div-midgoodsliston">

                            <!--<div class="righttopadd fr">
							 <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 408+$sign;?>"></script>
						</div>-->

                            <div class="righttopadd fr" style="display:none;">
                                <div class="righttopaddbox">
                                    <ul class="ul-righttopaddbox">
                                        <li><a target="_blank"   href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                    </ul>
                                </div>
                                <a  class="a-runpre a-lefttopaddpre" style="display:none;" href="javascript:void(0);"></a>
                                <a  class="a-runnet a-lefttopaddnet" style="display:none;" href="javascript:void(0);"></a>
                            </div>

                            <div class="hot-midgoodslist">
                                <ul class="ul-midgoodslist">
                                    <?php if(is_array($output['goods_list'])){$num=1;?>
                                        <?php foreach($output['goods_list'] as $key=>$value){?>
                                            <?php if(!empty($value)){?>
                                                <?php foreach($value as $k=>$v){ echo $num;?>
                                                    <?php if($v['goods_state'] != 1 ){ ?>
                                                        <?php if($val['gc_id'] == $key){?>
                                                            <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                <a target="_blank"  class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>" style="text-align:center;"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                <a target="_blank" class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                            </li>
                                                            <?php $num++;}?>

                                                    <?php }?>
                                                <?php }?>

                                            <?php  }?>

                                        <?php }?>

                                    <?php }?>
                                </ul>
                            </div>

                        </div>
                        <!-- 奶粉类-->
                        <?php if($val['child'] != ''){$d = 0;
                            foreach(explode(',',$val['child']) as $kk){ ?>
                                <div class="div-midgoodslist">
                                    <div class="normal-midgoodslist">

                                        <ul class="ul-midgoodslist">
                                            <?php if(is_array($output['goods_list'])){$num=1;?>
                                                <?php foreach($output['goods_list'] as $key=>$value){?>
                                                    <?php if(!empty($value)){?>
                                                        <?php foreach($value as $k=>$v){ ?>
                                                            <?php if($v['goods_state'] != 1 ){?>
                                                                <?php if($val['gc_id'] == $key && ($kk == $v['gc_id'] || $kk == $v['gc_parent_id'])){?>
                                                                    <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                        <a class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                        <a class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                        <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                                    </li>
                                                                    <?php $num++;}}}}}}?>
                                        </ul>

                                    </div>

                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>

            <!-- 奶粉结束-->
<!--            五六层-->
            <div class="indexgoodslist floor<?php echo $sign;?> m-btm" <?php if($sign>2){?>style="display:none;"<?php }?>  style="display:none;">
                <div class="indexgoodslisttop pr">
                    <!--                    <h2 class="indexgoodstitle">--><?php //echo $sign;?><!--F</h2>-->
                    <!-- <p class="p-indexgoodstitle font-mic"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" style="color:#ffffff"><?php echo $val['gc_name'];?></a></p> -->
                    <div class="indextab pr">
                        <ul class="ul-indextab">
                            <li class="li-indextab li-indextabon"><a href="javascript:void(0);">34热门</a><span class="i-triangle"></span></li>
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){

                                    ?>

                                    <?php if($d < 3){?>
                                        <!--
						<li class="li-indextab"><a href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						-->
                                        <li class="li-indextab"><a  href="javascript:void(0);"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
                                    <?php }?>
                                    <?php $d++;}}?>
                        </ul>
                    </div>
                </div>
                <div class="indexgoodslistmain">
                    <div class="lefttopadd fl">
                       <?php if(!empty($output['all_class'])){$a=0;?>

                            <?php foreach($output['all_class'] as $key=>$value){?>
								   <?php if($val['gc_id'] == $value['gc_parent_id']){?>
									<?php if($a==1){?>
                                    <a  class="a-lefttopaddtop" href="<?php echo  $value['gc_url']?>"><img style="width:209px; height:235px;" src="<?php if(!empty($value['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$value['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>" /></a>
									 <?php }?>	
                                <?php $a++; }?>

                            <?php }?>

                        <?php }?>

                        <div class="lefttopaddbtm pr">
                            <div class="lefttopaddbtmbox">
                                <ul>
                                    <?php if(!empty($output['brand_c_list']) && is_array($output['brand_c_list'])){?>
                                        <?php foreach($output['brand_c_list'] as $key=>$value){$num=1;?>
                                            <?php if($val['gc_name'] == $value['brand_class'] && !empty($value['brand_pic']) && in_array($value['brand_name'],array('nuk','大王','花王', 'innisfree','ETUDE HOUSE 爱丽小屋','丽得姿','VDL','爱他美'))){?>
                                                <li class="li-lefttopaddbtmbox" style="<?php if($num>3){?>display:none;<?php }?>"><a target="_blank" href="/index.php?act=search&brand_id=<?php echo $value['brand_id'];?>"><img src="<?php echo ATTACH_BRAND.'/'.$value['brand_pic'];?>"></a></li>
                                                <?php $num++;}?>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </div>
                            <!-- <a  class="a-runpre a-lefttopaddpre" href="javascript:void(0);"></a>
                            <a class="a-runnet a-lefttopaddnet" href="javascript:void(0);"></a> -->
                        </div>


                        <p class="p-lefttopaddbtm">
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){
                                    ?>

                                    <?php if($d == 1){?>
                                        <a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
                                    <?php }$d++;}}?>
                        </p>

                    </div>

                    <div class="midgoodslist">
                        <div class="div-midgoodslist div-midgoodsliston">

                            <!--<div class="righttopadd fr">
							 <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 408+$sign;?>"></script>
						</div>-->

                            <div class="righttopadd fr" style="display:none;">
                                <div class="righttopaddbox">
                                    <ul class="ul-righttopaddbox">
                                        <li><a target="_blank"   href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                    </ul>
                                </div>
                                <a  class="a-runpre a-lefttopaddpre" style="display:none;" href="javascript:void(0);"></a>
                                <a  class="a-runnet a-lefttopaddnet" style="display:none;" href="javascript:void(0);"></a>
                            </div>

                            <div class="hot-midgoodslist">
                                <ul class="ul-midgoodslist">
                                    <?php if(is_array($output['goods_list'])){$num=1;?>
                                        <?php foreach($output['goods_list'] as $key=>$value){?>
                                            <?php if(!empty($value)){?>
                                                <?php foreach($value as $k=>$v){?>
                                                    <?php if($v['goods_state'] != 1 ){?>
                                                        <?php if($val['gc_id'] == $key){?>
                                                            <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                <a target="_blank"  class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>" style="text-align:center;"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                <a target="_blank" class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                            </li>
                                                            <?php $num++;}?>

                                                    <?php }?>
                                                <?php }?>

                                            <?php }?>

                                        <?php }?>

                                    <?php }?>
                                </ul>
                            </div>

                        </div>
                        <?php if($val['child'] != ''){$d = 0;
                            foreach(explode(',',$val['child']) as $kk){	?>
                                <div class="div-midgoodslist">
                                    <div class="normal-midgoodslist">

                                        <ul class="ul-midgoodslist">
                                            <?php if(is_array($output['goods_list'])){$num=1;?>
                                                <?php foreach($output['goods_list'] as $key=>$value){?>
                                                    <?php if(!empty($value)){?>
                                                        <?php foreach($value as $k=>$v){?>
                                                            <?php if($v['goods_state'] != 1 ){?>
                                                                <?php if($val['gc_id'] == $key && $kk == $v['gc_id']){?>
                                                                    <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                        <a class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                        <a class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                        <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                                    </li>
                                                                    <?php $num++;}}}}}}?>
                                        </ul>

                                    </div>

                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
<!--            七八层-->
            <div class="indexgoodslist floor<?php echo $sign;?> m-btm" <?php if($sign>2){?>style="display:none;"<?php }?> style="display:none;">
                <div class="indexgoodslisttop pr">
                    <!--                    <h2 class="indexgoodstitle">--><?php //echo $sign;?><!--F</h2>-->
                    <!--<p class="p-indexgoodstitle font-mic"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" style="color:#ffffff"><?php echo $val['gc_name'];?></a></p> -->
                    <div class="indextab pr">
                        <ul class="ul-indextab">
                            <li class="li-indextab li-indextabon"><a href="javascript:void(0);">12热门</a><span class="i-triangle"></span></li>
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){

                                    ?>

                                    <?php if($d < 3){?>
                                        <!--
						<li class="li-indextab"><a href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
						-->
                                        <li class="li-indextab"><a  href="javascript:void(0);"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a><span class="i-triangle"></span></li>
                                    <?php }?>
                                    <?php $d++;}}?>
                        </ul>
                    </div>
                </div>
                <div class="indexgoodslistmain">
                    <div class="lefttopadd fl">
                        <?php if(!empty($output['all_class'])){$a=0;?>

                            <?php foreach($output['all_class'] as $key=>$value){?>

                                <?php if($val['gc_id'] == $value['gc_parent_id']){?>
									<?php if($a==2){?>

                                    <a  class="a-lefttopaddtop" href="<?php echo  $value['gc_url']?>"><img style="width:209px; height:235px;" src="<?php if(!empty($value['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$value['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>" /></a>
									 <?php }?>
                                <?php $a++;}?>

                            <?php }?>

                        <?php }?>

                        <div class="lefttopaddbtm pr">
                            <div class="lefttopaddbtmbox">
                                <ul>
                                    <?php if(!empty($output['brand_c_list']) && is_array($output['brand_c_list'])){ ?>
                                        <?php foreach($output['brand_c_list'] as $key=>$value){$num=1;?>
                                            <?php if($val['gc_name'] == $value['brand_class'] && !empty($value['brand_pic']) && in_array($value['brand_name'],array('nuk','大王','花王', 'innisfree','ETUDE HOUSE 爱丽小屋','丽得姿','VDL','爱他美'))){?>
                                                <li class="li-lefttopaddbtmbox" style="<?php if($num>3){?>display:none;<?php }?>"><a target="_blank" href="/index.php?act=search&brand_id=<?php echo $value['brand_id'];?>"><img src="<?php echo ATTACH_BRAND.'/'.$value['brand_pic'];?>"></a></li>
                                                <?php $num++;}?>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </div>
                            <!-- <a  class="a-runpre a-lefttopaddpre" href="javascript:void(0);"></a>
                            <a class="a-runnet a-lefttopaddnet" href="javascript:void(0);"></a> -->
                        </div>


                        <p class="p-lefttopaddbtm">
                            <?php

                            if($val['child'] != ''){

                                $d = 0;
                                foreach(explode(',',$val['child']) as $k){
                                    ?>

                                    <?php if($d == 2){?>
                                        <a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>"title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
                                    <?php }$d++;}}?>
                        </p>

                    </div>

                    <div class="midgoodslist">
                        <div class="div-midgoodslist div-midgoodsliston">

                            <!--<div class="righttopadd fr">
							 <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 408+$sign;?>"></script>
						</div>-->

                            <div class="righttopadd fr" style="display:none;">
                                <div class="righttopaddbox">
                                    <ul class="ul-righttopaddbox">
                                        <li><a target="_blank"   href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                        <li><a target="_blank" href=""><img style="width:379px;height:470px" border="0" src="/upload/adv/5502987cd017b63afa7dd679afb48fe2.jpg" alt=""></a></li>
                                    </ul>
                                </div>
                                <a  class="a-runpre a-lefttopaddpre" style="display:none;" href="javascript:void(0);"></a>
                                <a  class="a-runnet a-lefttopaddnet" style="display:none;" href="javascript:void(0);"></a>
                            </div>

                            <div class="hot-midgoodslist">
                                <ul class="ul-midgoodslist">
                                    <?php if(is_array($output['goods_list'])){$num=1;?>
                                        <?php foreach($output['goods_list'] as $key=>$value){?>
                                            <?php if(!empty($value)){?>
                                                <?php foreach($value as $k=>$v){?>
                                                    <?php if($v['goods_state'] != 1 ){?>
                                                        <?php if($val['gc_id'] == $key){?>
                                                            <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                <a target="_blank"  class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>" style="text-align:center;"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                <a target="_blank" class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                            </li>
                                                            <?php $num++;}?>

                                                    <?php }?>
                                                <?php }?>

                                            <?php }?>

                                        <?php }?>

                                    <?php }?>
                                </ul>
                            </div>

                        </div>
                        <?php if($val['child'] != ''){$d = 0;
                            foreach(explode(',',$val['child']) as $kk){	?>
                                <div class="div-midgoodslist">
                                    <div class="normal-midgoodslist">

                                        <ul class="ul-midgoodslist">
                                            <?php if(is_array($output['goods_list'])){$num=1;?>
                                                <?php foreach($output['goods_list'] as $key=>$value){?>
                                                    <?php if(!empty($value)){?>
                                                        <?php foreach($value as $k=>$v){?>
                                                            <?php if($v['goods_state'] != 1 ){?>
                                                                <?php if($val['gc_id'] == $key && $kk == $v['gc_id']){?>
                                                                    <li class="li-midgoodslist" style="<?php if($num>10){?>display:none;<?php }?>">
                                                                        <a class="a-midgoodslistimg" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>&id=<?php echo $v['store_id'];?>"><img src="/upload/store/goods/<?php echo $v['store_id'];?>/<?php echo $v['goods_image'];?>" alt="<?php echo $v['goods_name'];?>"  onload="javascript:DrawImage(this,120,120);" ></a>
                                                                        <a class="a-midgoodslistname" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a>
                                                                        <b class="b-midgoodslistprice">￥<?php echo $v['goods_store_price'];?></b>
                                                                    </li>
                                                                    <?php $num++;}}}}}}?>
                                        </ul>

                                    </div>

                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>


            <?php $sign++;$n++;}}  ?>
		
        <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=408"></script>
	</div>
