<?php

namespace LadyFormat\Formatter;

/**
 * Base definition
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * Get an array of the extensions and their format
     * managed by the formatter
     *
     * @return array
     */
    public function getExtensions()
    {
        throw new \LogicException('You must implement the getExtensions method.');
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function createFormatter()
    {
        $classname = $this->getFormatterClass();
        return new $classname;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatterClass()
    {
        return get_class($this).'Formatter';
    }

    /**
     * {@inheritdoc}
     */
    public function guessFormatFromFilename($file)
    {
        $info       = pathinfo($file);
        $ext        = strtolower($info['extension']);
        $extensions = $this->getExtensions();

        if (array_key_exists($ext, $extensions)) {
            return $extensions[$ext];
        }
    }
}
