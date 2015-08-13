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
								<li><span style="background-color:#ff6c00;"></span></li>
								<li class="li-ompanyregisterstepon"><span>2</span></li>
							</ul>
						</div>
						<div class="companyregisterstepin">
						<form action="index.php?act=login&op=find_password3" method="POST" id="find_password_form" onsubmit="return setpwd()">
							<input type="hidden" name="oldpassword" value="<?php echo $output['pwd'];?>">
							<table class="table-companyregister">
								<tbody><tr>
									<th>设置密码</th>
									<td>
										<input class="inputtxt8 inputword" type="password" name="password" id="password">
										<p id="p-error" class="p-error"></p>
									</td>
									<td class="td-imghit"></td>
								</tr>							
								<tr>
									<th>确认密码</th>
									<td><input class="inputtxt8 inputword" type="password" name="repassword" id="repassword"></td>
									<td class="td-imghit"></td>
								</tr>							
								<tr>
									<th> </th>
									<td><input class="inputsub5 fl inputw310" value="下一步" type="submit"></td>
									<td class="td-imghit"></td>
								</tr>
							</tbody></table>
							<form>
						</div>
					</div>
				</div>
			</div>
			<div class="returnlogin font-mic">
				<a href="/index.php?act=login">返回登录</a>
			</div>
			
<script type="text/javascript">
function setpwd(){
	if(password.value==repassword.value){
		return true;
	}else{
		document.getElementById('p-error').innerHTML = '重复密码输入错误，请再次确认密码。';
		return false;
	}
}
$('.inputword').focus(function(){
	document.getElementById('p-error').innerHTML = '';
});
$(function(){
    $('#find_password_form').validate({
        rules : {
            password : {
                required : true,
				minlength : 6
            },
			repassword : {
                required : true,
				minlength : 6
            }
        },
        messages : {
            password : {
                required : '请输入密码',
				minlength : '密码长度不合法'
            },
			repassword : {
                required : '请输入确认密码',
				minlength : '密码长度不合法'
            }
        }
    });
});
</script> 
