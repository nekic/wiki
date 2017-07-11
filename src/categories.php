<?php

/**
 * Created by PhpStorm.
 * User: nekic
 * Date: 4/23/17
 * Time: 11:18 PM
 */
class categories
{
    /**
     * 创建分类
     */
    public function create()
    {
        $pid = 0;
        if(isset($_POST['pid']) && $_POST['pid'] !=='') {
            $pid = $_POST['pid']+0;
        }

        $title = '未命名分类';
        if(isset($_POST['title']) && $_POST['title'] !=='') {
            $title = $_POST['title'];
        }

        $data = array(
            'pid' => $pid,
            'title' => $title,
            'type' => 1,
            'created_at'=>time(),
            'updated_at'=>time()
        );

        try {
            $db = new db('notes');
            $id = $db->create($data);
            $response = array(
                'status'=>1,
                'id' => $id,
                'title' => $title,
                'info' => '操作成功！'
            );
        } catch (Exception $e) {
            $response = array(
                'status'=>0,
                'info' => '操作失败, 原因：' . $e->getMessage()
            );
        }

        common::ajaxReturn($response);
    }
}