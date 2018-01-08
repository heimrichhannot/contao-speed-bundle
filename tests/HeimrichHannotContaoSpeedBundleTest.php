<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\Tests;

use HeimrichHannot\SpeedBundle\HeimrichHannotContaoSpeedBundle;
use PHPUnit\Framework\TestCase;

class HeimrichHannotContaoSpeedBundleTest extends TestCase
{
    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $bundle = new HeimrichHannotContaoSpeedBundle();

        $this->assertInstanceOf(HeimrichHannotContaoSpeedBundle::class, $bundle);
    }
}
