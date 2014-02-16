<?php

namespace LadyFormat\Tests\Units\Formatter;

class PhpMarkdownFormatter extends \FormatterTest
{
    /**
     * {@inheritdoc}
     */
    public function createFormatter()
    {
        return new \LadyFormat\Formatter\PhpMarkdownFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function stringsProvider()
    {
        return array(
            array("#title", "<h1>title</h1>")
        );
    }
}
