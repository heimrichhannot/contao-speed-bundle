<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SpeedBundle\Tests;

use HeimrichHannot\SpeedBundle\ContaoSpeedBundle;
use PHPUnit\Framework\TestCase;

class HeimrichHannotContaoSpeedBundleTest extends TestCase
{
    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoSpeedBundle();

        $this->assertInstanceOf(ContaoSpeedBundle::class, $bundle);
    }

    /**
     * Tests the getContainerExtension() method.
     */
    public function testReturnsTheContainerExtension()
    {
        $bundle = new ContaoSpeedBundle();

        $this->assertInstanceOf(
            'HeimrichHannot\SpeedBundle\DependencyInjection\ContaoSpeedExtension',
            $bundle->getContainerExtension()
        );
    }
}
