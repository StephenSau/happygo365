<style>
.fill_in_content li{line-height:54px;width:100%;overflow:visible;}
.dialog_content .submit{margin:0;padding-bottom:20px;width:100%;}
.submit button {display:block;margin:0 auto;width:310px;}
.fill_in_content .fill_in {float:none;padding:0 10px;width:718px;height:auto;}
</style>

<div style="height:auto;">
  <ul class="fill_in_content" >
    <li>
     
      <p class="fill_in">
      <?php echo $lang['cart_tips'];?>
        </p>
    </li>
  </ul>
  <div class="clear"></div>
  <div class="submit">
    <button class="btn inputsub6" onclick="javascript:submitaddress();"><?php echo $lang['cart_konw'];?></button>
  </div>
</div>
<script type="text/javascript">
//]]>
function submitaddress(){

	DialogManager.close('newtipsform');
	$('#goto_pay').submit();
	
}
</script>