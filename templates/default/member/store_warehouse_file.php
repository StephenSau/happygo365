<body>

	<form name="upload" action="" method="post" enctype="multipart/form-data">

		<input name="file" type="file" onChange="document.upload.submit();" />
		<input  type="hidden" name="payment_code" value="<?php echo $output['payment_code']?>" />
	</form>

</body>