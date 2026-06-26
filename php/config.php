<?php
// php/config.php — Database connection
// Change these values to match your MySQL setup

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // change to your MySQL user
define('DB_PASS', '');            // change to your MySQL password
define('DB_NAME', 'city_portal');

function getDB(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die('<div style="font-family:sans-serif;padding:40px;color:#c00;text-align:center">
                <h2>⚠️ Database Connection Failed</h2>
                <p>Please check your database configuration in <code>php/config.php</code></p>
                <p><small>' . htmlspecialchars($conn->connect_error) . '</small></p>
            </div>');
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

// Helper: sanitize output
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Helper: format date nicely
function fdate(string $date): string {
    return date('d M Y', strtotime($date));
}
