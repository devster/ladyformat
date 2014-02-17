<?php

namespace LadyFormat\Formatter;

use \Michelf\Markdown;

/**
 * PhpMarkdown formatter
 *
 * This formatter use the PHP Markdown Lib of Michel Fortin
 *
 * @link https://github.com/michelf/php-markdown
 */
class PhpMarkdownFormatter extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function getOutput($string)
    {
        return Markdown::defaultTransform($string);
    }
}
