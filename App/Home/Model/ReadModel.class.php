<?php

/* 阅读测评业务模型
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Model;

use Think\Model;

class ReadModel extends Model {

    protected $backMsg = array("response" => 0, "content" => '');
    protected $tableName = 'read_books';

    public function __construct() {
        parent::__construct();
    }

    //获取所有书的图片
    public function getReadBooksInfo() {
        $books = M('read_books')->select();
        $res = array();
        foreach ($books as $k => $v) {
            $res[$k]['id'] = $v['id'];
            $res[$k]['img_url'] = $v['img_url'];
        }
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

    /*
     * 获取对应的书及题
     * @param   string    $id   书ID
     */
    public function getReadTiInfo() {
        $id = $_REQUEST['id'];
        $res = array();
        $book = M('read_books')->where('id=' . $id)->find();
        $res['book']['id'] = $book['id'];
        $res['book']['word_num'] = $book['word_num'];
//        $res['book']['content'] = file_get_contents($book['content_url']);
        $res['book']['content'] = $book['content_url'];
        $res['ti'] = M('read_ti')->where("bid=" . $id)->select();
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

}
