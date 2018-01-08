# Contao speed bundle

![](https://img.shields.io/packagist/v/heimrichhannot/contao-speed-bundle.svg)
![](https://img.shields.io/packagist/l/heimrichhannot/contao-speed-bundle.svg)
![](https://img.shields.io/packagist/dt/heimrichhannot/contao-speed-bundle.svg)
[![](https://img.shields.io/travis/heimrichhannot/contao-speed-bundle/master.svg)](https://travis-ci.org/heimrichhannot/contao-speed-bundle/)
[![](https://img.shields.io/coveralls/heimrichhannot/contao-speed-bundle/master.svg)](https://coveralls.io/github/heimrichhannot/contao-speed-bundle)


This module improves the performance of your contao environment. 

## Improvements

### Lazy-loading offscreen images

Offscreen images are images that appear below the fold. Since users can't see offscreen images when they load a page, there's no reason to download the offscreen images as part of the initial page load. In other words, deferring the load of offscreen images can speed up page load time and time to interactive.

We ship an adjusted `picture_default.html5` with this bundle to provide proper lazy loading setup for images. If in case you use custom `picture-*.html5` templates adjust like the following:

#### How to use?

To enable lazy loading, enable the `js_lazyload` JavaScript-Template within your page layout.

**<img>**
```
<img data-src="/your/image1.jpg"
    data-srcset="/your/image1.jpg 200w, /your/image1@2x.jpg 400w"
    sizes="(min-width: 20em) 35vw, 100vw">
``` 

**<pictures>**

```
<picture>
    <source media="(min-width: 1024px)" data-srcset="/your/image1a.jpg" />
    <source media="(min-width: 500px)" data-srcset="/your/image1b.jpg" />
    <img alt="Stivaletti" data-src="/your/image1.jpg">
</picture>
```

**Background images**
We also support lazy loading for background images. To take advantage of this feature, add the css class `lazy` to the background image container and provide the path to the image within the `data-src`  attribute.

```
<div class="lazy" data-src="../img/44721746JJ_15_a.jpg"></div>
```