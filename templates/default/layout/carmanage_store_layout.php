<?php defined('haipinlegou') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo ($lang['nc_member_path_'.$output['menu_sign']]==''?'':$lang['nc_member_path_'.$output['menu_sign']].'_').$output['html_title'];?></title>
<meta name="keywords" content="<?php echo $GLOBALS['setting_config']['site_keywords']; ?>" />
<meta name="description" content="<?php echo $GLOBALS['setting_config']['site_description']; ?>" />
<meta name="author" content="haipinlegou">
<meta name="copyright" content="haipinlegou Inc. All Rights Reserved">
<link href="<?php echo TEMPLATES_PATH;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/member.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/member_store.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><style type="text/css">
body {_behavior: url(<?php echo TEMPLATES_PATH;?>/css/csshover.htc);}
</style>
<![endif]-->
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/utils.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>.


<!--[if IE]>
<script src="<?php echo RESOURCE_PATH;?>/js/html5.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_PATH;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script> 
// <![CDATA[ 
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand)) 
try{ 
document.execCommand("BackgroundImageCache", false, true); 
   } 
catch(e){} 
// ]]> 
</script> 
<![endif]-->
<script type="text/javascript">
COOKIE_PRE = '<?php echo COOKIE_PRE;?>';_CHARSET = '<?php echo strtolower(CHARSET);?>';SITEURL = '<?php echo SiteUrl;?>';
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
	//search
	$("#details").children('ul').children('li').click(function(){
		$(this).parent().children('li').removeClass("current");
		$(this).addClass("current");
		$('#search_act').attr("value",$(this).attr("act"));
	});
	var search_act = $("#details").find("li[class='current']").attr("act");
	$('#search_act').attr("value",search_act);
	$("#keyword").blur();
});
function show_store(store_id){
	var store_url="<?php echo SiteUrl;?>/index.php?act=show_store&id=";
	var s_id=store_id;
	$.get("api.php?act=get_session&key=store_id", function(data){
	  if(data != '') s_id=data;
	});
	if(s_id > 0) window.open(store_url+s_id,'','');
}
</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div id="header">
  <h1 title="<?php echo $GLOBALS['setting_config']['site_name']; ?>">
	<a href="<?php echo SiteUrl;?>">
		<img src="<?php echo ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logo']; ?>" alt="<?php echo $GLOBALS['setting_config']['site_name']; ?>" class="pngFix">
	</a>
  </h1>
  <div id="search" class="search">
    <div class="details" id="details">
      <ul class="tab">
        <li <?php if($_GET['act'] != 'search' ) echo 'class="current"'; ?> act="search"><span><?php echo $lang['site_search_goods'];?></span></li>
        <li <?php if($_GET['act'] == 'shop_search') echo 'class="current"'; ?> act="shop_search"><span><?php echo $lang['site_search_store'];?></span></li>
      </ul>
      <div id="a1" class="form">
        <form method="get" action="index.php" onSubmit="return searchInput();">
          <input name="act" id="search_act" value="search" type="hidden">
          <div class="formstyle">
            <input name="keyword" id="keyword" type="text" class="textinput" value="<?php echo $lang['nc_searchdefault']; ?>" onFocus="searchFocus(this)" onBlur="searchBlur(this)" maxlength="200"/>
            <input name="" type="submit" class="search-button" value="">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<br/>
<br/>


<nav class="navmenu pngFix"><span class="left-side pngFix"></span><span class="right-side pngFix"></span>
  <?php if ($_SESSION['store_id']){?>
  <div class="quicklink-set" ><a onClick="CUR_DIALOG = ajax_form('quicklink', '<?php echo $lang['nc_account_set_quicklink'];?>', 'index.php?act=store&op=quicklink', 722,0);" href="javascript:void(0);"><?php echo $lang['nc_account_set_quicklink'];?></span></a></div>
  <?php }?>
  <ul>
    <li><a href="index.php?act=carmanage" class="selected"><span><?php echo $_SESSION['cmservice'];?></span></a></li>
  </ul>
  <?php if (isset($output['quick_link'])){?>
  <div class="sub-quikc" >
    <?php foreach((array)$output['quick_link'] as $value){?>
    <?php $svalue = explode('||',$value)?>
    <a href="<?php echo $svalue[1];?>"><?php echo $svalue[2];?></a>
    <?php }?>
  </div>
  <?php }?>
</nav>
<script type="text/javascript">
// 收缩展开效果
$(document).ready(function(){
	$(".sidebar dl dt").click(function(){
		$(this).toggleClass("hou");
		var sidebar_id = $(this).attr("id");
		var sidebar_dd = $(this).next("dd");
		sidebar_dd.slideToggle("slow",function(){
				$.cookie(COOKIE_PRE+sidebar_id, sidebar_dd.css("display"), { expires: 7, path: '/'});
		 });
	});

});
</script>
<div class="layout">
  <?php if($output['left_show'] != 'order_view') { ?>
  <div class="sidebar">
   
    <dl>
      <dt id="sidebar_order_manage" <?php if(cookie('sidebar_order_manage') == 'none'){ echo "class='hou'";}?>><i class="pngFix"></i><?php echo $lang['nc_seller_order_manage'];?></dt>
      <dd style="display: <?php echo cookie('sidebar_order_manage');?>;">
        <ul>
          <li><a <?php if($output['menu_sign'] == 'carmanage_order'){ echo "class='active'";}else{ echo "class='normal'";}?> href="index.php?act=carmanage&op=carmanage_order"><?php echo $lang['nc_member_path_store_order'];?></a></li>
        </ul>
    </dl>
  
  </div>
  <div class="right-content">
    <?php if ($_SESSION['store_id']){?>
    <div class="path">
      <div><a href="index.php?act=store"><?php echo $lang['nc_seller'];?></a> <span>></span>
        <?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>
        <a href="<?php echo $output['menu_sign_url'];?>"/>
        <?php }?>
        <?php echo $lang['nc_member_path_'.$output['menu_sign']];?>
        <?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>
        </a><span>></span><?php echo $lang['nc_member_path_'.$output['menu_sign1']];?>
        <?php }?>
      </div>
    </div>
    <?php }?>
    <div class="main">
      <?php
		require_once($tpl_file);
		?>
    </div>
  </div>
  <?php
} else {
	require_once($tpl_file);
}
?>
  <div class="clear"></div>
</div>
<?php
require_once template('footer');
?>
</body>
</html>