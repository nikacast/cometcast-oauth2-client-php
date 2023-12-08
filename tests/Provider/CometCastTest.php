<?php

namespace CometCast\Oauth2\Client\Tests\Provider;

use CometCast\Oauth2\Client\Provider\CometCast;
use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CometCastTest extends TestCase
{
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new CometCast([
            'clientId'      => 'mock_client_id',
            'clientSecret'  => 'mock_client_secret',
            'redirectUri'   => 'mock_redirect_uri'
        ]);
    }


    public function test_getBaseAuthorizationUrl()
    {
        $this->assertEquals(
            "https://cometcast.live/member/oauth/authorize",
            $this->provider->getBaseAuthorizationUrl()
        );
    }

    public function test_getBaseAccessTokenUrl()
    {
        $this->assertEquals(
            "https://openapi.cometcast.live/oauth/token",
            $this->provider->getBaseAccessTokenUrl([])
        );
    }

    public function test_getResourceOwnerDetailsUrl()
    {
        $accessToken = \Mockery::mock(AccessToken::class);

        $this->assertEquals(
            "https://openapi.cometcast.live/v1/user/info",
            $this->provider->getResourceOwnerDetailsUrl($accessToken)
        );
    }

    public function test_getAccessToken()
    {
        $response = \Mockery::mock(ResponseInterface::class);

        $response->shouldReceive('getHeader')
            ->times(1)
            ->andReturn(['application/json']);

        $streamInterface = \Mockery::mock(StreamInterface::class);


        $streamInterface->shouldReceive('__toString')
            ->andReturn('{"access_token":"mock_access_token","refresh_token":"mock_refresh_token","token_type":"bearer","expires_in":3600,"host":"mock_host"}');

        $response->shouldReceive('getBody')
            ->times(1)
            ->andReturn($streamInterface);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertNull($token->getResourceOwnerId(), 'CometCast does not return user ID with access token. Expected null.');
    }
}