<?php defined('haipinlegou') or exit('Access Invalid!');?>


<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />


<div class="buyercenterright font-mic">

  <div class="buyerdoneintop mb30">

    <div class="buyerdongtitle pr">

      <?php include template('member/member_submenu3');?>

    </div>
	<?php if($output['member_info']['examine'] == 0){?>
	  <div class="buychange">
		<p style="text-align:center;font-size:30px;line-height:200px;">个人资料正在审核中....</p>
	  </div>     
	<?php }else{?>
	 <div class="buychange">
	 <?php if($output['member_info']['examine'] == 2){?>
		<p style="text-align:center;font-size:25px;color:red;padding-bottom:20px;">海品乐购通知：您的个人资料已被管理员否决，请重新填写正确信息</p>
	 <?php }?>
      <form method="post" id="profile_form" action="index.php?act=home&op=member">

       <input type="hidden" name="form_submit" value="ok" />

      <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />

        <div class="buyselfmess">

          <table class="table-passwordbuychange">

            <tr>

              <th><?php echo $lang['home_member_username'].$lang['nc_colon'];?></th>

              <td><?php echo $output['member_info']['member_name']; ?></td>

            </tr>

            <tr>

              <th><?php echo $lang['home_member_email'].$lang['nc_colon'];?></th>

              <td><?php echo $output['member_info']['member_email']; ?></td>

            </tr>

            <tr>

              <th class="jiathis_streak">推广链接：</th>
              <td>
                <div class="jiathis_streak" id="copy-link"><?php echo $output['popularize_url']; ?></div>
                <input  type="button" id="copy-btn" value="复制链接" style="margin-top:5px; "id="button-copy">
                &nbsp;<span id="copy-tip" style="color:#FF6C00;"></span>
              </td>
            </tr>

            <!--<tr>

              <th>手机：</th>

              <td>138***1300<a class="a-buyselfmess" href="">修改</a></td>

            </tr>-->

            <tr >

              <th  style="vertical-align:top; position: relative; top:13px;"><span style="color:red;">*</span><?php echo $lang['home_member_truename'].$lang['nc_colon'];?></th>

              <td>
                <p class="of_h"><input class="inputtxt10" type="text" id="member_truename" name="member_truename"  <?php if(!empty($output['member_info']['member_truename'])){ echo 'readonly'; }else ?> value="<?php echo $output['member_info']['member_truename']; ?>" ><span style="color:red;"></span></p>
                <p style="padding-top:5px;color:red;" class="font12">（请填写真实姓名，一经确认不能更改）</p>
              </td>

            </tr>

            <tr>

              <th><?php echo $lang['home_member_sex'].$lang['nc_colon'];?></th>

              <td>

              <label lass="label1">

                <input type="radio" name="member_sex" value="1" <?php if($output['member_info']['member_sex']==1) { ?>checked="checked"<?php } ?> />

                <?php echo $lang['home_member_male'];?>

              </label>

              <label lass="label1">

                <input type="radio" name="member_sex" value="2" <?php if($output['member_info']['member_sex']==2) { ?>checked="checked"<?php } ?> />

                <?php echo $lang['home_member_female'];?>

              </label>

              <label lass="label1">

                <input type="radio" name="member_sex" value="3" <?php if($output['member_info']['member_sex']==3 or ($output['member_info']['member_sex']!=2 and $output['member_info']['member_sex']!=1)) { ?>checked="checked"<?php } ?> />

                <?php echo $lang['home_member_secret'];?>

              </label>

              </td>

            </tr>

            <tr>

              <th><?php echo $lang['home_member_birthday'].$lang['nc_colon'];?></th>

              <td><input type="text" class="text" name="birthday" maxlength="10" id="birthday" value="<?php echo $output['member_info']['member_birthday']; ?>" /></td>

            </tr>                 

            <tr>

              <th><?php echo $lang['home_member_areainfo'].$lang['nc_colon'];?></th>

              <td>

                <div id="region">

                <input type="hidden" value="<?php echo $output['member_info']['member_provinceid'];?>" name="province_id" id="province_id">

                <input type="hidden" value="<?php echo $output['member_info']['member_cityid'];?>" name="city_id" id="city_id">

                

                

                <input type="hidden" value="<?php echo $output['member_info']['member_areaid'];?>" name="area_id" id="area_id" class="area_ids" />

                <input type="hidden" value="<?php echo $output['member_info']['member_areainfo'];?>" name="area_info" id="area_info" class="area_names" />

                

                <?php if(!empty($output['member_info']['member_areaid'])){?>

                <span><?php echo $output['member_info']['member_areainfo'];?></span>

                <input type="button" value="<?php echo $lang['nc_edit'];?>" class="edit_region" />

                <select class="select3" style="display:none;"> </select>

                <?php }else{?>

                <select></select>

                <?php }?>

                </div>

              </td>

            </tr>

            <tr>

              <th style=" position: relative; top:-15px;  vertical-align:middle;">QQ：</th>

              <td><input class="inputtxt10" type="text" maxlength="30" name="member_qq" value="<?php echo $output['member_info']['member_qq']; ?>"></td>

            </tr>                 

			<tr>

              <th style=" vertical-align:middle;  position: relative;bottom:16px;"><?php echo $lang['home_member_wangwang'].$lang['nc_colon'];?></th>

              <td><input class="inputtxt10" type="text" name="member_ww" maxlength="50" id="member_ww" value="<?php echo $output['member_info']['member_ww'];?>"></td>

            </tr>						

			<tr>

              <th style="  position:relative; bottom:16px; vertical-align:middle;"><span style="color:red;">*</span>&nbsp;&nbsp;<?php echo $lang['home_member_phone'].$lang['nc_colon'];?></th>

              <td><input class="inputtxt10" type="text" name="member_mob_phone" id="member_mob_phone" value="<?php echo $output['member_info']['member_mob_phone'];?>"><span style="color:red;"></span></td>

            </tr>			

			<tr>

              <th style="  vertical-align:middle;  position:relative; bottom:16px;"><span style="color:red;">*</span>&nbsp;&nbsp;<?php echo $lang['home_member_id_card'].$lang['nc_colon'];?></th>

              <td><input class="inputtxt10" type="text" name="member_id_card"  id="member_id_card" value="<?php echo $output['member_info']['member_id_card'];?>"><span style="color:red;"></span></td>

            </tr>			

			<!--身份证正面-->

<!--			<tr>-->
<!---->
<!--              <th><span style="color:red;">*</span>&nbsp;&nbsp;--><?php //echo $lang['home_member_idcard'].$lang['nc_colon'];?><!--</th>-->
<!---->
<!--              <td>-->
<!---->
<!--				<a class="uploadsubmit" href="javascript:void(0);" onclick="window.upload.document.upload.file.click()">点击上传</a>-->
<!---->
<!--				<iframe name="upload" src="index.php?act=member&op=uploadimg" width="1px" height="1px" ></iframe>-->
<!---->
<!--				--><?php //if(!empty($output['member_info']['member_idcard'])){?>
<!---->
<!--				<img id="upload1" src="--><?php //echo $output['member_info']['member_idcard'];?><!--" style="width:300px;height:200px;margin-top:20px;" />-->
<!---->
<!--				--><?php //}else{?>
<!---->
<!--				<img id="upload1" src="" style="width:300px;height:200px;margin-top:20px;display:none;" />-->
<!---->
<!--				--><?php //}?>
<!---->
<!--				<input class="inputfile1" id="idcard" type="hidden" name="member_idcard" value="--><?php //echo $output['member_info']['member_idcard'];?><!--">-->
<!---->
<!--				<span style="color:red;"></span>-->
<!---->
<!--			  </td>-->
<!---->
<!--            </tr>-->

			<!--身份证反面-->			

<!--			<tr>-->
<!---->
<!--              <th><span style="color:red;">*</span>&nbsp;&nbsp;--><?php //echo $lang['home_member_idcard2'].$lang['nc_colon'];?><!--</th>-->
<!---->
<!--              <td>-->
<!---->
<!--				<a class="uploadsubmit" href="javascript:void(0);" onclick="window.uploadimg.document.upload.file.click()">点击上传</a>-->
<!---->
<!--				<iframe name="uploadimg" src="index.php?act=member&op=uploadimg2" width="1px" height="1px" ></iframe>-->
<!---->
<!--				--><?php //if(!empty($output['member_info']['member_idcard2'])){?>
<!---->
<!--				<img id="upload2" src="--><?php //echo $output['member_info']['member_idcard2'];?><!--" style="width:300px;height:200px;" />-->
<!---->
<!--				--><?php //}else{?>
<!---->
<!--				<img id="upload2" src="" style="width:300px;height:200px;display:none;" />-->
<!---->
<!--				--><?php //}?>
<!---->
<!--				<input id="idcard2" type="hidden" name="member_idcard2" value="--><?php //echo $output['member_info']['member_idcard2'];?><!--">-->
<!---->
<!--				<span style="color:red;"></span>-->
<!---->
<!--			  </td>-->
<!---->
<!--            </tr>-->

            <tr>

              <th> </th>

              <td>
				<input id="commit" style="width:270px;" class="inputsub6 font-mic" type="submit" value="<?php echo $lang['home_member_save_modify'];?>">
			  </td>

            </tr>

          </table>

        </div>                

       </form>

      </div>
	<?php }?>
  </div>

</div> 

<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"16"},
/*"selectShare":{"bdContainerClass":null,*/"bdSelectMiniList":["weixin","qzone","tsina","tqq","renren"]};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_PATH;?>/js/jquery.zclip.min.js"></script>

<script type="text/javascript">

$(function(){
      /*复制功能*/
      $('#copy-btn').click(function() { 
          if (window.clipboardData ) { 
            window.clipboardData.setData("Text", $("#copy-link").text());  
            alert('复制成功！'); 
          }else{
            alert("此功能不支持该浏览器，请手工复制文本框中内容");
          }
      });  
  

	regionInit("region");

	$('#birthday').datepicker({dateFormat: 'yy-mm-dd'});

	$('#profile_form').validate({

    	submitHandler:function(form){

    		$('#province_id').val($('select[class="valid"]').eq(0).val());
      if($('#city_id').val() == ""){
  			$('#city_id').val($('select[class="valid"]').eq(1).val());
      }

			ajaxpost('profile_form', '', '', 'onerror')

		},

        errorPlacement: function(error, element){

            var error_td = element.parent('p').next('p');

            error_td.append(error);

        },

        rules : {

            member_truename : {

				minlength : 2,

                maxlength : 20

            },

            member_qq : {

				digits  : true,

                minlength : 5,

                maxlength : 12

            },

			/*

			member_id_card : {

                required : true,

                email    : true,

                remote   : {

                    url : 'index.php?act=login&op=check_id_card',

                    type: 'get',

                    data:{

                        email : function(){

                            return $('#member_id_card').val();

                        }

                    }

                }

            }, 			

			member_mob_phone : {

                required : true,

                email    : true,

                remote   : {

                    url : 'index.php?act=login&op=check_mob_phone',

                    type: 'get',

                    data:{

                        email : function(){

                            return $('#member_mob_phone').val();

                        }

                    }

                }

            }, */

        },

        messages : {

            member_truename : {

				minlength : '<?php echo $lang['home_member_username_range'];?>',

                maxlength : '<?php echo $lang['home_member_username_range'];?>'

            },

            member_qq  : {

				digits    : '<?php echo $lang['home_member_input_qq'];?>',

                minlength : '<?php echo $lang['home_member_input_qq'];?>',

                maxlength : '<?php echo $lang['home_member_input_qq'];?>'

            },     	

        }

    });

	

	$("#member_id_card").blur(function()

	{

		if( this.value=="" || ( !/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/.test(this.value) ) )

		{

			$(this).next().addClass('error').text('请输入正确的身份证号码！');

			

		}else if(this.value!="")

		{

			$.get('index.php?act=home&op=check_id_card&q='+Math.random(),{'member_id_card':this.value},function(d)

			{

				

				if(d ==1)

				{

					$("#member_id_card").next().addClass('error').text('身份证号码已存在');

				}else{

				    $("#member_id_card").next().removeClass('error').text('');

				}

				

			},'json');

			  

		}else

		{

			$("#member_id_card").next().removeClass('error').text('');

		}

	});

	

	$("#member_mob_phone").blur(function()

	{

		if( this.value=="")

		{

			$(this).next().addClass('error').text('请输入手机号码！');

			

		}else if(this.value!="" && (/^1[|3|4|5|8][0-9]\d{0,8}$/.test(this.value) ))

		{

			$.get('index.php?act=home&op=check_phone&q='+Math.random(),{'member_mob_phone':this.value},function(d)

			{

				

				if(d == 1)

				{

					$("#member_mob_phone").next().addClass('error').text('手机号码已存在');

				}else{

				   $("#member_mob_phone").next().removeClass('error').text('');

				}

				

			},'json');			

			

		}else

		{

			$("#member_mob_phone").next().removeClass('error').text('');

		}

	});	

	$("#member_truename").blur(function()

	{

		if( this.value=="" || ( this.value !=""  && ! /^([\u4e00-\u9fa5]+|([a-zA-Z]+\s?)+)$/.test(this.value) ) )

		{

			$("#member_truename").next().addClass('error').text('请输入真实名字！');

			

		}else

		{

			$("#member_truename").next().removeClass('error').text('');

		}		

	})



	$("#commit").click(function()

	{

		var member_id_card = $("#member_id_card").val();

		var member_mob_phone = $("#member_mob_phone").val();

		var member_truename = $("#member_truename").val();

		var idcard = $("#idcard").val();

		var idcard2 = $("#idcard2").val();

		if(member_id_card == "" || member_mob_phone== "" || member_truename=="" || idcard=="" || idcard2=="")

		{

			alert('依海关要求，请输入完整的关键数据');

			return false;

		}

		var numError = $('form .error').length;

		if(numError)

		{

			return false;

		}

	})

});

//上传图片回调函数

function stopSend(str){

 $("#upload1").show();

 $("#upload1").attr('src',str);

 $("#idcard").val(str);

}

function stop(str){

 $("#upload2").show();

 $("#upload2").attr('src',str);

 $("#idcard2").val(str);

}

</script> 

<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" ></script>