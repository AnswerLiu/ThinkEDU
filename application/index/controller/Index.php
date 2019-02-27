<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;

class Index extends Base
{
    public function index()
    {
        $this->isLogin(); //判断用户是否登录
        $this->getMenu();

        return $this->view->fetch();
    }
}
