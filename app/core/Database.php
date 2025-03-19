<?php 
namespace App\Models;

use PDO;
use PDOException;

defined('ROOTPATH') OR exit('Access Denied!');

trait Database
{
    private function connect()
    {
        try {
            $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT         => true
            ];
            
            return new PDO($dsn, DBUSER, DBPASS, $options);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public function read($query, $data = [])
    {
        $db = $this->connect();
        $stmt = $db->prepare($query);
        $stmt->execute($data);

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return (!empty($result)) ? $result : false;
    }

    public function write($query, $data = [])
    {
        $db = $this->connect();
        $stmt = $db->prepare($query);
        return $stmt->execute($data);
    }
}
