<?php



defined('haipinlegou') or exit('Access Invalid!');


/**
 * 应用场景：前端需要异步获取数据控制器
 */
class ajaxControl extends BaseHomeControl {



	public function __construct(){

		parent::__construct();
        
    }


    /**
     * ajax获取登录验证码
     */

	public function getcaptchaOp(){
        $captcha = strtolower($_POST['captcha']);
		/**
		 * 检查登录状态
         *
		 */
		if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == 1){
            echo json_encode(array('status'=>'fail','data'=>array('code'=>1)));
            die;
        }
        if (empty($captcha)) {
            echo json_encode(array('status'=>'fail','data'=>array('code'=>2)));
            die;
        }

        if (C('captcha_status_login')){
            $brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

            $brr[0] = strtolower($brr[0]);

            if ($brr[0]!=$captcha){
                echo json_encode(array('status'=>'fail','data'=>array('code'=>3)));
                die;
            }else{
                echo json_encode(array('status'=>'succ','data'=>''));
                die;
            }
        }
    }

    /**
     * ajax获取验证码,输出true或者false
     */

    public function getValidateCaptchaOp(){
        $captcha = strtolower($_POST['captcha']);
        /**
         * 检查登录状态
         *
         */
        if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == 1){
            echo "false";
            die;
        }
        if (empty($captcha)) {
            echo "false";
            die;
        }

        if (C('captcha_status_login')){
            $brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

            $brr[0] = strtolower($brr[0]);

            if ($brr[0]!=$captcha){
                echo "false";
                die;
            }else{
                echo "true";
                die;
            }
        }
    }


    /**
     *应用场景：异步获取账号是否存在 $act=2
     *                账号密码是否正确 $act=1
     */
    public function verifymemberOp()
    {
        $param['member_name'] = trim($_POST['member_name']);
        $password = md5(trim($_POST['pwd']));
        $act = intval($_POST['act']);
        $act = empty($act) ? 2 : $act;
        $model = Model('member');
        $info = $model->infoMember($param);
        switch($act) {
            case 1:
                if (!empty($info)) {
                    if ($info['member_passwd'] == $password) {
                        echo json_encode(array('status'=>'succ','data'=>''));
                    }else {
                        echo json_encode(array('status'=>'fail','data'=>array('code'=>1)));//code=1表示账号和密码不匹配
                    }
                }else {
                    //code=2 代表 账号不存在
                    echo json_encode(array('status'=>'fail','data'=>array('code'=>2)));
                }
            break;
            case 2:
                if (!empty($info)) {
                    echo json_encode(array('status'=>'succ','data'=>''));
                }else {
                    echo json_encode(array('status'=>'fail','data'=>array('code'=>2)));
                }
            break;
            default:
                echo json_encode(array('status'=>'fail','data'=>array('code'=>3)));//参数不正确
                return;
        }
        die;

    }

    /**
     * 应用场景：动态获取手机验证码
     */

    public function verifyphoneOp()
    {
        $phone = trim($_POST['phone']);
        $code  = trim($_POST['code']);

        if (empty($phone) or empty($code)) {
            echo "false";
            die;
        }
        $model = Model('mobile_vertify');
        $res = $model->where(array('mobile_num'=>$phone))->order('id DESC')->select();
        if (isset($res[0]['num']) && !empty($res[0]['num'])) {
            //验证码是否超时
            if ($res[0]['dead_time'] < time()){
                echo "false";
                die;
            }
            //验证码是否匹配
            if ($res[0]['num'] == $code) {
                echo "true";
                die;
            }else {
                echo "false";
                die;
            }
        }else {
            //手机号码不对
            echo "false";
            die;
        }

    }
    /**
     * 应用场景 ：判断用户是否绑定个人资料
     */
    public function bindinfoOp()
    {
        $member_id    = $_SESSION['member_id'];
        $is_login     = $_SESSION['is_login'];
        $member_model = Model('member');
        if ($is_login != 1) {
            echo json_encode(array('status'=>'fail'));
            die;
        }
        $member = $member_model->infoMember(array('member_id'=>$member_id));
        //输出是否需要完善个人资料标记
        $log = 1;
        if (empty($member['member_truename']) or empty($member['member_id_card']) or empty($member['member_mob_phone'])){
            $log = 2;
        }
        if ($log == 1) {
            echo json_encode(array('status'=>'succ'));
            die;
        } else {
            echo json_encode(array('status'=>'fail'));
            die;
        }
    }

    /**
     * 设置默认收货地址
     * code=1 地址不存在
     * code=2 地址更新失败
     * code=3 传参失误或者未登陆
     */
    public function setaddressOp()
    {
        $address_id = trim($_POST['address_id']);
        $member_id  = $_SESSION['member_id'];

        if (empty($address_id) or empty($member_id)) {
            echo json_encode(array('status'=>'fail','data'=>array('code'=>3)));
            die;
        }

        $model = Model();
        $member_model  = Model('member');
        $res = $model->table('address')->where(array('address_id'=>$address_id,'member_id'=>$member_id))->select();

        if (empty($res)) {
            echo json_encode(array('status'=>'fail','data'=>array('code'=>1)));
            die;
        }

        $update = $member_model->updateMember(array('member_address_id'=>$address_id),$member_id);

        if ($update) {
            echo json_encode(array('status'=>'succ'));
            die;
        } else {
            echo json_encode(array('status'=>'fail','data'=>array('code'=>2)));
            die;
        }

    }

    /**
     *获取商店的二级分类
     */
    public function getSecClassOp()
    {
        //获取父类下的分类
        $gc_id = $_GET['gc_id'];//die($gc_id);
        if (empty($gc_id) or $_SESSION['store_id']<1) {
            echo json_encode(array('status'=>'fail','data'=>''));
            die;
        }
        $store_model = Model('store');
        $class             = $store_model->getClass(array('gc_parent_id'=>$gc_id));//gc_id分类下所有分类
        $store_class       = $store_model->getStoreClass($_SESSION['store_id'],2);//商店的所有二级分类
        $tmp_all_class = array();

//        //store二级分类
        if(!empty($store_class) && is_array($store_class)) {
            foreach($store_class as $k2=>$v2) {
                if(!empty($class) && is_array($class)) {
                    foreach($class as $k3=>$v3) {
                        if ($v3['gc_id'] == $v2['gc_id']) {
                            $tmp_all_class[] = $v3;
                            unset($class[$k3]);
                            break;
                        }
                    }
                }
            }
        }

        if (!empty($tmp_all_class) && is_array($tmp_all_class)) {
            echo json_encode(array('status'=>'succ','data'=>$tmp_all_class));
            die;
        } else {
            echo json_encode(array('status'=>'fail','data'=>''));
            die;
        }
    }
    /**
     * 获取某类下的分类
     */
    public function getClassOp()
    {
        $gc_id = $_GET['gc_sec_id'];
        if(empty($gc_id)) {
            echo json_encode(array('status'=>'fail','data'=>''));
            die;
        }
        $store_modle = Model('store');
        $store_class = $store_modle->getClass(array('gc_parent_id'=>$gc_id));
        $gc          = $store_modle->getClass(array('gc_id_in'=>$gc_id));
        if(!empty($store_class) && is_array($store_class)) {
            echo json_encode(array('status'=>'succ','data'=>$store_class));
            die;
        } else {
            echo json_encode(array('status'=>'succ','data'=>$gc));
            die;
        }


    }


}

