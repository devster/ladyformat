<?php

namespace LadyFormat\Exception;

class IOException extends \Exception
{
    /**
     * @var string path
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param string     $path
     * @param string     $message
     * @param integer    $code
     * @param \Exception $previous
     */
    public function __construct($path, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->path = (string) $path;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
