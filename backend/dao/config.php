<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));
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

    public static function JWT_SECRET() {
        return '1b21fe565c32d20611e36a29369f14e6d9bcfa633cca3e9f73f308a2720c8eb70ce637f2099835e476d769195f507cd0c75c359bfed05ffbcc71fad333f16d0aa18bf5d29a27134a76dbbb2825241be8dd90197d2e7902b997e7e7cef90376cf8852446069fdc9e323f78511eceb0f64a61213fdcb952ea93160cbb5883c8d7c0bf57b795388dfbab01b3b2e20cd90a9c7f77d665a7f4b03737a8ea990ba12f20c114fc367eca8c50aa9c9dbda0994da8189986d3fb9c7f9188d1ae2008d6d480e37e7b3d276ce4a78050677820adf68d143533430884edbfbd90198f192cf705becef0c2dda921c97fe8e99f9f07a1229df3b617b3493b595b8c440213f8a72';
    }
}



?>