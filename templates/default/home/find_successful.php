<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link href="<?php echo TEMPLATES_PATH;?>/css/home_login.css" rel="stylesheet" type="text/css">
<style type="text/css">
#search, #navBar {
	display: none !important;
}
</style>

<div class="companyregister">
	<div class="companyregisterin pr">
		<div class="selfregistertop font-mic">
			<a style="width:100%;" class="a-registerin" href=""><?php echo $lang['login_index_find_password'];?></a>
		</div>
		<div class="companyregistermain">
			<div class="companyregisterstep">
				<ul class="ul-companyregisterstep font-mic">
					<li><span>1</span></li>
					<li><span>2</span></li>
					<li class="li-ompanyregisterstepon"><span>3</span></li>
				</ul>
			</div>
			<div class="forgetpasswordecho font-mic">
				<p class="p-forgetpasswordecho">密码修改成功!3秒钟后返回登录页面...</p>
				<p class="p-forgetpasswordbtn"><a href="/index.php?act=login">返回登录</a></p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function jumpurl(){  
	  location.href='/index.php?act=login';  
	}
	setTimeout('jumpurl()',3000);  
</script> 
