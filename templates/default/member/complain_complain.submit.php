<script type="text/javascript">
$(document).ready(function(){
    $(":radio").first().attr("checked",true);
    $("#submit_button").click(function(){
       submit_add_form(); 
    });
    
    $(".problem_desc").hide();
    $(".checkitem").click(function(){
        if($(this).attr('checked') == true) {
            $(this).parents('tr').next('.problem_desc').show();
        }
        else {
            $(this).parents('tr').next('.problem_desc').hide();
        }
    });
    $("#add_form").validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
            rules : {
                input_complain_pic1 : {
                    accept : 'jpg|jpeg|gif|png' 
                },
                input_complain_pic2 : {
                    accept : 'jpg|jpeg|gif|png' 
                },
                input_complain_pic3 : {
                    accept : 'jpg|jpeg|gif|png' 
                }
            },
                messages : {
                    input_complain_pic1: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    },
                    input_complain_pic2: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    },
                    input_complain_pic3: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    }
                }
    });

});
function submit_add_form() {
    var items = get_checked_items();
    if(items == '') {
    	showDialog('<?php echo $lang['complain_goods_select'];?>');
    }
    else {
        var complain_content = $("#input_complain_content").val();
        if(complain_content == ''||complain_content.length>100) {
            showDialog("<?php echo $lang['complain_content_error'];?>");
        }
        else {
        	if($("#add_form").valid()){
        		ajaxpost('add_form', '', '', 'onerror');
            }
        }
    }
}
function get_checked_items() {
    var items = '';
    $('.checkitem:checked').each(function(){
        items += this.value + ',';
    });
    return items;
}
</script>
<form action="index.php?act=<?php echo $_GET['act'];?>&op=complain_save" method="post" id="add_form" enctype="multipart/form-data">
<input name="input_order_id" type="hidden" value="<?php echo $output['order_info']['order_id'];?>" />
<div class="buyercomplaintlist">
	<h3 class="sellertlefttitle"><?php echo $lang['complain_detail'];?></h3>
	<div class="buyercomplaintshow">
		<h4 class="h4-buyercomplaint"><?php echo $lang['complain_message'];?></h4>
		<div class="buyercomplaintshowin">
			<p><?php echo $lang['complain_state'].$lang['nc_colon'];?><span class="spanblue"><?php echo $output['complain_info']['complain_state_text'];?></span></p>
			<p><?php echo $lang['complain_type'].$lang['nc_colon'];?><?php echo $output['complain_info']['complain_subject_type_text'];?></p>
			<p><?php echo $lang['complain_accuser'].$lang['nc_colon'];?><?php echo $output['complain_info']['complain_accuser_name'];?></p>
		</div>
		<h4 class="h4-buyercomplaint"><?php echo $lang['complain_subject_select'];?></h4>
		
		 <?php foreach($output['subject_list'] as $subject) {?>
        <p class="p-buyercomplaintshowradio">
          <input name="input_complain_subject" type="radio" value="<?php echo $subject['complain_subject_id'].','.$subject['complain_subject_content']?>" />
          <?php echo $subject['complain_subject_content']?>（<span><?php echo $subject['complain_subject_desc'];?></span>）</p>
        <?php } ?>
	</div>
</div>							
<div class="buyercomplaintlist">
	<h3 class="sellertlefttitle"><?php echo $lang['complain_goods_select'];?></h3>
	<div class="buyercomplaintgoodschoice">
		<table class="tbale-buyercomplaintgoodschoice">
			<thead>
				<tr>
					<th width="5%"> </th>
					<th width="40%"><?php echo $lang['complain_goods_message'];?></th>
					<th width="40%"><?php echo $lang['complain_text_num'];?></th>
					<th width="15%"><?php echo $lang['complain_text_price'];?></th>
				</tr>
			</thead>
			<tbody>
			 <?php $i=1; foreach($output['order_goods_list'] as $order_goods) { ?>
				<tr>
					<td><input class="checkitem" name="input_goods_check[<?php echo $i;?>]" type="checkbox" value="<?php echo $order_goods['rec_id'];?>" /></td>
					<td>
						<a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$order_goods['goods_id']), 'goods');?>" class="a-buyerordername" target="_blank">
						<img onload="javascript:DrawImage(this,60,60);"  src="<?php echo cthumb($order_goods['goods_image'],'tiny',$output['order_info']['store_id']);?>">
						
						<b><?php echo $order_goods['goods_name'];?></b>
						<?php echo $order_goods['spec_info'];?>
						</a>
					</td>
					<td><?php echo $order_goods['goods_num'];?></td>			
					<td><?php echo $order_goods['goods_price'];?></td>			
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="buyercomplaintgoodsmess">
		<h4 class="h4-buyercomplaint"><?php echo $lang['complain_content'].$lang['nc_colon'];?></h4>
		<textarea name="input_complain_content" rows="3" id="input_complain_content" class="textarea6"></textarea>
	</div>
	<div class="buyercomplaintgoodsproof">
		<h4 class="h4-buyercomplaint"><?php echo $lang['complain_evidence_upload'];?><span class="error"><span>(<?php echo $lang['complain_pic_error'];?>)</span></h4>
		<p> <input id="input_complain_pic1" name="input_complain_pic1" type="file" /></p>
		<p><input id="input_complain_pic2" name="input_complain_pic2" type="file" /></p>
		<p><input id="input_complain_pic3" name="input_complain_pic3" type="file" /></p>
	</div>
	<div class="buyercomplaintbtn">
		<p class="p-buyercomplaintbtn">
		 <input id="submit_button" type="button"  class="inputsub6 font-mic" value="<?php echo $lang['complain_text_submit'];?>" >
	</div>
	</form>
</div>
