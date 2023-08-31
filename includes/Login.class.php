<?php 

class Login
{
    private $connection;
    private $table_name = 'users';
    private $otp_table = 'otps';

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

    function insertOTP($email, $otp) 
    {
        $query = "INSERT INTO
                    " . $this->otp_table . "
                    SET
                    email=:email, otp=:otp, created_at=:created_at";

        $stmt = $this->connection->prepare($query);

        // posted values
        $email = htmlspecialchars(strip_tags($email));
        $otp   = htmlspecialchars(strip_tags($otp));

        // to get time-stamp for 'created' field
        $created_at = date('Y-m-d H:i:s');

        // bind values
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":otp", $otp);
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
