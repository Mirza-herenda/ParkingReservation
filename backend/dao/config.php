<?php

class Config {
    public static function DB_HOST() {
        return 'localhost'; // Replace with your database host
    }
    public static function DB_PORT()
    {
        return  3307;
    }
    public static function DB_NAME() {
        return 'parkingreservation'; // Replace with your database name
    }

    public static function DB_USER() {
        return 'root'; // Replace with your database username
    }

    public static function DB_PASSWORD() {
        return ''; // Replace with your database password
    }
}
?>