<?php

namespace Reactor\Database\Interfaces;

/**
 * Interface QueryInterface
 *
 * @package Reactor\Database\Interfaces
 */
interface QueryInterface extends \Iterator
{
    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function exec($parameters = []);
    
    /**
     * @param string $row
     *
     * @return mixed
     */
    public function line($row = '*');
    
    /**
     * @return mixed
     */
    public function free();
    
    /**
     * @param null   $key
     * @param string $row
     *
     * @return mixed
     */
    public function matr($key = null, $row = '*');
    
    /**
     * @return mixed
     */
    public function count();
    
    /**
     * @return mixed
     */
    public function getStats();
    
    /**
     * @return mixed
     */
    public function current();
    
    /**
     * @return mixed
     */
    public function key();
    
    /**
     * @return mixed
     */
    public function next();
    
    /**
     * @return mixed
     */
    public function rewind();
    
    /**
     * @return mixed
     */
    public function valid();
}
