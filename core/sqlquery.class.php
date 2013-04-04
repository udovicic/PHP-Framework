<?php

/**
 * Database abstraction layer
 *
 * Manages all communication between database and application
 */
abstract class SQLQuery {

/** @var object Database handle */
    protected $_dbh;
/** @var integer Number of rows affected by last query */
    protected $_rowCount;

/**
 * Connect to a database
 *
 * Opens connection to a database specified by the parameters
 *
 * @param string $host Database server address
 * @param string $user Database user
 * @param string $password Database password
 * @param string $dbname Name of database
 * @return bool True if connection was successful
 */
    function connect($host, $user, $password, $dbname)
    {
        try {
            $this->_dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return true;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
            return false;
        }
    }

/**
 * Disconnect from a database
 */
    function disconnect()
    {
        $this->_dbh = null;
    }

/**
 * Perform SQL query
 *
 * @param string $query SQL query which should be executed
 * @param array $param Optional parameters for prepared statements
 * @return mixed Associative array containing query result(s), or false in case of error, or empty result
 */
    function query($query, $param = null)
    {
        if ($param == null) {
            $param = array();
        }

        try {
            $stmt = $this->_dbh->prepare($query);
            $stmt->execute($param);
            $this->_rowCount = $stmt->rowCount();

            if ($this->_rowCount == 0) {
                return false;
            }

            if (preg_match("/select/i", $query)) {// SELECT statment was used
                $result = $stmt->fetchAll();
            } else {
                $result = true;
            }

            return $result;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
            return false;
        }
    }

/**
 * Retrieves row with specific id
 *
 * Retreives row with specific id from table specified by attached model's name
 *
 * @param mixed $id Row id
 * @return array Associative array containing single result from table, or false in case of error, or empty result
 */
    function select($id)
    {
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE id=:id';
        $param = array(
            'id' => $id,
        );
        $result = $this->query($sql, $param);
        
        return $result[0];
    }

/**
 * Retreives all rows from table
 *
 * Retrieves all rows from table specified by attached model's name
 *
 * @return array Associative array containing all table entries, or false in case of error, or empty result
 */
    function selectAll()
    {
        $sql = 'SELECT * FROM ' .$this->_table;

        return $this->query($sql);
    }

/**
 * Returns number of rows affected with last query
 *
 * @return int Number of affected rows
 */
    function numRows()
    {
        if (isset($this->_rowCount)) {
            return $this->_rowCount;
        }
        
        return 0;
    }
}
