<div class="curmbs">
	<p>
    <?php echo $lang['cur_location'].$lang['nc_colon'];?>
	<?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
	<?php foreach($output['nav_link_list'] as $nav_link){?>
	<?php if(!empty($nav_link['link'])){?>
    <a href="<?php echo $nav_link['link'];?>"><?php echo $nav_link['title'];?></a>&nbsp;>&nbsp;
	<?php }else{?>
	<span><?php echo $nav_link['title'];?></span>
    <?php }?>
	<?php }?>
	<?php }?>
	</p>
</div>