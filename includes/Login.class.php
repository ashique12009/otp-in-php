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

    function checkOTPIsExpired($otp)
    {
        $query = "SELECT
                    email 
                FROM
                    " . $this->otp_table . "
                WHERE
                    otp = :otp 
                    AND 
                    is_expired = 0 
                    AND 
                    NOW() <= DATE_ADD(created_at, INTERVAL 24 HOUR)
                LIMIT
                    0,1";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function expireOTP($otp)
    {
        $query = "UPDATE
                    " . $this->otp_table . "
                SET
                    is_expired = 1 
                WHERE
                    otp = :otp";

        $stmt = $this->connection->prepare($query);

        // Posted values
        $otp = htmlspecialchars(strip_tags($otp));

        // Bind parameters
        $stmt->bindParam(':otp', $otp);

        // Execute the query
        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }
}
