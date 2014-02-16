<?php

namespace LadyFormat\Formatter;

/**
 * Formatter definition interface
 */
interface DefinitionInterface
{
    /**
     * Instanciate a new Formatter
     *
     * @return FormatterInterface
     */
    public function createFormatter();

    /**
     * Returns the name of the formatter,
     * Useful for overriding formatters provided by LadyFormat
     *
     * @return string
     */
    public function getName();

    /**
     * Guess the format from filename
     *
     * @param string $file
     *
     * @return string
     */
    public function guessFormatFromFilename($file);

    /**
     * Returns the class name of the formatter
     *
     * @return string
     */
    public function getFormatterClass();

    /**
     * Returns the input format
     * Can returns an array of format and aliases
     *
     * @return string
     */
    public function getInputFormat();

    /**
     * Returns the output format
     * Can returns an array of format and aliases
     *
     * @return string
     */
    public function getOutputFormat();
}
