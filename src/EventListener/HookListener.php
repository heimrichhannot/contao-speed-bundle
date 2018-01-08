<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\LayoutModel;
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
        global $objPage;

        if (null === $objPage || !$objPage->layout || null === ($layout = LayoutModel::findByPk($objPage->layout))) {
            return;
        }
    }
}
