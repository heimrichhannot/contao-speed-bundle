<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SpeedBundle;

use HeimrichHannot\SpeedBundle\DependencyInjection\ContaoSpeedExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoSpeedBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ContaoSpeedExtension();
    }
}
