<?php

namespace LadyFormat\Formatter;

interface FormatterInterface
{
    /**
     * Format a string into a string
     *
     * @param string $string
     *
     * @return string
     */
    public function getOutput($string);

    /**
     * Format a file into a string
     *
     * @param string $input
     *
     * @return string
     */
    public function getOutputFromFile($input);

    /**
     * Format a string into a file
     *
     * @param string $string
     * @param string $output
     *
     * @return void
     */
    public function generate($string, $output);

    /**
     * Format a file into a file
     *
     * @param string $input
     * @param string $output
     *
     * @return void
     */
    public function generateFromFile($input, $output);
}
