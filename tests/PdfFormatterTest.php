<?php

use mageekguy\atoum\test;

abstract class PdfFormatterTest extends test
{

    /**
     * Create an instance of the tested formatter
     *
     * @return LadyFormat\Formatter\FormatterInterface
     */
    abstract public function createFormatter();

    /**
     * The data provider for tests
     *
     * @return array
     */
    abstract public function stringsProvider();

    public function beforeTestMethod($method)
    {
        $this->parser = new \Smalot\PdfParser\Parser();
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testGetOutput($from, $to)
    {
        $f = $this->createFormatter();

        $pdf = $this->parser->parseContent($f->getOutput($from));

        $this
            ->string($pdf->getText())
                ->contains($to)
        ;
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testGetOutputFromFile($from, $to)
    {
        $f = $this->createFormatter();

        $tempFile = $f->createTemporaryFile($from);

        $pdf = $this->parser->parseContent($f->getOutputFromFile($tempFile));

        $this
            ->string($pdf->getText())
                ->contains($to)
        ;

        unlink($tempFile);
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testGenerate($from, $to)
    {
        $f = $this->createFormatter();

        $output = $f->createTemporaryFile();

        $f->generate($from, $output);

        $this
            ->boolean(file_exists($output))
                ->isTrue()
            ->if($pdf = $this->parser->parseFile($output))
            ->string($pdf->getText())
                ->contains($to)
        ;

        unlink($output);
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testGenerateFromfile($from, $to)
    {
        $f = $this->createFormatter();

        $output = $f->createTemporaryFile();
        $input = $f->createTemporaryFile($from);

        $f->generateFromFile($input, $output);

        $this
            ->boolean(file_exists($output))
                ->isTrue()
            ->boolean(file_exists($input))
                ->isTrue()
            ->if($pdf = $this->parser->parseFile($output))
            ->string($pdf->getText())
                ->contains($to)
        ;

        unlink($input);
        unlink($output);
    }
}
