<?php defined('haipinlegou') or exit('Access Invalid!');?>

	<div class="content">
		<div class="w1210">
			<div class="curmbs">
				<p><?php include template('home/cur_local');?></p>
			</div>
			<div class="helpall">
				<div class="helpleft fl">
					<div class="helpnav mb20">
						<h3 class="helpnavtitle"><?php echo $lang['article_article_new_center'];?></h3>
					<!-- 	now -->
						<ul class="hg-helpnav">
                            <?php if(!empty($output['catelist']) && is_array($output['catelist'])) { ?>
                            <?php foreach($output['catelist'] as $key=>$val) { ?>
							<li class="hg-helpnav-ul">
								<a  class="hg-helpnav-bgb" ><?php echo $val[0]['ac_name'] ?></a>
								<ul class="hg-helpnav-dis">
                                    <?php if(!empty($val) && is_array($val)) { ?>
                                    <?php foreach ($val as $k1=>$v1){ ?>
									<li><a  href="<?php echo ncUrl(array('act'=>'article','article_id'=>$v1['article_id']));?>"><?php echo $v1['article_title'] ?></a></li>
                                    <?php } } ?>
								</ul>

							</li>
                            <?php } } ?>
						</ul>

					</div>
					<div class="helpnews">
						<h3 class="helpnavtitle"><?php echo $lang['article_article_new_article'];?></h3>
						<ul class="ul-helpnews">
							<?php if(is_array($output['new_article_list']) and !empty($output['new_article_list'])){?>
								<?php foreach ($output['new_article_list'] as $k=>$v){?>
									<li><a <?php if($v['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($v['article_url']!='')echo $v['article_url'];else echo ncUrl(array('act'=>'article','article_id'=>$v['article_id']), 'article');?>"><?php echo $v['article_title']?></a></li>	
								<?php }?>
								<?php }else{?>
									<li><?php echo $lang['article_article_no_new_article'];?></li>
							<?php }?>
						</ul>
					</div>
				</div>
				<div class="helpright">
					<h3 class="helpmaintitle"><?php echo $output['article']['article_title'];?></h3>
					<div class="helpmain">
						<p style="font-size:12px;color:#ff6c00;line-height:42px;"><?php echo $output['article']['article_content'];?></p>
					</div>
					<div class="helpmainnet">			
						<p class="p-prehelpnews">												<!--
							<a <?php if($output['pre_article']['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($output['pre_article']['article_url']!='')echo $output['pre_article']['article_url'];else echo ncUrl(array('act'=>'article','article_id'=>$output['pre_article']['article_id']), 'article');?>"><?php echo $lang['article_show_previous'];?>:<?php if(!empty($output['pre_article']) and is_array($output['pre_article'])){?>
							<?php echo $output['pre_article']['article_title'];?>
							<?php }else{?>
							<?php echo $lang['article_article_not_found'];?>
							<?php }?></a>						-->								<a <?php if($output['pre_article']['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($output['pre_article']['article_url']!='')echo $output['pre_article']['article_url'];else echo ncUrl(array('act'=>'article','article_id'=>$output['pre_article']['article_id']), 'article');?>"><?php echo $lang['article_show_previous'];?>:<?php if(!empty($output['pre_article']) and is_array($output['pre_article'])){?>							<?php echo $output['pre_article']['article_title'];?>							<?php }else{?>							<?php echo $lang['article_article_not_found'];?>							<?php }?></a>
						</p>
						<p class="p-nethelpnews">							
				            <a <?php if($output['next_article']['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($output['next_article']['article_url']!='')echo $output['next_article']['article_url'];else echo ncUrl(array('act'=>'article','article_id'=>$output['next_article']['article_id']), 'article');?>">
							<?php echo $lang['article_show_next'];?>:
							<?php if(!empty($output['next_article']) and is_array($output['next_article'])){?>
							<?php echo $output['next_article']['article_title'];?>
							<?php }else{?>
							<?php echo $lang['article_article_not_found'];?>
							<?php }?></a>
						</p>
					</div>
				</div>
			</div>
			
		</div>
	</div>