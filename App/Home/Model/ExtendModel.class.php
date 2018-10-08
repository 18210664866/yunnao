<?php

/* 视幅扩展训练业务模型
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Model;

use Think\Model;

class ExtendModel extends Model {

    protected $backMsg = array("response" => 0, "content" => '');
    protected $tableName = 'extend_category';

    public function __construct() {
        parent::__construct();
    }

    //获取所有分类
    public function getExtendCategoryInfo() {
        $category = M('extend_category')->select();
        $res = array();
        foreach ($category as $k => $v) {
            $res[$k]['id'] = $v['id'];
            $res[$k]['name'] = $v['name'];
            $res[$k]['pid'] = $v['pid'];
        }
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

    //获取参数设置
    public function getExtendSettingInfo() {
        $setting = M('extend_setting')->select();
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
    
    //获取长度设置
    public function getExtendLengthInfo() {
        $length = M('extend_length')->select();
        $res = array();
        foreach ($length as $k => $v) {
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
    
    //获取图片资源
    public function getExtendImgInfo() {
        $img = M('extend_img_resource')->select();
        $res = array();
        foreach ($img as $k => $v) {
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
     * 获取文字资源
     * @param   string    $lid   长度设置ID
     * @param   string    $type  类型（1：中文，2：英文）
     */
    public function getExtendTextInfo() {
        $where = $res = array();
        $where['lid'] = $_REQUEST['lid'];
        $where['type'] = $_REQUEST['type'];
        $text = M('extend_text_resource')->where($where)->select();
        foreach ($text as $k => $v) {
            $res[$k]['id'] = $v['id'];
            $res[$k]['text'] = $v['text'];
        }
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }
    
    //获取短文资源
    public function getExtendEssayInfo() {
        $essay = M('extend_essay_resource')->select();
        $key = array_rand($essay);
        $res = array();
        $res['id'] = $essay[$key]['id'];
        $res['essay_url'] = $essay[$key]['essay_url'];
        if ($res) {
            $this->backMsg['response'] = 1;
            $this->backMsg['content'] = $res;
        } else {
            $this->backMsg['content'] = "服务器繁忙,请稍后重试";
        }
        return $this->backMsg;
    }

}
