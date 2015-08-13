<div class="content">
		<div class="w1210">
			<div class="activityall pdt30">
				<div class="activityallright fr">
					<div class="activityalltj font-mic">
						<h3>热门推荐</h3>
						<ul class="ul-activityalltj">
							<?php foreach($output['asort'] as $k=>$v){?>
							<li  <?php if($k == 0){ echo "class='li-activityalltj'";}?> >
								<a class="a-activityalltj" href="index.php?act=activity&op=detailed&det_id=<?php echo $v['activity_id']?>"><?php echo $v['activity_title'];?></a>
								<p><a href=""><img src="<?php echo SiteUrl."/".ATTACH_ACTIVITY."/".$v['activity_banner']?>" width="390px" height="160px"></a></p>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				<div class="activityallleftin font-mic">
					<h2 style="font-size:24px;color:#000000;padding-bottom:20px;"><?php echo $output['det_id']['activity_title'];?></h2> 
					<img width="100%" src="<?php echo SiteUrl."/".ATTACH_ACTIVITY."/".$output['det_id']['activity_banner'];?>">
					<span style="font-size:12px;color:#999999;line-height:25px;">有效期：<?php echo date('Y-m-d',$output['det_id']['activity_start_date']);?> 至 <?php echo date('Y-m-d',$output['det_id']['activity_end_date']);?></span>
					<p style="font-size:14px;color:#555555;line-height:28px;padding:10px 0;"><?php echo $output['det_id']['activity_desc'];?></p>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$('.ul-activityalltj li').mouseover(function(){
	$(this).find('p').css("display","block");
	$(this).siblings().find('p').css("display","none");
	})
	</script>