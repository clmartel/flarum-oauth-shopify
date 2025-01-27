<?php

/*
 * This file is part of clmartel/oauth-shopify.
 *
 * Copyright (c) clmartel.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace clmartel\OauthShopify\Providers;

use InvalidArgumentException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

/**
 * Represents the Shopify OAuth flow and customer API call described here:
 * https://www.shopify.com/partners/blog/introducing-customer-account-api-for-headless-stores
 * https://shopify.dev/docs/api/customer
 */
class ShopifyProvider extends AbstractProvider
{
    /**
     * @var string
     */
    private $shopId;

    /**
     * @var string
     */
    private $responseError = 'error';

    /**
     * @var string
     */
    private $responseCode;

    /**
     * @var string
     */
    private $responseResourceOwnerId = 'id';

    /**
     * @var string|null
     */
    private $pkceMethod = null;

    /**
     * @var string
    */
    //private $userId;

    /**
     * @var string
    */
    //private $userDisplayName;

    /**
     * @var string
    */
    //private $userEmailAddress;

    /**
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->assertRequiredOptions($options);

        $possible   = $this->getConfigurableOptions();
        $configured = array_intersect_key($options, array_flip($possible));

        foreach ($configured as $key => $value) {
            $this->$key = $value;
        }

        // Remove all options that are only used locally
        $options = array_diff_key($options, $configured);

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns all options that can be configured.
     *
     * @return array
     */
    protected function getConfigurableOptions()
    {
        return array_merge($this->getRequiredOptions(), [
            'accessTokenMethod',
            'accessTokenResourceOwnerId',
            'scopeSeparator',
            'responseError',
            'responseCode',
            'responseResourceOwnerId',
            'scopes',
            'pkceMethod',
        ]);
    }

    /**
     * Returns all options that are required.
     *
     * @return array
     */
    protected function getRequiredOptions()
    {
        return [
            //'urlAuthorize',
            //'urlAccessToken',
            //'urlResourceOwnerDetails',
            'shopId',
            //'userId',
            //'userDisplayName',
            //'userEmailAddress',
        ];
    }

    /**
     * Verifies that all required options have been passed.
     *
     * @param  array $options
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertRequiredOptions(array $options)
    {
        $missing = array_diff_key(array_flip($this->getRequiredOptions()), $options);

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                'Required options not defined: ' . implode(', ', array_keys($missing))
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://shopify.com/authentication/'.$this->shopId.'/oauth/authorize';
    }

    /**
     * @inheritdoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://shopify.com/authentication/'.$this->shopId.'/oauth/token';
    }

    /**
     * @inheritdoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://shopify.com/'.$this->shopId.'/account/customer/api/2024-10/graphql'; 
    }

    public function getLogoutUrl() {
        return 'https://shopify.com/authentication/'.$this->shopId.'/logout';
    }

    /**
     * @inheritdoc
     */
    public function getDefaultScopes()
    {
        return 'openid email customer-account-api:full';
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return ['Authorization' => "{$token}"];
    }

    /**
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data[$this->responseError])) {
            $error = $data[$this->responseError];
            if (!is_string($error)) {
                $error = var_export($error, true);
            }
            $code  = $this->responseCode && !empty($data[$this->responseCode])? $data[$this->responseCode] : 0;
            if (!is_int($code)) {
                $code = intval($code);
            }
            throw new IdentityProviderException($error, $code, $data);
        }
    }

    /**
     * @inheritdoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ShopifyResourceOwner($response);
    }

    protected function fetchResourceOwnerDetails(AccessToken $token)
    {
        $url = $this->getResourceOwnerDetailsUrl($token);

	//GRAPHQL request
	$query = <<<'JSON'
{
  customer
  {
    id
    displayName
    emailAddress {
      emailAddress
    }
  }
}
JSON;
	$variables = '';

	$graphql = json_encode(['query' => $query, 'variables' => $variables]);

	$options = array(
          'body' => $graphql,
          'headers' => array( 'Content-Type' => "application/json")
        );

        $request = $this->getAuthenticatedRequest(self::METHOD_POST, $url, $token, $options);

        $response = $this->getParsedResponse($request);

        if (false === is_array($response)) {
            throw new UnexpectedValueException(
                'Invalid response received from Authorization Server. Expected JSON: '
            );
        }

        return $response;
    }
}
