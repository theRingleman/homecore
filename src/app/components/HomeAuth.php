<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class HomeAuth
{
    private $token;
    protected $cache;

    public function __construct()
    {
        $this->cache = new HomeCache;
        $this->signer = new Sha256();
    }

    public function createToken()
    {
        $token =  (new Builder)
            ->setIssuer("HomeCore")
            ->setId(Helpers::getToken(16))
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->sign($this->signer, "testers")
            ->getToken();
        $this->token = $token;
    }

    private function storeToken()
    {
        $this->cache->setHash("token-{$this->token->getClaim('jti')}", [
            "userId" => $this->user->id,
            "expires" => $this->token->getClaim('exp')
        ]);

        $this->cache->set();
    }

    public function getToken($user){
        $this->user = $user;
        $this->createToken();
        $this->storeToken();
        return (string)$this->token;
    }

    public function validate($token)
    {
        $token = (new Parser)->parse($token);
        if ($token->verify($this->signer, "testers")) {
           $this->token = $token;
           return true;
        } else {
            return false;
        }
    }

    public function getTokenFromCache()
    {
        return $this->cache->getHash("token-{$this->token->getClaim('jti')}");
    }

}