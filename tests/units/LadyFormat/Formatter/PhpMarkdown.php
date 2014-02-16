<?php

namespace LadyFormat\Tests\Units\Formatter;

use mageekguy\atoum\test;

class PhpMarkdown extends test
{
    public function createDefinition()
    {
        return new \LadyFormat\Formatter\PhpMarkdown;
    }

    public function test()
    {
        $d = $this->createDefinition();

        $this
            ->string($d->getName())
                ->isEqualTo('php-markdown')
            ->string($d->getInputFormat())
                ->isEqualTo('markdown')
            ->string($d->getOutputFormat())
                ->isEqualTo('html')
        ;
    }

    public function testCreateFormatter()
    {
        $d = $this->createDefinition();

        $this
            ->object($d->createFormatter())
                ->isInstanceOf('LadyFormat\Formatter\PhpMarkdownFormatter')
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
            array('file.md', 'markdown'),
            array('test/file.MD', 'markdown'),
            array('file.markdown', 'markdown'),
            array('file.html', 'html'),
            array('test.file.HTML', 'html'),
            array('file.xhtml', null)
        );
    }
}
