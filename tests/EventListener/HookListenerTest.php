<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
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
     * Test the parse template TL_HOOK for invalid templates.
     */
    public function testParseTemplateOtherTemplates()
    {
        $template = new FrontendTemplate('fe_page');

        $listener = new HookListener($this->mockContaoFramework([]));
        $listener->parseTemplate($template);
        $this->assertSame('fe_page', $template->getName());
    }

    /**
     * Test the parse template TL_HOOK without an global $objPage.
     */
    public function testParseTemplateWithoutObjPage()
    {
        $template = new FrontendTemplate('picture_default');

        $listener = new HookListener($this->mockContaoFramework([]));
        $listener->parseTemplate($template);
        $this->assertSame('picture_default', $template->getName());
    }

    /**
     * Test the parse template TL_HOOK without page layout id.
     */
    public function testParseTemplateWithoutLayoutId()
    {
        $template = new FrontendTemplate('picture_default');

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, []);

        $listener = new HookListener($this->mockContaoFramework([]));
        $listener->parseTemplate($template);
        $this->assertSame('picture_default', $template->getName());
    }

    /**
     * Test the parse template TL_HOOK without layout model.
     */
    public function testParseTemplateWithoutLayoutModel()
    {
        $adapter = $this->mockAdapter(['findByPk']);

        $adapter
            ->method('findByPk')
            ->willReturn(null);

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, [
            'layoutId' => 2,
        ]);

        $collector = new ContaoDataCollector();
        $collector->setFramework($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $template = new FrontendTemplate('picture_default');

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $listener->parseTemplate($template);
        $this->assertSame('picture_default', $template->getName());
    }

    /**
     * Test the parse template TL_HOOK without enabled lazyload in layout.
     */
    public function testParseTemplateWithDisabledLazyLoadScript()
    {
        $layout = $this->mockClassWithProperties(LayoutModel::class, [
            'name' => 'Default',
            'id' => 2,
            'template' => 'fe_page',
            'scripts' => [], // enable js_lazyload template
        ]);

        $adapter = $this->mockAdapter(['findByPk']);

        $adapter
            ->method('findByPk')
            ->willReturn($layout);

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, [
            'layoutId' => 2,
        ]);

        $collector = new ContaoDataCollector();
        $collector->setFramework($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $template = new FrontendTemplate('picture_default');

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $listener->parseTemplate($template);
        $this->assertSame('picture_default', $template->getName());
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

        $collector = new ContaoDataCollector();
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
     * Test the addImageToTemplateData TL_HOOK without page layout id.
     */
    public function testAddImageToTemplateDataWithoutLayoutId()
    {
        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, []);

        $listener = new HookListener($this->mockContaoFramework([]));
        $result = $listener->addImageToTemplateData([], '', '', []);
        $this->assertEmpty($result);
    }

    /**
     * Test the addImageToTemplateData TL_HOOK without valid layout model.
     */
    public function testAddImageToTemplateDataWithoutLayoutModel()
    {
        $adapter = $this->mockAdapter(['findByPk']);

        $adapter
            ->method('findByPk')
            ->willReturn(null);

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, [
            'layoutId' => 2,
        ]);

        $collector = new ContaoDataCollector();
        $collector->setFramework($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $result = $listener->addImageToTemplateData([], '', '', []);
        $this->assertEmpty($result);
    }

    /**
     * Test the addImageToTemplateData TL_HOOK without enabled lazyload in layout.
     */
    public function testAddImageToTemplateDataWithDisabledLazyLoadScript()
    {
        $layout = $this->mockClassWithProperties(LayoutModel::class, [
            'name' => 'Default',
            'id' => 2,
            'template' => 'fe_page',
            'scripts' => [], // enable js_lazyload template
        ]);

        $adapter = $this->mockAdapter(['findByPk']);

        $adapter
            ->method('findByPk')
            ->willReturn($layout);

        global $objPage;

        $objPage = $this->mockClassWithProperties(PageModel::class, [
            'layoutId' => 2,
        ]);

        $collector = new ContaoDataCollector();
        $collector->setFramework($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $collector->collect(new Request(), new Response());

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $result = $listener->addImageToTemplateData([], '', '', []);
        $this->assertEmpty($result);
    }

    /**
     * Test the addImageToTemplateData TL_HOOK.
     */
    public function testAddImageToTemplateData()
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

        $collector = new ContaoDataCollector();
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

        $listener = new HookListener($this->mockContaoFramework([LayoutModel::class => $adapter]));
        $result = $listener->addImageToTemplateData([], '', '', []);
        $this->assertNotEmpty($result);
        $this->assertSame(['picture' => ['lazyload' => true]], $result);
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
