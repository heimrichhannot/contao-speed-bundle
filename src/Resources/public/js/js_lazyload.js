(function(w, d) {
    document.addEventListener("DOMContentLoaded", function(event) {
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
    window.addEventListener('LazyLoad::Initialized', function(e) {
        // Get the instance and puts it in the lazyLoadInstance variable
        instances.push(e.detail.instance);
    }, false);

    w.lazyLoadOptions = [
        {
            // default image instance
            data_src: 'src',
            data_srcset: 'srcset',
            threshold: 100
        }, {
            // background image instance:  use lazy class on container and set data-src="[IMG_URL]" instead of style="background-image: url([IMG_URL]);"
            elements_selector: '.lazy',
            threshold: 100
        }];


        b.appendChild(s);

        // listen on each ajax request and trigger update() on each lazyload instance
        const send = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function() {
            this.addEventListener('load', function() {
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