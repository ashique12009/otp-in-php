<?php 

class Register
{
    private $connection;
    private $table_name = 'users';

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    function insertUser($email, $password) 
    {
        $query = "INSERT INTO
                    " . $this->table_name . "
                    SET
                    email=:email, password=:password, created_at=:created_at";

        $stmt = $this->connection->prepare($query);

        // posted values
        $email    = htmlspecialchars(strip_tags($email));
        $password = password_hash($password, PASSWORD_DEFAULT);

        // to get time-stamp for 'created' field
        $created_at = date('Y-m-d H:i:s');

        // bind values
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":created_at", $created_at);

        if ($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
