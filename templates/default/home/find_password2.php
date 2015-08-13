<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link href="<?php echo TEMPLATES_PATH;?>/css/home_login.css" rel="stylesheet" type="text/css">
<style type="text/css">
#search, #navBar {
	display: none !important;
}
</style>

			<div class="companyregister">
				<!-- /******/ -->
					<div class="companyregister_he">
							<div class="companyregister_he_main">
								<p><a href="<?php echo SiteUrl;?>" style="padding:0;"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo_forgot_pwd.jpg" title="海品乐购 精选海外正品，乐享品质生活" height="63px" width="260px"></a></p>
								<a href="<?php echo SiteUrl;?>">修改密码</a>
							</div>
					</div>
				<!-- /******/ -->
				<div class="companyregisterin pr">
					
					<div class="companyregistermain">
						
						<div class="companyregisterstepin">
						<form action="index.php?act=login&op=find_password2" method="POST" id="find_password_form">
							<table class="table-companyregister">
								<tbody><tr>
									<th>选择验证身份方式</th>
									<td>
										<select class="select2" name="checkMode" onchange="selectCheck(this)">
											<option value="1">邮箱</option>
											<option value="2">手机</option>
										</select>
									</td>
									<td class="td-imghit"></td>
								</tr>
								<tr class="email">

									<th><?php echo $lang['login_password_you_email'];?></th>

									<td>

										<input class="inputtxt8 inputemail" type="text" name="email">

										<p></p>

									</td>

									<td class="td-imghit"></td>

								</tr>
								<tr class="getCode" style="display:none">
									<th>手机号码</th>
									<td>
										<input class="inputtxt9" type="text" name="mobile_num" id="code">
										<input id="sendSMS" class="a-getcode font-mic" href="javascript:void(0);" value="获取验证码" style="border:none;padding:0;text-align:center;cursor:pointer;">
									</td>
								</tr>
								<tr class="getCode" style="display:none">
									<th>验证码</th>
									<td>
										<input class="inputtxt9" type="text" name="num">
									</td>
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
			
<script type="text/javascript">
function selectCheck(select){
	if(select.value==1){
		$('.getCode').css('display','none');
		$('.email').css('display','');
	}else{
		$('.getCode').css('display','');
		$('.email').css('display','none');
	}
}



$(function(){
    $('#find_password_form').validate({
        rules : {
            checkText : {
                code : true
            }
        },
        messages : {
            checkText : {
                required : '请填写验证码'
            }
        }
    });

	    //获取验证码倒计时
	var wait=60;
	var oSend = $("#sendSMS");
	if(oSend.length > 0){

		oSend.attr("disabled",false);   
		oSend.click(function(){
			sendSMS(this);	
		})
	}

	/*倒数的时间*/
	function time(o) {
        if (wait == 0) {

            $(o).attr("disabled",false);           
            $(o).val("获取验证码");
            wait = 60;
        } else {
            $(o).attr("disabled", true);
            $(o).val("重新发送(" + wait + ")");
            wait--;
            setTimeout(function() {
                time($(o))
            },
            1000)
        }
    }
    /*SMS的接口*/
    function sendSMS(e)
	{
		var mobile = $("#code").val();
		$.get('index.php?act=login&op=sendsmsnew&p='+Math.random(),{'mobile':mobile},function(d)
		{
			if(d == 0){
				alert('获取验证码失败！');
			}else{
				time($("#sendSMS"));
			}
		},'json');
	}
});
</script> 
