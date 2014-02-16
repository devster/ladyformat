<?php

namespace LadyFormat\Formatter;

class SnappyPdf extends Definition
{
    /**
     * @var string wkhtmltopdf binary
     */
    protected $binary = 'wkhtmltopdf';

    /**
     * @var array options which will be passed to the SnappyFormatter
     */
    protected $options = array();

    /**
     * Set the wkhtmltopdf binary
     *
     * @param string $binary
     *
     * @return SnappyPdf The current instance
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * Set snappy options
     *
     * @param array $options
     *
     * @return SnappyPdf The current instance
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createFormatter()
    {
        $classname = $this->getFormatterClass();

        return new $classname($this->binary, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'snappy-pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputFormat()
    {
        return 'html';
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputFormat()
    {
        return 'pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return array(
            'html' => 'html',
            'pdf'  => 'pdf'
        );
    }
}
