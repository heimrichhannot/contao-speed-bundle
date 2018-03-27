<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\FilesModel;
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
     * Modify the huh.utils.image template data.
     *
     * @param array           $templateData
     * @param string          $imageField
     * @param string          $imageSelectorField
     * @param array           $item
     * @param int|null        $maxWidth
     * @param string|null     $lightboxId
     * @param string|null     $lightboxName
     * @param FilesModel|null $model
     *
     * @return array The modified $templateData
     */
    public function addImageToTemplateData(
        array $templateData,
        string $imageField,
        string $imageSelectorField,
        array $item,
        int $maxWidth = null,
        string $lightboxId = null,
        string $lightboxName = null,
        FilesModel $model = null
    ) {
        global $objPage;

        /**
         * @var LayoutModel
         */
        $adapter = $this->framework->getAdapter(LayoutModel::class);

        if (null === $objPage || !$objPage->layoutId || null === ($layout = $adapter->findByPk($objPage->layoutId))) {
            return $templateData;
        }

        if (!in_array('js_lazyload', StringUtil::deserialize($layout->scripts, true), true)) {
            return $templateData;
        }

        $templateData['picture']['lazyload'] = true;

        return $templateData;
    }

    /**
     * Invoke the parseTemplate TL_HOOK.
     *
     * @param Template $template
     */
    public function parseTemplate($template): void
    {
        if ('picture_default' !== $template->getName() || false === $template->lazyload) {
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
