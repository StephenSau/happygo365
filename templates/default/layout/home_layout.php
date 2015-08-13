<?php defined('haipinlegou') or exit('Access Invalid!');?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="haipinlegou">
<meta name="copyright" content="haipinlegou Inc. All Rights Reserved">
<?php echo html_entity_decode($GLOBALS['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($GLOBALS['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body {
_behavior: url(<?php echo TEMPLATES_PATH;
?>/css/csshover.htc);
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css">
<script>

COOKIE_PRE = '<?php echo COOKIE_PRE;?>';_CHARSET = '<?php echo strtolower(CHARSET);?>';SITEURL = '<?php echo SiteUrl;?>';

</script>


<!--<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/haipin2/jquery-1.9.1.min.js"></script>-->


<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/haipin2/hplg.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/haipin2/hp0402.js"></script>

<script type="text/javascript">
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';

$(function(){


	$("#details").children('ul').children('li').click(function(){

		$(this).parent().children('li').removeClass("current");

		$(this).addClass("current");

		$('#search_act').attr("value",$(this).attr("act"));

	});

	var search_act = $("#details").find("li[class='current']").attr("act");

	$('#search_act').attr("value",search_act);

	$("#keyword").blur();

});

</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>


<?php  if(($_GET['act'])!='login' && ($_GET['act'])!='carmanage'){ ?>

<div class="logo-search w1200">
	<!--honghong-->
		<div class="a-indexlogo-box">
			<div class="a-indexlogo-box-l">
				<a href="<?php echo SiteUrl;?>"><p><img alt="海品乐购" src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo-you.jpg" width="185" height="131"></p></a>
			</div>
			<div class="a-indexlogo-box-r"></div>
		</div>
	<!--honhoh -->
 <!--
	<a class="a-indexlogo fl" href="<?php echo SiteUrl;?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo.jpg"></a>
-->
	<div class="top-search" id="details">
	
		<div class="top-search-box pr">
		    <form action="index.php" onSubmit="return searchInput();" method="get">
			<input name="act" id="search_act" value="search" type="hidden">
			<input class="index-input-txt" name="keyword" id="keyword" type="text" value="<?php echo $lang['nc_searchdefault']; ?>" onFocus="searchFocus(this)" onBlur="searchBlur(this)" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" >
			<input class="index-input-sub" type="submit" value="搜索" style="cursor:pointer">
			</form>
		</div>
		
		<ul class="ul-top-search">
		    <?php if(is_array($output['hot_search']) and !empty($output['hot_search']))
			{ foreach($output['hot_search'] as $val) {;?>
			<li class="li-top-search"><a href="index.php?act=search&keyword=<?php echo urlencode($val);?>"><?php echo $val; ?></a></li>
			<?php }}?>
		</ul>
		
	</div>
</div>

<!--下拉时显示start-->
<div id="fixedsearch" class="fixedsearch" style="display:;">
	<div class="w1210 pr">
		<div class="logo fl logo_top"><a href="<?php echo SiteUrl;?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo_min.jpg" alt="海品乐购" width="150" height="36"></a></div>
		<div class="search" style="margin-left:476px;">
			<div class="top-search-box pr">
		    <form action="index.php" onSubmit="return searchInput();" method="get">
			<input name="act" id="search_act" value="search" type="hidden">
			<input class="index-input-txt" name="keyword" id="keyword" type="text" value="<?php echo $lang['nc_searchdefault']; ?>" onFocus="searchFocus(this)" onBlur="searchBlur(this)" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" x-webkit-grammar="builtin:search" >
			<input class="index-input-sub" type="submit" value="搜索" style="cursor:pointer">
			</form>
		</div>

		</div>
		<div class="shopcar font-mic">
				<a href="<?php echo SiteUrl.'/';?>index.php?act=cart">购物车</a>
				<span><?php echo intval($output['goods_num']); ?></span>
			</div>
	</div>		
</div>

<!--下拉时显示end-->
<?php } ?>


<?php  if(($_GET['act'])!='login' && ($_GET['act'])!='carmanage') { ?>
<div class="indexnav">
<div class="topnav pr">
	<div class="w1210 pr">	
		<div class="indexnav-total fl">
				<a class="a-indexnav-total" href="index.php?act=search&keyword=" style="<?php if($_GET['act'] == 'search'){?>color:#ffffff<?php }?>"><?php echo $lang['nc_all_goods_class'];?></a>
				<div class="indexnav-total-main" id="navleftmain">
				    <?php
                                
					if(is_array($output['show_goods_class']) && count($output['show_goods_class']) != 0){$sign = 1;
                                    
					foreach ($output['show_goods_class'] as $tkey=>$val){
                                          
                                            
					if ($val['gc_parent_id'] == 0 && $val['gc_show'] == 1){
                                                                                                                       
					?>
					<div class="index-nav-list" style="<?php if($sign>6){?>display:none;<?php }?>">
						<div class="index-nav-listbox">
							
							<i class="i-indexleftnav i-indexleftnav<?php echo $sign;?>"></i>
							<h4 class="h4-indexleftnav"><a target="_blank" href="index.php?act=search&cate_id=<?php echo $val['gc_id'];?>" title="<?php echo $val['gc_name']?>"><?php echo $val['gc_name']?></a></h4>
							<p class="p-indexleftnav">
							  <?php 
							  if($val['child'] != ''){
							  foreach(explode(',',$val['child']) as $g=>$k){
							  ?>
							  <?php if($g < 2){?>
								<a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" ><?php echo $output['show_goods_class'][$k]['gc_name']?></a>
							  <?php }?>
					          <?php }}?>
							</p>
						</div>
                                            
						<div class="indexleftnavshow font-mic" style="">
							<div class="sed-indexleftnav-line"></div>
							<div class="sed-indexleftnav fl">
							  <?php 
							  if($val['child'] != ''){$Knum=1;
							  foreach(explode(',',$val['child']) as $k){?>
							  <?php if($Knum<10){?>
								<div class="sed-indexleftnav-nobor fl" style="<?php if($k % 2 !=0){?>display:none;<?php }?>">
									<dl class="dl-indexleftnav">
										<dt><a href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" style="color:#000" target="_blank"><?php echo $output['show_goods_class'][$k]['gc_name']?></a></dt>
										
										<dd>
										<?php 
										if($output['show_goods_class'][$k]['child'] != ''){$num=1;
										foreach(explode(',',$output['show_goods_class'][$k]['child']) as $key){
										?>
										<a target="_blank" style="<?php if($num>8){?>display:none<?php }?>" href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a>
										<?php $num++;}}?>
										</dd>
										
									</dl>
								</div>
                                                            
                                                            
                                                            
								
								<div class="sed-indexleftnav-bor" style="<?php if($k % 2 ==0){?>display:none;<?php }?>" >
									<dl class="dl-indexleftnav">
                                                                           
										<dt><a target="_blank" href="index.php?act=search&cate_id=<?php echo $k;?>" title="<?php echo $output['show_goods_class'][$k]['gc_name']?>" style="color:#000;"><?php echo $output['show_goods_class'][$k]['gc_name']?></a></dt>
										<!--
										<dd>
										<?php 
										if($output['show_goods_class'][$k]['child'] != ''){$num=1;
										foreach(explode(',',$output['show_goods_class'][$k]['child']) as $key){
										?>
										<a target="_blank" style="<?php if($num>8){?>display:none<?php }?>" href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a>
										<?php $num++;}}?>
										</dd>
										-->
									</dl>
								</div>
								<?php $Knum++;}}}?>
							</div>
							<div class="sed-indexleftnavadd">
							<?php if($sign<3){?>
							    <script type="text/javascript" src="<?php echo SiteUrl;?>/index.php?act=adv&op=advshow&ap_id=<?php echo 410+$sign;?>"></script>
							<?php }?>
							</div>
						</div>
					</div>
                                        <?php $sign++;}} }  ?>					
                    
				</div>
			</div>
			
		    <div class="indexnav-party1 fl">
				<ul class="ul-indexnav-party1">
					<li class="li-indexnav-party1">
						<a  class="a-indexnav-party1" href="index.php?act=search&keyword=">新品上市</a>
					</li>
					<li class="li-indexnav-party1">
						<a   class="a-indexnav-party1" href="index.php?act=search&keyword=&key=sales&order=desc">热卖</a>
						<span class="span-indexnav-party1">原装进口<i></i></span>
					</li>
					<li class="li-indexnav-party1">
						<a   class="a-indexnav-party1" href="index.php?act=xianshi">限时折扣</a>
					</li>
				</ul>
			</div>

			<div class="indexnav-party2 fl">
				<a   class="a-indexnav-party2" href="<?php echo SiteUrl;?>"><?php echo $lang['nc_index'];?></a>
				<a  class="a-indexnav-party2" href="index.php?act=national"><?php echo $lang['nc_korea'];?></a>
				<a  class="a-indexnav-party2" href="index.php?act=dubai"><?php echo $lang['nc_dubai'];?></a>
				<a   class="a-indexnav-party2" href="index.php?act=japan"><?php echo $lang['nc_japan'];?></a>
                <!-- <a   class="a-indexnav-party2" href="index.php?act=taiwan"><?php echo $lang['nc_taiwan'];?></a> -->
                <a   class="a-indexnav-party2" href="index.php?act=car&op=sindex"><?php echo $lang['nc_car'];?></a>
			</div>	
			
			<div class="indexnav-party3 fl">
				<a   class="a-indexnav-party3" href="index.php?act=service_guarantee" target="_blank">正品保证</a>
				<a   class="a-indexnav-party3" href="index.php?act=service_guarantee#quality" target="_blank">海外直销</a>
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
</div>
<?php } ?>


<?php require_once($tpl_file);?>
<?php  if(($_GET['act'])!='login') { ?>
<?php require_once template('footer');?>
<?php } ?>
<?php  if(($_GET['act'])=='login') { ?>
<div class="registerfooter">
	<p class="p-registerfooter">
		<a target="_blank" href="<?php echo SiteUrl;?>"><?php echo $lang['nc_index'];?></a>|
		<?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
	    <?php foreach($output['nav_list']  as $nav){?>
	    <?php if($nav['nav_location'] == '2'){?>
		<a <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
	    	case '0':echo $nav['nav_url'];break;
	    	case '1':echo ncUrl(array('act'=>'search','nav_id'=>$nav['nav_id'],'cate_id'=>$nav['item_id']), '', 'www');break;
	    	case '2':echo ncUrl(array('act'=>'article','nav_id'=>$nav['nav_id'],'ac_id'=>$nav['item_id']), '', 'www');break;
	    	case '3':echo ncUrl(array('act'=>'activity','activity_id'=>$nav['item_id'],'nav_id'=>$nav['nav_id']), 'activity', 'www');break;
	    }?>"><?php echo $nav['nav_title'];?></a>
	    <?php if($nav['nav_id'] == '7') {?><?php }else{?>|<?php }?>
		<?php }?>
		<?php }?>
		<?php }?>
	</p>
<p class="p-registerpatent"><?php echo $GLOBALS['setting_config']['icp_number']; ?></p>
</div>
<?php } ?>
</div>


</body>
</html>
<script type="text/javascript">

var navleftmain=document.getElementById('navleftmain');
	var	navleftlist=getByClass(navleftmain,'navleftlist','div');
	var snavleft=getByClass(navleftmain,'snavleft','div');
	for (var i = 0; i < navleftlist.length; i++) {
		var _thistop=navleftlist[i].offsetTop;
		snavleft[i].style.top=_thistop-2*81*i-40+'px';
	};

//轮播 
	// $(".banner").goban({
	// 	runbox:'.banner',
	// 	runmbox:'.banimg',
	// 	runmain:'.ul-banimg',
	// 	runbtnclass:'span-banbtn',
	// 	runalltime:5000,
	// 	runtime:1000,
	// 	resizee:1
	// });

/*母婴用品右侧轮播广告*/

var rightSlideObj = {
	oUl : $(".ul-righttopaddbox"),
	init : function(){
		var iWidth = rightSlideObj.oUl.find("li").eq(0).outerWidth(true);
			$.each(rightSlideObj.oUl,function(){
				$(this).css({"width":$(this).find("li").length * iWidth });

			});

			/*左右箭头显示隐藏*/
			$(".righttopadd").hover(function(){
				$(this).children("a").stop(false,true).fadeIn(500);
			},function(){
				$(this).children("a").stop(false,true).fadeOut(500);
			})

			/*左右轮动*/
			rightSlideObj.slide();
	},
	slide : function(){
		$(".righttopadd").addrun({
			rwidth:379,		//滚动的宽度，如果是全屏则是body
			rbox:".ul-righttopaddbox",		//滚动图片的父级
			rmain:".ul-righttopaddbox",		//要滚动的本体
			rallwidth:0
		})
	}
}

	rightSlideObj.init();


	// 浮动搜索
	var fixedsearch=$(".fixedsearch");
	$(window).scroll(function(){
		var scrolltop=$(window).scrollTop();
		if (scrolltop>101&&scrolltop<400) {
			fixedsearch.stop().animate({top:0},'slow');
		}
		if (scrolltop<103) {
			fixedsearch.stop().animate({top:-115+'px'},'slow');
		}
		// console.log(scrolltop);
	});
	// 轮播右tab
	var bantjtitle=$(".bantjtitle"),
		bantjmain=$(".bantjmain");
	bantjtitle.children().click(function(){
			var index=$(this).index();
			$(this).addClass("span-bantjtitle").siblings().removeClass("span-bantjtitle");
			bantjmain.children().eq(index).css('display','block').siblings().css('display','none');
		}
	);
		function getByClass(oParent,sClass,tagName){
			var aEle=document.getElementsByTagName(tagName),
				aResult=[],
				re=new RegExp('\\b'+sClass+'\\b','i'),
				i=0;
			for(i=0;i<aEle.length;i++){
				if (re.test(aEle[i].className)) {
					aResult.push(aEle[i]);
				}
			}
			return aResult;
		}
	// 左导航菜单
	$(".navleft").mouseover(function(){
		var navleftmain=document.getElementById('navleftmain');
		var	navleftlist=getByClass(navleftmain,'navleftlist','div');
		var snavleft=getByClass(navleftmain,'snavleft','div');
		for (var i = 0; i < navleftlist.length; i++) {
			var _thistop=navleftlist[i].offsetTop;
			snavleft[i].style.top=_thistop-2*81*i-40+'px';
		};	
	});
	// 回到顶部
	var agotop=$(".a-gotop");
	agotop.click(function(){
			$('html,body').animate({scrollTop:0},'slow');
		}
	);

/*首页楼梯*/
	function indexGoodsList(){
			$.each($(".floor1"),function(index){
				$(this).find(".ul-indextab").find("li").removeClass("li-indextabon").eq(index).addClass("li-indextabon");
				$(this).find(".div-midgoodslist").removeClass("div-midgoodsliston").eq(index).addClass("div-midgoodsliston");
			});
			$.each($(".floor2"),function(index){
				$(this).find(".ul-indextab").find("li").removeClass("li-indextabon").eq(index).addClass("li-indextabon");
				$(this).find(".div-midgoodslist").removeClass("div-midgoodsliston").eq(index).addClass("div-midgoodsliston");
			});
			$(".floor2").eq(0).css("margin-top","25px");
	}

	indexGoodsList();
</script>