<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\Tests\EventListener;

use Contao\CoreBundle\DataCollector\ContaoDataCollector;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Tests\TestCase;
use Contao\FrontendTemplate;
use Contao\LayoutModel;
use Contao\System;
use HeimrichHannot\SpeedBundle\EventListener\HookListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HookListenerTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        if (!\defined('TL_ROOT')) {
            \define('TL_ROOT', $this->getRootDir());
        }

        System::setContainer($this->mockContainerWithContaoScopes());
    }

    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $listener = new HookListener($this->mockContaoFramework());

        $this->assertInstanceOf('HeimrichHannot\SpeedBundle\EventListener\HookListener', $listener);
    }

    /**
     * Tests the parse template TL_HOOK.
     */
    public function testRenamePictureTemplateWithinParseTemplate()
    {
        $layout = new \stdClass();
        $layout->name = 'Default';
        $layout->id = 2;
        $layout->template = 'fe_page';
        $layout->scripts = ['js_lazyload']; // enable js_lazyload template

        $adapter = $this
            ->getMockBuilder(Adapter::class)
            ->setMethods(['__call'])
            ->disableOriginalConstructor()
            ->getMock();

        $adapter
            ->expects($this->any())
            ->method('__call')
            ->willReturn($layout);

        global $objPage;

        $objPage = new \stdClass();
        $objPage->layoutId = 2;

        $collector = new ContaoDataCollector([]);
        $collector->setFramework($this->mockContaoFramework(null, null, [LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $this->assertSame(
            [
                'version' => '',
                'framework' => false,
                'models' => 0,
                'frontend' => true,
                'preview' => false,
                'layout' => 'Default (ID 2)',
                'template' => 'fe_page',
            ],
            $collector->getSummary()
        );

        $template = new FrontendTemplate('picture_default');

        $listener = new HookListener($this->mockContaoFramework());
        $listener->parseTemplate($template);

        $this->assertSame('picture_lazyload', $template->getName());
    }
}
