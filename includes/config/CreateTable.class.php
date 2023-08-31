<?php

class CreateTable
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createDatabaseTables()
    {
        $sqlProductTable = "CREATE TABLE IF NOT EXISTS `otps` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(32) NOT NULL,
            `otp` varchar(32) NOT NULL,
            `created` datetime NOT NULL,
            `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlProductTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlCategoryTable = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlCategoryTable) === FALSE)
        {
            echo "Error creating table ";
        }
    }
}
