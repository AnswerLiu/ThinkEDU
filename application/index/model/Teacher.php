<?php
namespace app\index\model;
use think\Model;

class Teacher extends Model
{
    // 更新自动完成列表
    protected $update = [];
    // 是否需要自动写入时间戳，如果设置为字符串，则表示写入时间字段的类型
    protected $autoWriteTimeStamp = true;
    // 创建时间字段
    protected $createTime = 'create_time';
    // 更新时间字段
    protected $updateTime = 'update_time';

    /*数据表中学历字段：degree返回值*/
    public function getDegreeAttr($value){
        $degree = [
            0 => '其他',
            1 => '大专',
            2 => '本科',
            3 => '研究生',
            4 => '博士'
        ];
        return $degree[$value];
    }

    /*数据表中状态字段：status返回值*/
    public function getStatusAttr($value){
        $status = [
            0 => '已关闭',
            1 => '已启用'
        ];
        return $status[$value];
    }
}
