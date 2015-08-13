<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="sellerlogo">
  <!--<div class="w1210">
    <a href="<?php echo ncUrl(array('act'=>'show_store','id'=>$output['store_info']['store_id']),'store',$output['store_info']['store_domain']);?>" class="img">
    <?php if(!empty($output['store_info']['store_banner'])){?>
    <img style="width:211px;height:96px;" src="<?php echo $output['store_info']['store_logo'];?>"  onload="javascript:DrawImage(this,60,60);" title="<?php echo $output['store_info']['store_name']; ?>"class="pngFix" />
	<?php }else{?>
    <div class="ncs-default-banner pngFix"></div>
    <?php }?>
    </a>
  </div>-->
</div>
<div class="indexnav">
<div class="topnav pr" style="border-bottom:2px solid #ff6400;">
	<div class="w1210 pr">	
		<div class="indexnav-total fl">
				<a class="a-indexnav-total" href="index.php?act=category">所有商品分类</a>
				<div class="indexnav-total-main" id="navleftmain">
				    <?php
					if(is_array($output['show_goods_class']) && count($output['show_goods_class']) != 0){ $sign = 1;
                                       
					foreach ($output['show_goods_class'] as $tkey=>$val){
					if ($val['gc_parent_id'] == 0 && $val['gc_show'] == 1){
					?>
					<div class="index-nav-list" >
						<div class="index-nav-listbox">
							<i class="i-indexleftnav i-indexleftnav<?php echo $sign;?>"></i>
							<h4 class="h4-indexleftnav"><a href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" title="<?php echo $val['gc_name']?>"><?php echo $val['gc_name']?></a></h4>
							<p class="p-indexleftnav">
							  <?php 
							  if($val['child'] != ''){
							  foreach(explode(',',$val['child']) as $g=>$k){
							  ?>
							  <?php if($g < 2){?>
								<a href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
							  <?php }?>
					          <?php }}?>
							</p>
						</div>
						<div class="indexleftnavshow font-mic" style="">
							<div class="sed-indexleftnav fl">
							  <?php 
							  if($val['child'] != ''){$Knum=1;
							  foreach(explode(',',$val['child']) as $k){?>
							  <?php if($Knum<10){?>
								<div class="sed-indexleftnav-nobor fl" style="<?php if($k % 2 !=0){?>display:none;<?php }?>">
									<dl class="dl-indexleftnav">
										<dt><a href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" style="color:#000"><?php echo $output['show_goods_class'][$k]['gc_name']?></a></dt>
										
										<dd>
										<?php 
										if($output['show_goods_class'][$k]['child'] != ''){$num=1;
										foreach(explode(',',$output['show_goods_class'][$k]['child']) as $key){
										?>
										<a style="<?php if($num>8){?>display:none<?php }?>" href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a>
										<?php $num++;}}?>
										</dd>
										
									</dl>
								</div>
								
								<div class="sed-indexleftnav-bor" style="<?php if($k % 2 ==0){?>display:none;<?php }?>" >
									<dl class="dl-indexleftnav">
										<dt><a href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" style="color:#000;"><?php echo $output['show_goods_class'][$k]['gc_name']?></a></dt>
										<!--
										<dd>
										<?php 
										if($output['show_goods_class'][$k]['child'] != ''){$num=1;
										foreach(explode(',',$output['show_goods_class'][$k]['child']) as $key){
										?>
										<a style="<?php if($num>8){?>display:none<?php }?>" href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a>
										<?php $num++;}}?>
										</dd>
										-->
									</dl>
								</div>
								<?php $Knum++;}}}?>
							</div>
							<div class="sed-indexleftnavadd">
							    <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 410+$sign;?>"></script>
							</div>
						</div>
					</div>
                                        <?php $sign++;}} }  ?>	
                                    
					<!--<div class="index-nav-list">
						<div class="index-nav-listbox">
							<i class="i-indexleftnav i-indexleftnav3"></i>
							<h4 class="h4-indexleftnav">食品保健</h4>
							<p class="p-indexleftnav">
								<span class="span-nolist">即将上线...</span>
							</p>
						</div>
					</div>					
					<div class="index-nav-list">
						<div class="index-nav-listbox">
							<i class="i-indexleftnav i-indexleftnav4"></i>
							<h4 class="h4-indexleftnav">数码家电</h4>
							<p class="p-indexleftnav">
								<span class="span-nolist">即将上线...</span>
							</p>
						</div>
					</div>					
					<div class="index-nav-list">
						<div class="index-nav-listbox">
							<i class="i-indexleftnav i-indexleftnav5"></i>
							<h4 class="h4-indexleftnav">汽车用品</h4>
							<p class="p-indexleftnav">
								<span class="span-nolist">即将上线...</span>
							</p>
						</div>
					</div>					
					<div class="index-nav-list">
						<div class="index-nav-listbox">
							<i class="i-indexleftnav i-indexleftnav6"></i>
							<h4 class="h4-indexleftnav">生活日用</h4>
							<p class="p-indexleftnav">
								<span class="span-nolist">即将上线...</span>
							</p>
						</div>
					</div>-->
				</div>
			</div>
			
		    <div class="indexnav-party1 fl">
				<ul class="ul-indexnav-party1">
					<li class="li-indexnav-party1">
						<a class="a-indexnav-party1" href="index.php?act=search&keyword=">新品上市</a>
					</li>
					<li class="li-indexnav-party1">
						<a class="a-indexnav-party1" href="javascript:void(0);">热卖</a>
						<span class="span-indexnav-party1">原装进口<i></i></span>
					</li>
					<li class="li-indexnav-party1">
						<a class="a-indexnav-party1" href="index.php?act=xianshi">限时折扣</a>
					</li>
				</ul>
			</div>
			
			<div class="indexnav-party2 fl">
				<a class="a-indexnav-party2" href="<?php echo SiteUrl;?>"><?php echo $lang['nc_index'];?></a>
				<a class="a-indexnav-party2" href="index.php?act=national"><?php echo $lang['nc_korea'];?></a>
				<a class="a-indexnav-party2" href="index.php?act=dubai"><?php echo $lang['nc_dubai'];?></a>
				<a class="a-indexnav-party2" href="index.php?act=japan"><?php echo $lang['nc_japan'];?></a>
                <a class="a-indexnav-party2" href="index.php?act=car&op=sindex"><?php echo $lang['nc_car'];?></a>
			</div>	
			
			<div class="indexnav-party3 fl">
				<a class="a-indexnav-party3" href="index.php?act=service_guarantee" target="_blank">正品保证</a>
				<a class="a-indexnav-party3" href="index.php?act=service_guarantee#quality" target="_blank">海外直销</a>
			</div>
		<!--
		<ul class="ul-nav"> 
		
			<li><a <?php echo $output['index_sign'] == 'index'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>"><?php echo $lang['nc_index'];?></a></li>
			<?php if(C('flea_isuse')){;?>
			<li><a <?php echo $output['index_sign'] == 'flea'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>/index.php?act=flea"><?php echo $lang['nc_flea_index'];?></a></li>
			<?php }?>
			<?php if (C('groupbuy_allow')){ ?>
			<li><a <?php echo $output['index_sign'] == 'groupbuy'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>/index.php?act=show_groupbuy"><?php echo $lang['nc_groupbuy'];?></a></li>
			<?php } ?>
			
			屏蔽的部分
			<li><a <?php echo $output['index_sign'] == 'brand'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>/index.php?act=brand"><?php echo $lang['nc_brand'];?></a></li>
			<li><a <?php echo $output['index_sign'] == 'coupon'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>/index.php?act=coupon"><?php echo $lang['nc_coupon'];?></a></li>
			<?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
			<li><a <?php echo $output['index_sign'] == 'pointprod'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="<?php echo SiteUrl;?>/index.php?act=pointprod"><?php echo $lang['nc_pointprod'];?></a></li>
			屏蔽的部分
			
			<?php }?><?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
			
		    <?php foreach($output['nav_list'] as $nav){?>
		    <?php if($nav['nav_location'] == '1'){?>
			<li><a <?php if($nav['nav_new_open']){?>target="_blank" <?php }?> href="<?php switch($nav['nav_type']){
			    case '0':echo $nav['nav_url'];break;

				case '1':echo ncUrl(array('act'=>'search','nav_id'=>$nav['nav_id'],'cate_id'=>$nav['item_id']));break;

				case '2':echo ncUrl(array('act'=>'article','nav_id'=>$nav['nav_id'],'ac_id'=>$nav['item_id']));break;

				case '3':echo ncUrl(array('act'=>'activity','activity_id'=>$nav['item_id'],'nav_id'=>$nav['nav_id']), 'activity');break;
			}?>" <?php if($output['index_sign'] == $nav['nav_id']){echo 'class="a-nav"';}else{echo 'class=""';} ?>><?php echo $nav['nav_title'];?></a></li>
			<?php }?>
			<?php }?>
			<?php }?>
			
			自定义国家馆begin
		
			<li><a <?php echo $output['index_sign'] == 'korea'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="index.php?act=national" target="_blank"><?php echo $lang['nc_korea'];?></a></li>
			<li><a <?php echo $output['index_sign'] == 'dubai'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="index.php?act=dubai" target="_blank"><?php echo $lang['nc_dubai'];?></a></li>
			<li><a <?php echo $output['index_sign'] == 'japan'&&$output['index_sign'] != '0'?'class="a-nav"':'class=" "'; ?> href="index.php?act=japan" target="_blank"><?php echo $lang['nc_japan'];?></a></li>
			
			自定义国家馆end
			
		</ul>-->
	</div>
</div></div>
