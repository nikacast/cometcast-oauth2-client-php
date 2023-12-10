<?php

namespace CometCast\Oauth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class CometCast extends AbstractProvider
{
    protected $baseUrl = "https://cometcast.live";

    protected $openApiAuthBaseUrl = "https://openapi-oauth.cometcast.live";
    protected $openApiBaseUrl = "https://openapi.cometcast.live";


    public function getBaseAuthorizationUrl(): string
    {
        // TODO: Implement getBaseAuthorizationUrl() method.
        return "{$this->baseUrl}/member/oauth/authorize";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return "{$this->openApiAuthBaseUrl}/oauth/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
        return "{$this->openApiBaseUrl}/v1/user/info";
    }

    protected function getDefaultScopes()
    {
        // TODO: Implement getDefaultScopes() method.
        return ["user"];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
        if (isset($data['error'])) {
            $statusCode = $response->getStatusCode();
            $error = $data['error'];
            $errorMsg = $data['message'];
            throw new IdentityProviderException(
                "{$error} HTTP Response Code: {$statusCode} Message: {$errorMsg}",
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
        return new CometCastResourceOwner($response);
    }
}