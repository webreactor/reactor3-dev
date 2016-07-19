<?php

namespace Reactor\Database\Interfaces;

/**
 * Interface ConnectionInterface
 *
 * @package Reactor\Database\Interfaces
 */
interface ConnectionInterface
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function sql($query);
    
    /**
     * @param null $name
     *
     * @return mixed
     */
    public function lastId($name = null);
    
    /**
     * @param $table
     * @param $data
     *
     * @return mixed
     */
    public function insert($table, $data);
    
    /**
     * @param $table
     * @param $data
     *
     * @return mixed
     */
    public function replace($table, $data);
    
    /**
     * @param        $table
     * @param        $data
     * @param array  $where_data
     * @param string $where
     *
     * @return mixed
     */
    public function update($table, $data, $where_data = [], $where = '');
    
    /**
     * @param      $query
     * @param      $parameters
     * @param      $page
     * @param      $per_page
     * @param null $total_rows
     *
     * @return mixed
     */
    public function pages($query, $parameters, $page, $per_page, $total_rows = null);
}
