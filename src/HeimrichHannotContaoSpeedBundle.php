<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle;

use HeimrichHannot\SpeedBundle\DependencyInjection\HeimrichHannotContaoSpeedExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotContaoSpeedBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new HeimrichHannotContaoSpeedExtension();
    }
}
