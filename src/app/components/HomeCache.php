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

    /**
     * Set's a base key value pair.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->redis->set($key, $value);
    }

    /**
     * Get's a base key value pair.
     *
     * @param $key
     */
    public function get($key)
    {
        $this->redis->get($key);
    }

    /**
     * Confirms whether or not an entry exists.
     *
     * @param $key
     */
    public function exists($key)
    {
        $this->redis->exists($key);
    }

    /**
     * Sets a hash.
     *
     * @param $key
     * @param $value
     */
    public function setHash($key, array $value)
    {
        $this->redis->hmset($key, $value);
    }

    /**
     * Returns a hash by its respective key.
     *
     * @param $key
     * @return array
     */
    public function getHash($key)
    {
       return $this->redis->hgetall($key);
    }

    /**
     * Set's the items expiration, must be in seconds
     *
     * @param $key
     * @param $seconds
     * @throws Exception
     */
    public function expire($key, $seconds)
    {
        if (!is_int($seconds)) {
            throw new Exception("Seconds must be of type int");
        }
        $this->redis->expire($key, $seconds);
    }

    /**
     * Set's expiration for a future date, takes a unix timestamp.
     * @param $key
     * @param $timestamp
     */
    public function expireAt($key, $timestamp)
    {
        $this->redis->expire($key, $timestamp);
    }

    /**
     * Deletes an item from cache.
     * @param $key
     */
    public function delete($key)
    {
        return $this->redis->del($key);
    }
}