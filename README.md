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

We ship an adjusted `picture_lazyload.html5` with this bundle to provide proper lazy loading setup for images (`picture_default.html5` will be replaced with `picture_lazyload.html5` if script `js_lazyload` in `tl_layout.scripts` is enabled). If in case you use custom `picture-*.html5` templates adjust like the following:

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
**Lazy load inside sliders like slick slider**

In order to prevent bouncing of cloned carousel slide images (using infinite looping), the `lazyLoad` technique should be set to `progressive` instead of `on-demand`.

 
#### Prevent lazy loading 

If you want to prevent your image from being lazy loaded, you have to adjust your template and add `['lazyload' => false]` to the picture template data.

```
<?php $this->extend('block_searchable'); ?>

<?php $this->block('content'); ?>

  <figure class="image_container"<?php if ($this->margin): ?> style="<?= $this->margin ?>"<?php endif; ?> itemscope itemtype="http://schema.org/ImageObject">

    <?php if ($this->href): ?>
      <a href="<?= $this->href ?>"<?php if ($this->linkTitle): ?> title="<?= $this->linkTitle ?>"<?php endif; ?><?= $this->attributes ?> itemprop="contentUrl">
    <?php endif; ?>

    <?php $this->insert('picture_default', array_merge(['lazyload' => false] ,$this->picture)); ?>

    <?php if ($this->href): ?>
      </a>
    <?php endif; ?>

    <?php if ($this->caption): ?>
      <figcaption class="caption" itemprop="caption"><?= $this->caption ?></figcaption>
    <?php endif; ?>

  </figure>

<?php $this->endblock(); ?>
```
*Example: ce_image.html5*

If you use the `heimrichhannot/contao-utils-bundle` TwigExtension `image`, just add `'lazyload': false` to your item data.

```
{{ singleSRC|image([0,0,3],{'href' : url, 'lazyload': false})|raw }}
```

#### Update lazy load on demand

If you need to update your lazy load instances on demand, for example using the barba.js PJAX page transition plugin, use the `window.lazyLoad.update()` function.

#### Callbacks

In order to make adjustments like trigger animation on scroll after image has been loaded, there are some callbacks you might listen on the image:


| Name                | Meaning                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                            |
| ------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| `lazyload:enter`    | A function which is called when an element enters the viewport.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | `document.querySelector('[data-lazy]').addEventListener('lazyload:enter', function(event){});`     |
| `lazyload:set`      | A function which is called after the `src`/`srcset` of an element is set in the DOM.                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | `document.querySelector('[data-lazy]').addEventListener('lazyload:set', function(event){});`         |
| `lazyload:load`     | A function which is called when an element was loaded.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | `document.querySelector('[data-lazy]').addEventListener('lazyload:load', function(event){});`      |



#### 😋 Tips & tricks

##### Do NOT use placeholder images

Taken from the `verlok/lazyload` github README.md:

> We do not recommend to use a placeholder image (like a transparent pixel GIF) in your HTML. 
> 
> * For **best perceived performance, leave the `src` and `srcset` attributes blank**. Doing so, the image will be shown as soon as LazyLoad starts loading the image. See [this video](https://youtu.be/2E3ociaFJS0) or [this pen](https://> codepen.io/verlok/pen/bKYggE?editors=0110) to test the difference (remember to disable the cache and to set a slower connection speed if you have a very fast one).
> * If you put anything in the src (like a transparent GIF), then LazyLoad starts loading the image but it won't be shown by browsers until the new image is loaded, leading to a **worse perceived performance**.
>
> It's safe not to put any value in the `src` nor `srcset` attributes, even if your HTML won't validate by a static code analyzer. The reason is that once JavaScript is executed, those values will be set by LazyLoad. For SEO, if the > client is a crawler like Googlebot, it will be detected by LazyLoad which will fix the HTML.
