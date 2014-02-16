<?php

namespace LadyFormat\Tests\Units\Formatter;

class ChainFormatter extends \PdfFormatterTest
{
    public function stringsProvider()
    {
        return array(
            array("    Hello world", "Hello world")
        );
    }

    public function createFormatter()
    {
        return new \LadyFormat\Formatter\ChainFormatter(array(
            new \LadyFormat\Formatter\PhpMarkdownFormatter,
            new \LadyFormat\Formatter\SnappyPdfFormatter,
        ));
    }
}
