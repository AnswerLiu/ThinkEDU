<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use app\index\model\User as UserModel;
use think\Session;
use think\Db;

class User extends Base
{
    // 登录界面
    public function login(){

        $this->alreadyLogin(); //防止用户重复登录
        return $this->view->fetch();
    }

    //验证登录 $this->vliadate($data,$rule,$msg)
    public function checkLogin(Request $request){
        // 初始化返回参数
        $status = 0; //当前状态，默认为0
        $result = ''; //提示信息
        $data = $request -> param(); //返回数据

        // 创建验证规则
        $rule = [
            'user|用户名' => 'require', //用户名必填
            'password|密码' => 'require', //密码必填
            'verify|验证码' => 'require|captcha', //验证码必填
        ];
        //  自定义验证失败的提示信息
        $msg = [
            'user' => ['require' => '用户名不能为空，请检查。'],
            'password' => ['require' => '密码不能为空，请检查。'],
            'verify' => [
                'require' => '验证码不能为空，请检查。',
                'captcha' => '验证错误，请检查。',
            ],
        ];

        // $result 只会返回两种状态，true->表示验证通过，如果返回字符串，则是用户自定义的错误提示
        $result = $this->validate($data,$rule,$msg);

        if($result === true){
            // 构造数据查询
            $map = [
                'user' => $data['user'],
                'password' => md5($data['password']),
            ];

            // 查询用户信息
            $user = UserModel::get($map);
            if($user == null){
                $result = '没有该用户';
            }else{
                $login_count = $user['login_count'] + 1;
                $login_info = [
                    'login_time' => time(),
                    'login_count' => $login_count,
                ];
                UserModel::update($login_info,['id' => $user['id']]);
                $status = 1;
                $result = '验证通过，点击[确定]进入';
                // 设置用户登录信息：Session
                Session::set('user_id',$user->id); // 用户id
                Session::set('user_info',$user->getdata()); // 用户信息
            }
        }

        return ['status'=>$status,'message'=>$result,'data'=>$data];
    }

    /*退出登录*/
    public function logout(){

        // 注销session
        Session::delete('user_id');
        Session::delete('user_info');
        $this->success('注销登录，正在返回。','user/login');
    }

    /**
     * 管理员列表
     */
    public function adminList(Request $request){
        $this->isLogin(); //判断用户是否登录
        $this->getMenu(); //渲染菜单栏
        $this->assign('title','管理员列表——ThinkEDU');
        $this->assign('keywords','ThinkEDU,管理员列表页');
        $this->assign('desc','ThinkEDU,EDU,edu,ThinkPHP');

        // 统计当前记录数量
        $this->view->count = UserModel::count();

        // 判断当前用户是不是admin
        // 先通过session获取到当前用户名
        $user_name = Session::get('user_info.user');
        if($user_name == 'admin'){

            $get_title = input('get.name');
            // 判断搜索条件
            $title = isset($get_title) ? $get_title : '';

            $list = Db::table('te_user')
                    -> where('name|user','like','%'.$title.'%')
                    -> paginate(2);
        }else{
            // 非admin用户只能查看自己的信息
            $list = Db::table('te_user')
                    -> where(['user' => $user_name])
                    -> select();
        }

        $this->view->assign('list',$list);
        return $this->view->fetch('admin_list');
    }

    /*管理员状态变更*/
    public function setStatus(Request $request){
        $this->isLogin(); //判断用户是否登录
        $user_id = $request -> param('id');
        $result = UserModel::get($user_id);
        if($result->getData('status') == 1){
            UserModel::update(['status'=>0],['id'=>$user_id]);
        }else{
            UserModel::update(['status'=>1],['id'=>$user_id]);
        }
    }

    //渲染编辑管理员界面
    public function adminEdit(Request $request){
        $this->isLogin(); //判断用户是否登录
        $user_id = $request -> param('id');
        $result = UserModel::get($user_id);

        // dump($result->getData());
        $this->view->assign('title','编辑管理员信息');
        $this->view->assign('info',$result->getData());
        return $this->view->fetch('admin_edit');
    }

    /*编辑管理员操作*/
    public function editUser(Request $request){
        $this->isLogin(); //判断用户是否登录
        // 获取表单返回的数据
        $info = $request -> param();

        $condition = ['id' => $info['id']];
        $result = UserModel::update($info,$condition);

        // 如果是admin用户并且角色发生改变，更新session中user_info的role信息，供页面调用
        if(Session::get('user_info.name' == 'admin')){
            Session::set('user_info.role',$info['role']);
        }

        if($result == true){
            return ['status' => 1,'message' => '更新成功。'];
        }else{
            return ['status' => 0,'message' => '更新失败。'];
        }
    }

    /*删除管理员用户*/
    public function deleteUser(Request $request){
        $this->isLogin(); //判断用户是否登录
        $user_id = $request -> param();
        $result = UserModel::get($user_id);

        // 必须先停用帐号才能删除
        if($result->getData('status') == 0){
            // 进行删除操作
            // 判断用户是否已经删除
            if($result->getData('is_delete') == 1){
                $time = time();
                $is_del = [
                    'is_delete' => 0,
                    'delete_time' => $time,
                ];
                $info = UserModel::update($is_del,$user_id);
                return ['is_delete' => 1,'message' => '已删除','data' => $user_id];
            }else{
                UserModel::update(['is_delete' => 1],['delete_time' => null]);
                return ['is_delete' => 0,'message' => '删除失败','data' => $user_id];
            }
        }else{
            return ['status' => 1,'message' => '先停用帐号才可以删除','data' => $user_id];
        }
    }

    /*渲染添加管理员页面*/
    public function adminAdd(){
        $this->isLogin(); //判断用户是否登录
        $this->assign('title','添加管理员');
        return $this->view->fetch('admin_add');
    }

    /*添加管理员操作*/
    public function addUser(Request $request){
        $this->isLogin(); //判断用户是否登录
        $user = $request -> param();

        $user['password'] = md5($user['password']);
        $user['create_time'] = time();
        $result = UserModel::insert($user);

        if($result == true){
            return ['status' => 1,'message' => '添加成功。'];
        }else{
            return ['status' => 0,'message' => '添加失败。'];
        }
    }

    /*管理员角色管理页面渲染*/
    public function adminRole(){
        $this->isLogin(); //判断用户是否登录
        return $this->view->fetch('admin_role');
    }

    /*权限管理页面渲染*/
    public function adminPower(){
        $this->isLogin(); // 判断用户是否登录
        return $this->view->fetch('admin_power');
    }

    /*管理员列表搜索*/
    public function search(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $data = $request -> param();

        $name = $data['name'];
        $sdate = $data['sdate'];
        $edate = $data['edate'];

        // 判断搜索条件
        if(!empty($name)){ //只查询用户
            // $result = UserModel::all(['name' => $data['name'],'user' => $data['user']]);
            $result = Db::table('te_user')
                    -> where("name|user",'like','%$name')
                    -> where('is_delete',1)
                    -> order('id')
                    -> select();
        }elseif(!empty($sdate) or !empty($edate)){ //按时间段查询
            $result = Db::table('te_user')
                    -> whereTime('create_time|login_time','bewteen',[$sdate,$edate])
                    -> where('is_delete',1)
                    -> oreder('id')
                    -> select();
        }else{ // 时间端和用户一起查询
            $result = Db::table('te_user')
                    -> where('name|user','like','%$name')
                    -> whereTime('create_time|login_time','bewteen',[$sdate,$edate])
                    -> where('is_delete',1)
                    -> order('id')
                    -> select();
        }

    }
}
 ?>
