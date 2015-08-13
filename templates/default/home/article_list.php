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
						<ul class="ul-helpnav">
						<?php foreach ($output['sub_class_list'] as $k=>$v){?>
							<li><a class="a-helpnav1" href="<?php echo ncUrl(array('act'=>'article','ac_id'=>$v['ac_id']));?>"><?php echo $v['ac_name']?></a></li>
						<?php }?>
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
					<ul class="ul-helplist">
					<?php if(!empty($output['article_lists']) and is_array($output['article_lists'])){?>
					<?php foreach ($output['article_lists'] as $article) {?>
						<li><span><?php echo date('Y-m-d H:i',$article['article_time']);?></span><a <?php if($article['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($article['article_url']!='')echo $article['article_url'];else echo 'index.php?act=article&article_id='.$article['article_id'];?>"><?php echo $article['article_title'];?></a></li>
					<?php }?>
					<?php }else{?>
						<li><span><?php echo date('Y-m-d H:i',$article['article_time']);?></span><a href=""><?php echo $lang['article_article_not_found'];?></a></li>
					<?php }?>
					</ul>
					<div class="page">
						<!--<p class="font-mic">
							<a href="">上一页</a>
							<a class="a-page" href="">1</a>
							<a href="">2</a>
							<a href="">3</a>
							<a href="">4</a>
							<a href="">5</a>
							<a href="">...</a>
							<a href="">下一页</a>
						</p>
						--><?php echo $output['show_page'];?> 
					</div>
				</div>
			</div>
			
		</div>
		
	</div>