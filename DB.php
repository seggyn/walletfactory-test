<?php


class DB
{
    private static $instance = null;
    private $conn;

    private static $DB_HOST = '';
    private static $DB_PORT = '';
    private static $DB_NAME = '';
    private static $DB_USER = '';
    private static $DB_PASS = '';

    public function __construct()
    {
        $this->conn = new PDO(
            'pgsql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME. ';port=' .self::$DB_PORT,
            self::$DB_USER, self::$DB_PASS,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function connect($host, $port, $db_name, $user, $password) {
        self::$DB_HOST = $host;
        self::$DB_PORT = $port;
        self::$DB_NAME = $db_name;
        self::$DB_USER = $user;
        self::$DB_PASS = $password;
        return self::getInstance();
    }

    public function select($table, $array) {
        $sql = 'SELECT '.implode(',', $array).' FROM '.$table.';';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function insert($table, $array) {
        $sql = "INSERT INTO ".$table." (".implode(',', array_keys($array)).")";
        $sql .= " VALUES (:".implode(",:", array_keys($array)).");";
        $this->conn->prepare($sql)->execute($array);
    }
}