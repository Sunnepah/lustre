<?php
/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/25/16
 * Time: 1:50 AM
 */

namespace Luster\Database;

interface DatabaseInterface
{
    public function getAll($table, array $where = []);
    
    public function insert($table, &$object);
    
    public function find($table, $keyName);

    public function update($table, &$object, $keyName);

    public function delete($table, $keyName);
    
}