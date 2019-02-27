<?php
namespace app\index\model;
use think\Model;
// use traits\model\SoftDelete; //软删除功能

class User extends Model
{
    // 导入软删除方法及
    // use SoftDelete;
    // 设置软删除字段
    // 只有该字段为null时，该字段才会显示出来
    // protected $deleteTime = 'delete_time';

    /*保存自动完成列表，新增用户的时候，设置delete_time和is_delete默认为0*/
    /*protected $auto = [
        'delete_time' => null,
        'is_delete' => 1, //1未删除，允许删除，0已删除
    ];*/
    /*新增自动完成列表，新增用户的时候，设置login_time和longin_count，默认为0*/
    protected $insert = [
        'login_time' => null,
        'login_count' => 0,
    ];
    // 更新自动完成列表
    protected $update = [];
    // 是否需要自动写入时间戳，如果设置为字符串，则表示时间字段的类型
    protected $autoWriteTimeStamp = true;
    // 创建时间字段
    protected $createTime = 'create_time';
    // 更新时间字段
    protected $updateTime = 'update_time';
    // 时间字段取出后的默认时间格式
    protected $dateFormat = 'Y年m月d日 H:i:s';

    /*数据表中角色字段：role返回值*/
    public function getRoleAttr($value){
        $role = [
            0 => '管理员',
            1 => '超级管理员',
        ];

        return $role[$value];
    }

    /*数据表中状态字段：status返回值*/
    public function getStatusAttr($value){
        $status = [
            0 => '已停用',
            1 => '已启用',
        ];

        return $status[$value];
    }
}
