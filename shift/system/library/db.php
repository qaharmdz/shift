<?php

declare(strict_types=1);

class DB {
    private $adaptor;

    public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL)
    {
        $this->adaptor = new \DB\MySQLi($hostname, $username, $password, $database, $port);
    }

    public function query($sql, $params = array())
    {
        return $this->adaptor->query($sql, $params);
    }

    public function escape($value)
    {
        return $this->adaptor->escape($value);
    }

    public function countAffected()
    {
        return $this->adaptor->countAffected();
    }

    public function getLastId()
    {
        return $this->adaptor->getLastId();
    }

    public function connected()
    {
        return $this->adaptor->connected();
    }
}
