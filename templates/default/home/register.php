
<?php defined('haipinlegou') or exit('Access Invalid!');?>

<style type="text/css">#search, #navBar { display: none !important;} /*屏蔽头部搜索及导航菜单*/</style>




            <?php if($type=='start'){?>

			<!--个人注册 -->

			<div id="tab1" >

				<div class="selfregister nc-login-content">

					<div class="selfregisterin pr">
						

						<div class="selfregistermain">	

							<div class="selfregistermain_box">
									<img  src="<?php echo TEMPLATES_PATH;?>/images/login/login_04.jpg">
							
									<form id="self1" method="post" action="<?php echo SiteUrl;?>/index.php?act=login&op=selfregister1">

			                        <!--个人注册1 -->
			                    		
							<table class="table-selfregister">
								<tr>
									<td class="selfregister_table_tit"><em>用户注册</em><span>已经有帐号？<a href="index.php?act=login">直接登录</a><span></td>
								</tr>
                                <tr>
                                    
                                    <td>
                                    	<div style=" ">
                                        <input  placeholder="手机号码" autocomplete="off" maxlength="11" class="inputtxt8 inputRegPhone tip f1" id="mobile" name="mobile" type="text" title="<?php echo $lang['login_register_input_valid_mobile'];?>">
                                       
                                        
                                         <p class="p-error email_msg"></p>
                                    	 </div>
                                        <!--
                                        <a id="btn" class="a-getyzm btn_mfyzm" onclick="sendSMS(this)" href="javascript:void(0);" value="">获取验证码</a>
                                        -->
                                        <!--<p class="p-error email_msg"></p>-->
                                    </td>

                                </tr>
                                <tr>
                                   
                                    <td>
                                        <input placeholder="手机验证码" autocomplete="off"  class="inputtxt8 inputword tip f1" id="mobile_code" name="mobile_code" type="text" title="<?php echo $lang['login_register_input_valid_mobile_vertify'];?>">
                                        <input  id="btn" class="btn_mfyzm" type="button" value="获取验证码" onclick="sendSMS(this)" />
                                        <p class="p-error email_msg"></p>
                                    </td>
                                  
                                </tr>
								<tr>

									<td>

										<input placeholder="设置密码" autocomplete="off" class="inputtxt8 inputword tip f1" id="password" name="password" type="password" title="<?php echo $lang['login_register_password_to_login'];?>">

										<p class="p-error pwd_msg"></p>

									</td>

								

								</tr>							

								<tr>

									

									<td>

									<input placeholder="确认密码" type="password" class="inputtxt8 inputword tip f1" id="password_confirm" name="password_confirm" >

                                    <p class="p-error repwd_msg"></p><a href="index.php?act=login&op=find_password1"  class="register_forget">忘记密码？</a>

									</td>

		

							

								</tr>							

								<tr>

								

									<td>

										<input placeholder="邮箱" autocomplete="off" class="inputtxt8 inputemail tip f1" id="email" name="email" type="text" title="<?php echo $lang['login_register_input_valid_email'];?>">

										<p class="p-error email_msg"></p>

									</td>

								</tr>

								<tr>

									
									<td class="regsiterdeal_last_td">

										<p class="p-regsiterdeal">
											<input name="agree" type="checkbox" id="clause" value="1" checked="checked" /><?php echo $lang['login_register_agreed'];?>

										<!--<a href="<?php echo ncUrl(array('act'=>'document','code'=>'agreement'), 'document');?>" target="_blank" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></p></td>-->

											<a href="index.php?act=document&code=agreement" target="_blank" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a>
											&nbsp;&nbsp;和
											<a href="index.php?act=document&code=private" target="_blank" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_private'];?></a>
										</p>
										<input class="inputsub5 fl" type="submit" id="selfbtu1" name="Submit" value="注&nbsp;&nbsp;&nbsp;册">
										<span class="regToLogin">已经注册？<a href="index.php?act=login">马上登录</a></span>
									</td>


								</tr>

							</table>

							<input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">

			                <input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />

					                </form>

							</div>
							
			          
						</div>

					</div>

				</div>

			</div>



		<script>
			function sendSMS(e)
			{
				var mobile = $("#mobile").val();
				$.get('index.php?act=login&op=sendsmsnew&p='+Math.random(),{'mobile':mobile},function(d)
				{
					if(d == 0){
						alert('获取验证码失败！');
					}
				},'json');
			}
			</script>			<!--企业注册1 -->

			<div id="tab2" class="hidden">

				<div class="companyregister nc-login-content">

					<div class="companyregisterin pr">
						<div class="selfregistertopWrap">
							<div class="selfregistertop font-mic">

								<a href="javascript:void(0)" onclick="tab2click()"><?php echo $lang['login_register_join_us'];?></a>

								<a class="a-registerin active" href="javascript:void(0)"><?php echo $lang['login_register_join_company'];?></a>

							</div>
						</div>

				

	                   <!--企业注册 -->

						<div class="companyregistermain">

							<div class="companyregisterstep">

								<ul class="ul-companyregisterstep font-mic">

									<li class="li-ompanyregisterstepon first">
										<span>1</span>
										<em>设置邮箱用户名</em>
										<i></i>
									</li>

									<li class="second">
										<span>2</span>
										<em class="gray">设置登录密码</em>
										<i></i>
									</li>

									<li class="last">
										<span>3</span>
										<em class="gray">填写企业信息</em>
										<i></i>
									</li>


								</ul>

							</div>

						    

						    <form id="company1" method="post" action="<?php echo SiteUrl;?>/index.php?act=login&op=comregister1">

						    <!--企业注册1 -->

							<div class="companyregisterstepin">

								<table class="table-companyregister">

									<tr>

										<th><span style="color:red">＊</span><?php echo $lang['login_register_email'];?></th>

										<td>

											<input class="inputtxt8 inputemail tip" id="email1" name="email" type="text" title="<?php echo $lang['login_register_input_valid_email'];?>">

											<p class="p-error email_cmsg"></p>


										</td>

									
									</tr>

						

									<tr>

										<th></th>

										<td >
											<p class="p-regsiterdeal">

												<input name="agree" type="checkbox" id="clause" value="1" checked="checked" /><?php echo $lang['login_register_agreed'];?>
												<a href="<?php echo ncUrl(array('act'=>'document','code'=>'agreement'), 'document');?>" target="_blank" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a>
											
											</p>
											<input class="inputsub5 fl" type="submit" id="combtu1" value="<?php echo $lang['login_register_regist_next'];?>">
											<span class="regToLogin">已经注册？<a href="index.php?act=login">马上登录</a></span>
										</td>

									

									</tr>

								</table>

							</div>

						    </form>



						</div>

		

					</div>

				</div>

			</div>



            <?php }else if($type=='step2'){?>

            <!--个人注册2 -->

            <div>

				<div class="selfregister nc-login-content">

					<div class="selfregisterin pr">
							<div class="selfregistertop font-mic">

								<a class="a-registerin" href="javascript:void(0)"><?php echo $lang['login_register_join_us'];?></a>

								<a href="javascript:void(0)" id="sel" onclick="tabclick(1)"><?php echo $lang['login_register_join_company'];?></a>

							</div>
		

						<div class="selfregistermain">

							<div class="selfregisterad fr">

								<a href=""><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img32.jpg" style="width:450px" ></a>

							</div>                           

							<!--个人注册2 -->

							<form id="self2" method="post" action="<?php echo SiteUrl;?>/index.php?act=login&op=selfregister2">

							<table class="table-selfregister">

								
								<tr>

									<th><?php echo $lang['login_register_phone'];?></th>

									<td>

										<input class="inputtxt8 inputphone tip" maxlength="11" id="mob_phone" name="mob_phone" type="text" title="<?php echo $lang['login_register_phone_to_login'];?>">

										<p class="p-error phone_msg"></p>

									</td>

								

								</tr>

								<?php if(C('captcha_status_register') == '1') { ?>						

								<tr>

									<th><?php echo $lang['login_register_code'];?></th>

									<td>

										<input class="inputtxt9" id="captcha" name="captcha" type="text"><span><img id="codeimage" name="codeimage" src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>"></span><a class="a-verify" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>

										<p class="p-error"></p>

									</td>


								</tr>

								<?php } ?>							

								<tr>

									<th> </th>

									<td><input class="inputsub5 fl" type="submit" name="Submit" value="<?php echo $lang['login_register_regist_done'];?>"></td>

								</tr>

							</table>

							<input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">

			                <input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />

			            </form>

						</div>

					</div>

				</div>

			</div>

            

            

            <?php }else if($type=='step3'){?>

            <!--企业注册2 -->

			<div>

				<div class="companyregister nc-login-content">

					<div class="companyregisterin pr">
						<div class="selfregistertopWrap">
							<div class="selfregistertop font-mic">

								<a href="index.php?act=login&op=register" id="com"><?php echo $lang['login_register_join_us'];?></a>

								<a class="a-registerin active" href="javascript:void(0)"><?php echo $lang['login_register_join_company'];?></a>

							</div>
						</div>
				

	                   <!--企业注册 -->

						<div class="companyregistermain">

							<div class="companyregisterstep">

								<ul class="ul-companyregisterstep font-mic">

									<li class="li-ompanyregisterstepon first">
										<span>1</span>
										<em>设置邮箱用户名</em>
										<i class="on"></i>
									</li>

									<li class="li-ompanyregisterstepon second">
										<span>2</span>
										<em>设置登录密码</em>
										<i></i>
									</li>

									<li class="last">
										<span>3</span>
										<em class="gray">填写企业信息</em>
										<i></i>
									</li>

								</ul>

							</div>

						    

						    <form id="company2" method="post" action="<?php echo SiteUrl;?>/index.php?act=login&op=comregister2">



	                        <!--企业注册2 -->

						    <div class="companyregisterstepin">

								<p class="userstyle"><?php echo $lang['login_register_username'];?>：<?php echo $output['email'];?></p>

								<table class="table-companyregister">



									<tr>

										<th><span style="color:red">＊</span><?php echo $lang['login_register_password'];?></th>

										<td>

											<input class="inputtxt8 inputword tip" id="company_password" name="company_password" type="password" title="<?php echo $lang['login_register_password_to_login'];?>">

											<p class="p-error pwd_cmsg"></p>

										</td>

									

									</tr>							

									<tr>

										<th><span style="color:red">＊</span><?php echo $lang['login_register_ensure_password'];?></th>

										<td>

											<input class="inputtxt8 inputword tip" id="company_password_confirm" name="company_password_confirm" type="password" title="<?php echo $lang['login_register_input_password_again'];?>">

											<p class="p-error repwd_cmsg"></p>


										</td>

									

									</tr>							

									<tr>

										<th></th>

										<td>
											<input class="inputsub5 fl" type="submit" id="combtu2" value="<?php echo $lang['login_register_regist_next'];?>">
											<span class="regToLogin">已经注册？<a href="index.php?act=login">马上登录</a></span>
										</td>

									

									</tr>

								</table>

							</div>

						    </form>



						</div>

		

					</div>

				</div>

			</div>



			<?php }else if($type=='step4'){?>

			<!--企业注册3 -->

			<div>

				<div class="companyregister nc-login-content">

					<div class="companyregisterin pr">
						<div class="selfregistertopWrap">
							<div class="selfregistertop font-mic">

								<a href="index.php?act=login&op=register" id="com"><?php echo $lang['login_register_join_us'];?></a>

								<a class="a-registerin active" href="javascript:void(0)"><?php echo $lang['login_register_join_company'];?></a>

							</div>

						</div>

	                   <!--企业注册 -->

						<div class="companyregistermain">

							<div class="companyregisterstep">

								<ul class="ul-companyregisterstep font-mic">

									<li class="li-ompanyregisterstepon first">
										<span>1</span>
										<em>设置邮箱用户名</em>
										<i class="on"></i>
									</li>

									<li class="li-ompanyregisterstepon second">
										<span>2</span>
										<em>设置登录密码</em>
										<i class="on"></i>
									</li>

									<li class="li-ompanyregisterstepon last">
										<span>3</span>
										<em>填写企业信息</em>
										<i></i>
									</li>


								</ul>

							</div>

						    

						    <form id="company3" method="post" action="<?php echo SiteUrl;?>/index.php?act=login&op=comregister3">	                        

	                        <!--企业注册3 -->

							<div class="companyregisterstepin">

								<table class="table-companyregister">

									<tr>

										<th><i>*</i>企业名称</th>

										<td>

											<input class="inputtxt10 tip"  name="company_name" title="<?php echo $lang['login_company_name'];?>" type="text">

											<p class="p-error"></p>

										</td>

									</tr>							

									<tr>

										<th><i>*</i>营业执照注册号</th>

										<td>

											<input class="inputtxt10 tip" id="register_num" title="<?php echo $lang['login_register_num'];?>" name="register_num" type="text">

										    <p class="p-error"></p>

										</td>

									</tr>							

									<tr>

										<th><i>*</i>营业执照所在地</th>

										<td>

											<div id="region">

											<input type="hidden" value="" name="member_areainfo" id="area_info" title="<?php echo $lang['login_company_area_names'];?>"  class="area_names" />

											<select class="select2" name="select_areainfo"><option value="">请选择</option></select>

											<p class="p-error"></p>

											</div>

										</td>

									</tr>							

									<tr>

										<th><i>*</i>营业期限</th>

										<td>

										<input type="text" id="operation_term" name="operation_term" class="inputtxt10 tip" title="<?php echo $lang['login_company_operation_term'];?>"/>

										<p class="p-error"></p>

										</td>

									</tr>								

									<tr>

										<th><i>*</i>常用地址</th>

										<td>

											<input class="inputtxt10 tip" id="company_address" name="company_address" title="<?php echo $lang['login_company_address'];?>" type="text">

										    <p class="p-error"></p>

										</td>

									</tr>								

									<tr>

										<th><i>*</i>联系电话</th>

										<td>

											<input class="inputtxt10 tip" id="company_phone" maxlength="11" name="mob_phone" title="<?php echo $lang['login_mob_phone_style'];?>" type="text">

										    <p class="p-error"></p>

										</td>

									</tr>								

									<tr>

										<th><i>*</i>营业执照副本扫描件</th>

										<td>

										   <a href="javascript:void(0);" class="upimg" onclick="window.upload.document.upload.file.click()">点击上传</a>

										   <iframe name="upload" src="index.php?act=login&op=uploadimg" width="1px" height="1px" ></iframe> 

										   <!--<img id="upload1" src="" style="width:400px;display:none;" />-->

										   <input type="hidden" id="company_license" name="company_license" class="text tip" title="不能为空"/>

										   <p class="p-error"></p>

											<i class="p-normal">

												证件要求：<br>·必须为清晰彩色原件扫描件或数码照，图片大小不超过2M.<br>·必须在有效期内且年检章齐全（当年成立的公司可无年检章）<br>·必须为中国大陆工商局颁发

											</i>

											<div class="licenseimg">

												<p>营业执照单：</p>

												<img  id="upload1" style="width:400px;" src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img33.jpg">

											</div>

										</td>

									</tr>

									<tr>

										<th><i>*</i>组织机构代码</th>

										<td>

										    <input class="inputtxt10 tip" id="organization_code" name="organization_code" title="<?php echo $lang['login_organization_code'];?>" type="text">

                                            <p class="p-error"></p>

										</td>

									</tr>								

									<tr>

										<th><i>*</i>营业范围</th>

										<td>

										    <input class="inputtxt10 tip" id="company_range" name="company_range" title="<?php echo $lang['login_company_range'];?>" type="text">

                                            <p class="p-error"></p>

										</td>

									</tr>								

									<tr>

										<th><i>*</i>注册资金</th>

										<td>

										  <input class="inputtxt10 tip" id="registered_capital" name="registered_capital" title="<?php echo $lang['login_registered_capital'];?>" type="text">

										  <p class="p-error"></p>

										</td>

									</tr>

									<tr>

										<th><i>*</i>传真</th>

										<td>

										    <input class="inputtxt10 tip" id="company_fax" name="company_fax" title="<?php echo $lang['login_company_fax'];?>" type="text">

                                            <p class="p-error"></p>

									    </td>

									</tr>								

		                            

		                            <?php if(C('captcha_status_register') == '1') { ?>

									<tr>

										<th><i>*</i><?php echo $lang['login_register_code'];?></th>

										<td>

											<input class="inputtxt9" id="captcha" name="captcha" type="text" title="<?php echo $lang['login_register_input_code'];?>">
											<span class="codeimageWrap"><img src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>" name="codeimage" id="codeimage2"></span>
											<a class="a-verify" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage2').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>

											<!-- <p class="p-normal">请输入右侧图中的内容</p> -->

										</td>									

									</tr>

									<?php } ?>

									<tr>

										<th> </th>

										<td>
											<input class="inputsub5 fl" type="submit" value="<?php echo $lang['login_register_regist_done'];?>">
											<span class="regToLogin">已经注册？<a href="index.php?act=login">马上登录</a></span>
										</td>

									</tr>

								</table>

							    <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">

			                    <input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />

						    </div>

						    </form>



						</div>

		

					</div>

				</div>

			</div>





			<?php }else if($type=='step5'){?>

			<!--注册成功回显 start-->



			<div class="companyregister">

				<div class="companyregisterin pr">
					<div class="selfregistertopWrap">

						<div class="selfregistertop font-mic">

							<a href="javascript:void(0)"><?php echo $lang['login_register_join_us'];?></a>

							<a class="a-registerin active"  href="javascript:void(0)"><?php echo $lang['login_register_join_company'];?></a>

						</div>

					</div>

					<div class="companyregistermain">

						<div class="registerecho font-mic">

							<p class="p-registerechosucce">恭喜！注册成功！<br>请等待审核...</p>

						</div>

					</div>

				</div>

			</div>



			<?php }else if($type=='step6'){?>

			<!--注册成功回显 start-->



			<div class="companyregister">

				<div class="companyregisterin pr">

					<div class="selfregistertop font-mic">

						<a href="javascript:void(0)"><?php echo $lang['login_register_join_us'];?></a>

						<a class="a-registerin" href="javascript:void(0)"><?php echo $lang['login_register_join_company'];?></a>

					</div>



					<div class="companyregistermain">

						<div class="registerecho font-mic">

							<p class="p-registerechosucce">恭喜！注册成功！<br>请等待审核...</p>

						</div>

					</div>

				</div>

			</div>



			<?php }?>

		
		</div>

	</div>



<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/jquery.ui.js"></script> 

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.placeholder.min.js" charset="utf-8"></script>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<script>



//图片回执函数

function stopSend(str)

{

	$("#upload1").attr('src',str);

	$("#company_license").val(str);

	var content = $("#company_license").next('p.p-error').children().text();

	if(content)

	{

		$("#company_license").next('p.p-error').children().remove();

	}

}



function tab1click(){

	$('#tab1').addClass("hidden");

	$('#tab2').removeClass("hidden");



}

function tab2click(){

	$('#tab2').addClass("hidden");

	$('#tab1').removeClass("hidden");


}






/*$('.tip').poshytip({

	className: 'tip-yellowsimple',

	showOn: 'focus',

	alignTo: 'target',

	alignX: 'center',

	alignY: 'top',

	offsetX: 0,

	offsetY: 5,

	allowTipHover: false

});
*/




$(function(){
	/*IE9以下placeholder修复*/
	$(".table-selfregister input").placeholder();

	//修改营业期限bug

	$("#operation_term").blur(function()

	{

		var content = $("#operation_term").next('p.p-error').children().text();

		if(content)

		{

			$("#operation_term").next('p.p-error').children().remove();

		}

	})

		jQuery.validator.addMethod("lettersonly", function(value, element) {

			return this.optional(element) || /^[^:%,"\'\*\"\s\<\>\&"]+$/i.test(value);

		}, "Letters only please"); 

		jQuery.validator.addMethod("lettersmin", function(value, element) {

			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);

		}, "Letters min please"); 

		jQuery.validator.addMethod("lettersmax", function(value, element) {

			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);

		}, "Letters max please");

		jQuery.validator.addMethod("isPhone", function(value,element) {

		var length = value.length;

		//var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+d{8})$/;

		var mobile = /^1[3|4|5|8][0-9]\d{4,8}$/;

		var tel = /^d{3,4}-?d{7,9}$/;

		return this.optional(element) || (tel.test(value) || mobile.test(value));

		}, "请正确填写您的联系方式");

		jQuery.validator.addMethod("isMobile", function(value,element) {

		var length = value.length;

		//var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+d{8})$/;

		var mobile = /^1[3|4|5|8][0-9]\d{4,8}$/;

		return this.optional(element) || (length == 11 && mobile.test(value));

		}, "请正确填写您的手机方式");

		jQuery.validator.addMethod("checkfax1", function(value,element) {

		var length = value.length;

		var faxexec = /^d{3,4}-?d{7,8}$/;

		return this.optional(element) || (faxexec.test(value));

		}, "请正确填写您的传真方式");

		jQuery.validator.addMethod("password_compat", function(value,element) {

		return this.optional(element) || value == "设置密码";

		}, "密码不能为空");




/* 		jQuery.validator.addMethod("isfax", function(value,element) {

		    return this.optional(element) || /^d{3,4}-?d{7,8}$/.test(value);

		}, "请正确填写您的传真方式");  */



	$("#self1").validate({


        errorPlacement: function(error, element){

            var error_td = null
			error_td = element;

            error_td.html("");
            error_td.show();
            error.addClass("p-error");
            error_td.parent().append(error);
        },
        rules : {

            user_name : {

                required : true,

                lettersmin : true,

                lettersmax : true,

                lettersonly : true,

                remote   : {

                    url :'index.php?act=login&op=check_member&column=ok',

                    type:'get',

                    data:{

                        user_name : function(){

                            return $('#user_name').val();

                        }

                    }

                }

            }, 

            mobile_code:{
				required : true,
				remote   : {

					url :'index.php?act=ajax&op=verifyphone',

					type:'post',

					data:{

					phone : function(){

						return $('#mobile').val();

					},
					code :function(){
						return $('#mobile_code').val();
					}

					}
				}
            },
			
			mobile : {

                required : true,

                lettersmin : true,

                lettersmax : true,

                lettersonly : true,

                isMobile : true,

                remote   : {

                    url :'index.php?act=login&op=check_membermobile&column=ok',

                    type:'get',

                    data:{

                        user_name : function(){

                            return $('#mobile').val();

                        }

                    }

                }

            }, 
		       

			password : {

                required : true,

                minlength: 6,

				maxlength: 20

            },

            password_confirm : {

                required : true,

                equalTo  : '#password'

            },

            email : {
			
			
                required : true,

                email    : true,

                remote   : {

                    url : 'index.php?act=login&op=check_email',

                    type: 'get',

                    data:{

                        email : function(){

                            return $('#email').val();

                        }

                    }

                }

            },
			         
            agree : {

                required : true

            }



        },

        messages : {

            user_name : {

                required : '<?php echo $lang['login_register_input_username'];?>',

                lettersmin : '<?php echo $lang['login_register_username_range'];?>',

                lettersmax : '<?php echo $lang['login_register_username_range'];?>',

				lettersonly: '<?php echo $lang['login_register_username_lettersonly'];?>',

				remote	 : '<?php echo $lang['login_register_username_exists'];?>'

            },
            mobile :{
            	required : "请输入要验证的手机号码",
            	isMobile :"请输入正确的手机号",
            	remote : "手机码已注册"

            },

            mobile_code:{
            	required:'请输入手机验证码',
            	remote :"请输入正确的手机验证码"
            },
            password  : {

                required : '<?php echo $lang['login_register_input_password'];?>',

                minlength: '<?php echo $lang['login_register_password_range'];?>',

				maxlength: '<?php echo $lang['login_register_password_range'];?>'

            },

            password_confirm : {

                required : '<?php echo $lang['login_register_input_password_again'];?>',

                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'

            },

            email : {

                required : '<?php echo $lang['login_register_input_email'];?>',

                email    : '<?php echo $lang['login_register_invalid_email'];?>',

				remote	 : '<?php echo $lang['login_register_email_exists'];?>'

            },            
            agree : {

                required : '<?php echo $lang['login_register_must_agree'];?>'

            }



        }

  

    });



	$("#self2").validate({

        errorPlacement: function(error, element){

            var error_td = element.siblings('p');

            error_td.html("");

            error_td.append(error);

        },

        rules : {



            mob_phone : {

                required : true,

				maxlength: 11,

				remote   : {

                    url : 'index.php?act=login&op=check_mob_phone',

                    type: 'get',

                    data:{

                        mob_phone : function(){

                            return $('#mob_phone').val();

                        }

                    }

                }

            }        



        },

        messages : {

           mob_phone : {

                required : '<?php echo $lang['login_register_phone_to_login'];?>',

                maxlength: '<?php echo $lang['login_register_phone_to_login'];?>',

				remote	 : '<?php echo $lang['login_register_phone_to_login'];?>'

            }

        }

		//onclick : true

    });





    $("#company1").validate({

        errorPlacement: function(error, element){

            var error_td = element;

            error_td.html("");

            error_td.parent().append(error);

        },



        rules : {

            email : {

                required : true,

                email    : true,

                remote   : {

                    url : 'index.php?act=login&op=check_company_email',

                    type: 'get',

                    data:{

                        company_email : function(){

                            return $('#email').val();

                        }

                    }

                }

            }, 

            agree : {

                required : true

            }

        },

        messages : {

            email : {

                required : '<?php echo $lang['login_register_input_email'];?>',

                email    : '<?php echo $lang['login_register_invalid_email'];?>',

				remote	 : '<?php echo $lang['login_register_email_exists'];?>'

            }, 

            agree : {

                required : '<?php echo $lang['login_register_must_agree'];?>'

            }

        }



    });



    $("#company2").validate({

        errorPlacement: function(error, element){

            var error_td = element;

            error_td.html("");

            error_td.parent().append(error);

        },



        rules : {

			company_password : {

                required : true,

                minlength: 6,

				maxlength: 20

            },

            company_password_confirm : {

                required : true,

                equalTo  : '#company_password'

            }



        },

        messages : {

   

            company_password  : {

                required : '<?php echo $lang['login_register_input_password'];?>',

                minlength: '<?php echo $lang['login_register_password_range'];?>',

				maxlength: '<?php echo $lang['login_register_password_range'];?>'

            },

            company_password_confirm : {

                required : '<?php echo $lang['login_register_input_password_again'];?>',

                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'

            }         



        }



    });



    $("#company3").validate({

        errorPlacement: function(error, element){

            var error_td = element;

            // error_td.html("");

            error_td.parent().append(error);

        },



        rules : {

			company_name : {

                required : true

            },	

			register_num : {

                required : true,

/*				lettersmin : true,

                lettersmax : true,

                lettersonly : true,*/

                remote   : {

                    url :'index.php?act=login&op=check_register_num&column=ok',

                    type:'get',

                    data:{

                        register_num : function(){

                            return $('#register_num').val();

                        }

                    }

                }

            },

            select_areainfo :{
            	required: true
            },

            operation_term : {

            	required : true

            },

            mob_phone : {

                required : true,

				isPhone: true

            },

			company_address :{

				required : true

			},

			/*

			company_phone :{

				required : true,

			},*/			

			company_range :{

				required : true

			},

			registered_capital :{

				required : true

			},

			company_fax :{

				required : true
				//checkfax1: true,

			},

			organization_code : {

                required : true,

				lettersmin : true,

                lettersmax : true,

                lettersonly : true,

                remote   : {

                    url : 'index.php?act=login&op=check_organization_code&column=ok',

                    type: 'get',

                    data: {

                        user_name : function(){

                            return $('#organization_code').val();

                        }

                    }

                }

            },

			company_license : {

                required : true

            },

            <?php if(C('captcha_status_register') == '1') { ?>

            captcha : {

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

        messages : {

            

			company_name : {

                required : '<?php echo $lang['login_register_company_name'];?>'

            },

            mob_phone : {

                required : '<?php echo $lang['login_company_phone'];?>'

            },	

			register_num : {

                required : '<?php echo $lang['login_register_num'];?>',

                remote : '<?php echo $lang['login_register_num1'];?>'

            },
            select_areainfo :{
            	required : "所在地省要选择"
            },

            <?php if(C('captcha_status_register') == '1') { ?>

            captcha : {

                required : '<?php echo $lang['login_usersave_examine_isnull'];?>',

				remote	 : '<?php echo $lang['login_register_code_wrong'];?>'

            },

			<?php } ?>

			operation_term : {

                required : '<?php echo $lang['login_company_operation_term'];?>'

            },

            organization_code : {

            	required : '<?php echo $lang['login_organization_code'];?>',

            	remote : '<?php echo $lang['login_organization_code1'];?>'

            },

			company_fax : {

			    required : '<?php echo $lang['login_company_fax'];?>'

			}



        }

    });


	//地区表

	regionInit("region");

	//时间选择

	$('#operation_term').datepicker({dateFormat: 'yy-mm-dd'});

	$(".selfregistermain input[type='text'],.selfregistermain input[type='password'],.companyregistermain input[type='text'],.companyregistermain input[type='password']").bind("focus",function(){
		$(this).css("border-color","#00a0e9");
		if($(this).hasClass("error")){
			$(this).siblings("label").css(
				{
					"border-color":"#bfbfbf",
					
					"color":"#7d7d7d"
				})
		}
	}).bind("blur",function(){
		if($(this).hasClass("error")){
			$(this).css("border-color","#e60012");
			$(this).siblings("label").css(
				{
					"border-color":"#f29c9f",
				
					"color":"#bfbfbf",
					"height":"33px",
					"line-height":"33px",
					"background":"url('<?php echo TEMPLATES_PATH;?>/images/error_icon.png') 1px 10px no-repeat",
					"padding-left":"18px"
					
					
				})	
		}else{
			$(this).css("border-color","#bfbfbf");
		}
	})	
});



//获取验证码倒计时
var wait=60;
if(document.getElementById("btn")){

	document.getElementById("btn").disabled = false;   
	document.getElementById("btn").onclick=function(){
		if($.trim($("#mobile").val()) == "" || $.trim($("#mobile").val()) == "手机号"){
			alert("请输入手机号码");
			return;
		}
		time(this);sendSMS(this);
	}
}
function time(o) {

        if (wait == 0) {		
            o.removeAttribute("disabled");           
            o.value="获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            },
            1000)
        }
    }

		
		// /*判断登录的浏览器*/
		var isIE  = navigator.userAgent.indexOf('Trident');
		var lowerIE = /MSIE 6\.0|MSIE 7\.0|MSIE 8\.0|MSIE 9\.0/.test(navigator.userAgent);

		/*登录的修改*/
		if(isIE >=0 && !lowerIE){
			$(".table-selfregister :input").not("[type='submit']").addClass("color_ccc");

			$(".table-selfregister :input").not("[type='submit']").on("focus",function(){
				$(this).css("color","#000000");
			}).on("blur",function(){
				$(this).css("color","#cccccc");
			})

		}



</script>

