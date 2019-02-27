<?php
/**
 * @Author: anchen
 * @Date:   2018-03-09 03:24:33
 * @Last Modified by:   anchen
 * @Last Modified time: 2018-03-21 17:53:33
 */
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Gardes as GardesModel;
use think\Session;
use think\Request;
use think\Db;

class Gardes extends Base
{
    /*渲染班级管理页面*/
    public function gardesList(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $this->getMenu(); //渲染菜单栏
        $this->assign('title','班级管理—ThinkEDU');

        // 统计当前记录数量
        $this->view->count = GardesModel::count();

        // 判断当前用户的角色
        // 管理员和超级管理员可以查看全部班级信息
        $user_role = Session::get('user_info.role');
        $user_name = Session::get('user_info.name');
        if($user_role == 1 or $user_role == 0){
            $get_title = input('get.title');
            // 判断查询条件
            $title = isset($get_title) ? $get_title : '';

            $list = Db::table('te_gardes')
                    -> where('garde_name|teacher','like','%'.$title.'%')
                    -> paginate(2);

        }else{ // 否则只能查看自己所带班级的信息
            $list = Db::table('te_gardes')
                    -> where(['teacher' => $user_name])
                    -> select();
        }

        $this->view->assign('list',$list);
        return $this->view->fetch('gardes_list');
    }

    /*班级状态变更*/
    public function setStatus(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $garde_id = $request->param('id');
        $result = GardesModel::get($garde_id);
        if($result->getData('status') == 1){ //如果班级状态为1，则改为0
            GardesModel::update(['status' => 0],['id' => $garde_id]);
        }else{
            GardesModel::update(['status' => 1],['id' => $garde_id]);
        }
    }

    /*渲染班级管理编辑页面*/
    public function gardeEdit(Request $request){
        $this->isLogin(); //判断用户是否登录
        $garde_id = $request->param('id');
        $result = GardesModel::get($garde_id);

        $this->view->assign('title','编辑班级');
        $this->view->assign('info',$result->getData());
        return $this->view->fetch('gardes_edit');
    }

    /*编辑班级管理操作*/
    public function editGarde(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $data = $request->param();

        $id = ['id' => $data['id']];
        $data['start_time'] = strtotime($data['start_time']);
        $result = GardesModel::update($data,$id);

        if($result){
            return ['status' => 1,'message' => '修改成功。'];
        }else{
            return ['status' => 0,'message' => '修改失败。'];
        }
    }

    /*渲染班级管理新增页面*/
    public function gardeAdd(Request $request){
        $this->isLogin(); //判断用户是否登录
        $this->view->assign('title','新增班级');
        return $this->view->fetch('gardes_add');
    }

    /*新增班级操作*/
    public function addGarde(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $data = $request->param();
        $data['start_time'] = strtotime($data['start_time']);
        $data['create_time'] = time();
        $result = GardesModel::insert($data);

        if($result){
            return ['status' => 1,'message' => '添加成功。'];
        }else{
            return ['status' => 0,'message' => '添加失败。'];
        }
    }

    /*删除班级操作*/
    public function deleteGarde(Request $request){
        $this->isLogin(); // 判断用户是否登录
        $id = $request->param();
        $result = GardesModel::get($id);

        // 先把班级停用才可以删除
        if($result -> getData('status') == 0){
            // 先判断是否已经删除
            if($result -> getData('is_delete') == 1){
                $time = time();
                $is_del = [
                    'is_delete' => 0,
                    'delete_time' => $time,
                ];
                GardesModel::update($is_del,$id);
                return ['is_delete' => 1,'message' => '删除成功。'];
            }else{
                GardesModel::update(['is_delete' => 1],['delete_time' => null]);
                return ['is_delete' => 0,'message' => '删除失败。'];
            }
        }else{
            return ['status' => 0,'message' => '必须先停用班级才可以删除。'];
        }
    }
}
