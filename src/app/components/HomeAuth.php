<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class HomeAuth
{
    private $user;
    private $token;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = $this->setToken();
    }

    public function setToken()
    {
        $signer = new Sha256();
        return (new Builder)
            ->setIssuer("HomeCore")
            ->setId("1")
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->set('uid', $this->user->id)
            ->sign($signer, "testers")
            ->getToken();
    }

    public function getToken(){
        $cache = new HomeCache;
        return (string)$this->token;
    }

    public function validate()
    {

    }

}