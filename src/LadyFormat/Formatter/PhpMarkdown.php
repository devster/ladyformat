<?php

namespace LadyFormat\Formatter;

/**
 * PhpMarkdown definition
 */
class PhpMarkdown extends Definition
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'php-markdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputFormat()
    {
        return 'markdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputFormat()
    {
        return 'html';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return array(
            'markdown' => 'markdown',
            'md'       => 'markdown',
            'html'     => 'html',
        );
    }
}
