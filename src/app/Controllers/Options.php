<?php namespace App\Controllers;

class Options extends BaseController {

    // 查询某个用户的所有选项
    public static function getOptionsFromUID($uid) {
        $db = \Config\Database::connect();

        $builder = $db->table("options");

        $query = $builder->where([
            "uid"       =>      $uid,
            "enabled"   =>      true
        ])->get();

        $data = $query->getResult("array");

        $returnData = [];

        foreach ($data as $each) {
            $returnData[$each["option_name"]] = $each["option_val"];
        }

        return $returnData;
    }

    // 更新或插入选项
    public static function updateOption($uid, $option_name, $option_val, $enabled = true) {
        $db = \Config\Database::connect();

        $builder = $db->table("options");

        // 先查询是否存在
        $query = $builder->where([
            "uid"           =>      $uid,
            "option_name"   =>      $option_name
        ])->get();

        $data = $query->getResult("array");

        $builder = $db->table("options");
        if ($data) {
            // 直接进行更新
            $builder->where([
                "uid"           =>      $uid,
                "option_name"   =>      $option_name
            ])->update([
                "option_val"    =>      $option_val,
                "enabled"       =>      $enabled
            ]);
        } else {
            // 插入新的记录
            $builder->insert([
                "uid"           =>      $uid,
                "option_name"   =>      $option_name,
                "option_val"    =>      $option_val,
                "enabled"       =>      $enabled
            ]);
        }
    }
}