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

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class ShopifyResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }


    /**
     * Get resource owner id.
     *
     * @return string|null
     */
    public function getId()
    {
        $iduri = $this->response['data']['customer']['id'];
        $idpieces = explode('/',$iduri);
        return $idpieces[count($idpieces) - 1];
    }

    /**
     * Get resource owner name.
     *
     * @return string|null
     */
    public function getName()
    {
	return $this->response['data']['customer']['displayName'];
    }

    /**
     * Get resource owner email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
	return $this->response['data']['customer']['emailAddress']['emailAddress'];
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
