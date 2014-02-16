<?php

namespace LadyFormat\Tests\Units\Formatter;

class SnappyPdfFormatter extends \PdfFormatterTest
{
    public function stringsProvider()
    {
        return array(
            array("<pre>Hello world</pre>", "Hello world")
        );
    }

    public function createFormatter()
    {
        return new \LadyFormat\Formatter\SnappyPdfFormatter;
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testSetPdfTitle($from, $to)
    {
        $f = $this->createFormatter();

        $title = uniqid();

        $f->setOptions(array('title' => $title));

        $pdf = $this->parser->parseContent($f->getOutput($from));
        $details = $pdf->getDetails();

        $this
            ->string($details['Title'])
                ->isEqualTo($title)
        ;
    }
}
