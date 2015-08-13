<style>
.error {
    color:red;
}
</style>

<div style="height:auto;">
  <input type="hidden" name="form_submit" value="ok"/>
  <ul class="fill_in_content">
    <br/><br/>
    <li>
      <p class="title"><span>*</span><?php echo $lang['cart_new_pnum'];?></p>
      <p class="fill_in">
        <input type="text" name="pnum" maxlength="20" id="pnum" class="text1 formvalidatedata" value=""/>
        <span class="field_message explain"> <span class="field_notice"></span>
        <label class="error" generated="true"></label>
        </span> </p>
    </li>
  </ul>
  <div class="clear"></div>
  <div class="submit" style="margin-left:120px">
    <button class="btn inputsub6" onclick="javascript:submitaddress();"><?php echo $lang['cart_step1_addnewaddress_submit'];?></button>
  </div>
</div>
<script type="text/javascript">
//]]>
function submitaddress(){
var pnum=$("#pnum").val();
if(!pnum){
	alert("<?php echo $lang['cart_step1_new_pnum'];?>");return false;
}
var datastr = {'pnum':pnum,'form_submit':'ok'}
$.getJSON('index.php?act=cart&op=newpnum',datastr, function(data){
		  if (data.done){
		  		DialogManager.close('newpnumform');
					$('#order_form').submit();
		  }else{
		  	 alert(data.msg);
		  }
	});
}
</script>