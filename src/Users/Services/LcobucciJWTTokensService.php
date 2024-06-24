<?php

namespace EcomDemo\Users\Services;

use DateTimeImmutable;
use EcomDemo\Users\Services\Contracts\JWTToken;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Str;

class LcobucciJWTTokensService implements JWTTokensService
{
    /**
     * @var string
     */
    protected string $privateKey;

    /**
     * @var string
     */
    protected string $publicKey;

    /**
     * @var string
     */
    protected string $passPhrase;

    /**
     * @param string $privateKey
     * @param string $publicKey
     * @param string $passPhrase
     */
    public function __construct(string $privateKey, string $publicKey, string $passPhrase)
    {
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        $this->passPhrase = $passPhrase;
    }

    /**
     * @inheritDoc
     */
    public function getFreshTokenFor(string $uuid, string $tokenName): JWTToken
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $signingKey   = InMemory::file($this->privateKey, $this->passPhrase);
        $algorithm    = new Sha256();
        $now          = new DateTimeImmutable();
        $validFor     = config(sprintf('jwt.%s_expiry', $tokenName));

        $token = $tokenBuilder->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->relatedTo($tokenName)
            ->issuedAt($now)
            ->expiresAt($now->modify(sprintf('+%s', $validFor)))
            ->withClaim('issued_for', $uuid)
            ->withClaim('uuid', Str::orderedUuid())
            ->getToken($algorithm, $signingKey);

        return new LcobucciJWTToken($token);
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function parse(string $jwtToken, string $tokenName): ?JWTToken
    {
        $configuration = $this->getConfigurations();
        $parsed        = $configuration->parser()->parse($jwtToken);
        $token         = new LcobucciJWTToken($parsed);

        try {
            $constraints   = [
                new SignedWith($configuration->signer(), $configuration->verificationKey()),
                new IssuedBy(config('app.url')),
                new RelatedTo($tokenName),
            ];

            if (!$configuration->validator()->validate($parsed, ...$constraints)) {
                return null;
            }

            return $token;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @return Configuration
     */
    protected function getConfigurations(): Configuration
    {
        $publicKey = InMemory::plainText(file_get_contents($this->publicKey));

        return Configuration::forAsymmetricSigner(new Sha256(), $publicKey, $publicKey);
    }
}
