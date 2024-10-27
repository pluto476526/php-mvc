<?php

namespace Model;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

Trait Database
{
    private function connect()
    {
        $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
        $conn = new \PDO($string, DBUSER, DBPASS);
        return $conn;
    }

    public function query($query, $data = [])
    {
        $conn = $this->connect();
        $statement = $conn->prepare($query);
        $check = $statement->execute($data);

        if ($check) 
        {
            $result = $statement->fetchAll(\PDO::FETCH_OBJ);

            if (is_array($result) && count($result))
            {
                return $result;
            }
        }

        return false;
    }

    public function get_row($query, $data = [])
    {
        $conn = $this->connect();
        $statement = $conn->prepare($query);
        $check = $statement->execute($data);

        if ($check) 
        {
            $result = $statement->fetchAll(\PDO::FETCH_OBJ);

            if (is_array($result) && count($result))
            {
                return $result['0'];
            }
        }

        return false;
    }
}
