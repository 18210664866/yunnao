<?php

/* 课堂训练业务模型
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Model;

use Think\Model;

class ClassModel extends Model {

    protected $backMsg = array("response" => 0, "content" => '');
    protected $tableName = 'class_books';

    public function __construct() {
        parent::__construct();
    }

    //获取分类以及第一次进页面需要展示的书
    public function getClassCategoryInfo() {
        //获取分类
        $res['category'] = M("class_category")->select();
        $cid = current(reset($res))['id'];
        //根据CID获取对应的书
        $res['books'] = $this->getBooks($cid);
        if ($res['category'] && $res['books']) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

    //获取分类对应的书
    public function getClassBooksInfo() {
        $cid = $_REQUEST['cid'];
        $res = $this->getBooks($cid);
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

    //根据CID获取对应的书
    public function getBooks($cid = "") {
        if($cid){
            $books = M("class_books")->where("cid=" . $cid)->select();
            $res = array();
            foreach ($books as $k => $v) {
                $res[$k]['id'] = $v['id'];
                $res[$k]['img_url'] = $v['img_url'];
            }
            return $res;
        }else{
            return false;
        }
    }
    
    //获取书的内容
    public function getBooksContentInfo() {
        $id = $_REQUEST['id'];
        $book = M('class_books')->where('id=' . $id)->find();
        $res = array();
        $res['word_num'] = $book['word_num'];
//        $res['content'] = file_get_contents($book['content_url']);
        $res['content'] = $book['content_url'];
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }
    
    //获取阅读速度
    public function getClassReadSpeedInfo() {
        $speed = M('class_read_speed')->select();
        $res = array();
        foreach ($speed as $k => $v) {
            $res[$k]['id'] = $v['id'];
            $res[$k]['name'] = $v['name'];
        }
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }
    
    //获取阅读速度
    public function getClassSettingInfo() {
        $setting = M('class_setting')->select();
        $res = array();
        foreach ($setting as $k => $v) {
            $res[$k]['id'] = $v['id'];
            $res[$k]['name'] = $v['name'];
            $res[$k]['type'] = $v['type'];
        }
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }
    
    

}
