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
 * Opens connection to a database specified by parameters
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
            $this->_dbh->setFetchMode(PDO::FETCH_ASSOC);
            return true;
        } catch (PDOException $ex) {
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
 * @param array $param Optional parameteres for prepared statements
 * @return mixed Associative array containing query result(s), or false on error or empty result
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

            return $stmt->fetchAll();
        } catch (PDOException $ex) {
            return false;
        }
    }

/**
 * Retrieve row with specific id
 *
 * Retreives row with specific id from table specified by attached models name
 *
 * @param mixed $id Row id
 * @return array Associative array containg single result from table, or false on error
 */
    function select($id)
    {
        $sql = 'SELECT * FROM :table WHERE id=:id';
        $param = array(
            'table' => $this->_table,
            'id' => $id
        );

        return query($sql, $param);
    }

/**
 * Retreives all rows from table
 *
 * Retrieves all rows from table specified by attached models name
 *
 * @return array Associative array containg all table entries, or false on error
 */
    function selectAll()
    {
        $sql = 'SELECT * FROM :table';
        $param = array(
            'table' => $this->_table;
        );

        return query($sql, $param);
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