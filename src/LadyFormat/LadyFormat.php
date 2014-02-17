<?php

namespace LadyFormat;

use LadyFormat\Exception\FormatterNotFound;

/**
 * LadyFormat
 *
 * @author Jeremy  Perret  <jeremy@devster.org>
 * @author Thierry Geindre
 */
class LadyFormat
{
    /**
     * @var array definitions
     */
    protected $definitions = array();

    /**
     * @var array formatters
     */
    protected $formatters = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this
            ->addDefinition(new Formatter\PhpMarkdown)
            ->addDefinition(new Formatter\SnappyPdf)
        ;
    }

    /**
     * Add a formatter definition to LadyFormat
     *
     * @param LadyFormat\DefinitionInterface $definition
     *
     * @return LadyFormat The current instance
     */
    public function addDefinition(DefinitionInterface $definition)
    {
        $this->definitions[$definition->getName()] = $definition;

        return $this;
    }

    /**
     * Get formatter definitions
     *
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Has definition
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasDefinition($name)
    {
        return array_key_exists($name, $this->definitions);
    }

    /**
     * Get a definition by its name
     *
     * @param string $name
     *
     * @return LadyFormat\DefinitionInterface
     */
    public function getDefinition($name)
    {
        if (!$this->hasDefinition($name)) {
            throw new \InvalidArgumentException(sprintf('Definition %s not found', $name));
        }

        return $this->definitions[$name];
    }

    /**
     * Guess the format from a filename
     *
     * @param string $file
     *
     * @throws LadyFormat\Exception\FormatterNotFound If $throwException is true and no format was guessed
     *
     * @return string|null
     */
    public function guessFormat($file, $throwException = false)
    {
        foreach ($this->definitions as $definition) {
            if (($format = $definition->guessFormatFromFilename($file))) {
                return $format;
            }
        }

        if ($throwException) {
            throw new FormatterNotFound(sprintf('Formatter not found for file "%s"', $file));
        }
    }

    /**
     * Format a input file into an output file
     * Formatting will guess the formats based on the files
     *
     * @param string $input
     * @param string $output
     *
     * @throws LadyFormat\Exception\FormatterNotFound If no formatter is found
     */
    public function format($input, $output)
    {
        $inputFormat  = $this->guessFormat($input, true);
        $outputFormat = $this->guessFormat($output, true);

        $this->get($inputFormat, $outputFormat)->generateFromFile($input, $output);
    }

    /**
     * Get a formatter able to format from $input to $output format
     *
     * @param string $input
     * @param string $output
     *
     * @throws LadyFormat\Exception\FormatterNotFound If no formatter is found
     *
     * @return LadyFormat\FormatterInterface
     */
    public function get($input, $output)
    {
        if (!$formatter = $this->find($input, $output)) {
            throw new FormatterNotFound(sprintf(
                'Unable to find formatters to format %s to %s',
                $input,
                $output
            ));
        }

        return $formatter;
    }

    /**
     * Find a formatter able to format $input to $output format
     *
     * @param string $input
     * @param string $output
     *
     * @return LadyFormat\FormatterInterface|null
     */
    public function find($input, $output)
    {
        $result = $this->findPaths($this->definitions, $input, $output);

        if ($result instanceof DefinitionInterface) {
            return $this->getFormatterFromDefinition($result);
        }

        if (is_array($result) && count($result) > 1) {
            return $this->getFormatterFromDefinition(new Formatter\Chain($result));
        }
    }

    /**
     * Find the shorter way to format input to output format
     *
     * @param array  $graph
     * @param string $input
     * @param string $output
     *
     * @return array|LadyFormat\DefinitionInterface|null
     */
    protected function findPaths(array $graph, $input, $output)
    {
        $startPaths = $graph;

        // Search all possible start points
        // and removed them from possible next path
        foreach ($startPaths as $key => $def) {
            if ($def->getInputFormat() != $input) {
                unset($startPaths[$key]);
            } else {
                unset($graph[$key]);
            }
        }

        // No start point found
        if (count($startPaths) == 0) {
            return null;
        }

        // Test all possible start points
        $stackPath = array();
        foreach($startPaths as $def) {
            if ($def->getOutputFormat() == $output) {
                return $def;
            }

            if (!$found = $this->findPaths($graph, $def->getOutputFormat(), $output)) {
                continue;
            }

            $stackPath[] = $def;
            $stackPath[] = $found;
        }

        return $stackPath;
    }

    /**
     * Get a formatter from its name
     *
     * @param string $name
     *
     * @return LadyFormat\FormatterInterface
     */
    public function getFormatter($name)
    {
        if (!$this->hasDefinition($name)) {
            throw new FormatterNotFound(sprintf('Formatter %s not found', $name));
        }

        return $this->getFormatterFromDefinition($this->definitions[$name]);
    }

    /**
     * Get a formatter from its definition object
     *
     * @param LadyFormat\DefinitionInterface $definition
     *
     * @return LadyFormat\FormatterInterface
     */
    public function getFormatterFromDefinition(DefinitionInterface $definition)
    {
        if (!array_key_exists($definition->getName(), $this->formatters)) {
            $f = $definition->createFormatter();
            $this->formatters[$definition->getName()] = $f;
        }

        return $this->formatters[$definition->getName()];
    }
}
