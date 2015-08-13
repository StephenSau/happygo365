<style type="text/css">
table.gridtable 
{
	width:500px;
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
}
</style>
<div style="width:500px;height:auto;margin:50px auto;display:block;">
	<span class="left"><?php echo $lang['index_index_check'];?></span>
	<div style="clear:both"></div><br/><br/>
	<form action="" method="post">
		<table class="gridtable">
			<tr>
				<td>身份证号码：</td>
				<td>
					<input id="id_card" class="check" type="text" name="id_card" value="" /><span style="color:red;"></span>
				</td>
			</tr>			
			<tr>
				<td>身份证正面：</td>
				<td>
					  <a href="javascript:void(0);" onclick="window.upload.document.upload.file.click()">点击上传</a>
					  <iframe name="upload" src="index.php?act=index&op=uploadimg" width="1px" height="1px" ></iframe>
					  <img id="upload1" src="" style="width:300px;height:200px;display:none;" />
					  <input id="idcard" type="hidden" name="idcard" value="">
				</td>
			</tr>			
			<tr>
				<td>身份证反面：</td>
				<td>
					  <a href="javascript:void(0);" onclick="window.upload2.document.upload.file.click()">点击上传</a>
					  <iframe name="upload2" src="index.php?act=index&op=uploadimg2" width="1px" height="1px" ></iframe>
					  <img id="upload2" src="" style="width:300px;height:200px;display:none;" />
					  <input id="idcard2" type="hidden" name="idcard2" value="">
				</td>
			</tr>			
			<tr>
				<td colspan="2"><input id="submit" type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>
</div>
<script>
function show(n)
{
	$(".status").fadeIn(3000);
}
$("#close").click(function()
{
	$(".status").hide();
})

function stopSend(str){
 $("#upload1").css({"display":"block"});
 $("#upload1").attr('src',str);
 $("#idcard").val(str);
}

function stop(str){
 $("#upload2").css({"display":"block"});
 $("#upload2").attr('src',str);
 $("#idcard2").val(str);
}

function error(str)
{
	alert(str);
}
$("input.check").blur(function()
{
	if($(this).val() == "")
	{
		$(this).next('span').addClass('error').text('内容不能为空！');
	}else
	{
		$(this).next('span').removeClass('error').text('');
	}
});

$("#submit").click(function()
{
	var error = $("form .error").length;
	if(error > 0)
	{
		return false;
	}
	
	var id_card = $("#id_card").val();
	var idcard = $("#idcard").val();
	var idcard2 = $("#idcard2").val();
	if(id_card == "" || idcard=="" || idcard2=="")
	{
		alert('请填入完整信息！');
		return false;
	}else
	{
		$(".status").hide();
	}
})
</script>
