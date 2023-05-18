<?php

/**
 * A class representing a Database and its methods
 */
class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection = null;

    /**
     * Database constructor
     * 
     * The constructor pulls the data from the API, creates the tables and inserts the data.
     *
     * @param string $host The database host.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     * @param string $database The name of the database.
     **/
    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
        $this->createUsersTable();
        $this->createPostsTable();
        $this->createDateHourPostsTable();
    }

    /**
     * Database destructor to disconnect from the database.
     **/
    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * To connect to the database.
     * 
     * @throws PDOException if connection has failed.
     **/
    private function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: {$e->getMessage()}");
        }
    }

    /**
     * To insert a row into a table.
     *
     * @param string $table Table to insert data to.
     * @param array $data Data to insert.
     * @return PDOStatement|bool PDOStatement/false if insertion succeeded/failed.
     **/
    public function insert($table, $data)
    {
        $columns = "" . implode(", ", array_keys($data)) . "";
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        return $this->executeQuery($sql, $data);
    }

    /**
     * To select data from a table.
     *
     * @param string $table The table to select the data from.
     * @param string $columns The columns to select.
     * @param string $where Condition to select (Which row(s)).
     * @return array The results in array.
     **/
    public function select($table, $columns = '*', $where = '')
    {
        $sql = "SELECT $columns FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $statement = $this->executeQuery($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * To update a row in a table.
     * 
     * @param string $table 
     * @param array $data Data to update.
     * @param string $where Condition to update (Which row(s)).
     * @return PDOStatement|bool PDOStatement/false if update succeeded/failed.
     **/
    public function update($table, $data, $where)
    {
        $setValues = "";
        foreach ($data as $column => $value) {
            $setValues .= "$column = :$column, ";
        }
        $setValues = rtrim($setValues, ", ");
        $sql = "UPDATE $table SET $setValues WHERE $where";

        return $this->executeQuery($sql, $data);
    }

    /**
     * To delete a row in a table.
     * 
     * @param string $table
     * @param string $where Condition to delete (Which row(s)).
     * @return PDOStatement|bool PDOStatement/false if deletion succeeded/failed.
     **/
    public function delete($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";

        return $this->executeQuery($sql);
    }

    /**
     * To prepare & execute a query in the database with given table and data.
     * 
     * @param string $sql SQL code to prepare.
     * @param array $data Array of parameters (data) to execute query.
     * @return PDOStatement|bool PDOStatement/false if query execution succeeded/failed.
     * @throws PDOException if query execution has failed.
     **/
    private function executeQuery($sql, $params = [])
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $statement;
        }
        catch (PDOException $e) {
            die("Error executing query: {$e->getMessage()}");
        }
    }

    /**
     * Creates 'users' table.
     * 
     * @throws PDOException if creation has failed.
     **/
    private function createUsersTable()
    {
        $createQuery = "CREATE TABLE IF NOT EXISTS `proj_db`.`users` (
            `id` INT NOT NULL , 
            `username` VARCHAR(255) NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `active` BOOLEAN NOT NULL DEFAULT (RAND() < 0.5) , 
            PRIMARY KEY (`id`)
            );";

        try {
            if ( ! $this->connection->query($createQuery)){
                die("Query for creating 'posts' table failed");
            }
        } catch (PDOException $e) {
            die("(PDOException) Creating 'users' table failed: {$e->getMessage()}");
        }
    }

    /**
     * Creates 'posts' table.
     *
     * @throws PDOException if creation has failed.
     **/
    private function createPostsTable()
    {
        $createQuery = "CREATE TABLE IF NOT EXISTS `proj_db`.`posts` (
            `id` INT NOT NULL , 
            `user_id` INT NOT NULL , 
            `title` VARCHAR(255) NOT NULL , 
            `body` TEXT NOT NULL , 
            `published_at` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL FLOOR(RAND()*24) HOUR) , 
            `active` BOOLEAN NOT NULL DEFAULT (RAND() < 0.5) , 
            PRIMARY KEY (`id`) , 
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
            );";

        try {
            if ( ! $this->connection->query($createQuery)){
                die("Query for creating 'posts' table failed");
            }
        } catch (PDOException $e) {
            die("(PDOException) Creating 'posts' table failed: {$e->getMessage()}");
        }
    }

    /**
     * Function to select all active users and their posts.
     * 
     * @return type
     * @throws PDOException
     **/
    public function selectActiveUsersAndPosts()
    {
        $sql = "SELECT users.id AS user_id, users.username, users.email, posts.id AS post_id, posts.title, posts.body, posts.published_at
        FROM users
        JOIN posts
        WHERE users.id = posts.user_id
        AND users.active = 1";

        $statement = $this->executeQuery($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Function to return the last post of the user who has a birthday this month.
     * 
     * @return PDOStatement|bool false if fails.
     * @throws PDOException
     **/
    public function selectBirthdayMonthUserLastPost()
    {
        $sql = "SELECT posts.*
        FROM users
        JOIN posts ON users.id = posts.user_id
        WHERE MONTH(users.birthday) = MONTH(CURRENT_DATE())
        ORDER BY posts.published_at DESC LIMIT 1";

        try {
            $this->connection->query($sql);
        } catch (PDOException $e) {
            die("Error executing SELECT query: {$e->getMessage()}");
        }
    }

    /**
     * To create a table of date, hour and posts per hour.
     *
     * @throws PDOException
     **/
    private function createDateHourPostsTable()
    {
        $createQuery = "CREATE TABLE IF NOT EXISTS `proj_db`.`date_hour_posts` (
            `date` DATE NOT NULL , 
            `hour` INT NOT NULL , 
            `post_count` INT NULL , 
            PRIMARY KEY (`date`, `hour`)
            )";

        try {
            if ( ! $this->connection->query($createQuery)){
                die("Query for creating 'date_hour_posts' table failed");
            }
        } catch (PDOException $e) {
            die("(PDOException) Creating 'date_hour_posts' table failed: {$e->getMessage()}");
        }
    }

    /**
     * To insert new data to date_hour_posts table.
     *
     * @throws PDOException
     **/
    public function insertDataToDateHourPostsTable()
    {
        $sql = "INSERT INTO date_hour_posts (date, hour, post_count)
            SELECT DATE(published_at) AS date,
                   HOUR(published_at) AS hour,
                   COUNT(*) as post_count
                   FROM posts
                   GROUP BY date, hour";
        
        try {
            $this->connection->query($sql);
        } catch (PDOException $e) {
            die("(PDOException) Creating 'date_hour_posts' table failed: {$e->getMessage()}");
        }
    }
}
