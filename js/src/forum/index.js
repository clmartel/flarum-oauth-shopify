import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import Stream from 'flarum/common/utils/Stream';
import SignUpModal from 'flarum/forum/components/SignUpModal';

/*

  This is a hack to override the bug described at https://github.com/flarum/framework/pull/4004
  It can be removed if the pull request above has been merged into your flarum version

*/
app.initializers.add('clmartel/oauth-shopify', () => {
  extend(SignUpModal.prototype, 'oninit', function () {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    this.nickname = Stream(this.attrs.nickname || this.attrs.username || '');
  });
}, -90);

