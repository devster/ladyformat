<?php

namespace LadyFormat\Tests\Units;

use mageekguy\atoum\test;
use LadyFormat\LadyFormat as LF;

class LadyFormat extends test
{
    public function testdefinitionCrud()
    {
        $lf = new LF;

        $def = new \mock\LadyFormat\Formatter\DefinitionInterface;
        $def->getMockController()->getName = 'test';

        $mdDef = new \mock\LadyFormat\Formatter\PhpMarkdown;
        $mdDef->getMockController()->getName = 'test';

        $this
            ->if($lf->addDefinition($def))
            ->array($lf->getDefinitions())
                ->contains($def)
                ->hasKey('test')
            ->boolean($lf->hasDefinition('test'))
                ->isTrue()
            ->boolean($lf->hasDefinition('unknown'))
                ->isFalse()
            ->object($lf->getDefinition('test'))
                ->isInstanceOf('LadyFormat\Formatter\DefinitionInterface')
            ->exception(function () use ($lf) {
                $lf->getDefinition('unknown');
            })
                ->isInstanceOf('\InvalidArgumentException')
            ->if($lf->addDefinition($mdDef))
            ->array($lf->getDefinitions())
                ->notContains($def)
                ->contains($mdDef)
        ;
    }

    /**
     * @dataProvider filesDataProvider
     */
    public function testGuessFormat($file, $format)
    {
        $lf = new LF;

        $this
            ->variable($lf->guessFormat($file))
                ->isEqualTo($format)
        ;

        if (!$format) {
            $this
                ->exception(function () use ($lf, $file) {
                    $lf->guessFormat($file, true);
                })
                    ->isInstanceOf('LadyFormat\Exception\FormatterNotFound')
                    ->message
                        ->contains($file)
            ;
        }
    }

    protected function filesDataProvider()
    {
        return array(
            array('test.html', 'html'),
            array('test.HTML', 'html'),
            array('file.nada', null),
            array('sdffsd/sdfdf.MD', 'markdown'),
            array('sdffsd/sdfdf.md', 'markdown'),
            array('test.Markdown', 'markdown'),
            array('test.Mdown', null),
            array('test/file.pdf', 'pdf')
        );
    }

    public function testFormat()
    {
        $f = new \mock\LadyFormat\Formatter\Formatter;

        $inputTempFile = $f->createTemporaryFile("#title", 'md');
        $outputTempFile = $f->createTemporaryFile(null, 'html');

        $lf = new LF;
        $lf->format($inputTempFile, $outputTempFile);

        $this
            ->boolean(file_exists($inputTempFile))
                ->isTrue()
            ->boolean(file_exists($outputTempFile))
                ->isTrue()
            ->string(file_get_contents($outputTempFile))
                ->contains('<h1>title</h1>')
            ->exception(function () use ($lf) {
                $lf->format('file.nada', 'file.pdf');
            })
                ->isInstanceOf('LadyFormat\Exception\FormatterNotFound')
        ;

        unlink($inputTempFile);
        unlink($outputTempFile);
    }

    public function testGet()
    {
        $lf = new LF;

        $this
            ->exception(function () use ($lf) {
                $lf->get('test', 'nothing');
            })
                ->isInstanceOf('LadyFormat\Exception\FormatterNotFound')
                ->message
                    ->contains('test')
                    ->contains('nothing')
            ->object($lf->get('markdown', 'pdf'))
                ->isInstanceOf('LadyFormat\Formatter\ChainFormatter')
        ;
    }

    public function testFind()
    {
        $lf = new LF;

        $this
            ->variable($lf->find('unknown', 'pdf'))
                ->isNull()
            ->variable($lf->find('markdown', 'unknown'))
                ->isNull()
            ->object($lf->find('markdown', 'html'))
                ->isInstanceOf('LadyFormat\Formatter\PhpMarkdownFormatter')
            ->object($chain = $lf->find('markdown', 'pdf'))
                ->isInstanceOf('LadyFormat\Formatter\ChainFormatter')
        ;

        $ref = new \ReflectionClass($chain);
        $prop = $ref->getProperty('formatters');
        $prop->setAccessible(true);
        $formatters = $prop->getValue($chain);

        $this
            ->array($formatters)
                ->size
                    ->isEqualTo(2)
            ->object($formatters[0])
                ->isInstanceOf('LadyFormat\Formatter\PhpMarkdownFormatter')
            ->object($formatters[1])
                ->isInstanceOf('LadyFormat\Formatter\SnappyPdfFormatter')
        ;
    }

    public function testGetFormatterFromDefinition()
    {
        $lf = new LF;

        $mdDef = new \LadyFormat\Formatter\PhpMarkdown;

        $this
            ->object($lf->getFormatterFromDefinition($mdDef))
                ->isInstanceOf('LadyFormat\Formatter\PhpMarkdownFormatter')
        ;
    }

    /**
     * @dataProvider formattersNameDataProvider
     */
    public function testGetFormatter($name, $classname)
    {
        $lf = new LF;

        if (!$classname) {
            $this
                ->exception(function () use ($lf, $name) {
                    $lf->getFormatter($name);
                })
                    ->isInstanceOf('LadyFormat\Exception\FormatterNotFound')
                    ->message
                        ->contains($name)
            ;
        } else {
            $this
                ->object($lf->getFormatter($name))
                    ->isInstanceOf($classname)
            ;
        }
    }

    public function formattersNameDataProvider()
    {
        return array(
            array('unknown', false),
            array('php-markdown', 'LadyFormat\Formatter\PhpMarkdownFormatter')
        );
    }
}
