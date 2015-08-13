<?php defined('haipinlegou') or exit('Access Invalid!');?>





<div class="buyercenterright font-mic">

  <div class="buyerdoneintop mb30">

  <div class="buyerdongtitle pr">

     <div class="tabmenu"><?php include template('member/member_submenu3');?></div>

  </div>

  <div class="buychange">

    <div class="buyselfmess" style="margin-left:90px;margin-top:30px;">

      <form method="post" id="email_form" action="index.php?act=home&op=email">

      <input type="hidden" name="form_submit" value="ok" />

      

      <dl>

        <dt class="required" style="float:left;padding-top:10px;font-size:14px;color:#999;text-indent:1em; width:70px;">&nbsp;&nbsp;密&nbsp;码：</dt>

        <dd>

          <input type="password" class="inputtxt8"  maxlength="40" name="orig_password" id="orig_password" />

          <label for="orig_password" generated="true" class="error" style="color:red;"></label>

        </dd>

      </dl>



      <dl style="margin-top:20px;">

        <dt class="required" style="float:left;padding-top:10px;font-size:14px;color:#999"><?php echo $lang['home_member_email'].$lang['nc_colon'];?></dt>

        <dd>

          <input type="text" class="inputtxt8"  maxlength="40" name="email" id="email" />

          <label for="email" generated="true" class="error" style="color:red;"></label>

        </dd>

      </dl>



      <dl style="margin:30px 0 0 70px;">

        <dt></dt>

        <dd>

          <input type="submit" class="inputsub6 font-mic" value="<?php echo $lang['home_member_submit'];?>" style="width:270px;" />

        </dd>

      </dl>



    </form>

    </div>                

  </div>

  </div>

</div>

<script type="text/javascript">

$(function(){

    $('#email_form').validate({

        submitHandler:function(form){

            ajaxpost('email_form', '', '', 'onerror') 

        },

        rules : {

            orig_password : {

                required : true

            },

           email : {

                required   : true,

                email      : true,

                remote   : {

                    url : 'index.php?act=login&op=check_email',

                    type: 'get',

                    data:{

                        email : function(){

                            return $('#email').val();

                        }

                    }

                }

            }

        },

        messages : {

            orig_password : {

                required : '<?php echo $lang['home_member_password_null'];?>'

            },

            email : {

                required : '<?php echo $lang['home_member_email_null'];?>',

                email    : '<?php echo $lang['home_member_email_format_wrong'];?>',

				remote	 : '<?php echo $lang['home_member_email_exists'];?>'

            }

        }

    });

});

</script>

