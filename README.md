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
