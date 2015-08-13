<?php defined('haipinlegou') or exit('Access Invalid!');?>

<!doctype html>

<html>

<head>

<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">

<title><?php echo ($lang['nc_member_path_'.$output['menu_sign']]==''?'':$lang['nc_member_path_'.$output['menu_sign']].'_').$output['html_title'];?></title>

<meta name="keywords" content="<?php echo C('site_keywords'); ?>" />

<meta name="description" content="<?php echo C('site_description'); ?>" />

<meta name="author" content="haipinlegou">

<meta name="copyright" content="haipinlegou Inc. All Rights Reserved">



<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/style0402.css" rel="stylesheet" type="text/css">

<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css" rel="stylesheet" type="text/css">





<script>

COOKIE_PRE = '<?php echo COOKIE_PRE;?>';_CHARSET = '<?php echo strtolower(CHARSET);?>';SITEURL = '<?php echo SiteUrl;?>';

</script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/jquery.ui.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.validate.min.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/member.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/utils.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/nc-sideMenu.js" charset="utf-8"></script>



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

</head>

<body>

<?php require_once template('layout/layout_top');?>
<!-- 新增用户信息头部 -->
<?php require_once template('layout/member_layout_top');?>
<!-- 新增用户信息头部 -->
<!--海品bane页-->

<!-- <div class="buyerhead">

		<div class="w1210">

			<div class="buyerlogo fl"><a href="<?php echo SiteUrl;?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo2.png"></a></div>

			<div class="buyernav fl font-mic">

				<ul class="ul-buyernav">

					<li><a <?php if($output['header_menu_sign'] == 'snsindex'){ echo "class='active'";}else{ echo "class='normal'";}?>  class="a-buyernav" href="index.php?act=member_snsindex" title="<?php echo $lang['nc_member_path_buyerindex'];?>"><?php echo $lang['nc_member_path_buyerindex'];?></a></li>

					<li><a <?php if($output['header_menu_sign'] == 'message'){ echo "class='active'";}else{ echo "class='normal'";}?> href="index.php?act=home&op=message" title="<?php echo $lang['nc_member_path_message'];?>"><?php echo $lang['nc_member_path_message'];?>

				    <?php if (intval($output['message_num']) > 0){ ?>

				    <i class="new-message"><?php echo intval($output['message_num']); ?></i>

				    <?php }?>

				    </a></li>

					 <li><a <?php if($output['header_menu_sign'] == 'setting'){ echo "class='a-buyernav'";}else{ echo "class='normal'";}?> href="index.php?act=home&op=member" title="<?php echo $lang['nc_member_path_setting'];?>"><?php echo $lang['nc_member_path_setting'];?></a></li>

				</ul>

			</div>

			<div class="buyersearch fr">

				<div class="buyersearchmain">

					<form method="get" action="index.php" onSubmit="return searchInput();">

					 <input name="act" id="search_act" value="search" type="hidden">

					<input  name="keyword" id="keyword" class="inputtxt5 font-mic" type="text" placeholder="<?php echo $lang['nc_searchdefault']; ?>">

					<input name="" class="inputsub4 font-mic" type="submit" value="搜索">

					</form>

				</div>

			</div>

		</div>

	</div> -->

<!--海品内容-->

<div class="content">

	<div class="buyercenter">

		<div class="w1210">

				<div class="curmbs">

					<p>

						您当前的位置：

						<a href="index.php?act=member_snsindex"><?php echo $lang['nc_user_center'];?></a>

						&gt;

						<span><?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>

						  <a href="<?php echo $output['menu_sign_url'];?>"/>

						  <?php }?>

						  <?php echo $lang['nc_member_path_'.$output['menu_sign']];?>

						  <?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>

						  </a><span>&raquo;</span><?php echo $lang['nc_member_path_'.$output['menu_sign1']];?>

						  <?php }?></span>

					</p>

				</div>

				<div class="buyercenterin">

					<div class="buyercenterleft font-mic">

						<div class="buyerimg mb14">

							<div class="buyerimgimg">

							<img style="width:200px;height:200px;" src="<?php if ($output['member_info']['member_avatar']!='') { echo ATTACH_AVATAR.DS.$output['member_info']['member_avatar']; } else { echo ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" onload="javascript:DrawImage(this,200,200);" alt="<?php echo $output['member_info']['member_name']; ?>" />

							<a href="index.php?act=home&op=avatar" title="<?php echo $lang['nc_updateavatar'];?>"><?php echo $lang['nc_updateavatar'];?></a>

							</div>

							<div class="buyerimgmess">

							  <a class="buyeringname"href="index.php?act=home&op=address" title="<?php echo $lang['nc_edituserinfo'];?>"><?php echo empty($output['member_info']['member_truename'])? $output['member_info']['member_name']:$output['member_info']['member_truename'];?></a>

							    <p>

								  <span class="span-buyerimg">

									  <?php if (empty($output['member_info']['credit_arr'])){ echo 买家信用.$lang['nc_colon'].$output['member_info']['member_credit']; }else {?>

									  <span class="buyer-<?php echo $output['member_info']['credit_arr']['grade']; ?> level-<?php echo $output['member_info']['credit_arr']['songrade']; ?>"></span>

									  <?php }?>

									</span>

								</p>

									<?php if (C('points_isuse') == 1){ ?>

									<p><span class="span-buyerimg"><?php echo $lang['nc_pointsnum'].$lang['nc_colon'];?><b><?php echo $output['member_info']['member_points'];?></b>分</span></p>

									<?php }?>

  				

							</div>

						</div>

					<div class="buyercenterleftitem">

							<!-- <h3 class="sellertlefttitle"><?php echo $lang['nc_member_order_manage'];?></h3> -->

							<div class="dealdone">

								<ul class="ul-dealdone">
									<!--账号信息  -->
									<li>
										<div class="title"><h3 class="sellertlefttitle">个人信息管理</h3></div>
										<div class="sdealdone subList">
											<div class="normal" <?php if($output['menu_sign'] == 'profile'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=home&op=member"><?php echo $lang['nc_member_path_profile'];?></a></div>

											<div class="normal" <?php if($output['menu_sign'] == 'address'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member&op=address"><?php echo $lang['nc_member_path_address'];?></a></div>

											<?php if (C('qq_isuse') == 1){?>

											<div class="normal" <?php if($output['menu_sign'] == 'qq_bind'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_qqconnect&op=qqbind"><?php echo $lang['nc_member_path_qq_bind'];?></a></div>

											<?php }?>

											<?php if (C('sina_isuse') == 1){?>

											<div class="normal" <?php if($output['menu_sign'] == 'sina_bind'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_sconnect&op=sinabind"><?php echo $lang['nc_member_path_sina_bind'];?></a></div>

											<?php }?>
										</div>

									</li>	
									<li>

										<div class="title"><h3 class="sellertlefttitle"><em class="i2"></em><?php echo $lang['nc_member_order_manage'];?></h3></div>
										<?php if (C('flea_isuse') == 1){ ?>

										<div class="sdealdone subList"><div <?php if($output['menu_sign'] == 'flea'){ echo "class='active'";}else{ echo "class='normal'";}?>><em class="i1"></em><a href="index.php?act=member_flea"><?php echo $lang['nc_member_path_flea'];?></a></div></div>

										<?php }?>

										<div class="sdealdone subList"><div <?php if($output['menu_sign'] == 'myorder'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member&op=order"><?php echo $lang['nc_member_myorder'];?></a></div></div>
                                        <div class="sdealdone subList"><div <?php if($output['menu_sign'] == 'myorder'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_return">退货申请</a></div></div>
									</li>

									<li>

									  	<div class="title"><h3 class="sellertlefttitle"><em class="i4"></em><?php echo $lang['nc_member_path_favorites'];?><i></i></h3></div>

										<div class="sdealdone subList">

											<div <?php if($output['menu_sign'] == 'collect_list'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_favorites&op=fglist"><?php echo $lang['nc_member_path_collect_list'];?></a></div>

											 

											 <div <?php if($output['menu_sign'] == 'collect_store'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_favorites&op=fslist"><?php echo $lang['nc_member_path_collect_store'];?></a></div>

											 <?php if (C('flea_isuse') == 1){ ?>

											  <div <?php if($output['menu_sign'] == 'flea_favorites'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_flea&op=favorites&type=flea"><?php echo $lang['nc_member_path_flea_favorites'];?></a></div>

											  <?php }?>

										</div>

									</li>

									<!--账号信息  -->
									<li>
										<div class="title"><h3 class="sellertlefttitle"><?php echo $lang['nc_member_path_accountsettings'];?></h3></div>
										<div class="sdealdone subList">
											<?php if (C('predeposit_isuse') == 1){ ?>

											<div class="normal" <?php if($output['menu_sign'] == 'predepositrecharge'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=predeposit"><?php echo $lang['nc_member_path_predepositrecharge'];?></a></div>

											<div class="normal" <?php if($output['menu_sign'] == 'predepositcash'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=predeposit&op=predepositcash"><?php echo $lang['nc_member_path_predepositcash'];?></a></div>

											<div class="normal" <?php if($output['menu_sign'] == 'predepositlog'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=predeposit&op=predepositlog"><?php echo $lang['nc_member_path_predepositlog'];?></a></div>

											<?php }?>
											<?php if (C('points_isuse') == 1){ ?>

											<div <?php if($output['menu_sign'] == 'points'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_points"><?php echo $lang['nc_member_path_points'];?></a></div>

											  <?php if (C('points_isuse') == 1 && C('pointprod_isuse') == 1){ ?>

											  <!--<dd <?php if($output['menu_sign'] == 'pointorder'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_pointorder"><?php echo $lang['nc_member_path_pointorder'];?></a></dd>-->

											  	<?php if (C('ucenter_status') == 1){ ?>

											  <div <?php if($output['menu_sign'] == 'points_exchange'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_pointsexchange"><?php echo $lang['nc_member_path_points_exchange'];?></a></div>

											  	<?php }?>

											  <?php }?>
											</div>
											<?php };?>
										
									</li>

									<li>

									

									<!--				

									<?php if (C('voucher_allow') == 1){?>

									<li>

									<div <?php if($output['menu_sign'] == 'myvoucher'){ echo "class='active'";}else{ echo "class='normal'";}?>><em class="i6"></em><a href="index.php?act=member_voucher"><?php echo $lang['nc_member_path_myvoucher'];?></a>

									  </dt>

									</div>

									</li>

									<?php } ?>

									-->

									<li>

										<div class="title"><h3 class="sellertlefttitle"><?php echo $lang['nc_member_path_evalmanage'];?></h3></div>
										<div class="sdealdone subList">
											<div <?php if($output['menu_sign'] == 'evaluatemanage'){ echo "class='active'";}else{ echo "class='normal'";}?>><em class="i7"></em><a href="index.php?act=member_evaluate&op=list"><?php echo $lang['nc_member_path_evalmanage'];?></a></div>
										</div>

									</li>

									<li>

										<div class="title"><h3 class="sellertlefttitle"><?php echo $lang['nc_member_consult_complain'];?></h3></div>

										<div class="sdealdone subList">

										<div <?php if($output['menu_sign'] == 'myinform'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_inform"><?php echo $lang['nc_member_path_myinform'];?></a></div>

										<div <?php if($output['menu_sign'] == 'consult'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_consult&op=my_consult"><?php echo $lang['nc_member_path_consult'];?></a></div>

										<div <?php if($output['menu_sign'] == 'complain'){ echo "class='active'";}else{ echo "class='normal'";}?>><a href="index.php?act=member_complain"><?php echo $lang['nc_member_path_complain'];?></a></div>								

										</div>

									</li>

								</ul>

							</div>

						</div>

					</div>

					<div class="buyercenterright font-mic">

							<div class="path">

							

						  </div>

						  <div class="main">

							<?php

							require_once($tpl_file);

							?>

						  </div>

					</div>

				</div>

		</div>
<!--
<div class="gotop">

		<a href="javascript:void(0);" class="a-gotop"></a>

	</div>
-->
<?php

require_once template('footer');

?>

</body>

</html>

<script type="text/javascript">

	// 回到顶部

	var agotop=$(".a-gotop");

	agotop.click(function(){

			$('html,body').animate({scrollTop:0},'slow');

		}

	);

</script>