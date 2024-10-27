<?php

namespace Pluto;

DEFINED ('CPATH') OR exit ('Access Denied');

class Pluto
{
    private $version = '0.0.1 beta';

    /**
     * Handles database operations.
     *
     * @param array $argv Command line arguments.
     * @return void
     */
    public function db($argv)
    {
        /**
         * The mode of operation.
         *
         * @var string|null
         */
        $mode = $argv[1] ?? null;

        /**
         * Additional parameter for the operation.
         *
         * @var string|null
         */
        $param1 = $argv[2] ?? null;

        switch ($mode)
        {
            case 'db:create':
                // Check if database name is provided.
                if (empty($param1))
                    die("\n\rPlease provide database name\n\r");

                // Create a new database.
                $db = new Database;
                $query = "create database if not exists ". $param1;
                $db->query($query);
                die("\n\rDatabase created\n\r");
                break;

            case 'db:table':
                // Check if table name is provided.
                if (empty($param1))
                    die("\n\rPlease provide table name\n\r");

                // Describe the table.
                $db = new Database;
                $query = "describe ". $param1;
                $result = $db->query($query);

                // Print the result or display an error message.
                if ($result)
                {
                    print_r($result);
                }
                else
                {
                    echo "\n\r$param1 Data not found\n\r";
                }
                die();
                break;

            case 'db:drop':
                // Check if database name is provided.
                if (empty($param1))
                    die("\n\rPlease provide database name\n\r");

                // Drop the database.
                $db = new Database;
                $query = "drop database ". $param1;
                $db->query($query);
                die("\n\rDatabase deleted\n\r");
                break;

            case 'db:seed':
                // TODO: Implement database seeding.
                break;

            default:
                // Display an error message for unknown commands.
                echo "\n\rUnknown command $argv[1]";
                break;
        }
    }

    /**
     * Handles information retrieval operations.
     *
     * @param array $argv Command line arguments.
     * @return void
     */
    public function info($argv)
    {
        /**
         * The mode of operation.
         *
         * @var string|null
         */
        $mode = $argv[1] ?? null;

        switch ($mode)
        {
            case 'info:migrations':
                // Define the folder path for migration files.
                $folder = 'app'.DS.'migrations'.DS;

                // Check if the migration folder exists.
                if (!file_exists($folder))
                {
                    // If the folder does not exist, display an error message.
                    die("\n\r No migrations \n\r");
                }

                // Retrieve all PHP files in the migration folder.
                $files = glob($folder . "*.php");

                // Display a header for the migration files.
                echo "\n\r Migration files:\n\r";

                // Iterate through the migration files and display their names.
                foreach ($files as $file)
                {
                    echo "\n\r". basename($file) . "\n\r";
                }

                break;
            
            default:
                // If an unknown mode is provided, do nothing.
                break;
        }
    }

    /**
     * Handles class generation operations.
     *
     * @param array $argv Command line arguments.
     * @return void
     */
    public function spit($argv)
    {
        /**
         * The mode of operation.
         *
         * @var string|null
         */
        $mode = $argv[1] ?? null;

        /**
         * The name of the class to be generated.
         *
         * @var string|null
         */
        $classname = $argv[2] ?? null;

        // Check if a class name is provided.
        if (empty($classname))
            die("\n\r Please provide class name \n\r");

        // Sanitize the class name to ensure it only contains alphanumeric characters and underscores.
        $classname = preg_replace("/[^a-zA-Z0-9_]+/", "", $classname);

        // Check if the class name starts with a letter.
        if (preg_match("/^[^a-zA-Z_]+/", $classname))
            die("\n\r Check class name \n\r");

        // Switch based on the mode of operation.
        switch ($mode)
        {
            case 'spit:controller':
                // Generate a new controller file.
                $filename = 'app'.DS.'controllers'.DS.ucfirst($classname) . ".php";

                // Check if the controller file already exists.
                if (file_exists($filename))
                    die("\n\r Controller already exists \n\r");

                // Read the sample controller file.
                $sample_file = file_get_contents('app'.DS.'pluto'.DS.'samples'.DS.'controller-sample.php');

                // Replace placeholders in the sample file with the actual class name.
                $sample_file = preg_replace("/\{CLASSNAME\}/", ucfirst($classname), $sample_file);
                $sample_file = preg_replace("/\{classname\}/", strtolower($classname), $sample_file);

                // Write the new controller file.
                if (file_put_contents($filename, $sample_file))
                {
                    die("\n\r Controller created \n\r");
                }
                else
                {
                    die("\n\r Fatal error \n\r");
                }
                break;

            // Other cases for generating model, migration, and seeder files.
            // ...

            default:
                // Display an error message for unknown commands.
                echo "\n\rUnknown command $argv[1]";
                break;
        }
    }

    /**
     * Handles migration operations.
     *
     * @param array $argv Command line arguments.
     * @return void
     */
    public function migrate($argv)
    {
        /**
         * The mode of operation.
         *
         * @var string|null
         */
        $mode = $argv[1] ?? null;

        /**
         * The name of the migration file.
         *
         * @var string|null
         */
        $filename = $argv[2] ?? null;

        // Construct the full path to the migration file.
        $filename = "app".DS."migrations".DS.$filename;

        // Check if the migration file exists.
        if (file_exists($filename))
        {
            // Include the migration file.
            require $filename;

            // Extract the class name from the migration file name.
            preg_match("/[a-zA-Z]+\.php$/", $filename, $match);
            $classname = str_replace(".php", "", $match[0]);
            
            // Instantiate the migration class.
            $myclass = new ("\Pluto\\$classname")();

            // Perform the specified migration operation.
            switch ($mode) {
                case 'migrate':
                    $myclass->up();
                    echo("\n\r Tables created \n\r");
                    break;

                case 'migrate:rollback':
                    $myclass->down();
                    echo("\n\r Tables removed \n\r");
                    break;

                case 'migrate:refresh':
                    $myclass->down();
                    $myclass->up();
                    echo("\n\r Tables data refreshed \n\r");
                    break;
                
                default: 
                    $myclass->up();
                    break;
            }
            
        }
        else
        {
            // Display an error message if the migration file is not found.
            die("\n\r Migration file not found \n\r");
        }

        // Display a success message after processing the migration file.
        echo "\n\r Migration " . basename($filename) . " processed\n\r";
    }

    /**
     * Displays the help information for the Pluto CLI.
     *
     * @return void
     */
    public function help()
    {
        echo "

        Pluto v$this->version

        Database
            db:create           Creates a new database schema.
            db:seed             Runs the specified seeder to populate a database.
            db:table            Retrieves info on the selected table.
            db:drop             Deletes a database.
            migrate             Locates and runs a migration file.
            migrate:refresh     Runs the 'down' & then 'up' methods.
            migrate:rollback    Runs the 'down' method.

        Generators
            spit:controller     Generates a new controller file.
            spit:migration      Generates a new migration file.
            spit:model          Generates a new model file.
            spit:seeder         Generates a new seeder file.

        Other
            info:migrations    Displays all migration files available

        ";
    }
}