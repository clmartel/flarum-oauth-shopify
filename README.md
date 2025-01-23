This is an extension for Flarum that will allow users to authenticate using a Shopify store as their identity provider.  

This extension utlizes the flows described at:
 * https://www.shopify.com/partners/blog/introducing-customer-account-api-for-headless-stores
 * https://shopify.dev/docs/api/customer

We first attempted this using the https://flarum.org/extension/blt950/oauth-generic extension, but Shopify does not provide a typical user info endpoint for customers, so we made our own extension which makes a GraphQL call to their Customer API.  The extension pulls 3 customer fields into Flarum- ID (the numeric, unique Shopify customer ID), e-mail address, and display name.

To configure the extension, you must enable the "Headless" app in your Spotify store and configure the Customer API.

![Shopify Headless storefront](https://github.com/clmartel/flarum-oauth-shopify/blob/main/doc/Screenshot1.jpg?raw=true)

The Client ID can be found on the Customer Account API screen

![Client ID reference](https://github.com/clmartel/flarum-oauth-shopify/blob/main/doc/Screenshot2.jpg?raw=true)

The Store ID can be found in the endpoint links further down that page

![Store ID is highlighted](https://github.com/clmartel/flarum-oauth-shopify/blob/main/doc/Screenshot3.jpg?raw=true)


