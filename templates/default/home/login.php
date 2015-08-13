<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style type="text/css">#search, #navBar { display: none !important;} /*屏蔽头部搜索及导航菜单*/</style>

<div class="content">

		<div class="selfregister loginWrap" style="background-color: #f2f2f2;">
			
			<div class="selfregisterin pr">
				<div class="selfregisterad fl">
					<a href=""><img <?php echo  'src='.TEMPLATES_PATH.'/images/login/login_04.jpg'?>  border="0"></a>

				</div>
					<div style="width:522px; float:left; padding:0; padding-top:100px;">
					<div style="padding:0; background:#fff; float:left; border-right: 1px solid #e6e6e6;border-bottom: 1px solid #e6e6e6;">
					<div class="selfloginmain">
						<form id="login_form" method="post" class="bg">
							<div class="userlogin">
								<em>登录帐号</em>
								<span>还没账号？<a href="<?php echo SiteUrl.'/';?>index.php?act=login&op=register" >免费注册</a></span>
							</div>
							
							<?php Security::getToken();?>
							<table class="table-selfregister logintable">
								<input type="hidden" name="form_submit" value="ok" />
								<input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />
								<tbody>
									<tr style="display:none;">
										<th colspan="3" style="padding-top:0;">
											<div class="loginTableTit">&nbsp;</div>
										</th>
									</tr>	
									<tr>
										<td > 
											<div class="tbo_line"></div>
											<input  style="margin-bottom:15px;" class="inputtxt8 inputname" type="text" name="user_name" autocomplete="off" placeholder="手机号" id="user_name" tabindex="1">
										</td>
										<td class="td-imghit"></td>
									</tr>							
									<tr>
										<td>
											<div class="tbo_line"></div>
											<input class="inputtxt8 inputword" type="password" name="password" autocomplete="off" placeholder="密 码" autocomplete="off"  id="password" tabindex="2">
									<div style="padding-top:10px; padding-bottom:10px;">
										<input type="checkbox" name="auto"  value="1" style="position: relative; top: 2px; right: 4px;" /><a>两周内自动登录</a>
										<a href="index.php?act=login&op=find_password1" id="forgot_my_pwd"><?php echo $lang['login_index_forget_password'];?></a>
									</div>
										</td>
										<td class="td-imghit"></td>
									</tr>
									

									<?php if(C('captcha_status_login') == '1') { ?>
									<tr class="noaccountsWrap">
										<td>
											<input class="inputtxt9" type="text" name="captcha" id="captcha" autocomplete="off" placeholder="验证码" tabindex="3" />
											<span class="codeimageWrap"><img id="codeimage9" name="codeimage" src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>"></span>
											<a class="a-verify" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage9').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
										</td>
										<td class="td-imghit"></td>
									</tr>
									<?php $_COOKIE['wing']='wing'; } ?>

									<tr>
										<!--
										<td><input class="inputsub5 fl inputw310" type="submit" name="Submit" value="<?php echo $lang['login_index_login'];?>"><a class="a-forgetpasswprd" href="index.php?act=login&op=forget_password"><?php echo $lang['login_index_forget_password'];?></a><input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url"></td>
										-->
										<td style="padding-top:15px;  height:50px; color:#fff;">
											<!-- <a class="a-forgetpasswprd" href="index.php?act=login&op=find_password1"><?php echo $lang['login_index_forget_password'];?></a> -->
											<input style="#fff" id="login-index" class="inputsub5 fl inputw310 " type="button" name="button" value="<?php echo $lang['login_index_login'];?>">
											<input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url"></td>
										<td class="td-imghit"></td>
									</tr>
								
								</tbody>
							</table>
							
						</form>	
						
					</div>
					
					<div  class="selfloginmain_min" >
						<a class="selfloginmain_min_sao">微信扫一扫</a>
						<p class="selfloginmain_min_img"><img <?php echo  'src='.TEMPLATES_PATH.'/images/login/login_right_weixin.jpg'?> ></p>
						<p>关注<a>海品乐购</a></p>
						<p>获取最新优惠信息</p>
					</div>
				</div>
				</div>
			</div>
		</div>

		</div>
	</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.placeholder.min.js"></script>
<script>
	//注册tab切换
	$("#loginer h3").click(function()
	{
		var index = $("#loginer h3").index(this);
		$(".nc-login-content").eq(index).show().siblings('.nc-login-content').hide();
	})

//会员登陆验证
$(document).ready(function(){

	/*IE9以下placeholder修复*/
	$("#login_form input").placeholder();
			
	$("#login_company_form ").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd');
			error_td.find('label').hide();
			error_td.append(error);
		},
		rules: {
			company_email: "required",
			company_passwd: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
				,captcha : {
					required : true,
					remote   : {
						url : 'index.php?act=seccode&op=check&nchash=<?php echo $output['nchash'];?>',
						type: 'get',
						data:{
							captcha : function(){
								return $('#captcha').val();
							}
						}
					}
				}
				<?php } ?>
			},
			
			messages: {
				company_email: "<?php echo $lang['login_index_input_username'];?>",
				company_passwd: "<?php echo $lang['login_index_input_password'];?>"
				<?php if(C('captcha_status_login') == '1') { ?>
					,captcha : {
						required : '<?php echo $lang['login_index_input_checkcode'];?>',
						remote	 : '<?php echo $lang['login_index_wrong_checkcode'];?>'
					}
					<?php } ?>
				}
			});
		
		/*判断登录的浏览器*/
		var oName  = $("#user_name");
		var oCaptcha = $("#captcha");
		var isIE  = navigator.userAgent.indexOf('Trident');
		var noPlaceholder = /MSIE 6\.0|MSIE 7\.0|MSIE 8\.0|MSIE 9\.0/.test(navigator.userAgent);



		/*登录的修改*/

			if(isIE >=0 ){
				$(".logintable input").css("color","#ccc");
			}
			oName.bind("focus",function(){
				if(isIE >=0 ){					
					if($.trim($(this).val()) == "手机号" || $.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#000000").val("");
						}else{
							$(this).css("color","#000000");
						}
					}	
				}
			}).bind("blur",function(){
				if(isIE >=0){
					if($.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#cccccc").val("手机号");
						}else{
							$(this).css("color","#cccccc");
						}
					}					
				}
			})
			$("[name='password']").bind("focus",function(){
				if(isIE >=0 ){					
					if($.trim($(this).val()) == "密 码" || $.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#000000").get(0).value = "";
						}else{
							$(this).css("color","#000000");
						}
					}	
				}
			}).bind("blur",function(){
				if(isIE >=0){
					if($.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#cccccc").val("密 码");
						}else{
							$(this).css("color","#cccccc");
						}
					}					
				}
			})

			oCaptcha.bind("focus",function(){
				if(isIE >=0 ){					
					if($.trim($(this).val()) == "验证码" || $.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#000000").val("");
						}else{
							$(this).css("color","#000000");
						}
					}	
				}
			}).bind("blur",function(){
				if(isIE >=0){
					if($.trim($(this).val()) == ""){
						if(noPlaceholder){
							$(this).css("color","#cccccc").val("验证码");
						}else{
							$(this).css("color","#cccccc");
						}
					}					
				}
			})		

		/*点击提交登录验证*/
		$(".logintable [type='button']").bind("click",function(event){
			checkForm();
		})

		/*键盘enter可以提交*/
		$("#login_form").bind("keydown",function(event){
			/*利用冒泡的作用*/
			if( event.keyCode == 13){
				checkForm();
			}
		})
			/*忘记密码-鼠标经过*/
		$("#forgot_my_pwd").mouseover(function(){
			$(this).css("color","#ff6c00")
		})
		$("#forgot_my_pwd").mouseout(function(){
			$(this).css("color","#626262")
		})
			/*看不清楚换一张-鼠标经过*/
		$(".noaccountsWrap .a-verify").mouseover(function(){
			$(this).css("color","#ff6c00")
		})
		$(".noaccountsWrap .a-verify").mouseout(function(){
			$(this).css("color","#626262")
		})
			/*登录-鼠标经过*/  
		$("#login-index").mouseover(function(){
			$(this).css("background-color","#000")
		})
		$("#login-index").mouseout(function(){
			$(this).css("background-color","#ff6c00")
		})

		/*验证登录各项*/

		function checkForm() {
			var oLoginTableTit = $(".loginTableTit");

			if($.trim(oName.val()) == "" || $.trim(oName.val()) == "手机号"){
				oLoginTableTit.parents("tr").show().end().html("用户名不能为空");
				oName.focus();
				return false;
			}else{
				oLoginTableTit.parents("tr").hide().end().html("");
			}
			if($.trim($("#password").val()) == "" || $.trim($("#password").val()) == "密 码"){
				oLoginTableTit.parents("tr").show().end().html("密码不能为空");
				$("#password").focus();
				return false;
			}else{
				oLoginTableTit.parents("tr").hide().end().html("");
			}
	
			if($.trim(oCaptcha.val()) == "" || $.trim(oCaptcha.val()) == "验证码"){
				oLoginTableTit.parents("tr").show().end().html("验证码不能为空");
				oCaptcha.focus();
				return false;
				
			}else{
				oLoginTableTit.parents("tr").hide().end().html("");
			}

			checkCaptcha();

		}
		/*验证码校对*/
		function checkCaptcha(){
			 $.ajax({
			 	type : "post",
			 	url :"index.php?act=ajax&op=getcaptcha",
			 	data : {
			 		captcha : $.trim($("#captcha").val()),
			 		nchash :$.trim($("[name = 'nchash']").val())
			 	},
			 	dataType : "json",
			 	success : function(data){

			 		if(data.status == "fail"){
			 			$(".loginTableTit").parents("tr").show().end().html("验证码错误，请重新输入验证码");
			 			$("#captcha").focus();
			 			$("#captcha").val("");
			 			$(".a-verify").trigger("click");
			 			return;
			 		}
			 		checkUser();
			 	}
			 })
		}
	


		/*验证用户与密码匹配*/
		function checkUser(){
	 		$.ajax({
	 			type : "post",
			 	url :"?act=ajax&op=verifymember",
			 	data : {
			 		act : "1",
			 		member_name : $.trim($("#user_name").val()),
			 		pwd : $.trim($("#password").val())
			 	},
			 	dataType : "json",
			 	success :function(data){
			 		if(data.status == "fail"){
			 			if(data.data.code == "2"){
			 				$(".loginTableTit").parents("tr").show().end().html("用户名不存在");
			 			}else{
			 				$(".loginTableTit").parents("tr").show().end().html("用户名或密码不正确");
			 			}
			 			if(isIE >=0){
							$("#password").val("").css("color","#cccccc");
			 				$("#captcha").val("").css("color","#cccccc");				
						}else{
				 			$("#password").val("");
				 			$("#captcha").val("");
						}
			 			$(".a-verify").trigger("click");
	 					
			 		}else{
			 			$("#login_form").submit();
			 		}
			 	}
	 		})
		}
		
});

</script>

