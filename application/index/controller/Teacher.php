<?php
/**
 * @Author: liupeng
 * @Date:   2018-03-09 03:33:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2018-03-21 17:22:34
 */
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;
use think\Request;
use app\index\model\Teacher as TeacherModel;
use think\Db;

class Teacher extends Base
{
    /*渲染老师管理页面*/
    public function teacherList(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $this->getMenu(); //渲染菜单栏
        $this->view->assign('title','教师管理—ThinkEDU');
        // 统计当前记录数量
        $this->view->count = TeacherModel::count();
        // 判断当前用户的角色
        // 管理员和超级管理员可以查看全部的老师信息
        $user_role = Session::get('user_info.role');
        $user_name = Session::get('user_info.name');

        if($user_role == 1 or $user_role == 0){
            $get_search = input('get.search');
            // 判断查询条件
            $search = isset($get_search) ? $get_search : '';
            $list = Db::table('te_teacher')
                -> alias('t')
                -> join('te_gardes g','t.garde_id = g.id')
                -> where('t.teacher_name|g.garde_name|t.school','like','%'.$search.'%')
                -> paginate(2);
        }else{
            $list = Db::table('te_teacher')
                -> alias('t')
                -> join('te_gardes g','t.garde_id = g.id')
                -> where('t.teacher_name =$user_name ')
                -> select();
        }
        $this->view->assign('list',$list);
        return $this->view->fetch('teacher_list');
    }
}
