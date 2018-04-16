# Changelog
All notable changes to this project will be documented in this file.

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
