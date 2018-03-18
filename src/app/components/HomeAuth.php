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

    /**
     * HomeAuth constructor.
     */
    public function __construct()
    {
        $this->cache = new HomeCache;
        $this->signer = new Sha256();
        $this->validator = new ValidationData();
    }

    /**
     * Creates the JWT and sets all the claims and signs it.
     *
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
     *
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
     *
     * @param $token
     * @return bool
     */
    public function validateToken($token)
    {
        $this->validator->setIssuer("Sam Ringleman");
        $this->token = (new Parser)->parse($token);
        if ($this->validateTokenCache()) {
            if ($this->token->validate($this->validator)) {
                return $this->verifyToken();
            }
        }else {
            // I realize that this is redundant, however I want to make sure that
            // we set the second token to false.
            $this->deleteCachedTokens();
            return false;
        }
    }


    /**
     * This verifies the tokens signer.
     *
     * @return mixed
     */
    private function verifyToken()
    {
        if ($this->token->verify($this->signer, 'testers')) {
            return true;
        } else {
            $this->deleteCachedTokens();
            return false;
        }
    }

    /**
     * Simple check to make sure we have the token in cache, we want to fail quick if it is not there.
     *
     * @return bool
     */
    private function validateTokenCache()
    {
        return !is_null($this->getTokenFromCache()) ? true : false;
    }

    /**
     * This allows us to grab the token information from the cache where we store user info.
     *
     * @return array
     */
    public function getTokenFromCache()
    {
        return $this->cache->getHash("token-{$this->token->getClaim('jti')}");
    }

    /**
     * This sets the cached item by user id to false when verification or validation fails, and it deletes the
     * cached item that is keyed by token id.
     *
     * @return int
     */
    private function deleteCachedTokens()
    {
        $this->cache->set("token-{$this->user->id}", false);
        return $this->cache->delete("token-{$this->token->getClaim('jti')}");
    }

}