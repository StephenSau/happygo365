<?php defined('haipinlegou') or exit('Access Invalid!');?>

<body>
	<form name="upload" action="index.php?act=member&op=uploadimg" method="post" enctype="multipart/form-data">
		<input name="file" type="file" onchange="document.upload.submit();" />
	</form>
</body>
