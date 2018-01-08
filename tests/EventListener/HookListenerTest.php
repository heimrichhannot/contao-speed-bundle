<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0+
 */

namespace HeimrichHannot\SpeedBundle\Tests\EventListener;

use Contao\CoreBundle\DataCollector\ContaoDataCollector;
use Contao\FrontendTemplate;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\System;
use Contao\TestCase\ContaoTestCase;
use HeimrichHannot\SpeedBundle\EventListener\HookListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HookListenerTest extends ContaoTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        \define('TL_ROOT', static::getRootDir());

        $_SERVER['HTTP_HOST'] = 'localhost';
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        System::setContainer($this->mockContainer());
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
        $layout = $this->mockClassWithProperties(LayoutModel::class, [
            'name' => 'Default',
            'id' => 2,
            'template' => 'fe_page',
            'scripts' => ['js_lazyload'], // enable js_lazyload template
        ]);

        $adapter = $this->mockAdapter(['findByPk']);

        $adapter
            ->method('findByPk')
            ->willReturn($layout);

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, [
            'layoutId' => 2,
        ]);

        $collector = new ContaoDataCollector([]);
        $collector->setFramework($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $summary = $collector->getSummary();
        unset($summary['version']); // unset in order to test multiple contao versions

        $this->assertSame(
            [
                'framework' => false,
                'models' => 0,
                'frontend' => true,
                'preview' => false,
                'layout' => 'Default (ID 2)',
                'template' => 'fe_page',
            ],
            $summary
        );

        $template = new FrontendTemplate('picture_default');

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $listener->parseTemplate($template);

        $this->assertSame('picture_lazyload', $template->getName());
    }

    /**
     * Returns the path to the fixtures directory.
     *
     * @return string
     */
    public static function getRootDir()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Fixtures';
    }
}
