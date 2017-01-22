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

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO('mysql:host=' . $option['host'] . ';dbname=' . $option['database'] . ';charset=utf8mb4',
            $option['user'], $option['password'], $opt);
    }

    /**
     * @param $table
     * @param array|NULL $where
     * @return array
     */
    public function getAll($table, array $where = NULL) {
        $query = $this->pdo->prepare("SELECT * FROM {$table}");
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
        $stmt = $this->pdo->prepare("SELECT * FROM {$table} WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res = count($result) > 0 ? $result[0] : [];
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
     */
    public function delete($table, $id) {

        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * @param $table
     * @param $data
     * @param null $keyName
     * @return bool
     */
    private function insertData($table, &$data, $keyName = NULL) {
        $query = 'INSERT INTO ' . $table . ' (%s) VALUES (%s)';

        $columns = array();
        $placeHolders = array();

        $stmt = null;
        $values = array();

        foreach (get_object_vars($data) as $k => $v) {
            if (is_array($v) || is_object($v) || $v === NULL) {
                continue;
            }

            $columns[] = $table . "." . $k;
            $placeHolders[] = "?";

            $values[] = $v;
        }

        try {
            $query = sprintf($query, implode (",", $columns), implode (",", $placeHolders));
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);

        } catch (PDOException $e) {
            throw $e;
        }

        $id = $this->pdo->lastInsertId();
        if ($keyName && $id) {
            $data->$keyName = $id;
        }

        return $data;
    }

    /**
     * @param $table
     * @param $data
     * @param $keyName
     * @param bool $updateNulls
     * @return mixed
     */
    private function updateData($table, &$data, $keyName, $updateNulls=true) {
        $query = 'UPDATE ' . $table . ' SET %s WHERE %s';
        $where = "";
        $tmp = array();
        $values = array();

        foreach (get_object_vars($data) as $k => $v) {
            if (is_array($v) || is_object($v) || $k[0] == '_' ) {
                continue;
            }

            if ($k == $keyName) {
                $where = $keyName . '=' . "'" . $v . "'";
                continue;
            }

            if ($v === null) {
                if ($updateNulls) {
                    $val = 'NULL';
                } else {
                    continue;
                }
            } else {
                $val = $v;
            }

            $tmp[] = $k . '=' . "?";
            $values[] = $val;
        }

        try {
            $query = sprintf($query, implode( ",", $tmp ) , $where);
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);

            return $data;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}