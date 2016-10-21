<?php
/**
 * Created by: Sunday Ayandokun
 * Email: sunday.ayandokun@gmail.com
 * Date: 7/31/16
 * Time: 10:05 PM
 */

namespace Lustre\Database;

use Application\Config\DBConfig;
use PDO;
use PDOException;

abstract class PDOMysql implements DatabaseInterface {

    protected $pdo;

    /**
     * PDOMysql constructor.
     */
    function __construct() {
        $option = array ();
        $option['host'] = DBConfig::$driver['mysql']['host'];
        $option['user'] = DBConfig::$driver['mysql']['username'];
        $option['password'] = DBConfig::$driver['mysql']['password'];
        $option['database'] = DBConfig::$driver['mysql']['database'];

        $this->pdo = new PDO('mysql:host=' . $option['host'] . ';dbname=' . $option['database'] . ';charset=utf8mb4',
            $option['user'], $option['password']);
    }

    /**
     * @param $table
     * @param array|NULL $where
     * @return array
     */
    public function getAll($table, array $where = NULL) {
        $query = $this->pdo->prepare("SELECT * FROM :tableName ");
        $query->bindParam(':tableName', $table);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $table
     * @param $object
     * @param string $keyName
     * @return bool
     */
    public function insert($table, &$object, $keyName = 'id') {
        return $this->insertData($table, $object, $keyName);
    }

    /**
     * @param $table
     * @param $id
     * @return mixed
     */
    public function find($table, $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM  :tableName WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tableName', $table);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $table
     * @param $object
     * @param $keyName
     * @return mixed
     */
    public function update($table, &$object, $keyName = 'id') {
        return $this->updateData($table, $object, $keyName);
    }

    /**
     * @param $table
     * @param $id
     * @return mixed
     * @internal param $keyName
     */
    public function delete($table, $id) {

        $stmt = $this->pdo->prepare("DELETE FROM :tableName WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tableName', $table);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * @param $table
     * @param $object
     * @param null $keyName
     * @return bool
     */
    private function insertData($table, &$object, $keyName = NULL) {
        $fmtsql = 'INSERT INTO '.$table.' ( %s ) VALUES ( %s ) ';
        $fields = array();
        $values = array();
        foreach (get_object_vars( $object ) as $k => $v) {
            if (is_array($v) or is_object($v) or $v === NULL) {
                continue;
            }
            if ($k[0] == '_') { // internal field
                continue;
            }
            $fields[] = $table . "." . $k;
            $values[] = "'".$v."'";
        }

        try {
            $query = sprintf ($fmtsql, implode (",", $fields), implode (",", $values));
            $this->pdo->query($query);
        } catch (PDOException $e) {
            throw $e;
        }

        $id = $this->pdo->lastInsertId();
        if ($keyName && $id) {
            $object->$keyName = $id;
        }

        return $object;
    }

    /**
     * @param $table
     * @param $object
     * @param $keyName
     * @param bool $updateNulls
     * @return mixed
     */
    private function updateData($table, &$object, $keyName, $updateNulls=true) {
        $fmtsql = 'UPDATE ' . $table . ' SET %s WHERE %s';
        $where = "";
        $tmp = array();
        foreach (get_object_vars( $object ) as $k => $v) {
            if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
                continue;
            }
            if( $k == $keyName ) { // PK not to be updated
                $where = $keyName . '=' . "'".$v."'";
                continue;
            }
            if ($v === null)
            {
                if ($updateNulls) {
                    $val = 'NULL';
                } else {
                    continue;
                }
            } else {
                $val = $v;
            }
            $tmp[] = $k . '=' . "'" . $val . "'";
        }

        try {
            $query = sprintf($fmtsql, implode( ",", $tmp ) , $where);
            $this->pdo->query($query);

            return $object;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}