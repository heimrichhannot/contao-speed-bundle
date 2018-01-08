<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\LayoutModel;
use Contao\StringUtil;
use Contao\Template;

class HookListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * Constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Invoke the parseTemplate TL_HOOK.
     *
     * @param Template $template
     */
    public function parseTemplate($template)
    {
        if ('picture_default' !== $template->getName()) {
            return;
        }

        global $objPage;

        /**
         * @var LayoutModel
         */
        $adapter = $this->framework->getAdapter(LayoutModel::class);

        if (null === $objPage || !$objPage->layoutId || null === ($layout = $adapter->findByPk($objPage->layoutId))) {
            return;
        }

        if (!in_array('js_lazyload', StringUtil::deserialize($layout->scripts, true), true)) {
            return;
        }

        $template->setName('picture_lazyload');
    }
}
