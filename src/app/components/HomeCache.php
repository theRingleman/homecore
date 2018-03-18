<?php

class HomeCache
{

    private $redis;

    public function __construct()
    {
        try {
            $this->redis = new Predis\Client([
                "scheme" => "tcp",
                "host" => "homecache",
                "port" => 6379
            ]);
        } catch(Exception $e){
            echo "Redis did not initialize correctly";
        }
    }

    public function set($key, $value)
    {
        $this->redis->set($key, $value);
    }

    public function get($key)
    {
        $this->redis->get($key);
    }

    public function exists($key)
    {
        $this->redis->exist($key);
    }

    public function setHash($title, $hash)
    {
        $this->redis->hmset($title, $hash);
    }

    public function getHash($title)
    {
       return $this->redis->hgetall($title);
    }

}