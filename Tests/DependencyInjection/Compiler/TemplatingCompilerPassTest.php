<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Tests\DependencyInjection\Compiler;

use Ivory\CKEditorBundle\DependencyInjection\Compiler\TemplatingCompilerPass;
use Ivory\CKEditorBundle\Tests\AbstractTestCase;

/**
 * Templating compiler pass test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class TemplatingCompilerPassTest extends AbstractTestCase
{
    /** @var \Ivory\CKEditorBundle\DependencyInjection\Compiler\TemplatingCompilerPass */
    private $compilerPass;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->compilerPass = new TemplatingCompilerPass();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->compilerPass);
    }

    public function testPhpTemplating()
    {
        $containerBuilder = $this->createContainerBuilderMock();
        $containerBuilder
            ->expects($this->exactly(2))
            ->method('hasDefinition')
            ->will($this->returnValueMap(array(
                array('templating.engine.php', false),
                array('twig', true),
            )));

        $containerBuilder
            ->expects($this->once())
            ->method('removeDefinition')
            ->with($this->identicalTo('ivory_ck_editor.templating.helper'));

        $this->compilerPass->process($containerBuilder);
    }

    public function testTwigTemplating()
    {
        $containerBuilder = $this->createContainerBuilderMock();
        $containerBuilder
            ->expects($this->exactly(2))
            ->method('hasDefinition')
            ->will($this->returnValueMap(array(
                array('templating.engine.php', true),
                array('twig', false),
            )));

        $containerBuilder
            ->expects($this->once())
            ->method('removeDefinition')
            ->with($this->identicalTo('ivory_ck_editor.twig_extension'));

        $this->compilerPass->process($containerBuilder);
    }

    /**
     * Creates a container builder mock.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject The container builder mock.
     */
    private function createContainerBuilderMock()
    {
        return $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()
            ->setMethods(array('hasDefinition', 'removeDefinition'))
            ->getMock();
    }
}
