<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style type="text/css">#search, #navBar { display: none !important;} /*屏蔽头部搜索及导航菜单*/</style>
<div class="content">
	<div class="w1210">
		<div class="registerheader">
			<div class="logo fl"><a href="index.php"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/logo.png"></a></div>
			<div class="registertop fr">
				<ul class="ul-registertop font-mic">
					<li class="ul-registertop1">真品</li>
					<li class="ul-registertop2">合法</li>
					<li class="ul-registertop3">低价</li>
					<li class="ul-registertop4">快捷</li>
				</ul>
			</div>
		</div>
		<div class="selfregister">
			<div class="selfregisterad fl" >
				<a href=""><img src="<?php echo $output['lpic'];?>"  border="0"></a>
			</div>
			<div class="selfregisterin pr" style="float:right">
				<div class="userlogin">
					<a class="" href="javascript:void(0)">汽配管理登陆</a>
				</div>
				<div class="selfloginmain">
					<form id="login_form" method="post" class="bg">
						<?php Security::getToken();?>
						<table class="table-selfregister logintable">
							<input type="hidden" name="form_submit" value="ok" />
							<input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />
							<tr style="display:none;">
								<th colspan="3" style="padding-top:0;">
									<div class="loginTableTit" >&nbsp;</div>
								</th>
							</tr>	
							<tr>
								<th><?php echo $lang['login_index_username'];?></th>
								<td>
									<input class="inputtxt8 inputname" type="text" name="user_name" value="用户名" id="user_name">
								</td>
								<td class="td-imghit"></td>
							</tr>							
							<tr>
								<th><?php echo $lang['login_index_password'];?></th>
								<td>
									<input class="inputtxt8 inputword" type="password" name="password" autocomplete="off"  id="password">
								</td>
								<td class="td-imghit"></td>
							</tr>
							
							<?php if(C('captcha_status_login') == '1') { ?>
							<tr>
								<th><?php echo $lang['login_index_checkcode'];?></th>
								<td>
									<input class="inputtxt9" type="text" name="captcha" id="captcha"><span><img id="codeimage9" name="codeimage" src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>"></span><a class="a-verify" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage9').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
								</td>
								<td class="td-imghit"></td>
							</tr>
							<?php } ?>
							<tr>
								<th></th>
								
								<td><input class="inputsub5 fl inputw310" type="submit" name="Submit" value="<?php echo $lang['login_index_login'];?>"><input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url"></td>
								<td class="td-imghit"></td>
							</tr>
						</table>
					</form>	
				</div>
			</div>
		</div>
			
		</div>
	</div>
	<script>
//会员登陆验证
$(document).ready(function(){
	$("#login_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd');
			error_td.find('label').hide();
			error_td.append(error);
		},
		rules: {
			user_name: "required",
			password: "required"
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
				user_name: "<?php echo $lang['login_index_input_username'];?>",
				password: "<?php echo $lang['login_index_input_password'];?>"
				<?php if(C('captcha_status_login') == '1') { ?>
					,captcha : {
						required : '<?php echo $lang['login_index_input_checkcode'];?>',
						remote	 : '<?php echo $lang['login_index_wrong_checkcode'];?>'
					}
					<?php } ?>
				}
			});	
			
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

		var oUserName = $("#user_name");

		oUserName.bind("focus",function(){
			if($.trim(oUserName.val()) == "用户名"){
				oUserName.css("color","#000000").val("");
			}
		}).bind("blur",function(){

				if($.trim(oUserName.val()) == ""){
				oUserName.css("color","#cccccc").val("用户名");
			}
		})	

		$("[name='Submit'").bind("click",function(){
			var oName  = $("#user_name");
			var oPwd = $("#password");
			var oCaptcha = $("#captcha");
			var oLoginTableTit = $(".loginTableTit");

			if($.trim(oName.val()) == "用户名"){
				oLoginTableTit.parents("tr").show().end().html("用户名不能为空");
				oName.focus();
				return;
			}else{
				oLoginTableTit.parents("tr").hide().end().html("密码不能为空");
			}
			if($.trim(oPwd.val()) == ""){
				oLoginTableTit.parents("tr").show().end().html();
				oPwd.focus();
				return;
			}else{
				oLoginTableTit.parents("tr").hide().end().html("");
			}
			if($.trim(oCaptcha.val()) == ""){
				oLoginTableTit.parents("tr").show().end().html("验证码不能为空");
				oCaptcha.focus();
				return;
			}else{
				oLoginTableTit.parents("tr").hide().end().html("");
			}
		})
});
</script>	