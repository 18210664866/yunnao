<?php
namespace Common\Model;

use Think\Model;

class RedisModel extends Model {
    public $redis;
    public static $_CATEGORYBOOKS = 1; //分类对应的书


    public function __construct(){
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1',6379);
    }
    
    //更新redis指定库数据
    public function changeRedisData($set_data,$db){
        $this->redis->select($db);
        $this->delRedisDb($db); //删除原有数据
        $this->redis->mset($set_data);
    }
    
    //清除redis指定库数据
    public function delRedisDb($db){
        $this->redis->select($db);
        $keys = $this->redis->keys('*');
        foreach($keys as $_key){
            $this->redis->del($_key);
        }
    }
    
    //查找redis数据
    public function findRedisData($map,$db){
        $this->redis->select($db);
        $all_key = $this->redis->keys($map);
        return $this->disposeData($all_key);
    }
    
    //处理返回数据 json_decode
    private function disposeData($all_key){
        $rtn = array();
        if(!$all_key){
            return $rtn;
        }elseif(!is_array($all_key)){
            $value = $this->redis->get($all_key);
            $rtn = json_decode($value,true);
        }else{
            $all_value = $this->redis->mget($all_key);
            foreach($all_value as $one_json){
                $rtn[] = json_decode($one_json,true);
            }
        }
        return $rtn;
    }
}