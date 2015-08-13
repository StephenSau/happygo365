<?php defined('haipinlegou') or exit('Access Invalid!');?>

<script type="text/javascript" src="<?php echo RESOURCE_PATH.'/js/search_goods.js';?>" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH.'/js/class_area_array.js';?>" charset="utf-8"></script>

<script src="<?php echo RESOURCE_PATH;?>/js/jquery.datalazyload.js" type="text/javascript"></script>

<script src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" type="text/javascript"></script>

<script src="<?php echo RESOURCE_PATH;?>/js/sns.js" type="text/javascript" charset="utf-8"></script> 

<link href="<?php echo TEMPLATES_PATH;?>/css/layout.css" rel="stylesheet" type="text/css">

<link href="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.css" rel="stylesheet" type="text/css">

<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css" rel="stylesheet" type="text/css">

<!--<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css" rel="stylesheet" type="text/css">-->

<style type="text/css">

  body {

    _behavior: url(<?php echo TEMPLATES_PATH;

      ?>/css/csshover.htc);

  }.goodssearchedmids {

    background-color: #f9f9f9;

    border: 1px solid #dedede;

    height: 48px;

    width: 100%;

  }.sort-bar{

    background: none repeat scroll 0 0 #f7f7f7;

    display: block;

    height: 48px;

  }

</style>
<!--<hr style="border:1px solid #FF6400; position:relative; bottom:6px;">-->
<div class="goodsclassify" style="margin-top:2px;">
  <!--<div>面包屑导航？？？？？？？？？？？？？？？？？？？？？？</div>-->
  
  <div class="keyword">
     <!-- 
	 <h4><?php echo $lang['cur_location'].$lang['nc_colon'];?>1</h4>  
	 -->

<!--
    <?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
    <?php foreach($output['nav_link_list'] as $nav_link){?>
    <?php if(!empty($nav_link['link'])){?>
    <a href="<?php echo $nav_link['link'];?>"><?php echo $nav_link['title'];?></a><span>&nbsp;</span>
    <?php }else{?>
    <?php echo $nav_link['title'];?>
    <?php }?>
    <?php }?>
    <?php }?>
-->

<!-- 后来修改-->
	<?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
		<?php $nav_i=0;?>
		<?php foreach($output['nav_link_list'] as $navKey=>$nav_link){?>
			<?php if(!empty($nav_link['link'])){?>
				<?php $nav_i++;?>
					<?php if($nav_i<=2){?>
					<a href="<?php echo $nav_link['link'];?>"><?php echo $nav_link['title'];?></a><span>&nbsp;</span>
					<?php }}else{?>
				<?php echo $nav_link['title'];?>
			<?php }?>
		<?php }?>
    <?php }?>
<!--后来修改end -->
  </div>
  
  <div class="goodsclassifyright">
  
    <div class="goodsclassify-search">
      <ul class="ul-goodsclassify-search">
        <li class="li-goodsclassify-search">
          <h3 class="h3-searchend"><b class="c-red"><?php $data=explode(',',$output['name']['gc_tag_value']);echo $data[1];?></b><i>商品筛选</i></h3>
          <p class="p-searchtotal">共<?php echo $output['goods_count'];?>个商品</p>
        </li>        
      </ul>
    </div>
	
	<?php if(!isset($output['goods_class_array']['child']) && empty($output['goods_class_array']['child']) && !empty($output['goods_class_array'])){?>
    <?php $dl=1;  //dl标记?>
    <?php if((!empty($output['spec_array']) && is_array($output['spec_array'])) || (!empty($output['brand_array']) && is_array($output['brand_array'])) || (!empty($output['attr_array']) && is_array($output['attr_array']))){?>
    <div class="module_filter">
      <div class="module_filter_line">


<!--

        <?php if(!empty($output['spec_array']) && is_array($output['spec_array'])){?>
        <?php foreach ($output['spec_array'] as $key=>$val){?>
        <?php if(!isset($output['checked_spec'][$key]) && !empty($val['value']) && is_array($val['value'])){?>
        <dl <?php if($dl>3){?>class="dl_hide"<?php }?>>
          <dt><?php echo $val['name'].$lang['nc_colon'];?></dt>
          <dd class="list">
            <ul>
              <?php $i=0;foreach ($val['value'] as $k=>$v){$i++;?>
              <li <?php if ($i>10){?>style="display: none" nc_type="none"<?php }?>><a href="javascript:replaceParam('s_id','<?php echo $_GET['s_id'] != ''?$_GET['s_id'].','.$k:$k;?>');">
                <?php if(isset($v['image']) && $v['image'] != ''){?>
                <img alt="<?php echo $v['name'];?>" title="<?php echo $v['name']?>" src="<?php echo SiteUrl.DS.ATTACH_SPEC.DS.$v['image'];?>" />
                <?php }else{echo $v['name'];}?>
                </a>(<?php echo $v['count']?>) </li>
              <?php }?>
            </ul>
          </dd>
          <?php if (count($val['value']) > 10){?>
          <dd class="all"><span class="btn1" nc_type="show"><?php echo $lang['goods_class_index_more'];?></span></dd>
          <?php }?>
        </dl>
        <?php }?>
        <?php $dl++;}?>
        <?php }?>

-->
<!--品牌-->
        <?php if (!isset($output['checked_brand']) || empty($output['checked_brand'])){?>
        <?php if(!empty($output['brand_array']) && is_array($output['brand_array'])){?>
        <dl <?php if($dl>3){?>class="dl_hide"<?php }?>>
          <dt><?php echo $lang['goods_class_index_brand'].$lang['nc_colon'];?></dt>
          <dd class="list">
            <ul>
              <?php $i = 0;foreach ($output['brand_array'] as $k=>$v){$i++;?>
              <li <?php if ($i>10){?>style="display:none" nc_type="none"<?php }?>><a href="javascript:replaceParam('b_id','<?php echo $_GET['b_id'] != ''?$_GET['b_id'].','.$k:$k;?>');"><?php echo $v['name'];?></a><!-- em>(<?php echo $v['count']?>)</em --></li>
              <?php }?>
            </ul>
          </dd>
          <?php if (count($output['brand_array']) > 10){?>
          <dd class="all"><span class="btn1 search_all_span_more" nc_type="show"><?php echo $lang['goods_class_index_more'];?></span></dd>
          <?php }?>
        </dl>
        <?php $dl++;}?>
        <?php }?>
<!--品牌end-->
<!--ad-->
<dl>
	<dt>价格：</dt>
	<dd class="list">
		<ul>
			<li><a href="javascript:replaceParam('b_id','24');">0-299</a></li>
			<li><a href="javascript:replaceParam('b_id','53');">300-599</a></li>
			<li><a href="javascript:replaceParam('b_id','21');">600-999</a></li>
			<li><a href="javascript:replaceParam('b_id','52');">1000-1499</a></li>
			<li><a href="javascript:replaceParam('b_id','52');">1500-2299</a></li>
			<li><a href="javascript:replaceParam('b_id','52');">2399-2799</a></li>
			<li><a href="javascript:replaceParam('b_id','52');">2800以上</a></li>
		</ul>
	</dd>
</dl>
<!--sd-->

	<div class="prices_an">
			<input class="w30" type="text" value="">
			<em>-</em>
			<input class="w30" type="text" value="">
			<input id="search_by_price" type="submit" value="确定">
	</div>
      </div>

    </div>
    <?php }?>
    <?php }?>
	
	
	<nav class="nc-gl-sort-bar" id="main-nav">
        <div class="sort-bar">

          <div class="bar-l"> 
            <!-- 查看方式S -->
            <!--<div class="switch"><span <?php if($output['display_mode'] == 'squares'){?>class="selected"<?php }?>><a href="javascript:void(0)" class="pm" nc_type="display_mode" ecvalue="squares" title="<?php echo $lang['goods_class_index_by_pane'];?>"><?php echo $lang['goods_class_index_pane'];?></a></span><span <?php if($output['display_mode'] == 'list'){?>class="selected"<?php }?> style="border-left:none;"><a href="javascript:void(0)" class="lm" nc_type="display_mode" ecvalue="list" title="<?php echo $lang['goods_class_index_by_list'];?>"><?php echo $lang['goods_class_index_list'];?></a></span></div>-->
            <!-- 查看方式E --> 
            <!-- 排序方式S -->
            <ul class="array">
              <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" class="nobg" onClick="javascript:dropParam(['key','order'],'','array');" title="<?php echo $lang['goods_class_index_default_sort'];?>">排序</a></li>
			  
              <li <?php if($_GET['key'] == 'sales'){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" <?php if($_GET['key'] == 'sales'){?>class="<?php echo $_GET['order'];?>"<?php }?> onClick="javascript:replaceParam(['key','order'],['sales','<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'sales')?'asc':'desc' ?>'],'array');" title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'sales')?$lang['goods_class_index_sold_asc']:$lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold']	;?></a></li>
              <li <?php if($_GET['key'] == 'click'){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" <?php if($_GET['key'] == 'click'){?>class="<?php echo $_GET['order'];?>"<?php }?> onClick="javascript:replaceParam(['key','order'],['click','<?php  echo ($_GET['order'] == 'desc' && $_GET['key'] == 'click')?'asc':'desc' ?>'],'array');" title="<?php  echo ($_GET['order'] == 'desc' && $_GET['key'] == 'click')?$lang['goods_class_index_click_asc']:$lang['goods_class_index_click_desc']; ?>"><?php echo $lang['goods_class_index_click']?></a></li>
             <!-- <li <?php if($_GET['key'] == 'credit'){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" <?php if($_GET['key'] == 'credit'){?>class="<?php echo $_GET['order'];?>"<?php }?> onClick="javascript:replaceParam(['key','order'],['credit','<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'credit')?'asc':'desc' ?>'],'array');" title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'credit')?$lang['goods_class_index_credit_asc']:$lang['goods_class_index_credit_desc']; ?>"><?php echo $lang['goods_class_index_credit'];?></a></li> -->
              <li <?php if($_GET['key'] == 'show'){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" <?php if($_GET['key'] == 'show'){?>class="<?php echo $_GET['order'];?>"<?php }?> onClick="javascript:replaceParam(['key','order'],['show','<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'show')?'asc':'desc' ?>'],'array');" title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'show')?$lang['goods_class_index_show_asc']:$lang['goods_class_index_show_desc']; ?>"><?php echo $lang['goods_class_index_show'];?></a></li>
              <li <?php if($_GET['key'] == 'price'){?>class="selected"<?php }?>><a style="font-size:12px;" href="javascript:void(0)" <?php if($_GET['key'] == 'price'){?>class="<?php echo $_GET['order'];?>"<?php }?> onClick="javascript:replaceParam(['key','order'],['price','<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'price')?'asc':'desc' ?>'],'array');" title="<?php echo ($_GET['order'] == 'desc' && $_GET['key'] == 'price')?$lang['goods_class_index_price_asc']:$lang['goods_class_index_price_desc']; ?>"><?php echo $lang['goods_class_index_price'];?></a></li>
            </ul>
            <!-- 排序方式E --> 
            <!-- 价格段S -->
            <div class="prices"> <em>&yen;</em>
              <input  type="text" class="w30" value="<?php echo $output['price_interval'][0];?>" />
              <em>-</em>
              <input type="text" class="w30" value="<?php echo $output['price_interval'][1];?>" />
              <input id="search_by_price" type="submit" value="搜索" />
            </div>
            <!-- 价格段E --> 
			<!--商品数量-->
			<div class="pro_num" >
					<p>
						共有<a><?php echo $output['goods_count'];?></a>件商品
						<!--<input type="checkbox">
						
						仅显示有货商品-->
					</p>
					
					
			</div>
			<!--商品数量end-->
          </div>
        </div>
        <div class="gotop"> <a href="#topNav">&nbsp;</a> </div>
      </nav>

    <div class="goodsclassifylist">
      <div id="div_lazyload">
        <textarea class="text-lazyload" style="display: none;">
          <?php require_once (BASE_TPL_PATH.'/home/goods_class_'.$output['display_mode'].'.php');?>
        </textarea>
      </div>
      <!--
      <ul class="ul-goodsclassifylist">
        <li class="li-goodsclassifylist">
          <a class="a-specialoffer-img" href=""><img src="<?php echo TEMPLATES_PATH;?>/images/haipin/img37.jpg"></a>
          <a class="a-specialoffer-name" href=""><b class="c-red">7.2折/</b>惠氏金装【免税发货】学儿乐善儿加防偏食-900g</a>
          <del class="del-goodsclassify">$42.30(￥320.00)</del>
          <b class="b-goodsclassify-price">￥92.00</b>
          <p class="p-goodsclassify-do">
            <a class="a-goodsclassify-incart" href="">加入购物车</a>
            <a class="a-goodsclassify-collect" href="">收藏</a>
          </p>
        </li>
      </ul>
      -->
      <div class="pagination"> <?php echo $output['show_page']; ?> </div>
    </div>
	

  </div>
<!-- 左边start-->

<!--新添加 左侧下拉菜单-->
<div style="width:50px; height:27px; background:#fff;"></div>
<script type="text/javascript"> 
$(document).ready(function(){
	var iNow=0;
	
	$(".go_commodity2 ").click(function(){
	
	iNow=iNow+1;
	
	var currentItem = $(this);
	var currentIndex = currentItem.attr("index");
if (iNow%2==1){
	$(".go_commodity2").eq(currentIndex).css({"background":"url(templates/default/images/ie6/plus-icn.jpg)","background-repeat":"no-repeat","background-position":"10px 11px"}); 
	$(".go_commodity2 a").eq(currentIndex).css("color","#ff6400");
	
}else{
	$(".go_commodity2").eq(currentIndex).css({"background":"url(templates/default/images/ie6/jian-icn.jpg)","background-repeat":"no-repeat","background-position":"10px 11px"}); 
	$(".go_commodity2 a").css("color","#000");
	
	}
	$(".go_commodity2_content").eq(currentIndex).stop(false,true).slideToggle("500");
	
 ;
  });
});
</script>

 <?php if(is_array($output['goods_class_array']) && !empty($output['goods_class_array'])){?>
<div class="go_class_2">
  
	<div class="go_commodity2" index="0">
		<a><?php echo empty($output['goods_class_array']['gc_name'])? '所有分类' : $output['goods_class_array']['gc_name'];?></a>
	</div>
    
	<div class="go_commodity2_content">	
    		 <?php if(is_array($output['goods_class_array']['child']) && !empty($output['goods_class_array']['child'])){?>
            <?php foreach ($output['goods_class_array']['child'] as $val){?>
                <div class="go_commodity2_content_m">
                    <dl>
                        <dt><a href="javascript:replaceParam('cate_id','<?php echo $val['gc_id'];?>')"><?php echo $val['gc_name'];?></a></dt>
                         <?php if(is_array($val['childchild']) && !empty($val['childchild'])){?>
           				 <?php foreach ($val['childchild'] as $vals){?>
                        <dd><a href="javascript:replaceParam('cate_id','<?php echo $vals['gc_id'];?>')"><?php echo $vals['gc_name'];?></a></dd>
                         <?php }?>
                         <?php }?> 
                    </dl>
                </div>	
                 <?php }?>
              <?php }?>
		
		</div>
	
	
</div>
  <?php }?>
<!--新添加 左侧下拉菜单 end-->

	<!--  <?php if(is_array($output['goods_class_array']) && !empty($output['goods_class_array'])){?>
    <div class="module_sidebar" style="float:left">
      <h2><b><?php echo $lang['goods_class_index_goods_class'];?></b></h2>
      <div class="wrap">
        <div class="side_category">
          <dl nc_type="ul_category">
            <dt><?php echo $output['goods_class_array']['gc_name'];?></dt>
            <?php if(is_array($output['goods_class_array']['child']) && !empty($output['goods_class_array']['child'])){?>
            <?php foreach ($output['goods_class_array']['child'] as $val){?>
            <dd>&nbsp;&nbsp;<a href="javascript:replaceParam('cate_id','<?php echo $val['gc_id'];?>')"><?php echo $val['gc_name'];?></a></dd>
            <?php }?>
            <?php }?>
          </dl>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <?php }?>-->
    
    <div style="width: 209px; padding-bottom: 13px; float: right; padding-right: 14px;">
		<p><img src="../../../templates/default/images/recommend/as23ss5.jpg"></p>

	</div>
  <div class="goodsclassifyleft fl">
	

    <div class="guessyoulike">
      <h4 class="h3-guessyoulike">猜你喜欢</h4>
      <?php if(!empty($output['guess_list'])){?>
	 
        <ul class="ul-goodsclassifyleft">
          <?php foreach($output['guess_list'] as $k=>$v){?>
		  <div class="li-goodsclassifyleft-div">
          <li class="li-goodsclassifyleft"><p>
			
				<a class="a-goodsclassifyleft-img" href="<?php echo SiteUrl."/goods-{$v['goods_id']}-{$v['store_id']}.html"; ?>">
				  <img width="168" src="<?php echo thumb($v,'mid');?>" onload="javascript:DrawImage(this,160,160);" title="<?php echo $v['goods_name'];?>" alt="<?php echo $v['goods_name'];?>" />
				</a>
				<a class="a-goodsclassifyleft-name" href=""><?php echo $v['goods_name']; ?></a>
				<b class="b-goodsclassifyleft">￥<?php echo $v['goods_store_price'];?></b>

		  </li></p></div>
          <?php }?>
        </ul>

      <?php }?>
    </div>
  </div>
			<!-- 左边end-->
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/waypoints.js"></script> 

<script type="text/javascript">

	var defaultSmallGoodsImage = '<?php echo defaultGoodsImage('small');?>';

	var defaultTinyGoodsImage = '<?php echo defaultGoodsImage('tiny');?>';



    //浮动效果（条件导航）  waypoints.js

    // $('#main-nav-holder').waypoint(function(event, direction) {

    //     $(this).parent().toggleClass('sticky', direction === "down");

    //     event.stopPropagation();

    // });



  //轮播 

  $(".banner").goban({

    runbox:'.banner',

    runmbox:'.banimg',

    runmain:'.ul-banimg',

    runbtnclass:'span-banbtn',

    runalltime:5000,

    runtime:1000,

    resizee:1

  });

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

  bantjtitle.children().on({

    click:function(){

      var index=$(this).index();

      $(this).addClass("span-bantjtitle").siblings().removeClass("span-bantjtitle");

      bantjmain.children().eq(index).css('display','block').siblings().css('display','none');

    }

  })



  // 左导航菜单

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

  var navleftmain=document.getElementById('navleftmain');

  var navleftlist=getByClass(navleftmain,'navleftlist','div');

  var snavleft=getByClass(navleftmain,'snavleft','div');

  for (var i = 0; i < navleftlist.length; i++) {

    var _thistop=navleftlist[i].offsetTop;

    snavleft[i].style.top=_thistop-2*81*i-40;

  };

  

  // 回到顶部

  var agotop=$(".a-gotop");

  agotop.on({

    click:function(){

      $('html,body').animate({scrollTop:0},'slow');

    }

  });

</script> 

<script>

  $(function(){

   <?php if($_GET['a_id'] == ''){?>

     $(this).children().addClass('btn2').removeClass('btn1');

     $('.dl_hide').hide();

     $('.dl_hide:first').prev().css('border','none');

     $('div[nc_type="show"] > span').html('<?php echo $lang['goods_class_index_show_more'];?>');

     <?php }?>



  // 总的 显示更多

  $('div[nc_type="show"]').click(function(){

    if($('.dl_hide').css('display') == 'none'){

     $('.dl_hide').show();

     $('.dl_hide:first').prev().css('border','');

   }else{

     $('.dl_hide').hide();

     $('.dl_hide:first').prev().css('border','none');

   }

 });



  // 单行显示更多

  $('span[nc_type="show"]').click(function(){

    s = $(this).parents('dd').prev().find('li[nc_type="none"]');

    if(s.css('display') == 'none'){

     $(this).addClass('btn2').removeClass('btn1');

     s.show();

     $(this).html('<?php echo $lang['goods_class_index_retract'];?>');

   }else{

     $(this).addClass('btn1').removeClass('btn2');

     s.hide();

     $(this).html('<?php echo $lang['goods_class_index_more'];?>');

   }

 });



  <?php if(isset($_GET['area_id']) && intval($_GET['area_id']) > 0){?>

	// 选择地区后的地区显示

  $('[nc_type="area_name"]').html(nc_class_a[<?php echo intval($_GET['area_id']);?>]);

  <?php }?>

});



</script>
