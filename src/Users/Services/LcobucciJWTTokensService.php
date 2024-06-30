<?php

namespace EcomDemo\Users\Services;

use DateTimeImmutable;
use EcomDemo\Users\Services\Contracts\JWTToken;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use Exception;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

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
     * @var ConfigRepository
     */
    protected ConfigRepository $configRepository;

    /**
     * @param string $privateKey
     * @param string $publicKey
     * @param ConfigRepository $configRepository
     */
    public function __construct(string $privateKey, string $publicKey, ConfigRepository $configRepository)
    {
        $this->privateKey       = $privateKey;
        $this->publicKey        = $publicKey;
        $this->configRepository = $configRepository;
    }

    /**
     * @inheritDoc
     */
    public function getFreshTokenFor(string $uuid, string $tokenName): JWTToken
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $signingKey   = InMemory::file($this->privateKey, $this->configRepository->get('jwt.pass_phrase'));
        $algorithm    = new Sha256();
        $now          = new DateTimeImmutable();
        $validFor     = $this->configRepository->get(sprintf('jwt.%s_expiry', $tokenName));

        $token = $tokenBuilder->issuedBy($this->configRepository->get('app.url'))
            ->permittedFor($this->configRepository->get('app.url'))
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

        try {
            $parsed = $configuration->parser()->parse($jwtToken);
            $token  = new LcobucciJWTToken($parsed);

            $constraints   = [
                new SignedWith($configuration->signer(), $configuration->verificationKey()),
                new IssuedBy($this->configRepository->get('app.url')),
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
