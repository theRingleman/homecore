<?php

class HomeCache
{

    private $redis;

    public function __construct()
    {
        try {
            $this->redis = new Predis\Client([
                "scheme" => "tcp",
                "host" => "homecacse",
                "port" => 6379
            ]);
        } catch(Exception $e){
            echo "Redis did not initialize correctly";
        }
    }

}