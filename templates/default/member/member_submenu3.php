
<ul class="ul-buyerdongtitle">

	<?php if(is_array($output['member_menu']) and !empty($output['member_menu'])) {

// print_r($output['menu_key']);exit;

		foreach ($output['member_menu'] as $key => $val) {

			if($val['menu_key'] == $output['menu_key']) { 

               if($val['menu_key'] != 'more'){

				echo '<li style="width:135px;" ><a class="a-buyerdongtitle" '.(isset($val['target'])?"target=".$val['target']:"").' href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';

			   }

			}else{

				if($val['menu_key'] != 'more'){

				echo '<li style="width:135px;"><a '.(isset($val['target'])?"target=".$val['target']:"").' href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';

			    }

			}

		}

	}

	?>

</ul>

