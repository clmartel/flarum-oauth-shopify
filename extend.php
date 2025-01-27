<?php

/*
 * This file is part of clmartel/oauth-shopify.
 *
 * Copyright (c) clmartel.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace clmartel\OauthShopify;

use Flarum\Extend;
use FoF\OAuth\Extend as OAuthExtend;

return [
    (new Extend\Frontend('forum'))
        ->css(__DIR__.'/less/forum.less')
        ->js(__DIR__.'/js/dist/forum.js'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new OAuthExtend\RegisterProvider(Providers\Shopify::class)),

    (new Extend\Routes('forum'))
    ->get('/auth/shopify/logout', 'auth.shopify.logout', Controllers\ShopifyLogoutController::class),

];
