<?php defined('haipinlegou') or exit('Access Invalid!');?>
<?php if(!empty($output['bundling_array']) && is_array($output['bundling_array'])){$i=0;?>

<script>
$(function(){
	$('.nc-promotion').show();
	$('#nc-bundling').show();
	$('.nc-bundling-container').F_slider({len:<?php echo $i;?>});
});
</script>
<?php }?>