<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="<?php echo $output['display_mode'];?>" nc_type="current_display_mode">
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="ul-goodsclassifylist">
    <?php foreach($output['goods_list'] as $goods){?>
		  <li class="li-goodsclassifylist item">
			<div class="li-goods-bor">
				<div class="goodsclassifylist-div-hover">
				<div class="goodscla-div">
				<a class="a-specialoffer-img" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$goods['goods_id']),'goods',$goods['store_domain']);?>" target="_blank" title="<?php echo $goods['goods_name'];?>"><img src="<?php echo thumb($goods,'small');?>" onload="javascript:DrawImage(this,160,160);" title="<?php echo $goods['goods_name'];?>" alt="<?php echo $goods['goods_name'];?>" /></a>
				</div>
				
				<a class="a-specialoffer-name" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$goods['goods_id']),'goods',$goods['store_domain']);?>" target="_blank" title="<?php echo $goods['goods_name'];?>"><span><?php echo round(($goods['goods_store_price']/$goods['market_price']*10),1);?>折/</span><?php echo $goods['goods_name'];?></a>
				<!-- S 促销价格显示 -->
				<?php if(intval($goods['group_flag']) === 1) { ?>
				<!-- 团购 -->
				
				<b class="price-prev">￥<del><?php echo $goods['goods_store_price'];?></del></b>
				<b class="b-goodsclassify-price">￥<?php echo $goods['group_price'];?></b>
				<?php } elseif(intval($goods['xianshi_flag']) === 1) { ?>
				
				<?php
                    if(isset($output['goods_xianshi'][$goods['goods_id']])&&!empty($output['goods_xianshi'][$goods['goods_id']])){
                        $xianshi_price=ncPriceFormat($output['goods_xianshi'][$goods['goods_id']]['xianshi_price']);
                    }else{
                        $xianshi_price = ncPriceFormat($goods['goods_store_price'] * $goods['xianshi_discount']);
                    }
                    ?>
				
				<b class="price-prev" >￥<del><?php echo $goods['goods_store_price'];?></del></b>
				<b class="b-goodsclassify-price">限时折扣:￥<?php echo $xianshi_price;?></b>
				<?php } else { ?>
				<a class="price-prev">￥<del><?php echo $goods['market_price'];?></del></a>
				<b class="b-goodsclassify-price">￥<?php echo $goods['goods_store_price'];?></b>
				<?php }?>

					<p class="p-goodsclassify-do">
					  <a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>&id=<?php echo $goods['store_id'];?>" class="a-goodsclassify-incart2" title="<?php echo $lang['goods_index_add_to_cart'];?>">立即购买</a> 
					  <a class="a-goodsclassify-collect" href="javascript:collect_goods('<?php echo $goods['goods_id']?>','count','goods_collect');">收藏</a>
					</p>
				</div>
			</div>
		  </li>
    <?php }?>
  </ul>
  <?php }else{?>
	<!-- 搜索不到产品人性提示 -->
	<div class="outStore">
		<h2>抱歉，没有找到相关的商品</h2>
		<dl>
			<dt>建议您：</dt>
			<dd>1.查看文字是否输入有误</dd>
			<dd>2.调整关键字</dd>
		</dd>
	</div>
  <?php };?>
</div>