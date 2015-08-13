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

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.lazyload.mini.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/newindex.js"></script>

<script type="text/javascript">

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
<?php require_once template('layout/new_layout_top');?>


<!--搜索区域-->
<div id="searchLogoWrap" class="w100 bc_white">
    <div class="searchLogo">
        <h1 id="logo"><a href="index.php" target=""><img src="<?php echo TEMPLATES_PATH;?>/images/newindex/logo.jpg" alt="" /></a></h1>
        <div class="searchWrap">
            <form action="index.php" method="get">
            <input name="act" id="search_act" value="search" type="hidden">
                <div class="searchTxt">
                    <span class="revamp_icon"></span>
                    <input type="text" value="" placeholder="请输入你想要买的商品…" name="keyword" id="search_input" class="w100 search_input" />
                </div>
                <input type="submit" value="搜索" id="subBtn_input" class="font14 white bc_black fl lh29 subBtn_input ff_yh" >
            </form>
        </div>
    </div>
</div>
<!--搜索区域-->

<!--菜单区域-->
<div id="navWrap" class="bc_black w100 pr">
    <div id="navContent" class="a_white">
        <ul id="nav" class="fl font15">
            <li class="active"><a href="index.php" target="blank">首 页 </a></li>
            <?php if(!empty($output['head_menu']) && is_array($output['head_menu'])){ ;?>
                <?php foreach ($output['head_menu'] as $k1=>$v1) { ?>
                    <li><a href="<?php echo $v1['word_link'] ?>" target="blank"><?php echo $v1['word_value'] ?></a></li>
                <?php } } ?>
            <li class="subNav pr">
                <span class="revamp_icon"></span>
                <a href="javascript:;">主题馆</a>
                <dl class="w100 pa a_white a_bg_white dp_n">
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=national' ?>" target="_blank">韩国馆</a></dd>
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=dubai' ?>" target="_blank">迪拜馆</a></dd>
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=japan' ?>" target="_blank">日本馆</a></dd>
                </dl>
            </li>
        </ul>
        <a id="shopCart" href="<?php echo SiteUrl.'/';?>index.php?act=cart" target="">
            <span class="revamp_icon"></span>购物车<span>(<?php echo intval($output['goods_num']); ?>)</span>
        </a>
    </div>
</div>
<!--菜单区域-->
</div>
<!--公共头部-->


<?php require_once($tpl_file);?>

<?php require_once template('new_footer');?>
</div>


</body>
</html>
