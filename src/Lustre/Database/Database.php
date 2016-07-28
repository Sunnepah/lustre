<?php
/**
 * Created by PhpStorm.
 * User: sunnepah
 * Date: 6/25/16
 * Time: 3:11 AM
 */

namespace Lustre\Database;

class Database extends MysqliDBO
{
    /*
     * This class extends from the chosen implementation of 
     * Data storage mechanism. If the storage mechanism changes,
     * this class should extend the new implementation knowing that the new mechanism must implement DatabaseInterface
     */
}