
<!--
<div class="gotop">
        <a target="_blank" href="tencent://message/?uin=3047450986" style="display:block;"><img src="<?php echo TEMPLATES_PATH;?>/images/qqkefu/zxkf_qq.gif" width="50px" height="50px" style="z-index:999;position:relative;left:0px;top:0px;" /></a>
		<a class="a-gotop" href="javascript:void(0); "></a>

</div>

<div class="side">
	<ul>
		<li><a href="tencent://message/?uin=3047450986"><div class="sidebox" <?php if($_GET['act'] == 'search'){?>style="padding-left:0px;padding-right:0px;margin:0px;"<?php }?>><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/side_icon01.png">客服一号</div></a></li>
		<li><a href="tencent://message/?uin=2711212465"><div class="sidebox" <?php if($_GET['act'] == 'search'){?>style="padding-left:0px;padding-right:0px;margin:0px;"<?php }?>><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/side_icon02.png">客服二号</div></a></li>
		<li><a href="javascript:void(0);" ><div class="sidebox" <?php if($_GET['act'] == 'search'){?>style="padding-left:0px;padding-right:0px;margin:0px;"<?php }?>><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/side_icon03.png">400-853-5557</div></a></li>
		<li style="border:none;"><a href="javascript:goTop();" class="sidetop"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/side_icon05.png"></a></li>
	</ul>
</div>
-->
<div class="indexfloat" style="z-index:100;">
	<!-- <p class="p-indexfloat">
		<img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img8-2.jpg">
		<span>加入微信</span>
	</p>
	<ul class="ul-indexfloat">
		<li class="li-indexfloat"><a class="a-indexfloat1" href="tencent://message/?uin=3047450986">购买咨询</a></li>
		<li class="li-indexfloat"><a class="a-indexfloat2" href="tencent://message/?uin=2711212465">售后服务</a></li>
		<li class="li-indexfloat"><a class="a-indexfloat3" href="tencent://message/?uin=2711212465">意见反馈</a></li>
		<li class="li-indexfloat"><a class="a-indexfloat4" href="">400-853-5557</a></li>
	</ul>
	<a class="a-indexgotop" href="javascript:goTop();">回到顶部</a> -->

	<div class="serviceContact">
		<ul>
			<li class="car">
				<a href="tencent://message/?uin=1480451279">
					<span></span>
					<i>汽车馆</i>
				</a>
			</li>
			<li class="consult">
				<a href="tencent://message/?uin=3047450986">
					<span></span>
					<i>咨询</i>
				</a>
			</li>
			<li class="service">
				<a href="tencent://message/?uin=2711212465">
					<span></span>
					<i>售后</i>
				</a>
			</li>
			<li class="weixin">
				<span></span>
				<em></em>
			</li>
			<li class="phone">
				<span></span>
				<em></em>
			</li>
			<li class="back">
				<span></span>
			</li>
			<!-- <li class="ercode">
				<span></span>
				<em></em>
			</li> -->
		</ul>
	</div>
</div>
<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="content">

<div class="w1210">

<div class="guarantee font-mic">

	<ul class="ul-guarantee pdt40">

		<li class="li-guarantee1 nomargin-l">

			<h4>真品</h4>

			<p>所有商品都在海关备案，原装进口，保真购物。</p>

		</li>					

		<li class="li-guarantee2">

			<h4>合法</h4>

			<p>国家特批跨境零售政策，合法购物。</p>

		</li>					

		<li class="li-guarantee3">

			<h4>低价</h4>

			<p>直接从境外商家购买原装进口商品，低价购物。</p>

		</li>					

		<li class="li-guarantee4">

			<h4>快捷</h4>

			<p>利用快速通关的优势，快捷购物。</p>

		</li>

	</ul>

</div>

</div>

</div>

<div class="footer">

<div class="w1210 footnav pr">

	<ul class="ul-footnav">

		<?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
		<?php foreach ($output['article_list'] as $k=>$article_class){ ?>

		<?php if(!empty($article_class)){ ?>

		<li <?php if($k == 0 ){?>class="nomargin-l"<?php }elseif($k == 3){?>class="ml"<?php }?>>

			<h5><?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?></h5>

			<?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>

		    <?php foreach ($article_class['list'] as $article){ ?>
            <!--
			<a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo ncUrl(array('act'=>'article','article_id'=>$article['article_id']) ,'article');?>" title="<?php echo $article['article_title']; ?>"> <?php echo str_cut($article['article_title'],13);?> </a>
            -->
			<a href="<?php 	
			if(str_cut($article['article_title'],13) == "质量保证"){
				echo "index.php?act=service_guarantee#quality";
			}elseif(str_cut($article['article_title'],13) == "物流保证"){
				echo "index.php?act=service_guarantee#logistics";
			}elseif(str_cut($article['article_title'],13) == "价格保证"){
				echo "index.php?act=service_guarantee#price";
			}else{

				if($article['article_url'] != ''){

					echo $article['article_url'];
				}else{
					echo 'index.php?act=article&article_id='.$article['article_id'];
				}

			}

		?>

			" title="<?php echo $article['article_title']; ?>" target="_blank"> <?php echo str_cut($article['article_title'],13);?> </a>

		    <?php }?>

		    <?php }?>

		</li>

		<?php }?>

		<?php }?>

		<?php }?>

	</ul>

	<div class="code-telphong">

		<p class="p-callphone"></p>

		<ul class="ul-cade font-mic">

			<li>

				<img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img8-weixin.jpg">

				<p><img src="<?php echo TEMPLATES_PATH;?>/images/index_fd_wx.jpg" width=14 height=14  >&nbsp;官方微信</p>

			</li>					

			<li>

				<img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img8-weibo.jpg">

				<p><img src="<?php echo TEMPLATES_PATH;?>/images/index_fd_wb.jpg" width=14 height=14 >&nbsp;官方微博</p>

			</li>

		</ul>

	</div>

</div>

<p class="p-snav">

	<a href="<?php echo SiteUrl;?>"><?php echo $lang['nc_index'];?></a>|

	<?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>

    <?php foreach($output['nav_list'] as $nav){?>

    <?php if($nav['nav_location'] == '2'){?>

	<a <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){

    	case '0':echo $nav['nav_url'];break;

    	case '1':echo ncUrl(array('act'=>'search','nav_id'=>$nav['nav_id'],'cate_id'=>$nav['item_id']), '', 'www');break;

    	case '2':echo ncUrl(array('act'=>'article','nav_id'=>$nav['nav_id'],'ac_id'=>$nav['item_id']), '', 'www');break;

    	case '3':echo ncUrl(array('act'=>'activity','activity_id'=>$nav['item_id'],'nav_id'=>$nav['nav_id']), 'activity', 'www');break;

    }?>"><?php echo $nav['nav_title'];?></a>

    <?php if($nav['nav_id'] == '7'){?><?php }else{?>|<?php }?>

	<?php }?> 

	<?php }?>

	<?php }?>
<a href="http://www.ciku5.com" title="词库网" target="_blank">词库网</a> 
</p>
<p class="p-copyright"><?php echo $GLOBALS['setting_config']['icp_number']; ?></p><br />

<?php echo html_entity_decode($GLOBALS['setting_config']['statistics_code'],ENT_QUOTES); ?> </div>

<?php if (C('debug') == 1){?>

	<?php echo $lang['nc_debug_trace_title'];?>

	<?php print_r(Tpl::showTrace());?>

<?php }?>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/public.js"></script>

<script language="javascript">

var searchTxt = '<?php echo $lang['nc_searchdefault']; ?>';

function searchFocus(e){

	if(e.value == searchTxt){

		e.value='';

		$('#keyword').css("color","");

	}

}

function searchBlur(e){

	if(e.value == ''){

		e.value=searchTxt;

		$('#keyword').css("color","#999999");

	}

}

function searchInput() {

	if($('#keyword').val()==searchTxt)

		$('#keyword').attr("value","");

	return true;

}

<?php

if(isset($_GET['keyword'])) {

?>

$('#keyword').attr("value","<?php echo trim($_GET['keyword']); ?>");

<?php

} else {

?>

$('#keyword').css("color","#999999");

<?php

}

?>

function load_cart_information(){

	$.getJSON('index.php?act=cart&op=ajaxcart', function(result){

	    if(result){

	        var result  = result;

	       	$('.goods_num').html(result.goods_all_num);

	       	var html = '';

	       	if(result.goods_all_num >0){

	       		html+="<div class='order'><table border='0' cellpadding='0' cellspacing='0'>";

	       		var i= 0;

	       		var data = result['goodslist'];

	            for (i = 0; i < data.length; i++)

	            {

	            	html+="<tr id='cart_item_"+data[i]['specid']+"' count='"+data[i]['num']+"'>";

	            	html+="<td class='picture'><span class='thumb size40'><i></i><img src='"+data[i]['images']+"' title='"+data[i]['gname']+"' onload='javascript:DrawImage(this,40,40);' ></span></td>";

	            	html+="<td class='name'><a href='<?php echo SiteUrl.'/';?>index.php?act=goods&goods_id="+data[i]['goodsid']+"' title='"+data[i]['gname']+"' target='_top'>"+data[i]['gname']+"</a></td>";

		          	html+="<td class='price'><p><?php echo $lang['currency'];?>"+data[i]['price']+"<?php echo $lang['nc_sign_multiply']; ?>"+data[i]['num']+"</p><p><a href='javascript:void(0)' onClick='drop_topcart_item("+data[i]['storeid']+","+data[i]['specid']+");' style='color: #999;'><?php echo $lang['nc_delete'];?></a></p></td>";

		          	html+="</tr>";

		        }

	         	html+="<tr><td colspan='3' class='no-border'><span class='all'><?php echo $lang['nc_goods_num_one'];?><strong class='goods_num'>"+result.goods_all_num+"</strong><?php echo $lang['nc_goods_num_two'].$lang['nc_colon'];?><strong id='cart_amount'><?php echo $lang['currency'];?>"+result.goods_all_price+"</strong></span><span class='button' ><a href='<?php echo SiteUrl.'/';?>index.php?act=cart' target='_top' title='<?php echo $lang['nc_accounts_goods'];?>' style='color: #FFF;' ><?php echo $lang['nc_accounts_goods'];?></a></span></td></tr>";

	      }else{

	      	html="<div class='no-order'><span><?php echo $lang['nc_cart_no_goods'];?></span><a href='<?php echo SiteUrl.'/';?>index.php?act=cart' class='button' target='_top' title='<?php echo $lang['nc_check_cart'];?>' style=' color: #FFF;' ><?php echo $lang['nc_check_cart'];?></a></div>";

	        }

	        $("#top_cartlist").html(html);

	   }

	});

}



function drop_topcart_item(store_id, spec_id){

    var tr = $('#cart_item_' + spec_id);

    var amount_span = $('#cart_amount');

    var cart_goods_kinds = $('.goods_num');

    $.getJSON('index.php?act=cart&op=drop&specid=' + spec_id + '&storeid=' + store_id, function(result){

        if(result.done){

            if(result.quantity == 0){

            	$('.goods_num').html('0');

            	var html = '';

            	html="<div class='no-order'><span><?php echo $lang['nc_cart_no_goods'];?></span><a href='<?php echo SiteUrl.'/';?>index.php?act=cart' class='button' target='_top' title='<?php echo $lang['nc_check_cart'];?>' style=' color: #FFF;' ><?php echo $lang['nc_check_cart'];?></a></div>";

            	$("#top_cartlist").html(html);

            }

            else{

                tr.remove();       

                amount_span.html(price_format(result.amount));

                cart_goods_kinds.html(result.quantity);      

            }

        }else{

            alert(result.msg);

        }

    });

}

/*通过闭包进行封闭*/

(function($){
	$(function(){
		var oBack = $(".serviceContact .back");
		$('#topNav').find('li[class="cart"]').mouseover(function(){

			load_cart_information();

			$(this).unbind();

		});

		<?php if ($_SESSION['store_id'] > 0){?>

		$.include('index.php?act=scan&type=updown');

		<?php }?>

		$(".side ul li").hover(function(){
			$(this).find(".sidebox").stop().animate({"width":"160px"},200).css({"opacity":"1","filter":"Alpha(opacity=100)","background":"#00479f"})	
		},function(){
			$(this).find(".sidebox").stop().animate({"width":"54px"},200).css({"opacity":"0.8","filter":"Alpha(opacity=80)","background":"#000"})	
		});

		/*客服*/
		$(".serviceContact .weixin,.serviceContact .phone").hover(function(){
			serviceObj.serviceOver(this)
		},function(){
			serviceObj.serviceOut(this);
		})
		/*回到顶部*/
		oBack.bind("click",function(){
			serviceObj.goTop();
		});
		/*客服到一定的高度慢慢淡出*/
		serviceObj.scrollShow();
		$(window).bind("scroll",function(){
			serviceObj.scrollShow();
		})

	});

})(jQuery)

/*客服*/
var serviceObj = {
	serviceOut : function(This){
			var _this = This;
			$(_this).find("em").stop(true,false).animate({
				width : "0"
			},600,function(){
				$(this).hide();
			});

		},
	serviceOver : function(This){
		var iWidth = 159;
		var _this = This;
		if($(_this).attr("class") == "phone"){
			iWidth = 213;
		}
		$(_this).find("em").show().stop(true,false).animate({
			width : iWidth
		},600);
	},
	//回到顶部
	goTop : function (){
		$('html,body').animate({'scrollTop':0},600);
	},
	scrollShow : function(){
		if($(window).scrollTop() >= 309){
			$(".indexfloat").stop(false,true).fadeIn(2000);
		}else{
			$(".indexfloat").stop(false,true).fadeOut(2000);
		}
	}

}

</script>

