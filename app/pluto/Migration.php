<?php

namespace Pluto;

DEFINED ('CPATH') OR exit ('Access Denied');

class Migration
{
        use \Model\Database;

        protected $columns = [];
        protected $keys = [];
        protected $primaryKeys = [];
        protected $uniqueKeys = [];
        protected $data = [];

    /**
     * This method is used to create a new table in the database.
     *
     * @param string $table The name of the table to be created.
     *
     * @return void
     */
    protected function createTable($table)
    {
        // Check if there are any columns defined
        if (!empty($this->columns))
        {
            // Start building the SQL query
            $query = "CREATE TABLE IF NOT EXISTS $table (";

            // Add each column to the query
            foreach ($this->columns as $column)
            {
                $query .= $column . ",";
            }

            // Add primary keys to the query
            foreach ($this->primaryKeys as $key)
            {
                $query .= "PRIMARY KEY (".$key . "),";
            }

            // Add unique keys to the query
            foreach ($this->uniqueKeys as $key)
            {
                $query .= "UNIQUE KEY (".$key . "),";
            }

            // Add keys to the query
            foreach ($this->keys as $key)
            {
                $query .= "KEY (".$key . "),";
            }

            // Remove trailing comma and add closing parenthesis
            $query = trim($query, ",");
            $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            // Execute the query
            $this->query($query);

            // Clear the column, key, primary key, and unique key arrays
            $this->columns = [];
            $this->keys = [];
            $this->primaryKeys = [];
            $this->uniqueKeys = [];

            // Output success message
            echo "\n\r $table Table created \n\r";
        }
        else
        {
            // Output failure message
            echo "\n\r $table Table not created \n\r";
        }
    }

    /**
     * This method is used to add a new column to the table being created.
     *
     * @param string $text The definition of the column to be added.
     *                     The format should be compatible with MySQL CREATE TABLE syntax.
     *
     * @return void
     *
     */
    protected function addColumn($text)
    {
        // Add the column definition to the columns array
        $this->columns[] = $text;
    }

    /**
     * This method is used to add a primary key to the table being created.
     *
     * @param string $key The name of the primary key column.
     *                    The column must already be defined in the columns array.
     *
     * @return void
     *
     */
    protected function addPrimaryKey($key)
    {
        // Add the primary key to the primaryKeys array
        $this->primaryKeys[] = $key;
    }

    /**
     * This method is used to add a unique key to the table being created.
     *
     * @param string $key The name of the unique key column.
     *                    The column must already be defined in the columns array.
     *
     * @return void
     *
     */
    protected function addUniqueKey($key)
    {
        $this->uniqueKeys[] = $key;
    }

    /**
     * This method is used to add data to be inserted into the table.
     *
     * @param string $key   The name of the column where the data will be inserted.
     *                      The column must already be defined in the columns array.
     * @param mixed  $value The value to be inserted into the specified column.
     *                      The value must be compatible with the column data type.
     *
     * @return void
     *
     */
    protected function adddata($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * This method is used to drop a table from the database.
     *
     * @param string $table The name of the table to be dropped.
     *                      The table must exist in the database.
     *
     * @return void
     *
     * @throws Exception If the table does not exist or an error occurs during the operation.
     */
    protected function dropTable($table)
    {
        // Execute the SQL query to drop the table
        $this->query('drop table '. $table);

        // Output success message
        echo "\n\r Table $table deleted \n\r";
    }

    /**
     * This method is used to insert data into the specified table.
     *
     * @param string $table The name of the table where the data will be inserted.
     *                      The table must already exist in the database.
     *
     * @return void
     *
     * @throws Exception If the data array is empty or an error occurs during the operation.
     */
    protected function insertData($table)
    {
        // Check if the data array is not empty
        if (!empty($this->data))
        {
            // Get the keys from the data array
            $keys = array_keys($this->data);

            // Build the SQL query with placeholders for the values
            $query = "INSERT INTO $table (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";

            // Execute the query with the data array as parameters
            $this->query($query, $this->data);

            // Clear the data array
            $this->data = [];

            // Output success message
            echo "\n\r Data inserted into $table \n\r";
        }
        else
        {
            // Output failure message
            echo "\n\r Data not inserted into $table \n\r";
        }
    }
}