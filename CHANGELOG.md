# Changelog
All notable changes to this project will be documented in this file.

## [1.5.1] - 2019-01-24

### Fixed
- lazyload in `picture_lazyload.html5` only set `height` (do not set `width`, otherwise lazyload will break)

## [1.5.0] - 2019-01-24

### Changed
- do no longer maintain image aspect ratio with `padding`, instead use `width` and `height` of the images and sources
- no longer handle styles inside `body` tag, use `link` tag instead

## [1.4.2] - 2019-01-18

### Fixed
- correct calculation of padding for portrait format images in picture_lazyload template

## [1.4.1] - 2018-01-15

### Fixed
- selector in `picture_lazyload.html5` for responsive media query styles, thanks @seibtph (https://github.com/heimrichhannot/contao-speed-bundle/pull/2)

## [1.4.0] - 2018-12-21

### Added
- support to disable lazyload within image (twig templates)

## [1.3.0] - 2018-12-21

### Added
- callbacks `lazyload:enter`, `lazyload:set`, `lazyload:load`

### Fixed
- added styles to `pictury_lazyload.html5` that adjusts padding-bottom for media query image sizes

## [1.2.3] - 2018-12-18

### Fixed
- contao 4.6 (symfony 4) compatibility

## [1.2.2] - 2018-11-28

### Fixed
- restore img css class in picture_lazyload.html5

## [1.2.1] - 2018-07-20

### Fixed
- prepend `{{env::url}}/` to  `data-srcset`, `data-src`, `data-lazy` attribute in order to load images via absolute url, otherwise images might get loaded from `page-alias/assets/images` (iOS) and trigger 404 error which will result in too many http requests (may slow down server in huge way)

## [1.2.0] - 2018-07-11

### Changes
- moved `image-wrapper` out of picture element in `picture_lazyload.html5` template to restore functionality for source elements

> Caution: May lead to broken css styles!

## [1.1.3] - 2018-04-26

### Fixed
- `picture_lazyload.html5` template

## [1.1.2] - 2018-04-16

### Fixed
- `picture_lazyload.html5` lazyload padding, number format (use dot as decimal point instead of comma)

## [1.1.1] - 2018-04-16

### Fixed
- bind `DOMContentLoaded` to lazyload script

## [1.1.0] - 2018-03-27

### Added
- Lazyload support images added by xhr requests e.g. masonry endless scroll
- now supports `huh.utils.image` twig templates by invoke lazy load to template data by using `$GLOBALS['TL_HOOKS']['addImageToTemplateData']` 

### Changed
- Make js_lazyload js aggregatable
- reduced `threshold` from 300 to 100px

## [1.0.5] - 2018-03-13

### Added
- disable lazyload on demand (e.g. `ce_image.html5`)

## [1.0.4] - 2018-02-27

### Fixed
- safari support (iOS and Mac Version)

### Added
- slider/carousel lazyload notice in `README.md`

## [1.0.3] - 2018-01-24

### Added
- data-lazy for slick slider to be working

## [1.0.2] - 2018-01-08

### Fixed
- invoke the `lazyload.min.css` within `TL_CSS`

## [1.0.1] - 2018-01-08

### Added
- additional unit tests and code coverage
