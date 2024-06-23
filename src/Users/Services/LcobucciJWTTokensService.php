<?php

namespace EcomDemo\Users\Services;

use Carbon\Carbon;
use DateTimeImmutable;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\RegisteredClaims;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

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
    public function getFreshTokenFor(string $uuid, string $tokenName): string
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
            ->withClaim('uuid', $uuid)
            ->getToken($algorithm, $signingKey);

        return $token->toString();
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function getClaimFrom(string $jwtToken): string
    {
        $token = $this->getConfigurations()->parser()->parse($jwtToken);

        return $token->claims()->get('uuid');
    }

    /**
     * @return Configuration
     */
    protected function getConfigurations(): Configuration
    {
        $publicKey = InMemory::plainText(file_get_contents($this->publicKey));

        return Configuration::forAsymmetricSigner(new Sha256(), $publicKey, $publicKey);
    }

    /**
     * @inheritDoc
     */
    public function validate(string $jwtToken): bool
    {
        $configuration = $this->getConfigurations();
        $constraints   = [
            new SignedWith($configuration->signer(), $configuration->verificationKey()),
            new StrictValidAt(SystemClock::fromUTC()),
            new IssuedBy(config('app.url'))
        ];

        $token = $configuration->parser()->parse($jwtToken);

        if (!$configuration->validator()->validate($token, ...$constraints)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getExpiry(string $jwtToken): ?Carbon
    {
        $parser    = new Parser(new JoseEncoder());
        $token     = $parser->parse($jwtToken);
        $expiresAt = $token->claims()->get(RegisteredClaims::EXPIRATION_TIME);

        return $expiresAt ? Carbon::parse($expiresAt) : null;
    }
}
