<?php

namespace LadyFormat\Formatter;

/**
 * Allow to chain several formatters
 */
class Chain extends Definition
{
    protected $definitions = array();

    /**
     * Constructor.
     *
     * @param array $definitions Definitions that compose the chain
     */
    public function __construct(array $definitions)
    {
        if (count($definitions) < 2) {
            throw new \InvalidArgumentException("Chain definition required an array of 2 definitions at least");
        }

        $this->definitions = $definitions;
    }

    /**
     * Get the starting definition
     *
     * @return LadyFormat\DefinitionInterface
     */
    protected function getStart()
    {
        return $this->definitions[0];
    }

    /**
     * Get the ending definition
     *
     * @return LadyFormat\DefinitionInterface
     */
    protected function getEnd()
    {
        return $this->definitions[count($this->definitions) - 1];
    }

    /**
     * {@inheritdoc}
     */
    public function createFormatter()
    {
        $formatters = array();

        foreach ($this->definitions as $definition) {
            $formatters[] = $definition->createFormatter();
        }

        $classname = $this->getFormatterClass();

        return new $classname($formatters);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf(
            'chain-%s-%s',
            $this->getInputFormat(),
            $this->getOutputFormat()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function guessFormatFromFilename($file)
    {
        foreach ($this->definitions as $definition) {
            if ($format = $definition->guessFormatFromFilename($file)) {
                return $format;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInputFormat()
    {
        return $this->getStart()->getInputFormat();
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputFormat()
    {
        return $this->getEnd()->getOutputFormat();
    }
}
