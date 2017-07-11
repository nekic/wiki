<?php

/**
 * Created by PhpStorm.
 * User: nekic
 * Date: 4/23/17
 * Time: 3:29 PM
 */
class db
{
//    private $user='nekicweb_nekic';      //数据库连接用户名
//    private $pass='zhao5201314';          //对应的密码
//    private $dsn="mysql:host=127.0.0.1;dbname=nekicweb_wiki";
    private $user='homestead';      //数据库连接用户名
    private $pass='secret';          //对应的密码
    private $dsn="mysql:host=127.0.0.1;dbname=wiki";
    private $dbh;
    private $tableName;
    private $data;
    private $sql;

    public function __construct($tableName='notes')
    {
        $this->tableName = $tableName;

        try {
            $this->dbh = new PDO($this->dsn, $this->user, $this->pass,array

            (PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'utf8';")); //初始化一个PDO对象
        } catch (PDOException $e) {
            common::ajaxReturn(['status'=>0,'info'=>$e->getMessage()]);
        }
    }


    public function create($data)
    {
        $this->data = $data;
        $fields = array_keys($this->data);
        $fieldStr = implode(',',$fields);
        $valueStr = array();
        foreach ($fields as $fieldName) {
            $valueStr[] = ':' . $fieldName;
        }
        $valueStr = implode(',',$valueStr);

        $insertData = array();
        foreach ($this->data as $key => $value) {
            $field = ':'.$key;
            $insertData[$field] = $value;
        }

        $sql = 'INSERT INTO ' . $this->tableName . ' (' . $fieldStr .')  VALUE (' . $valueStr .')';
        $sth = $this->dbh->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($insertData);
        return $this->dbh->lastInsertId();
    }

    public function read($data=[])
    {

        $field = isset($data['field']) ? $data['field'] : '';
        if(!$field) {
            $field = '*';
        }

        $table = isset($data['table']) ? $data['table']:'';
        if(!$table) {
            $table = $this->tableName;
        }

        $order = isset($data['order']) ? $data['order'] : '';
        if($order) {
            $order = ' ORDER BY ' . $order;
        }

        $where = isset($data['where']) ? $data['where'] : '';
        $whereData = array();
        if(!$where) {
            $where = 1;
        } else {
            $whereFieldsArr = array();

            foreach ($where as $filedName => $value) {
                if(is_array($value)) {
                    $whereFieldsArr[] = $filedName . ' ' . $value[0]. " :{$filedName}";
                    $whereData[":{$filedName}"] = $value[1];
                } else {
                    $whereFieldsArr[] = $filedName . " = :{$filedName}";
                    $whereData[":{$filedName}"] = $value;
                }
            }

            $where = implode(' AND ',$whereFieldsArr);

        }


        $sql = "SELECT {$field} FROM {$table} WHERE {$where} {$order}";

        $sth = $this->dbh->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $sth->execute($whereData);

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function update($data)
    {
        $where = $data['where'];
        $data = $data['data'];
        $setArr = array();

        $fields = array_keys($data);
        foreach ($fields as $field) {
            $setArr[] = "`{$field}` = :{$field}";
        }

        $setStr = implode(',',$setArr);

        $whereArr = array();
        foreach ($where as $field => $value) {
            if(is_array($value)) {
                $whereArr[] = "`{$field}` {$value[0]} :where{$field}";
            } else {
                $whereArr[] = "`{$field}` = :where{$field}";
            }

        }

        $whereStr = implode(',',$whereArr);
        
        $setDataArr = array();
        $whereDataArr = array();

        foreach ($data as $field => $value) {
            $field = ":{$field}";
            $setDataArr[$field] = $value;
        }

        foreach ($where as $field => $value) {
            $field = ":where{$field}";
            if(is_array($value)) {
                $whereDataArr[$field] = $value['1'];
            } else {
                $whereDataArr[$field] = $value;
            }
        }

        $dataArr = array_merge($setDataArr,$whereDataArr);

        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $setStr . ' WHERE ' . $whereStr ;
        $sth = $this->dbh->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($dataArr);
        return true;
    }

    public function delete()
    {

    }
}