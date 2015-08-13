<?php



defined('haipinlegou') or exit('Access Invalid!');

class member_voucherControl extends BaseMemberControl{

	private $voucherstate_arr;

	

	public function __construct() {

		parent::__construct();

		Language::read('member_layout,member_voucher');

		if (intval(C('voucher_allow')) !== 1){

			showMessage(Language::get('member_voucher_unavailable'),'index.php?act=member_snsindex','html','error');

		}

		$this->voucherstate_arr = array('unused'=>array(1,Language::get('voucher_voucher_state_unused')),'used'=>array(2,Language::get('voucher_voucher_state_used')),'expire'=>array(3,Language::get('voucher_voucher_state_expire')));

		Tpl::output('voucherstate_arr',$this->voucherstate_arr);

	}

	

	public function indexOp() {

        $this->voucher_listOp() ;

    }

    //S脚部内容显示

	private function _article() {



		if (file_exists(BasePath.'/cache/index/article.php')){

			include(BasePath.'/cache/index/article.php');

			Tpl::output('show_article',$show_article);

			Tpl::output('article_list',$article_list);

			return ;		

		}

		$model_article_class	= Model('article_class');

		$model_article	= Model('article');

		$show_article = array();

		$article_list	= array();

		$notice_class	= array('notice','store','about');

		$code_array	= array('member','store','payment','sold','service','about');

		$notice_limit	= 5;

		$faq_limit	= 5;



		$class_condition	= array();

		$class_condition['home_index'] = 'home_index';

		$class_condition['order'] = 'ac_sort asc';

		$article_class	= $model_article_class->getClassList($class_condition);

		$class_list	= array();

		if(!empty($article_class) && is_array($article_class)){

			foreach ($article_class as $key => $val){

				$ac_code = $val['ac_code'];

				$ac_id = $val['ac_id'];

				$val['list']	= array();

				$class_list[$ac_id]	= $val;

			}

		}

		

		$condition	= array();

		$condition['article_show'] = '1';

		$condition['home_index'] = 'home_index';

		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';

		$condition['order'] = 'article_sort desc,article_time desc';

		$condition['limit'] = '300';

		$article_array	= $model_article->getJoinList($condition);

		if(!empty($article_array) && is_array($article_array)){

			foreach ($article_array as $key => $val){

				$ac_id = $val['ac_id'];

				$ac_parent_id = $val['ac_parent_id'];

				if($ac_parent_id == 0) {

					$class_list[$ac_id]['list'][] = $val;

				} else {

					$class_list[$ac_parent_id]['list'][] = $val;

				}

			}

		}

		if(!empty($class_list) && is_array($class_list)){

			foreach ($class_list as $key => $val){

				$ac_code = $val['ac_code'];

				if(in_array($ac_code,$notice_class)) {

					$list = $val['list'];

					array_splice($list, $notice_limit);

					$val['list'] = $list;

					$show_article[$ac_code] = $val;

				}

				if (in_array($ac_code,$code_array)){

					$list = $val['list'];

					$val['class']['ac_name']	= $val['ac_name'];

					array_splice($list, $faq_limit);

					$val['list'] = $list;

					$article_list[] = $val;

				}

			}

		}

		$string = "<?php\n\$show_article=".var_export($show_article,true).";\n";

		$string .= "\$article_list=".var_export($article_list,true).";\n?>";

		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));



		Tpl::output('show_article',$show_article);

		Tpl::output('article_list',$article_list);

	}

    //E脚部内容显示

	

    public function voucher_listOp(){

        $this->check_voucher_expire();

		$model = Model();

		$where = array('voucher_owner_id'=>$_SESSION['member_id']);

		if (intval($_GET['select_detail_state'])>0){

			$where['voucher_state'] = intval($_GET['select_detail_state']);

		}

	//	$field = "voucher_id,voucher_code,voucher_title,voucher_desc,voucher_start_date,voucher_end_date,voucher_price,voucher_limit,voucher_state,voucher_order_id,voucher_store_id,store_name,store_id,store_domain";

	//	$list = $model->table('voucher,store')->field($field)->join('inner')->on('voucher.voucher_store_id = store.store_id')->where($where)->order('voucher_id desc')->page(10)->select();

        $list = $model->table('voucher')->where($where)->order('voucher_id desc')->page(10)->select();
        
		if(is_array($list)){

			foreach ($list as $key=>$val){

				if (!$val['voucher_t_customimg'] || !file_exists(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$val['voucher_t_customimg'])){

					$list[$key]['voucher_t_customimg'] = defaultGoodsImage('tiny');

				}else{

					$list[$key]['voucher_t_customimg'] = SiteUrl.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$val['voucher_t_customimg']."_small.".get_image_type($val['voucher_t_customimg']);

				}

			}

		}

		//S脚部文章输出

	//	$list=$this->_article();

		//E脚部文章输出

		Tpl::output('list', $list);

        Tpl::output('show_page',$model->showpage(2)) ;

		$this->get_member_info();

        $this->profile_menu('voucher_list');

		Tpl::output('menu_sign','myvoucher');

		Tpl::output('menu_sign_url','index.php?act=member_voucher');

		Tpl::output('menu_sign1','member_voucher');

        Tpl::showpage('member_voucher.list');

    }

    private function check_voucher_expire() {

        $model = Model();

        $model->table('voucher')->where(array('voucher_owner_id'=>$_SESSION['member_id'],'voucher_state'=>$this->voucherstate_arr['unused'][0],'voucher_end_date'=>array('lt',time())))->update(array('voucher_state'=>$this->voucherstate_arr['expire'][0]));

    }

	

	private function profile_menu($menu_key='') {

		$menu_array = array(

			1=>array('menu_key'=>'voucher_list','menu_name'=>Language::get('nc_myvoucher'),'menu_url'=>'index.php?act=member_voucher&op=voucher_list'),

		);

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

    }	



}

