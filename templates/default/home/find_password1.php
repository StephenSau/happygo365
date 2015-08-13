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
					<li class="li-ompanyregisterstepon"><span>1</span></li>
					<li><span>2</span></li>
					<li><span>3</span></li>
				</ul>
			</div>
			<div class="companyregisterstepin">
			<form action="index.php?act=login&op=find_password1" method="POST" id="find_password_form">
				<?php Security::getToken();?>
				<input type="hidden" name="form_submit" value="ok" />
				<input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />
				<table class="table-companyregister">
					<tbody>					<tr>
						<th><?php echo $lang['login_password_you_email'];?></th>
						<td>
							<input class="inputtxt8 inputemail" type="text" name="email">
							<p></p>
						</td>
						<td class="td-imghit"></td>
					</tr>							
					<tr>
						<th><?php echo $lang['login_register_code'];?></th>
						<td>
							<input class="inputtxt9" type="text" name="captcha"><span>
							<img src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" id="codeimage"></span><a class="a-verify" href="javascript:void(0);" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();">换一张</a>
							<p class="p-error"></p>
						</td>
						<td class="td-imghit"></td>
					</tr>							
					<tr>
						<th> </th>
						<td><input class="inputsub5 fl inputw310 submit" value="下一步" type="submit" name="Submit" id="Submit"></td>
						<td class="td-imghit"></td>
					</tr>
				</tbody></table>
				<input type="hidden" value="<?php echo $output['ref_url']?>" name="ref_url">
				</form>
			</div>
		</div>
	</div>
</div>
<div class="returnlogin font-mic">
	<a href="/index.php?act=login">返回登陆</a>
</div>
			
<script type="text/javascript">
$(function(){
    $('#find_password_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('td');
            error_td.append(error);
        },
        rules : {
            username : {
                required : true
            },
            email : {
                required : true,
                email : true
            },
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
        },
        messages : {
            username : {
                required : '<?php echo $lang['login_usersave_login_usersave_username_isnull'];?>'
            },
            email  : {
                required : '<?php echo $lang['login_password_input_email'];?>',
                email : '<?php echo $lang['login_password_wrong_email'];?>'
            },
            captcha : {
                required : '<?php echo $lang['login_usersave_code_isnull']	;?>',
                remote   : '<?php echo $lang['login_usersave_wrong_code'];?>'
            }
        }
    });
});
</script> 
