<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Db;
use think\View;

class Base extends Controller
{
    protected function _initialize(){
        parent::_initialize(); //继承父类中的初始化操作
        define('USER_ID',Session::get('user_id'));

        // 判断网站的状态，是否正常运行，1正常运行，0关闭网站
        $web_status = Db::table('te_config')->where('id',0)->find();
        define('WEB_STA',$web_status['status']);

    }


    // 判断用户是否登录，放在后台的入口：index/index
    protected function isLogin(){
        if(WEB_STA == 0){
            // 显示网站关闭的页面
            echo $this->fetch('common/close');exit();
        }elseif(empty(USER_ID)){
            $this->error('用户未登录，无权访问。',url('user/login'));
        }
    }

    // 防止用户重复登录 user/index
    protected function alreadyLogin(){
        if(WEB_STA == 0){
            // 显示网站关闭的页面
            echo $this->fetch('common/close');exit();
        }elseif(!empty(USER_ID)){
            $this->error('您已登录，请勿重复登录。',url('index/index'));
        }
    }

    // 全局遍历菜单
    public function getMenu(){
        if(WEB_STA == 0){
            // 显示网站关闭页面
            echo $this->fetch('common/close');exit();
        }elseif(!empty(USER_ID)){
            $list_two = array();
            $list = Db::table('te_menu')
                    -> where(['pid' => 1,'status' => 1])
                    -> select();
            foreach($list as $key => $val){
                $list[$key]['child'] = array();//获取二级分类的名字
                $list_two = Db::table('te_menu')
                        -> where(['pid' => $val['id'],'status' => 1])
                        -> select();
                foreach($list_two as $v){
                    array_push($list[$key]['child'],$v);
                }
            }
            $this->assign('list',$list);
            echo $this->fetch('common/menu');
        }
    }

}
