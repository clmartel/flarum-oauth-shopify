<?php

/*
 * This file is part of fof/oauth.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace clmartel\OauthShopify\Controllers;

use Flarum\Http\Exception\RouteNotFoundException;
use FoF\OAuth\Controllers\AuthController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use Laminas\Diactoros\Response\RedirectResponse;

class ShopifyLogoutController extends AuthController
{
    /**
     * @var ?Provider
     */
    protected $provider;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = 'shopify';
        $providers = resolve('container')->tagged('fof-oauth.providers');

        foreach ($providers as $provider) {
            if ($provider->name() === $name) {
                if ($provider->enabled()) {
                    $this->provider = $provider;
                }

                break;
            }
        }

        $siteUrl = app('flarum.config')['url'];

        $shopifyProvider = $provider->provider($siteUrl.'auth/shopify/logout');

        if (!is_null($request->getQueryParams()) && array_key_exists('code', $request->getQueryParams())) {
            $options = array('code' => $request->getQueryParams()['code']);

            $token = $shopifyProvider->getAccessToken('authorization_code', $options);
    
            $idToken = $token->getValues()['id_token'];
            $logoutUrl = $shopifyProvider->getLogoutUrl() . "?id_token_hint=" . $idToken . '&post_logout_redirect_uri=' . urlencode($siteUrl);
             
            return new RedirectResponse($logoutUrl);
        }
        else {
            return new RedirectResponse($shopifyProvider->getAuthorizationUrl());
        }
    }
}