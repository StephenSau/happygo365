<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common2.2.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/haipin2/scroll.js" charset="utf-8"></script>

<!--
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css">
-->
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/shop.css">
<script type="text/javascript">
/*首页的公告滚动*/
var indexTopObj = indexTopObj || {};
		indexTopObj = {
			oTimer : null,
			iIndex : 0,
			oMarquee : ".marqueeWrap .marquee",

			scroll : function(){

				var oMarqueeWrap = $(".marqueeWrap");
				var oP = $(".marqueeWrap p");
				var oNewpP= oP.eq(0).clone();
				var iWidth =oP.eq(0).outerWidth() ;
				var iLen = 0;

				$(indexTopObj.oMarquee).append(oNewpP);
				iLen = $(".marqueeWrap p").length;
				$(indexTopObj.oMarquee).css("width",iLen * iWidth+1);
				/*自动播放*/
				indexTopObj.autoRun();
				/*hover事件*/
				oMarqueeWrap.hover(function(){
					clearInterval(indexTopObj.oTimer);
				},function(){
					indexTopObj.autoRun();
				})

				
			},

			autoRun : function(){
				indexTopObj.oTimer = setInterval(function(){
					indexTopObj.iIndex--;
					if(parseInt($(indexTopObj.oMarquee).css("left")) <= -$(".marqueeWrap p").eq(0).outerWidth()){
						indexTopObj.iIndex = 0;
					}
					$(indexTopObj.oMarquee).css("left",indexTopObj.iIndex);
					
				},30);
			}
		}


$(function(){
	/*$(".notice-board p").myScroll({
		speed:100, //数值越大，速度越慢
		rowHeight:34 //li的高度
	});*/

	/*首页的公告滚动*/
	indexTopObj.scroll();
	


});
</script>


<div id="append_parent"></div>

<div id="ajaxwaitid"></div>
<!--
<div class="header">

	<div class="w1210 pr">

	    <?php if($_SESSION['is_login'] == '1'){?>

		<p class="p-welcome">

		    <?php echo $lang['nc_hello'];?>

			<a <?php if($_SESSION['category'] == '1'){?> href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex" <?php }else{?>href="<?php echo SiteUrl.'/';?>index.php?act=store"<?php }?>>

			<?php echo str_cut($_SESSION['member_name'],20);?>

			</a>

			<?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>

			<a href="<?php echo SiteUrl;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $GLOBALS['setting_config']['site_name']; ?></a>

			<span>[<a href="<?php echo SiteUrl.'/';?>index.php?act=login&op=logout"><?php echo $lang['nc_logout'];?></a>]</span>

		</p>

			

			<?php }else if($_SESSION['is_company_login'] == '1'){?>

		<p class="p-welcome">

			<?php echo $lang['nc_hello'];?><a href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex"><?php echo str_cut($_SESSION['company_name'],20);?></a></span>
			
			<?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>
			
			<a href="<?php echo SiteUrl;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $GLOBALS['setting_config']['site_name']; ?></a>

			<span>

			[<a href="<?php echo SiteUrl.'/';?>index.php?act=login&op=logout"><?php echo $lang['nc_logout'];?></a>]

			</span>

		</p>

		<?php }else{?>

		<p class="p-welcome">

			<?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?><a href="<?php echo SiteUrl;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $GLOBALS['setting_config']['site_name']; ?></a>

			<span>

			[<a href="<?php echo SiteUrl.'/';?>index.php?act=login"><?php echo $lang['nc_login'];?></a>]

			</span>

			<span>

			[<a href="<?php echo SiteUrl.'/';?>index.php?act=login&op=register"><?php echo $lang['nc_register'];?></a>]

			</span>

		</p>

		<?php }?>

		<div class="headleft fl">

			<ul class="ul-head">

				<li class="li-cs"><a href="http://www.hplego.com/" onclick="window.external.addFavorite(this.href,this.title);return false;" title='海品乐购网' rel="sidebar"  target="_Blank">收藏本站</a></li>

				<li class="li-ws"><a href="">微商城</a></li>

			</ul>

		</div>

		<div class="headright">

			<ul class="ul-head">

			    判断是个人登陆还是企业登陆，用于显示订单管理的链接，1为个人，2为企业

			    <?php if($_SESSION['category'] == '1'){?>

				<li class="li-dd"><a href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex">订单管理</a></li>

				<?php }?>

				判断是个人登陆还是企业登陆，用于显示我的商城的链接，1为个人，2为企业

				<?php if($_SESSION['category'] == '1'){?>

				<li class="li-gr"><a href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex">个人中心</a></li>

				<?php }?>

				判断是个人登陆还是企业登陆，用于显示卖家中心的链接，1为个人，2为企业

				<?php if($_SESSION['category'] == '2' ){?>

				<li class="li-gr"><a href="<?php echo SiteUrl.'/';?>index.php?act=store">卖家中心</a></li>

				<?php }?>

				<li class="li-bz"><a href="<?php echo SiteUrl.'/';?>index.php?act=article&article_id=15"><?php echo $lang['nc_help_center'];?></a></li>

			</ul>

		</div>

	</div>

</div>
-->
<div class="headerTop">
        
		<div class="w1200 pr">
		<?php if($_SESSION['is_login'] == '1'){?>
		
			<p class="p-greet fl">
			
			<span class="c-yellow"><?php echo $lang['nc_hello'];?></span>
			
			<a class="a-enterin c-yellow" <?php if($_SESSION['category'] == '1'){?> href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex" <?php }else{?>href="<?php echo SiteUrl.'/';?>index.php?act=store"<?php }?>>

			<?php echo str_cut($_SESSION['member_name'],20);?>

			</a>
			<?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>
			<a href="<?php echo SiteUrl;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $GLOBALS['setting_config']['site_name']; ?></a>
			<a class="a-enterin c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=login&op=logout"><?php echo $lang['nc_logout'];?></a>
			</p>
			<?php }else if($_SESSION['is_company_login'] == '1'){?>
			
			<p class="p-greet fl">
			
			<span class="c-yellow"><?php echo $lang['nc_hello'];?></span>
			
			<a class="a-enterin c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex"><?php echo str_cut($_SESSION['company_name'],20);?></a>
			
			<?php echo $lang['nc_comma'],$lang['welcome_to_site'];?>
			
			<a href="<?php echo SiteUrl;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $GLOBALS['setting_config']['site_name']; ?></a>
			
			<a class="a-enterin c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=login&op=logout"><?php echo $lang['nc_logout'];?></a>
			
			</p>
			
		    <?php }else{?>
			<p class="p-greet fl">
			
			<span class="c-yellow">
			<?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?>
			<a href="<?php echo SiteUrl;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $GLOBALS['setting_config']['site_name']; ?></a>
			</span>
			
			请<a class="a-enterin c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=login"><?php echo $lang['nc_login'];?></a>
			
			快速<a class="a-enterin c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=login&op=register"><?php echo $lang['nc_register'];?></a>
			
			</p>
		<?php }?>
		
			<div class="notice-board">
				<span class="notice-board-t">商城公告：</span>
				<?php if(!empty($output['index_article']) && is_array($output['index_article'])){ $sentence = '';?>
				  <?php foreach($output['index_article'] as $a_key=>$a_val){?>
				  <?php if($a_val['article_id'] == 50){?>
					<?php $sentence.=$a_val['article_content'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?>
				  <?php }?>
				  <?php }?>

				<?php echo '<div class="marqueeWrap"><div class="marquee"><p>'.$sentence.'"</p></div></div>';}?>
				
				<!--<ul class="ul-notice-board">
				<?php if(!empty($output['index_article']) && is_array($output['index_article'])){?>
				  <?php foreach($output['index_article'] as $a_key=>$a_val){?>
				  <?php if($a_val['ac_id'] == 1){?>
					<li><a href="<?php echo $a_val['article_url'];?>"><?php echo $a_val['article_content']?></a></li>
				  <?php }?>
				  <?php }?>
				<?php }?>
				</ul>-->
			</div>
			<ul class="ul-topnav">
			    <?php if($_SESSION['category'] == '1'){?>
				<li class="li-topnav">
					<p class="p-usercenter c-yellow"><b><a class="a-usercentertop c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=member_snsindex">我的海品</a></b>
					<!--<i class="i-triangle i-usercenter"></i>-->
					</p>
				</li>
				<?php }?>
				<?php if($_SESSION['category'] == '2' ){?>
				<li class="li-topnav">
					<p class="p-usercenter">
					<b>
					<a class="a-usercentertop c-yellow" href="<?php echo SiteUrl.'/';?>index.php?act=store">卖家中心</a>
					</b>
					</p>
				</li>
				<?php }?>
				<?php if($_GET['act'] != 'cart'){?>
				<li class="li-topnav">
				
				<a class="a-shoppingcart" href="<?php echo SiteUrl.'/';?>index.php?act=cart"><?php echo $lang['nc_cart'];?><b class="c-yellow"><?php echo intval($output['goods_num']); ?></b><?php echo $lang['nc_kindof_goods'];?></a>
				
				</li>
				<?php }?>
				<li class="li-topnav">
				<a class="a-bookmarks" href="http://www.hplego.com/" onclick="window.external.addFavorite(this.href,this.title);return false;" title='海品乐购网' rel="sidebar"  target="_Blank">收藏本站</a></li>
				<li class="li-topnav"><a class="a-customerservice  a-customerservice-bg" href="tencent://message/?uin=3047450986">客户服务</a></li>
				<!-- <li class="li-topnav"><a class="a-phonelg" href="javascript:void(0)">手机海品乐购</a></li> -->
				<li class="li-topnav"><a class="a-sitemap" href="<?php echo SiteUrl.'/';?>index.php?act=article&article_id=79">帮助中心</a></li>
			</ul>
		</div>
	</div>