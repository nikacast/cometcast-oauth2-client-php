<?php

namespace CometCast\Oauth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class CometCastResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected array $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->getValueByKey($this->response['data'], 'id');
    }


    public function getFullName()
    {
        return $this->getValueByKey($this->response['data'], 'full_name');
    }

    public function toArray(): array
    {
        return $this->response;
    }
}