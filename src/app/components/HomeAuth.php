<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class HomeAuth
{
    private $user;
    private $token;
    protected $cache;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->cache = new HomeCache;
    }

    public function createToken()
    {
        $signer = new Sha256();
        $token =  (new Builder)
            ->setIssuer("HomeCore")
            ->setId(Helpers::getToken(16))
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->sign($signer, "testers")
            ->getToken();
        $this->token = $token;
    }

    private function storeToken()
    {
        $this->cache->set("token-{$this->token->getClaim('jti')}", [
            "userId" => $this->user->id,
            "expires" => $this->token->getClaim('exp')
        ]);
    }

    public function getToken(){
        $this->createToken();
        $this->storeToken();
        return (string)$this->token;
    }

    public function validate()
    {

    }

}