import app from 'flarum/forum/app';
import { extend, override } from 'flarum/extend';
import Session from 'flarum/common/Session';
import Stream from 'flarum/common/utils/Stream';
import SignUpModal from 'flarum/forum/components/SignUpModal';

app.initializers.add('clmartel/oauth-shopify', () => {
  override(Session.prototype, 'logout', function () {
    var redirectUri = encodeURIComponent(app.forum.attribute('baseUrl') + '/auth/shopify/logout');
    window.location = `${app.forum.attribute('baseUrl')}/logout?token=${this.csrfToken}&return=${redirectUri}`;
  });

  /*
  This is a hack to override the bug described at https://github.com/flarum/framework/pull/4004
  It can be removed if the pull request above has been merged into your flarum version
  */
  extend(SignUpModal.prototype, 'oninit', function () {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    this.nickname = Stream(this.attrs.nickname || this.attrs.username || '');
  });
}, -90);
