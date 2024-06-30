<?php

namespace Tests\Unit\Users\Services;

use Dotenv\Dotenv;
use EcomDemo\Users\Services\LcobucciJWTTokensService;
use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class LcobucciJWTTokensServiceTest extends TestCase
{
    /**
     * @var Repository
     */
    protected Repository $config;

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../', '.env.testing');
        $dotenv->load();

        $this->config = new Repository($_ENV);

        $this->config->set('app.url', $this->config->get('APP_URL'));
        $this->config->set('jwt.pass_phrase', $this->config->get('JWT_PASSPHRASE'));
        $this->config->set('jwt.access_token_expiry', $this->config->get('JWT_ACCESS_TOKEN_EXPIRY'));
    }

    public function test_get_fresh_token_for()
    {
        $uuid      = Str::orderedUuid()->toString();
        $tokenName = 'access_token';

        $tokenService = new LcobucciJWTTokensService('storage/keys/private_key_test.pem', 'storage/keys/public_key_test.pem', $this->config);

        $token = $tokenService->getFreshTokenFor($uuid, $tokenName);

        $this->assertSame($uuid, $token->getIssuedFor());
        $this->assertSame($tokenName, $token->getType());
    }

    public function test_parse_valid_token()
    {
        $uuid         = Str::orderedUuid()->toString();
        $tokenName    = 'access_token';
        $tokenService = new LcobucciJWTTokensService('storage/keys/private_key_test.pem', 'storage/keys/public_key_test.pem', $this->config);

        $jwtToken  = $tokenService->getFreshTokenFor($uuid, $tokenName)->getToken();

        $parsedToken = $tokenService->parse($jwtToken, $tokenName);

        $this->assertSame($jwtToken, $parsedToken->getToken());
        $this->assertSame($tokenName, $parsedToken->getType());
    }

    public function test_parse_invalid_token_returns_null()
    {
        $jwtToken  = 'mock.jwt.token';
        $tokenName = 'access_token';

        $tokenService = new LcobucciJWTTokensService('storage/keys/private_key_test.pem', 'storage/keys/public_key_test.pem', $this->config);

        $parsedToken = $tokenService->parse($jwtToken, $tokenName);

        $this->assertNull($parsedToken);
    }
}
