<?php

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseTemplate']['speed'] = ['huh.speed.listener.hooks', 'parseTemplate'];
$GLOBALS['TL_HOOKS']['addImageToTemplateData']['speed'] = ['huh.speed.listener.hooks', 'addImageToTemplateData'];