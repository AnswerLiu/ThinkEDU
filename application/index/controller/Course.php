<?php
/**
 * @Author: anchen
 * @Date:   2018-03-09 03:27:55
 * @Last Modified by:   anchen
 * @Last Modified time: 2018-03-09 18:29:22
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;
use think\Request;

class Course extends Base
{
    /*渲染课程管理页面*/
    public function courseList(){
        $this->isLogin(); // 判断用户是否登录
        $this->getMenu(); //渲染菜单栏
        return $this->view->fetch('course_list');
    }
}
