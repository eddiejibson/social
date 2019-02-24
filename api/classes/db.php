<?php

class db
{

    private $db; //Connection

    public function __construct(array $credentials = [])
    {
        $this->connect($credentials);
        if (!$this->db) {
            die();
        }
    }

    public function __destruct()
    {
        pg_close($this->db);
        $this->db = null;
    }

    private function connect(array $credentials)
    {
        $host = $credentials["host"] ?? "127.0.0.1";
        $port = (int)$credentials["port"] ?? 5432;
        $dbname = $credentials["dbname"] ?? "oxro";
        $user = $credentials["user"] ?? "postgres";
        $password = $credentials["password"] ?? "";
        $this->db = pg_connect("host=$host port=$port dbname=$dbname password=$password");
    }

    private function prepare(string $query, string $stmtName)
    {
        return pg_prepare($this->db, $stmtName, $query);
    }

    private function execute(array $details, string $stmtName)
    {
        $res = pg_execute($this->db, $stmtName, $details);
        if (pg_last_error()) {
            $this->err(pg_last_error());
        }
        return pg_fetch_object($res);
    }

    public function findOne(string $query, array $details = [], string $stmtName)
    {
        $prepared = $this->prepare($query, $stmtName);
        $res = $this->execute($details, $stmtName);
    }

    public function find(string $query, array $details = [], string $stmtName)
    {
        $prepared = $this->prepare($query, $stmtName);
        $res = $this->execute($details, $stmtName);
    }

    private function err($err)
    {
        throw new Exception("Error executing query: " . pg_last_error(), 1);
    }
}
