<?php
/**
 * @Author: anchen
 * @Date:   2018-03-09 03:35:31
 * @Last Modified by:   anchen
 * @Last Modified time: 2018-03-09 18:29:09
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;
use think\Request;
use app\index\model\Student as StudentModel;
use think\Db;

class Student extends Base
{
    /*渲染学生管理页面*/
    public function studentList(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $this->getMenu(); //渲染菜单栏
        $this->view->assign('title','学生管理--ThinkEDU');
        // 统计当前记录数量
        $this->view->count = StudentModel::count();
        // 判断当前用户的角色
        // 管理员和超级管理员可以查看全部的学生信息
        $user_role = Session::get('user_info.role');

        // 搜索分页
        // 获取搜索框的内容
        $get_search = input('get.search');
        // 判断搜索框是否为空
        $search = isset($get_search) ? $get_search : '';
        $field = 's.id,s.student_name,s.sex,s.age,s.tel,s.email,s.start_time,s.status,g.garde_name';
        $list = Db::table('te_student')
            -> alias('s')
            -> join('te_gardes g','s.garde_id = g.id')
            -> where('s.student_name|g.garde_name','like','%'.$search.'%')
            -> field($field)
            -> paginate(1);

        $this->view->assign('list',$list);
        return $this->view->fetch('student_list');
    }
}
