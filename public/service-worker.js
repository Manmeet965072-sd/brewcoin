/**
 * Copyright 2018 Google Inc. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *     http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// If the loader is already loaded, just stop.
if (!self.define) {
  let registry = {};

  // Used for `eval` and `importScripts` where we can't get script URL by other means.
  // In both cases, it's safe to use a global var because those functions are synchronous.
  let nextDefineUri;

  const singleRequire = (uri, parentUri) => {
    uri = new URL(uri + ".js", parentUri).href;
    return registry[uri] || (
      
        new Promise(resolve => {
          if ("document" in self) {
            const script = document.createElement("script");
            script.src = uri;
            script.onload = resolve;
            document.head.appendChild(script);
          } else {
            nextDefineUri = uri;
            importScripts(uri);
            resolve();
          }
        })
      
      .then(() => {
        let promise = registry[uri];
        if (!promise) {
          throw new Error(`Module ${uri} didn’t register its module`);
        }
        return promise;
      })
    );
  };

  self.define = (depsNames, factory) => {
    const uri = nextDefineUri || ("document" in self ? document.currentScript.src : "") || location.href;
    if (registry[uri]) {
      // Module is already loading or loaded.
      return;
    }
    let exports = {};
    const require = depUri => singleRequire(depUri, uri);
    const specialDeps = {
      module: { uri },
      exports,
      require
    };
    registry[uri] = Promise.all(depsNames.map(
      depName => specialDeps[depName] || require(depName)
    )).then(deps => {
      factory(...deps);
      return exports;
    });
  };
}
define(['./workbox-b7445681'], (function (workbox) { 'use strict';

  /**
  * Welcome to your Workbox-powered service worker!
  *
  * You'll need to register this file in your web app.
  * See https://goo.gl/nhQhGp
  *
  * The rest of the code is auto-generated. Please don't update this file
  * directly; instead, make changes to your Workbox build configuration
  * and re-run your build process.
  * See https://goo.gl/2aRDsh
  */

  self.skipWaiting();
  /**
   * The precacheAndRoute() method efficiently caches and responds to
   * requests for URLs in the manifest.
   * See https://goo.gl/S9QRab
   */

  workbox.precacheAndRoute([{
    "url": "/js/core/app-menu.js",
    "revision": "cff48047c2f8182393777f29aafe45b0"
  }, {
    "url": "/js/core/app.js",
    "revision": "98dfa3563a66876a85ed1f565865cb85"
  }, {
    "url": "/js/core/scripts.js",
    "revision": "99df4125bf62980c140b09703a9f0ab6"
  }, {
    "url": "/js/frontend/manifest.js",
    "revision": "828f256b98199237e737e71dfd71205d"
  }, {
    "url": "/js/frontend/vendor.js",
    "revision": "7382c011f4f9a8be7b3142d995db2d3e"
  }, {
    "url": "css/base/core/colors/palette-gradient.css",
    "revision": "66a5cb8c46ccc9a8e80ccf736477387f"
  }, {
    "url": "css/base/core/colors/palette-noui.css",
    "revision": "d2e14a269269e76bc0b2ca2287a06ab2"
  }, {
    "url": "css/base/core/colors/palette-variables.css",
    "revision": "68b329da9893e34099c7d8ad5cb9c940"
  }, {
    "url": "css/base/core/menu/menu-types/horizontal-menu.css",
    "revision": "1752097218186b7b0081c458b439c5a6"
  }, {
    "url": "css/base/core/menu/menu-types/vertical-menu.css",
    "revision": "8d272ab77092b3c81ca71558d6799dcc"
  }, {
    "url": "css/base/core/menu/menu-types/vertical-overlay-menu.css",
    "revision": "d2887bd1af10f3627e300b0d2de79508"
  }, {
    "url": "css/base/core/mixins/alert.css",
    "revision": "68b329da9893e34099c7d8ad5cb9c940"
  }, {
    "url": "css/base/core/mixins/hex2rgb.css",
    "revision": "68b329da9893e34099c7d8ad5cb9c940"
  }, {
    "url": "css/base/core/mixins/main-menu-mixin.css",
    "revision": "68b329da9893e34099c7d8ad5cb9c940"
  }, {
    "url": "css/base/core/mixins/transitions.css",
    "revision": "68b329da9893e34099c7d8ad5cb9c940"
  }, {
    "url": "css/base/pages/app-calendar.css",
    "revision": "11e4e32dbc7e2c9f80b7e438aa6061a6"
  }, {
    "url": "css/base/pages/app-chat-list.css",
    "revision": "6906df8ce02e8824b6e074491e846a53"
  }, {
    "url": "css/base/pages/app-chat.css",
    "revision": "f976481041b1ec34b24d68ba3bdb3d7a"
  }, {
    "url": "css/base/pages/app-ecommerce-details.css",
    "revision": "6806f8c031b7d5657a62f2e6c4e30a10"
  }, {
    "url": "css/base/pages/app-ecommerce.css",
    "revision": "f92e09b7c98b826fd1d8a41d47015ba3"
  }, {
    "url": "css/base/pages/app-email.css",
    "revision": "f39642740aae2585818df2f7b89e15d8"
  }, {
    "url": "css/base/pages/app-file-manager.css",
    "revision": "88fc3e11e60ce28758064dec66e623d7"
  }, {
    "url": "css/base/pages/app-invoice-list.css",
    "revision": "100a727835e3fae701fe87d270008c8a"
  }, {
    "url": "css/base/pages/app-invoice-print.css",
    "revision": "20ee25d970ffd636b5b79538820f8580"
  }, {
    "url": "css/base/pages/app-invoice.css",
    "revision": "4678477c50a136bc583fca2a9eec3ca7"
  }, {
    "url": "css/base/pages/app-kanban.css",
    "revision": "96c86b35e484142f4e47412b7c743b2f"
  }, {
    "url": "css/base/pages/app-todo.css",
    "revision": "6c3dc7b53f0118fcc98bbe0d8b87ed23"
  }, {
    "url": "css/base/pages/authentication.css",
    "revision": "5b704e4afac904d54ab058fac99c885c"
  }, {
    "url": "css/base/pages/dashboard-ecommerce.css",
    "revision": "16d99315cfcf3d250ff2422470280472"
  }, {
    "url": "css/base/pages/modal-create-app.css",
    "revision": "b4ee8bc1e587dc7c6727208ccfbe6ad3"
  }, {
    "url": "css/base/pages/page-blog.css",
    "revision": "63a2edd907d1f6e63e2387c03038de4e"
  }, {
    "url": "css/base/pages/page-coming-soon.css",
    "revision": "b2928901328ab4b3a7b99a420a796c8c"
  }, {
    "url": "css/base/pages/page-faq.css",
    "revision": "877dc1e5d09d27ccab55bff15d238d2d"
  }, {
    "url": "css/base/pages/page-knowledge-base.css",
    "revision": "7ccbee863dd3da45ac84d7ef1d5c6260"
  }, {
    "url": "css/base/pages/page-misc.css",
    "revision": "560310296bd2b009c86b62bbb48141f2"
  }, {
    "url": "css/base/pages/page-pricing.css",
    "revision": "bd36411559bd32b3fde5c2748c73c19e"
  }, {
    "url": "css/base/pages/page-profile.css",
    "revision": "58b8ea0dae09b2ac628ef6ed9f9e453e"
  }, {
    "url": "css/base/pages/ui-feather.css",
    "revision": "118707746ce2cd05cf0a804a38126d0b"
  }, {
    "url": "css/base/plugins/charts/chart-apex.css",
    "revision": "3d8a2eb1f1465f19c76dfa3155bc3e17"
  }, {
    "url": "css/base/plugins/extensions/ext-component-context-menu.css",
    "revision": "c5207cd278b216add174175d69c9e053"
  }, {
    "url": "css/base/plugins/extensions/ext-component-drag-drop.css",
    "revision": "86346d0d8d728d02459f48c8cf5e1821"
  }, {
    "url": "css/base/plugins/extensions/ext-component-media-player.css",
    "revision": "47662d3a19ee4a6c0b479bebbdb0e279"
  }, {
    "url": "css/base/plugins/extensions/ext-component-ratings.css",
    "revision": "60854964f671ce706342bf4e044e6fe9"
  }, {
    "url": "css/base/plugins/extensions/ext-component-sliders.css",
    "revision": "e89d9c2135616c1a2ae336014b8501cd"
  }, {
    "url": "css/base/plugins/extensions/ext-component-sweet-alerts.css",
    "revision": "7b32625108fe40ce8411271f55ce7e9b"
  }, {
    "url": "css/base/plugins/extensions/ext-component-swiper.css",
    "revision": "0a18794269d3589dde03ef0d48c0a717"
  }, {
    "url": "css/base/plugins/extensions/ext-component-toastr.css",
    "revision": "f3f0c5624b1c05a10910d56702e3b981"
  }, {
    "url": "css/base/plugins/extensions/ext-component-tour.css",
    "revision": "79bfbfa793f66678b5248b897fecc20b"
  }, {
    "url": "css/base/plugins/extensions/ext-component-tree.css",
    "revision": "d41c425b7a08a56d83fcdcd6dfae57aa"
  }, {
    "url": "css/base/plugins/forms/form-file-uploader.css",
    "revision": "df132cc17afe602361c9b353e37e9623"
  }, {
    "url": "css/base/plugins/forms/form-number-input.css",
    "revision": "b44da50f8b99c91463cef9041603fa38"
  }, {
    "url": "css/base/plugins/forms/form-quill-editor.css",
    "revision": "944f99d3807d09d7bcebdefb8c63d67b"
  }, {
    "url": "css/base/plugins/forms/form-validation.css",
    "revision": "f8ba27b711ba2cd514dacc83fe97673b"
  }, {
    "url": "css/base/plugins/forms/form-wizard.css",
    "revision": "5f1c5d5189fed48048bccbdba0b93856"
  }, {
    "url": "css/base/plugins/forms/pickers/form-flat-pickr.css",
    "revision": "7e8772b44d2de404e8ef569d7826ab19"
  }, {
    "url": "css/base/plugins/forms/pickers/form-pickadate.css",
    "revision": "5a3366bf6129b1ebee6c309c2f5cf5b5"
  }, {
    "url": "css/base/plugins/maps/map-leaflet.css",
    "revision": "c477fad84ab794628633d42e8be75939"
  }, {
    "url": "css/base/plugins/ui/coming-soon.css",
    "revision": "b6a00d89ca3c26be89b990cd2e087797"
  }, {
    "url": "css/base/themes/dark-layout.css",
    "revision": "9ea20ac67d41968cec838bf53817783d"
  }, {
    "url": "css/core.css",
    "revision": "62068194c338afd08d30cf0e0df147dc"
  }, {
    "url": "css/overrides.css",
    "revision": "f1c44f66a118e0b6c484476d1b081ad8"
  }, {
    "url": "css/style.css",
    "revision": "0bcd8f81fb3a38b70842eee017673518"
  }], {});

}));
//# sourceMappingURL=service-worker.js.map
