<?php defined('haipinlegou') or exit('Access Invalid!');?>

<body>
	<form name="upload" action="index.php?act=member&op=uploadimg2" method="post" enctype="multipart/form-data">
		<input name="file" type="file" onchange="document.upload.submit();" />
	</form>
</body>
