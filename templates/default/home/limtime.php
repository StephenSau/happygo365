<link  rel="stylesheet"  type="text/css" href="<?php echo SiteUrl.'/resource/xianshi/'; ?>css/style.css">
<div class="content">

	<div class="banner">
    	<p><img src="<?php echo SiteUrl.'/resource/xianshi/'; ?>images/banner1.jpg"></p>
    </div>

    <div class="special">
        <p><img src="<?php echo SiteUrl.'/resource/xianshi/'; ?>images/special.jpg"></p>
    </div>

    <!--"推荐一"start-->
    <?php if(is_array($output['limbegin_goods'])&&!empty($output['limbegin_goods'])){ ?>
    <?php foreach($output['limbegin_goods'] as $k=>$v){ ?>
    <div class="recommend">
    	<div class="recommend_a">
        	<p><img src="<?php echo cthumb($v['goods_xianshi_image'],'mid',$v['store_id']);?>"></p>
        </div>
        <div class="recommend_b">
        	<div class="re_time">
            	<p data-time="<?php echo $v['end_time']; ?>">仅剩：<span>00天00时00分00秒</span></p>
			</div>
          <div class="re_info">
            	<p class="inf_a"><a class="in_title" ><?php echo $v['discount']; ?>折/【免税发货】</a>&nbsp;<?php echo $v['goods_name']; ?></p>
                <p class="inf_b"><?php echo $v['goods_description']; ?></p>
                <div class="inf_c">
                    <div class="market">
                        <a class="mon_1">¥</a>
                        <a class="mon_2"><?php echo ncPriceFormat($v['xianshi_price']); ?></a>
                    </div>
                    <div class="orgin">
                        <a>原价：<?php echo empty($v['market_price'])?'':ncPriceFormat($v['market_price']); ?></a>
                    </div>

                    <div class="shop">
                        <p class="have_buy"><a class="bought"><?php echo $v['salenum']; ?></a><a class="bought_f">人已经购买</a></p>
                        <p class="acar"><a href="index.php?act=goods&goods_id=<?php echo $v['goods_id']; ?>"><img src="<?php echo SiteUrl.'/resource/xianshi/'; ?>images/add_car.jpg"></a></p>
                    </div>
                </div>                
            </div>
            
      </div>
    </div>    
    <div class="recommend_c"></div>
    <?php } ?>
    <?php } ?>
    <!--"推荐一"end-->
      
	<div class="next_logo">
    	<p><img src="<?php echo SiteUrl.'/resource/xianshi/'; ?>images/next_logo.jpg"></p>
    </div>
    <div class="noti">
           <!-- 即将开始start -->
        <?php if(is_array($output['limnobegin_goods'])&&!empty($output['limnobegin_goods'])){ ?>
            <?php foreach($output['limnobegin_goods'] as $key=>$val){ ?>
            <div class="notice">
                <p class="noticePic"><img src="<?php echo cthumb($val['goods_xianshi_image'],'mid',$val['store_id']);?>" width="310" height="310" /></p>
                <p class="notice_red"><?php echo number_format($val['discount'],1,'.',''); ?>折/【免税发货】</p>
                <p class="notice_title"><?php echo $val['goods_name']; ?></p>
                <p class="notice_content"><?php echo $val['goods_description']; ?></p>
                <div class="notice_mo">
                <p class="mon_1">¥</p>
                <p class="mon_2"><?php echo ncPriceFormat($val['xianshi_price']); ?></p>
                <p class="mon_3">原价：<?php echo empty($val['market_price'])?'':ncPriceFormat($val['market_price']); ?></p>
                </div>
                <p class="notice_man"><?php echo $val['goods_collect']; ?>人</p>
                <p class="notice_want">想购买</p>
                <p class="notice_favorites"><a href="javascript:collect_goods(<?php echo $val['goods_id']; ?>,'count','goods_collect')"><img src="<?php echo SiteUrl.'/resource/xianshi/'; ?>images/add_favorites.jpg"></a></p>
            </div>
                <?php if($key!=2){ ?>
                <div class="inter"></div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
           <!-- 即将开始end-->
           <div class="notice_bottom"></div>
	</div>

</div>
<script src="<?php echo RESOURCE_PATH;?>/js/time.js"></script>
