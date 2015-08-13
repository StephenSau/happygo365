<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu" style="font-size:24px;margin-bottom:20px;border:1px solid #dedede;height:50px">
    <ul class="tab">
      <li class="active"><a href="javascript:void(0)" style="display:block;height:50px;line-height:50px;padding-left:20px;background-color:#ff6c00;width:120px;color:#fff"><?php echo $lang['inform_page_title']; ?></a></li>
    </ul>
  </div>
  <div class="ncu-form-style" style="border:1px solid #dedede">
    <div id="warning"></div>
    <form id="add_form" enctype="multipart/form-data" method="post" action="index.php?act=member_inform&op=inform_save">
      <input name="inform_goods_id" type="hidden" value=<?php echo $output['goods_info']['goods_id']; ?> />
      <dl style="margin-left:30px;margin-bottom:10px;font-size:16px;margin-top:10px;">
        <dt style="float:left"><?php echo $lang['inform_goods_name'].$lang['nc_colon'];?></dt>
        <dd> <a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$output['goods_info']['goods_id']), 'goods');?>" target="_blank"> <?php echo $output['goods_info']['goods_name']; ?> </a> </dd>
      </dl>      </br>
      <dl style="margin-left:30px;margin-bottom:10px;font-size:16px">
        <dt style="float:left"><?php echo $lang['inform_type'].$lang['nc_colon'];?></dt>
        <dd>
          <ul>
            <?php foreach($output['type_list'] as $inform_type) {?>
            <li>
              <p>
                <input type='radio' name="inform_subject_type"  
                value ="<?php echo $inform_type['inform_type_id'].','.$inform_type['inform_type_name'];?>">
                <?php echo $inform_type['inform_type_name'];?>
                </input>
              </p>
              <p class="hint" style="color:#bbb;font-size:14px;margin-top:10px;margin-left:80px"><?php echo $inform_type['inform_type_desc'];?></p>
            </li>
            <?php } ?>
          </ul>
        </dd>
      </dl>      </br>
      <dl style="margin-left:30px;margin-bottom:10px;font-size:16px">
        <dt style="float:left"><?php echo $lang['inform_subject'].$lang['nc_colon'];?></dt>
        <dd>
          <select id="inform_subject" name="inform_subject">
          </select>
        </dd>
      </dl>      </br>
      <dl style="margin-left:30px;margin-bottom:10px;font-size:16px">
        <dt style="float:left"><?php echo $lang['inform_content'].$lang['nc_colon'];?></dt>
        <dd>
          <textarea id="inform_content" name="inform_content" ></textarea>
        </dd>
      </dl>      </br>
      <dl class="noborder" style="margin-left:30px;margin-bottom:10px;font-size:16px">
        <dt style="text-indent: 2em;"><?php echo $lang['inform_pic'].$lang['nc_colon'];?></dt>
        <dd style="float:left;margin-left:75px;">
          <p class="mb5" style="clear:both">
            <input id="inform_pic1" name="inform_pic1" type="file"  />
          </p>
          <p class="mb5" style="clear:both">
            <input id="inform_pic2" name="inform_pic2" type="file"  />
          </p>
          <p class="mb5" style="clear:both">
            <input id="inform_pic3" name="inform_pic3" type="file"  />
          </p>
          <p class="hint" style="clear:both;color:#bbb;font-size:14px;"><?php echo $lang['inform_pic_error'];?></p>
        </dd>
      </dl>      </br>
      <dl class="bottom" style="margin-top:120px;margin-left:105px; margin-bottom:10px;">
        <dt>&nbsp;</dt>
        <dd>
          <input id="btn_inform_submit" type="submit" class="submit" value="<?php echo $lang['nc_ok'];?>" style="width:70px;height:30px;font-size:14px;font-weight:bold" />
        </dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.selectboxes.pack.js" charset="utf-8" ></script> 
<script type="text/javascript">
$(document).ready(function(){
    $("#btn_inform_submit").attr('disabled',true);
    $(":radio").first().attr("checked",true);
    bindInformSubject($(":radio").first().val());
    $(":radio").click(function(){
        bindInformSubject($(this).val());
    });
	$("#add_form").validate({
		errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror') 
    	},
        rules : {
        	inform_content : {
                required : true,
                maxlength : 100
            },
        	inform_subject: {
                required : true
            },
            inform_pic1 : {
                accept : 'jpg|jpeg|gif|png' 
            },
            inform_pic2 : {
                accept : 'jpg|jpeg|gif|png' 
            },
            inform_pic3 : {
                accept : 'jpg|jpeg|gif|png' 
            }
        },
        messages : {
	    	inform_content : {
                required : '<?php echo $lang['inform_content_null'];?>',
                maxlength : '<?php echo $lang['inform_content_null'];?>'
            },
        	inform_subject: {
                required : '<?php echo $lang['inform_subject_select'];?>'
            },
            inform_pic1: {
                accept : '<?php echo $lang['inform_pic_error'];?>'
            },
            inform_pic2: {
                accept : '<?php echo $lang['inform_pic_error'];?>'
            },
            inform_pic3: {
                accept : '<?php echo $lang['inform_pic_error'];?>'
            }
        }
	});
    
});
function bindInformSubject(key) {
    type_id = key.split(",")[0];
    $("#inform_subject").empty();
    $.ajax({
        type:'POST',
        url:'index.php?act=member_inform&op=get_subject_by_typeid',
        cache:false,
        data:'typeid='+type_id,
        dataType:'json',
        success:function(type_list){
            $("#btn_inform_submit").attr('disabled',false);
            if(type_list.length >= 1) {
                $("#inform_subject").addOption('','<?php echo $lang['nc_please_choose'];?>');
                for(var i = 0; i < type_list.length; i++)
                {
                    $("#inform_subject").addOption(type_list[i].inform_subject_id+","+type_list[i].inform_subject_content,type_list[i].inform_subject_content);
                }
                $("#inform_subject").selectOptions('');
            }
            else {
                $("#btn_inform_submit").attr('disabled',true);
                alert("<?php echo $lang['inform_subject_null'];?>");
            }
            
        }
	});
}
</script> 
