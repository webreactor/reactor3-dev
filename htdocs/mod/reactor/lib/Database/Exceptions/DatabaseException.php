<?php

namespace Reactor\Database\Exceptions;

/**
 * Class DatabaseException
 *
 * @package Reactor\Database\Exceptions
 */
class DatabaseException extends \Exception
{
    protected $context;
    
    /**
     * DatabaseException constructor.
     *
     * @param string $message
     * @param null   $context
     */
    public function __construct($message, $context = null)
    {
        parent::__construct($message);
        
        $this->context = $context;
    }
    
    /**
     * @return null
     */
    public function getContext()
    {
        return $this->context;
    }
}
