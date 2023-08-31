<?php 

class Login
{
    private $connection;
    private $table_name = 'users';

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    function checkUser($email) 
    {
        $query = "SELECT
                    email 
                FROM
                    " . $this->table_name . "
                WHERE
                    email = ?
                LIMIT
                    0,1";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);   
    }
}
