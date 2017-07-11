<?php

/**
 * Created by PhpStorm.
 * User: nekic
 * Date: 4/23/17
 * Time: 3:00 PM
 */
class notes
{
    private $trees;

    /**
     * 创建笔记
     */
    public function create()
    {
        $pid = 0;
        if(isset($_POST['pid']) && $_POST['pid'] !=='') {
            $pid = $_POST['pid']+0;
        }

        $title = '未命名笔记';
        if(isset($_POST['title']) && $_POST['title'] !=='') {
            $title = $_POST['title'];
        }

        $data = array(
            'pid' => $pid,
            'title' => $title,
            'type' => 0,
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

    public function rename()
    {

        if(isset($_POST['title']) && $_POST['title'] !=='') {
            $title = $_POST['title'];
        } else {
            $response = array(
                'status'=>0,
                'info' => '操作失败, 原因：新名称为空！'
            );
            common::ajaxReturn($response);
        }

        if(isset($_POST['id']) && $_POST['id'] !=='') {
            $id = $_POST['id'];
        } else {
            $response = array(
                'status'=>0,
                'info' => '操作失败, 原因：ID为空！'
            );
            common::ajaxReturn($response);
        }


        $data = array(
            'where' => array(
                'id' => $id
            ),
            'data' => array(
                'title' => $title
            )
        );

        try {
            $db = new db('notes');
            $db->update($data);
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

    public function lists()
    {

        $db = new db('notes');
        $queryCondition = array(
            'field'=>'id,pid,title,type',
            'order'=>'type desc, id asc '
        );
        $result = $db->read($queryCondition);
        $lists = $this->makeTree($result);
        $this->makeUlist($lists);
        return $this->trees;
    }

    private function makeUlist($lists)
    {
        $this->trees .= '<ul class="unstyled" id="tree">';
        foreach ($lists as $key => $list) {
            if($list['type']) { // =1 是目录
                $this->trees .= '<li class="directory" data-id="'.$list['id'].'"><a href="#" data-role="directory"><i class="glyphicon glyphicon-folder-close"></i>&nbsp; <sapn class="name">'. $list['title'] .'</sapn></a>';
                if(!empty($list['child'])) {
                    $this->makeUlist($list['child']);
                } else { // 空目录
                    $this->trees .= '<ul class="unstyled" id="tree">';
                    $this->trees .=  '</ul>';
                }


                $this->trees .= '</li>';
            } else {
                $this->trees .= '<li class="file" data-id="'.$list['id'].'"><a href="javascript:void(0);"><sapn class="name">'. $list['title'] . '</span></a></li>';
            }
        }
        $this->trees .=  '</ul>';

    }

    public function getNoteById()
    {
        $id = empty($_GET['id']) ? '' : $_GET['id'];



        if(!$id) {
//            $response = array(
//                'status'=>0,
//                'info' => '操作失败, 原因：ID为空！'
//            );
//            common::ajaxReturn($response);
            echo 'ID为空!';
            return ;
        }
        $db = new db('notes');
        $searchData = array(
            'where' => array('id'=>$id)
        );
        $note = $db->read($searchData);

        if(!$note) {
//            $response = array(
//                'status'=>0,
//                'info' => '操作失败, 原因：结果集为空！'
//            );
//            common::ajaxReturn($response);

            echo '操作失败, 原因：结果集为空！';
            return;
        }

        $content = $note[0]['content'];
        $source = $content;

        if(isset($_GET['source'])) {
            include 'static/source.php';
        } else {
            $Parsedown = new Parsedown();

            $content =  $Parsedown->text($content);

            // $content = str_replace("'","\\'",$content);
            // $content = htmlspecialchars($content);
            include 'static/content.php';
        }

//        $response = array(
//            'status'=>1,
//            'info' => '操作成功！',
//            'data' => $note[0]
//        );
//        common::ajaxReturn($response);
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pk 自增字段（栏目id）
     * @param string $pid parent标记字段
     * @return array
     */
    private function makeTree($list,$pk='id',$pid='pid',$child='child',$root=0){
        $tree=array();
        $packData=array();
        foreach ($list as  $data) {
            $packData[$data[$pk]] = $data;
        }
        foreach ($packData as $key =>$val){
            if($val[$pid]==$root){//代表跟节点
                $tree[]=& $packData[$key];
            }else{
                //找到其父类
                $packData[$val[$pid]][$child][]=& $packData[$key];
            }
        }
        return $tree;
    }

    public function edit()
    {

        $id = empty($_POST['id']) ? '' : $_POST['id'];
        $content = isset($_POST['content']) ? $_POST['content'] : '';

        if(!$id) {
            $response = array(
                'status'=>0,
                'info' => '操作失败, 原因：ID为空！'
            );
            common::ajaxReturn($response);
        }

        $data = array(
            'where' => array('id'=>$id),
            'data' => array('content'=>$content)
        );

        try {
            $db = new db('notes');
            $res = $db->update($data);
            if($res === false) {
                throw new Exception('笔记修改失败！');
            }
        } catch (\Exception $e) {
            $response = array(
                'status'=>0,
                'info' => '操作失败，原因' . $e->getMessage(),
            );
            common::ajaxReturn($response);

        }


        $response = array(
            'status'=>1,
            'info' => '操作成功！',
        );
        common::ajaxReturn($response);
    }
}