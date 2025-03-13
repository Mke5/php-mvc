<?php 

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Main Model trait
 */
trait Model
{
    use Database;

    protected $limit        = 10;
    protected $offset       = 0;
    protected $order_type   = "desc";
    protected $order_column = "id";
    public $errors          = [];

    public function findAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        return $this->read($query);
    }

    public function where($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key) {
            $query .= "$key = :$key AND ";
        }

        foreach ($keys_not as $key) {
            $query .= "$key != :$key AND ";
        }
        
        $query = rtrim($query, " AND ");
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";

        return $this->read($query, array_merge($data, $data_not));
    }

    public function first($data, $data_not = [])
    {
        $result = $this->where($data, $data_not);
        return $result ? $result[0] : false;
    }

    public function insert($data)
    {
        $data = $this->filterAllowedColumns($data);
        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";
        return $this->write($query, $data);
    }

    public function update($id, $data, $id_column = 'id')
    {
        $data = $this->filterAllowedColumns($data);
        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        foreach ($keys as $key) {
            $query .= "$key = :$key, ";
        }

        $query = rtrim($query, ", ");
        $query .= " WHERE $id_column = :$id_column";
        $data[$id_column] = $id;
        
        return $this->write($query, $data);
    }

    public function delete($id, $id_column = 'id')
    {
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column";
        return $this->write($query, [$id_column => $id]);
    }

    private function filterAllowedColumns($data)
    {
        if (!empty($this->allowedColumns)) {
            return array_filter($data, function ($key) {
                return in_array($key, $this->allowedColumns);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $data;
    }
}
