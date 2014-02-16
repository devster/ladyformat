<?php

namespace LadyFormat\Tests\Units\Formatter;

use mageekguy\atoum\test;
use LadyFormat\Formatter\Chain as Definition;

class Chain extends test
{
    protected function createChain()
    {
        return new Definition(array(
            new \LadyFormat\Formatter\PhpMarkdown,
            new \LadyFormat\Formatter\SnappyPdf,
        ));
    }

    public function test()
    {
        $this
            ->exception(function () {
                new Definition(array());
            })
                ->isInstanceOf('\InvalidArgumentException')
        ;

        $d = $this->createChain();

        $this
            ->string($d->getName())
                ->isEqualTo('chain-markdown-pdf')
            ->string($d->getInputFormat())
                ->isEqualTo('markdown')
            ->string($d->getOutputFormat())
                ->isEqualTo('pdf')
        ;
    }

    public function testCreateFormatter()
    {
        $d = $this->createChain();
        $f = $d->createFormatter();

        $ref = new \ReflectionClass($f);
        $property = $ref->getProperty('formatters');
        $property->setAccessible(true);
        $formatters = $property->getValue($f);

        $this
            ->object($f)
                ->isInstanceOf('LadyFormat\Formatter\ChainFormatter')
            ->array($formatters)
                ->size
                    ->isEqualTo(2)
        ;
    }

    /**
     * @dataProvider filesProvider
     */
    public function testGuessFormatFromFilename($file, $format)
    {
        $d = $this->createChain();

        $this
            ->variable($d->guessFormatFromFilename($file))
                ->isEqualTo($format)
        ;
    }

    protected function filesProvider()
    {
        return array(
            array('test/file.php', null),
            array('file.md', 'markdown'),
            array('test/file.MD', 'markdown'),
            array('file.markdown', 'markdown'),
            array('file.html', 'html'),
            array('test.file.HTML', 'html'),
            array('file.xhtml', null),
            array('file.PDF', 'pdf'),
            array('TEST/file.pdf', 'pdf')
        );
    }
}
