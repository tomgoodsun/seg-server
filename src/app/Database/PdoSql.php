<?php
namespace App\Database;

use PDO;

class PdoSql
{
    private $driverOptions = [];
    protected $pdo = null;
    protected $statement = null;
    protected $dsn = '';

    /**
     * Database configuration must be kept in instance variables
     */
    protected $host = '';
    protected $dbname = '';
    protected $username = '';
    protected $password = '';
    protected $port = '';
    protected $tablePrefix = '';
    protected $tablePrefixSign = '';

    /**
     * MySQL class original variable
     * To use transaction because MySQL default setting of transaction is turned off
     */
    private $autoTransaction = false;

    /**
     * Connection must
     */
    protected $connection = null;

    /**
     * The SQL is kept temporarily before execute
     */
    private $temporarySql = '';

    public function __construct($dsn, $username, $password, array $dbconfig)
    {
        $dbconfig += [
            'options'  => [],
        ];
        $this->dsn = $dsn;
        $this->driverOptions = $dbconfig['options'];
        static::initialize($dsn, $username, $password, $dbconfig);
    }

    /**
     * Parse DSN into vendor and source
     *
     * @param string $dsn  DSN string
     * @return array Parsed result
     */
    public static function parseDsnByVendor($dsn)
    {
        $vendor = $source = '';
        $colonPos = strpos($dsn, ':');
        if ($colonPos > 0) {
            $vendor = substr($dsn, 0, $colonPos);
            $source = substr($dsn, $colonPos + 1, strlen($dsn));
        }
        return [$vendor, $source];
    }

    /**
     * Parse DSN
     *
     * @param string $dsn  DSN string
     * @return array Parsed result
     */
    public static function parseDsn($dsn)
    {
        $params = [];
        list($vendor, $source) = self::parseDsnByVendor($dsn);
        $params['vendor'] = $vendor;
        $settings = explode(';', $source);
        foreach ($settings as $setting) {
            list($label, $value) = explode('=', $setting);
            $params[$label] = $value;
        }
        ksort($params);
        return $params;
    }

    /**
     * Detect query type
     *
     * @param string $query  SQL
     * @return string  Query type
     */
    public static function detectQueryType($query)
    {
        $queryTypes = [
            'select',
            'show',
            'describe',
            'explain',
            'update',
            'insert',
            'delete',
            'alter table',
            'create',
            'drop'
        ];
        foreach ($queryTypes as $type) {
            if (preg_match('/\bselect\b/i', $query)) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Compute offset/limit
     *
     * @param int $page = null         Page No.
     * @param int $itemPerPage = null  Number of items per page
     * @return array (offset/row count)
     */
    public static function computeOffset($page = null, $itemPerPage = null)
    {
        $offset = $rowCount = null;
        if ($page !== null && $itemPerPage !== null) {
            $offset = intval($page) <= 0 ? 0 : (intval($page) - 1) * intval($itemPerPage);
            $rowCount = intval($itemPerPage);
        }
        return [$offset, $rowCount];
    }

    /**
     * Check availability of database vendor
     *
     * @param boolean $return = false  Use return or not (exception)
     */
    protected function isAvailable($return = false)
    {
        if ($return) {
            return class_exists('\PDO');
        }
        if (!class_exists('\PDO')) {
            throw new \Exception('This server\'s PHP does not have commands.');
        }
    }

    /**
     * Connect to MySQL server
     */
    public function connect()
    {
        if (!$this->pdo) {
            try {
                $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->driverOptions);
            } catch (\PDOException $e) {
                throw new \Exception('Connection error: ' . $e->getMessage());
            }
        }
        return $this;
    }

    /**
     * Disconnect from MySQL server
     */
    public function close()
    {
        $this->pdo = null;
    }

    /**
     * Start MySQL transaction
     */
    public function begin()
    {
        $this->startTransaction();
    }

    /**
     * Start MySQL transaction
     */
    public function startTransaction()
    {
        $this->pdo->startTransaction();
    }

    /**
     * Rollback transaction
     *
     * @access    public
     */
    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Commit transaction
     *
     * @access    public
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Create data set for each result type to return to caller
     *
     * @param Result $result            The pointer to the resource of result
     * @param string $vendorResultType  Result type of returned array
     *     [ASSOC]
     *         Default. 2-level numbered-associated array.
     *     [NUM]
     *         Default. 2-level numbered-numbered array.
     */
    protected function fetchResultByType($result, $vendorResultType)
    {
        return $result->fetchAll($vendorResultType);
    }

    /**
     * Execute temporary set query
     * Every query is executed by this function
     *
     * @param array $params
     * @return \PDOStatement
     */
    public function executeQuery(array $params = [])
    {
        $params = $this->quoteParams($params);
        $this->statement->execute($params);
        $result = $this->statement;
        return $result;
    }

    /**
     * Get affected rows
     *
     * @param \PDOStatement $result
     * @return int
     */
    protected function affectedRows($result)
    {
        return $result->rowCount();
    }

    /**
     * This function must be called by subclass
     *
     * @param string $dsn           DSN
     * @param string $username      Username
     * @param string $password      Password
     * @param array $dbconfig = []  Array of database configurations
     */
    protected function initialize($dsn, $username, $password, array $dbconfig = [])
    {
        $this->dsn = $dsn;
        $dbconfig += static::parseDsn($this->dsn);
        $dbconfig += [
            'host' => 'localhost',
            'dbname' => '',
            'username' => '',
            'password' => '',
            'port' => '',
            'table_prefix' => '',
            'table_prefix_sign' => '#__',
            'auto_transaction' => false,
        ];
        $this->isAvailable(false);
        $this->host = $dbconfig['host'];
        $this->dbname = $dbconfig['dbname'];
        $this->username = $username;
        $this->password = $password;
        $this->port = $dbconfig['port'];
        $this->tablePrefix = $dbconfig['table_prefix'];
        $this->tablePrefixSign = $dbconfig['table_prefix_sign'];
        $this->autoTransaction = $dbconfig['auto_transaction'];
    }

    /**
     * Quote value for SQL
     *
     * @param array $params  Bind parameters
     * @return array  Quoted bind prameters
     */
    public function quoteParams(array $params)
    {
        foreach ($params as &$param) {
            $param = $this->quote($param);
        }
        return $params;
    }

    /**
     * Quote value for SQL
     *
     * @param mixed $value  Bind parameter
     * @return string  Quoted value
     */
    public function quote($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return "'" . addslashes((string)$value) . "'";
    }

    /**
     * Bind values to SQL
     * *CAUTION* This method DOES NOT work for some vendor.
     *
     * @param $query
     * @param array $params
     * @return String  Value-binded SQL statement
     */
    public function bindValues($query, array $params)
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            } else {
                $value = $this->quote($value);
            }
            //$pattern = '/\b' . preg_quote($key) . '\b/';
            $pattern = '/' . preg_quote($key) . '\b/';
            if (preg_match($pattern, $query)) {
                $query = preg_replace($pattern, $value, $query);
                unset($params[$key]);
            }
        }
        if (count($params) > 0) {
            throw new \Exception('Number of bind parameters does not match to specified ones on query.');
        }
        return $query;
    }

    /**
     * Prepare SQL and database before executing query
     *
     * @param string $query            SQL statement
     * @param int $page = null         Page No.
     * @param int $itemPerPage = null  Number of items per page
     */
    protected function prepare($query, $page = null, $itemPerPage = null)
    {
        if ($page !== null && $itemPerPage !== null) {
            list($offset, $rowCount) = static::computeOffset($page, $itemPerPage);
            $query = $this->createLimitQuery($query, $offset, $rowCount);
        }
        $this->setQuery($query);
        $this->statement = $this->pdo->prepare($this->getTemporarySql());
    }

    /**
     * Export binded SQL statement
     *
     * @param $query                   SQL statement
     * @param int $page = null         Page No.
     * @param int $itemPerPage = null  Number of items per page
     * @return string Binded SQL statement
     */
    public function exportBindSql($query, array $params, $page = null, $itemPerPage = null)
    {
        if ($page !== null && $itemPerPage !== null) {
            list($offset, $rowCount) = static::computeOffset($page, $itemPerPage);
            $query = $this->createLimitQuery($query, $offset, $rowCount);
        }
        $query = $this->bindValues($query, $params);
        return $query;
    }

    /**
     * Create offset-limit query
     *
     * @param string $query  SQL statement
     * @param int $offset    Offset
     * @param int $rowCount  Limit
     * @return string  SQL
     */
    protected function createLimitQuery($query, $offset, $rowCount)
    {
        $query .= sprintf(' LIMIT %d, %d', $offset, $rowCount);
        $query = preg_replace('/\bselect\b/i', 'SELECT SQL_CALC_FOUND_ROWS ', $query);
        return $query;
    }

    /**
     * Set and convert sql temporarily into instance variables
     *
     * @param string $query  SQL statement
     */
    protected function setQuery($query)
    {
        $this->temporarySql = str_replace($this->tablePrefixSign, $this->tablePrefix . '_', $query);
    }

    /**
     * Get temporary query
     */
    public function getTemporarySql()
    {
        return $this->temporarySql;
    }

    /**
     * Get hostname
     *
     * @return string  Hostname
     */
    public function getHostName()
    {
        return $this->host;
    }

    /**
     * Get port no
     *
     * @return int  Port no
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Free result on memory and set empty the temporary sql variable
     */
    public function freeResult()
    {
        $this->temporarySql = '';
    }

    /**
     * Execute DML query statement
     *
     * @param $query                   SQL statement
     * @param int $page = null         Page No.
     * @param int $itemPerPage = null  Number of items per page
     * @param $resultType              Type of result array
     */
    public function query($query, array $params = [], $page = null, $itemPerPage = null, $resultType = 'ASSOC')
    {
        $data = $this->fetchResult($query, $params, $page, $itemPerPage, $resultType);
        $count = null;
        if ($page !== null && $itemPerPage !== null) {
            $counter = $this->query('SELECT FOUND_ROWS() AS count');
            $count = $counter[0]['count'];
        }
        $result = new Result($data, $page, $itemPerPage, $count);
        return $result;
    }

    /**
     * Execute DDL query statement
     *
     * @param string $query      SQL statement
     * @param array $param = []  Bind parameters
     * @return array|object      Resultset
     */
    public function exec($query, array $params = [])
    {
        try {
            $result = $this->execute($query, $params);
            if (!$result) {
                throw new \Exception('Unavailable result set is return');
            }
        } catch (\Exception $e) {
            $this->freeResult($result);
            //$e->printAll();
        }
        return $result;
    }

    /**
     * Execute DDL query statement
     *
     * @param string $query      SQL statement
     * @param array $param = []  Bind parameters
     * @return int
     */
    public function execute($query, array $params = [])
    {
        $this->prepare($query);
        $result = null;
        if ($this->autoTransaction) {
            $this->startTransaction();
            try {
                $result = $this->executeQuery($params);
                $this->commit();
            } catch (\Exception $e) {
                $this->rollback();
            }
        } else {
            $result = $this->executeQuery($params);
        }
        return $this->affectedRows($result);
    }

    /**
     * Get last instert ID
     *
     * @param string $name = null  Name of sequence object
     * @return int Last insert ID
     */
    public function lastInsertId($name = null)
    {
        return $this->statement->lastInsertId($name);
    }

    /**
     * Fetch result
     *
     * @param string $query            SQL statement
     * @param array $param = []        Bind parameters
     * @param int $page = null         Page No.
     * @param int $itemPerPage = null  Number of items per page
     * @param $resultType              Type of result array
     * @return \PDOStatement
     * @throws \Exception
     */
    protected function fetchResult($query, array $params = [], $page = null, $itemPerPage = null, $resultType = 'ASSOC')
    {
        $this->prepare($query, $page, $itemPerPage);
        $result = $this->executeQuery($params);
        try {
            if (!$result) {
                throw new \Exception('Unexecutable query is set.');
            }
            $vendorResultType = $this->getResultType($resultType);
            return $this->fetchResultByType($result, $vendorResultType);
        } catch (\Exception $e) {
            $this->freeResult($result);
            //$e->printAll();
        }
    }

    /**
     * Get result type for vendors
     *
     * @param string $resultType  Result type of returned array
     *     [ASSOC]
     *         Default. 2-level numbered-associated array.
     *     [NUM]
     *         Default. 2-level numbered-numbered array.
     */
    protected function getResultType($resultType)
    {
        switch ($resultType) {
            case 'NUM':
                return PDO::FETCH_NUM;
            case 'ASSOC':
            default:
                return PDO::FETCH_ASSOC;
        }
    }
}
