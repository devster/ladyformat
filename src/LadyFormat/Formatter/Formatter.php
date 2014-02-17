<?php

namespace LadyFormat\Formatter;

use LadyFormat\FormatterInterface;
use LadyFormat\Exception\IOException;

/**
 * Add some helper methods to formatters
 */
abstract class Formatter implements FormatterInterface
{
    /**
     * Creates a temporary file.
     * The file is not created if the $content argument is null
     *
     * @param string $content Optional content for the temporary file
     * @param string $extension An optional extension for the filename
     *
     * @throws LadyFormat\Exception\IOException If fails to create the file
     *
     * @return string The filename
     */
    public function createTemporaryFile($content = null, $extension = null)
    {
        $filename = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid('lady_format', true);

        if (null !== $extension) {
            $filename .= '.'.$extension;
        }

        if (null !== $content) {
            $this->writeFile($filename, $content);
        }

        return $filename;
    }

    /**
     * Read the content of a file
     *
     * @param string $file
     *
     * @throws LadyFormat\Exception\IOException If the file doesn't exist
     *
     * @return string
     */
    public function readFile($file)
    {
        if (!file_exists($file)) {
            throw new IOException($file, sprintf('File %s not found', $file));
        }

        $result = @file_get_contents($file);

        if (false === $result) {
            throw new IOException($file, sprintf('Unable to read the file %s', $file));
        }

        return $result;
    }

    /**
     * Write the content in a file
     * Create recursively the path if not exists
     *
     * @param string  $file
     * @param string  $content
     * @param boolean $override
     *
     * @throws LadyFormat\Exception\IOException If the file already exist and override is false
     * @throws LadyFormat\Exception\IOException If unable to create the path
     * @throws LadyFormat\Exception\IOException If unable to write the file
     */
    public function writeFile($file, $content, $override = true)
    {
        if (!$override && file_exists($file)) {
            throw new IOException($file, sprintf('File "%s" already exists.', $file));
        }

        $this->mkdir(dirname($file));

        $result = @file_put_contents($file, $content);

        if (false === $result) {
            throw new IOException($file, sprintf('Unable to write the file "%s".', $file));
        }

        return $result;
    }

    /**
     * Create a directory recursively
     *
     * @param string  $dir
     * @param integer $mode
     *
     * @throws LadyFormat\Exception\IOException If fails to create the directory
     */
    public function mkdir($dir, $mode = 0777)
    {
        if (is_dir($dir)) {
            return;
        }

        if (true !== @mkdir($dir, $mode, true)) {
            throw new IOException($dir, sprintf('Fails to create "%s".', $dir));
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getOutput($string);

    /**
     * {@inheritdoc}
     */
    public function getOutputFromFile($input)
    {
        $content = $this->readFile($input);

        return $this->getOutput($content);
    }

    /**
     * {@inheritdoc}
     */
    public function generate($string, $output, $override = true)
    {
        $string = $this->getOutput($string);

        $this->writeFile($output, $string, $override);
    }

    /**
     * {@inheritdoc}
     */
    public function generateFromFile($input, $output, $override = true)
    {
        $content = $this->getOutputFromFile($input);

        $this->writeFile($output, $content, $override);
    }
}
