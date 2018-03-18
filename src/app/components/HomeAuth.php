<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class HomeAuth
{
    private $token;
    protected $cache;
    private $validator;

    public function __construct()
    {
        $this->cache = new HomeCache;
        $this->signer = new Sha256();
        $this->validator = new ValidationData();
    }

    /**
     * Creates the JWT and sets all the claims and signs it.
     * @throws Exception
     */
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

    /**
     * Stores the token in the cache. We create two values in cache, one keyed by the randomly
     * generated token id, the other keyed by the tokens user id. This will be used for future validation.
     */
    private function storeToken()
    {
        $this->cache->setHash("token-{$this->token->getClaim('jti')}", [
            "userId" => $this->user->id,
            "expires" => $this->token->getClaim('exp')
        ]);

        $this->cache->set("token-{$this->user->id}", true);
    }

    /**
     * This returns the newly created token to the requester.
     * @param $user
     * @return string
     * @throws Exception
     */
    public function getToken(User $user){
        $this->user = $user;
        $this->createToken();
        $this->storeToken();
        return (string)$this->token;
    }

    /**
     * This does the initial token validation, it checks the tokens claims, if it passes validation, then we run
     * the verify method to verify the signer.
     * @param $token
     * @return bool
     */
    public function validateToken($token)
    {
        $this->validator->setIssuer("Sam Ringleman");
        $this->token = (new Parser)->parse($token);
        return $this->token->validate($this->validator) ? $this->verifyToken() : $this->token->validate($this->validator);
    }

    /**
     * This verifies the tokens signer.
     * @return mixed
     */
    private function verifyToken()
    {
        return $this->token->verify($this->signer, 'testers');
    }

    /**
     * This allows us to grab the token information from the cache where we store user info.
     * @return array
     */
    public function getTokenFromCache()
    {
        return $this->cache->getHash("token-{$this->token->getClaim('jti')}");
    }

}