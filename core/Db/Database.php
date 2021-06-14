<?php


namespace app\core\Db;


use app\core\Application;

class Database
{
    public \PDO $pdo;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        try {
            $this->pdo = new \PDO("mysql:host=$servername;dbname=mvc_framework", $username, $password);
            // set the PDO error mode to exception
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];
        $files = scandir(Application::$RootDir.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration)
        {
            if ($migration === '.' || $migration === '..'){
                continue;
            }
            require_once Application::$RootDir.'/migrations/'.$migration;
            $className =  pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migrations $migration");
            $instance->up();
            $this->log("Applied migrations $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)){
            $this->saveMigration($newMigrations);
        }
        else{
            $this->log('All migrations are applied');
        }
//        echo '<pre>';
//        var_dump($toApplyMigrations);
//        echo '</pre>';
//        exit;
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations;");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigration(array $migrations)
    {
        $str = implode(",",array_map(fn($m)=> "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str;");
        $statement->execute();
    }
    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    public function log($message){
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}