(function (w, d) {
    document.addEventListener("DOMContentLoaded", function (event) {
        var b = d.getElementsByTagName('body')[0];
        var s = d.createElement('script');
        s.async = true;
        var v = !('IntersectionObserver' in w) ? '8' : '10';
        // currently there is an bug in edge -> https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/12156111/
        if (window.navigator.userAgent.indexOf('Edge') > -1) {
            v = 8;
        }

        // safari works best with version 10, but window object has no IntersectionObserver -> force version 10
        if (window.navigator.userAgent.indexOf('Safari') > -1) {
            v = 10;
        }

        s.src = '/assets/lazyload/js/' + v + '/lazyload.min.js';

        var instances = [];

        // Listen to the Initialized event
        window.addEventListener('LazyLoad::Initialized', function (e) {
            // Get the instance and puts it in the lazyLoadInstance variable
            instances.push(e.detail.instance);

            var wrapperStyles = [];

            if (typeof e.detail.instance._elements !== 'undefined' && e.detail.instance._elements.length > 0) {
                e.detail.instance._elements.forEach(function (el) {
                    var wrapperStyle = el.getAttribute('data-wrapper-style');

                    if (wrapperStyle) {
                        wrapperStyles.push(wrapperStyle);
                    }
                });

                if (wrapperStyles.length > 0) {
                    var linkElement = document.createElement('link');
                    linkElement.setAttribute('rel', 'stylesheet');
                    linkElement.setAttribute('href', 'data:text/css;charset=UTF-8,' + encodeURIComponent(wrapperStyles.join('')));
                    document.querySelector('head').appendChild(linkElement);
                }
            }

        }, false);

        w.lazyLoadOptions = [
            {
                // default image instance
                data_src: 'src',
                data_srcset: 'srcset',
                threshold: 100,
                callback_enter: function (el) {
                    var event = new CustomEvent('lazyload:enter');
                    el.dispatchEvent(event);

                    var styleData = el.getAttribute('data-style');

                    if (styleData) {
                        var linkElement = this.document.createElement('link');
                        linkElement.setAttribute('rel', 'stylesheet');
                        linkElement.setAttribute('href', 'data:text/css;charset=UTF-8,' + encodeURIComponent(styleData));
                        document.querySelector('head').appendChild(linkElement);
                    }
                },
                callback_set: function (el) {
                    var event = new CustomEvent('lazyload:set');
                    el.dispatchEvent(event);
                },
                callback_load: function (el) {
                    var event = new CustomEvent('lazyload:load');
                    el.dispatchEvent(event);

                    var wrapperId = el.getAttribute('data-wrapper');
                    if(null !== wrapperId){
                        var wrapper = document.querySelector(el.getAttribute('data-wrapper'));
                        wrapper.classList.add('loaded');
                    }
                }
            }, {
                // background image instance:  use lazy class on container and set data-src="[IMG_URL]" instead of style="background-image: url([IMG_URL]);"
                elements_selector: '.lazy',
                threshold: 100,
                callback_enter: function (el) {
                    var event = new CustomEvent('lazyload:enter');
                    el.dispatchEvent(event);
                },
                callback_set: function (el) {
                    var event = new CustomEvent('lazyload:set');
                    el.dispatchEvent(event);
                },
                callback_load: function (el) {
                    var event = new CustomEvent('lazyload:load');
                    el.dispatchEvent(event);
                }
            }];


        b.appendChild(s);

        // listen on each ajax request and trigger update() on each lazyload instance
        const send = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function () {
            this.addEventListener('load', function () {
                for (i in instances) {
                    if (instances.hasOwnProperty(i)) {
                        instances[i].update();
                    }
                }
            });
            return send.apply(this, arguments)
        }
    });

}(window, document));