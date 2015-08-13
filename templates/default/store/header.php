<?php defined('haipinlegou') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<!--
<meta content="IE=9" http-equiv="X-UA-Compatible">
-->
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopNC">
<meta name="copyright" content="ShopNC Inc. All Rights Reserved">
<!--
<link href="<?php echo TEMPLATES_PATH;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/shop.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/home_login.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/store/style/<?php echo $output['store_info']['store_theme'];?>/style.css" rel="stylesheet" type="text/css">
-->
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/html5.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/utils.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/shop.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
 
<script>
COOKIE_PRE = '<?php echo COOKIE_PRE;?>';_CHARSET = '<?php echo strtolower(CHARSET);?>';SITEURL = '<?php echo SiteUrl;?>';
</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div class="logo-search w1200">
    <a class="a-indexlogo fl" href="<?php echo SiteUrl;?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo.jpg"></a>
	
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
			{ foreach($output['hot_search'] as $val) { ?>
			<li class="li-top-search"><a href="index.php?act=search&keyword=<?php echo urlencode($val);?>"><?php echo $val; ?></a></li>
			<?php }}?>
		</ul>
		
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('a[nctype="search_in_store"]').click(function(){
		$('#search_act').val('show_store');
		$('<input type="hidden" value="<?php echo $output['store_info']['store_id'];?>" name="id" /> <input type="hidden" name="op" value="goods_all" />').appendTo("#formSearch");
		$('#formSearch').submit();
	});
	$('a[nctype="search_in_shop"]').click(function(){
		$('#formSearch').submit();
	});
	var store_id = "<?php echo $_GET['id']; ?>";
	var goods_id = "<?php echo $_GET['goods_id']; ?>";
	var act = "<?php echo trim($_GET['act']); ?>";
	var op  = "<?php echo trim($_GET['op']) != ''?trim($_GET['op']):'index'; ?>";
	$.getJSON("index.php?act=show_store&op=ajax_flowstat_record",{id:store_id,goods_id:goods_id,act_param:act,op_param:op},function(result){
	});
});
</script>