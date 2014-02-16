<?php

namespace LadyFormat\Tests\Units\Formatter;

use mageekguy\atoum\test;

class SnappyPdf extends test
{
    public function createDefinition()
    {
        return new \LadyFormat\Formatter\SnappyPdf;
    }

    public function test()
    {
        $d = $this->createDefinition();

        $this
            ->string($d->getName())
                ->isEqualTo('snappy-pdf')
            ->string($d->getInputFormat())
                ->isEqualTo('html')
            ->string($d->getOutputFormat())
                ->isEqualTo('pdf')
        ;
    }

    public function testCreateFormatter()
    {
        $d = $this->createDefinition();

        $this
            ->object($d->createFormatter())
                ->isInstanceOf('LadyFormat\Formatter\SnappyPdfFormatter')
        ;
    }

    /**
     * @dataProvider filesProvider
     */
    public function testGuessFormatFromFilename($file, $format)
    {
        $d = $this->createDefinition();

        $this
            ->variable($d->guessFormatFromFilename($file))
                ->isEqualTo($format)
        ;
    }

    protected function filesProvider()
    {
        return array(
            array('test/file.php', null),
            array('file.html', 'html'),
            array('test/file.HTML', 'html'),
            array('file.PDF', 'pdf'),
            array('test/file.pdf', 'pdf'),
            array('file.md', null),
        );
    }

    public function testSetBinary()
    {
        $binary = uniqid();

        $d = $this->createDefinition();
        $d->setBinary($binary);

        $f = $d->createFormatter();

        $this
            ->string($f->snappy->getBinary())
                ->isEqualTo($binary)
        ;
    }

    public function testSetOptions()
    {
        $value   = uniqid();
        $options = array('title' => $value);

        $d = $this->createDefinition();
        $d->setOptions($options);

        $this
            ->array($d->createFormatter()->snappy->getOptions())
                ->hasKey('title')
                ->contains($value)
        ;
    }
}
