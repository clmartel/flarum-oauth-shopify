(()=>{var o={n:t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return o.d(e,{a:e}),e},d:(t,e)=>{for(var r in e)o.o(e,r)&&!o.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},o:(o,t)=>Object.prototype.hasOwnProperty.call(o,t)};(()=>{"use strict";const t=flarum.core.compat["forum/app"];var e=o.n(t);const r=flarum.core.compat.extend,n=flarum.core.compat["common/Session"];var a=o.n(n);const i=flarum.core.compat["common/utils/Stream"];var c=o.n(i);const m=flarum.core.compat["forum/components/SignUpModal"];var u=o.n(m);e().initializers.add("clmartel/oauth-shopify",(function(){(0,r.override)(a().prototype,"logout",(function(){var o=encodeURIComponent(e().forum.attribute("baseUrl")+"/auth/shopify/logout");window.location=e().forum.attribute("baseUrl")+"/logout?token="+this.csrfToken+"&return="+o})),(0,r.extend)(u().prototype,"oninit",(function(){"nickname"===e().forum.attribute("displayNameDriver")&&(this.nickname=c()(this.attrs.nickname||this.attrs.username||""))}))}),-90)})(),module.exports={}})();
//# sourceMappingURL=forum.js.map