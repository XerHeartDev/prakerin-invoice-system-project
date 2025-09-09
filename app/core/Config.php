<?php


define("BASEURL", "http://localhost/phpmvc/public");

// DB
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bljrphpmvc');

// Currency Formater
function currencyRupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}
