<?php

namespace LadyFormat\Formatter;

use Knp\Snappy\Pdf;

/**
 * SnappyPdf formatter
 *
 * This formatter use the Snappy lib by KnpLabs
 * which use itself the tool wkhtmltopdf
 *
 * @link https://github.com/KnpLabs/snappy
 * @link https://github.com/wkhtmltopdf/wkhtmltopdf
 */
class SnappyPdfFormatter extends Formatter
{
    /**
     * @var Knp\Snappy\Pdf Snappy instance
     */
    public $snappy;

    /**
     * Constructor.
     */
    public function __construct($binary = 'wkhtmltopdf', array $options = array())
    {
        $this->snappy = new Pdf($binary);
        $this->setOptions($options);
    }

    /**
     * Set Snappy options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->snappy->setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput($string)
    {
        return $this->snappy->getOutputFromHtml($string);
    }
}
