<?php

use mageekguy\atoum\test;

abstract class FormatterTest extends test
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

    /**
     * @dataProvider stringsProvider
     */
    public function testGetOutput($from, $to)
    {
        $f = $this->createFormatter();

        $this
            ->string($f->getOutput($from))
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

        $this
            ->string($f->getOutputFromFile($tempFile))
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
            ->string(file_get_contents($output))
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
            ->string(file_get_contents($output))
                ->contains($to)
        ;

        unlink($input);
        unlink($output);
    }
}
