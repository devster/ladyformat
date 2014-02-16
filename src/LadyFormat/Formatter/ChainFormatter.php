<?php

namespace LadyFormat\Formatter;

/**
 * Chain formatter
 */
class ChainFormatter extends Formatter
{
    /**
     * @var array formatters
     */
    protected $formatters = array();

    /**
     * Constructor.
     *
     * @param array $formatters Formatters that compose the chain
     */
    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput($string)
    {
        foreach ($this->formatters as $f) {
            $string = $f->getOutput($string);
        }

        return $string;
    }
}
