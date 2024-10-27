<?php

namespace Model;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

Trait Model
{
    use Database;

    protected $limit =         10;
    protected $offset =        0;
    protected $order_type =   "DESC";
    protected $order_column = "id";
    protected $errors =        [];


    public function findAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";

        return $this->query($query);
    }
    
    public function where($data, $not_data = [])
    {
        $keys = array_keys($data);
        $not_keys = array_keys($not_data);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key)
        {
            $query .= $key . " = :" . $key . " && ";
        }

        foreach ($not_keys as $key)
        {
            $query .= $key . " != :" . $key . " && ";
        } 

        $query = trim($query," && ");

        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset ";
        $data = array_merge($data, $not_data);

        return $this->query($query, $data);
    }

    public function first($data, $not_data = [])
    {
        $keys = array_keys($data);
        $not_keys = array_keys($not_data);
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($keys as $key)
        {
            $query .= $key . " = :" . $key . " && ";
        }

        foreach ($not_keys as $key)
        {
            $query .= $key . " != :" . $key . " && ";
        } 

        $query = trim($query," && ");

        $query .= " limit $this->limit offset $this->offset ";
        $data = array_merge($data, $not_data);

        $result = $this->query($query, $data);

        if ($result)
        {
            return $result[0];
        }
        return false;
    }

    public function insert($data)
    {
        if (!empty($this->allowedColumns))
        {
            foreach ($data as $key => $value)
            {
                if (!in_array($key, $this->allowedColumns))
                {
                    unset($data[$key]);
                }
            }
        }
        
        $keys = array_keys($data);

        $query = "INSERT INTO $this->table (" .implode(",", $keys).") VALUES (:" .implode(",:", $keys).")";

        $this->query($query, $data);

        return false;
    }

    public function update($id, $data, $id_column = 'id')
    {
        if (!empty($this->allowedColumns))
        {
            foreach ($data as $key => $value)
            if (!in_array($key, $this->allowedColumns))
            {
                unset($data[$key]);
            }
        }

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";

        foreach ($keys as $key)
        {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = trim($query,", ");

        $query .= " WHERE $id_column = :$id_column ";

        $data[$id_column] = $id;
        
        $this->query($query, $data);
        return false;
    }

    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);
        
        return $result[0];
    }

    public function countWhere($data, $not_data = [])
    {
        $keys = array_keys($data);
        $not_keys = array_keys($not_data);

        $query = "SELECT COUNT(*) as total FROM $this->table WHERE ";

        foreach ($keys as $key)
        {
            $query .= $key . " = :" . $key . " && ";
        }

        foreach ($not_keys as $key)
        {
            $query .= $key . " != :" . $key . " && ";
        } 

        $query = trim($query, " && ");
        $data = array_merge($data, $not_data);

        $result = $this->query($query, $data);

        return $result[0];
    }

    public function sumColumn(string $column, $data = [], $not_data = [])
    {
        // Build the base query
        $query = "SELECT SUM($column) as total FROM $this->table";

        // Add conditions if provided
        if (!empty($data) || !empty($not_data)) {
            $query .= " WHERE ";
            $keys = array_keys($data);
            $not_keys = array_keys($not_data);

            foreach ($keys as $key) {
                $query .= $key . " = :" . $key . " && ";
            }

            foreach ($not_keys as $key) {
                $query .= $key . " != :" . $key . " && ";
            }

            $query = trim($query, " && ");
        }

        $data = array_merge($data, $not_data);

        // Execute the query and return the result
        $result = $this->query($query, $data);

        return $result[0]->total;
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column ";

        $this->query($query, $data);

        return false;
    }

    public function getError($key)
    {
        if (!empty($this->errors[$key]))
        {
            return $this->errors[$key];
        }

        return "";
    }

    protected function getPrimaryKey()
    {
        return $this->primaryKey ?? 'id';
    }

    public function validate($data)
    {
        $this->errors = [];

        if (!empty($this->primaryKey) && !empty($data[$this->primaryKey]))
        {
            $validationRules = $this->onUpdateValidationRules;
        }
        else
        {
            $validationRules = $this->onInsertValidationRules;
        }

        if (!empty($validationRules))
        {
            foreach ($validationRules as $column => $rules)
            {
                if (!isset($data[$column]))
                    continue;
                
                foreach ($rules as $rule)
                {
                    switch ($rule)
                    {
                        case 'required':
                            if (empty($data[$column]))
                                $this->errors[$column] = ucfirst($column) . " is required";
                            break;

                        case 'email':
                            if (!filter_var(trim($data[$column]), FILTER_VALIDATE_EMAIL))
                                $this->errors[$column] = "Invalid email address";
                            break;

                        case 'alpha':
                            if (!preg_match("/^[a-zA-Z]+$/", trim($data[$column])))
                                $this->errors[$column] = ucfirst($column) . " should only contain alphabetical letters without spaces";
                            break;

                        case 'alpha_space':
                            if (!preg_match("/^[a-zA-Z ]+$/", trim($data[$column])))
                                $this->errors[$column] = ucfirst($column) . " should only contain alphabetical letters & spaces";
                            break;

                        case 'alpha_numeric':
                            if (!preg_match("/^[a-zA-Z0-9]+$/", trim($data[$column])))
                                $this->errors[$column] = ucfirst($column) . " should only alphabetical letters & numbers";
                            break;

                        case 'alpha_numeric_symbol':
                            if (!preg_match("/^[a-zA-Z0-9\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
                                $this->errors[$column] = ucfirst($column) . " should only contain alphabetical letters, numbers, & symbols";
                            break;

                        case 'alpha_symbol':
                            if (!preg_match("/^[a-zA-Z\-\_\$\%\*\[\]\(\)\& ]+$/", trim($data[$column])))
                                $this->errors[$column] = ucfirst($column) . " should only contain alphabetical letters & symbols";
                            break;

                        case 'not_less_than_8_chars':
                            if (strlen(trim($data[$column])) < 8)
                                $this->errors[$column] = ucfirst($column) . " should be 8 or more characters";
                            break;

                        case 'unique':
                            $key = $this->getPrimaryKey();
                            if (!empty($data[$key]))
                                {
                                if ($this->first([$column=>$data[$column]], [$key=>$data[$key]]))
                                    {
                                    $this->errors[$column] = ucfirst($column) . " should be unique";
                                    }
                                }
                            else
                                {
                                if ($this->first([$column=>$data[$column]]))
                                    {
                                    $this->errors[$column] = ucfirst($column) . " should be unique";
                                    }
                                }
                            break;

                        default:
                        $this->errors['rules'] = "The rule ". $rule . " was not found";
                        break;
                    }
                }
            }
        }

        if (empty($this->errors))
        {
            return true;
        }

        return false;
    }
}