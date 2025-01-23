import app from 'flarum/admin/app';
import { ConfigureWithOAuthPage } from '@fof-oauth';

app.initializers.add('clmartel/oauth-shopify', () => {
  app.extensionData.for('clmartel-oauth-shopify').registerPage(ConfigureWithOAuthPage);
});
