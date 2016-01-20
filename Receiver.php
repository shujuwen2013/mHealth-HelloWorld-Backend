<?php
/**
 * Created by PhpStorm.
 * User: singingleaf
 * Date: 1/16/16
 * Time: 10:16 PM
 */

// Initiate
$cfg = array( 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME' );
loadConfig( $cfg );

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    echo "post: ";
    //Ok we got a POST, read from $_POST.
    $v = $_POST['content'];
    $threshold = $_POST['threshold'];
    if (!is_numeric($threshold)) {
        $threshold = 600.0; // default
    }

    if (is_numeric($v)) {
        if ((float)$v > $threshold) {
            insertToDB("Hello");

        } elseif ((float)$v <= $threshold) {
            insertToDB("World");
        }
    }
}

// Very simple loader
function loadConfig( $vars = array() ) {
    foreach( $vars as $v ) {
        define( $v, get_cfg_var( "mHealth.cfg.$v" ) );
    }
}

// Insert into database
function insertToDB( $content ) {
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get server time
    date_default_timezone_set('America/Chicago'); // CDT
    $now = date('Y-m-d H:i:s');

    // Create and execute SQL
    $sql = "INSERT INTO hello_world (ptime, content) VALUES ('$now', '$content')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // Close connection
    $conn->close();
}

?>