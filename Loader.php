<?php
/**
 * Created by PhpStorm.
 * User: singingleaf
 * Date: 1/19/16
 * Time: 8:29 AM
 */

$cfg = array( 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME' );
loadConfig( $cfg );


if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    readFromDB();
}

function loadConfig( $vars = array() ) {
    foreach( $vars as $v ) {
        define( $v, get_cfg_var( "mHealth.cfg.$v" ) );
    }
}

// Insert into database
function readFromDB() {
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
    $sql = "SELECT ptime, content FROM `hello_world` WHERE TIMEDIFF( ADDTIME( ptime,  '0 0:0:3' ) ,  '$now' ) >0";
    $result = $conn->query($sql);
    $conn->close();


    $encode = array();
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
        //$encode[$row['ptime']][] = $row['content'];
        $encode[$i]['ptime'] = $row['ptime'];
        $encode[$i]['content'] = $row['content'];
        $i = $i + 1;
    }

    echo json_encode($encode);

}
?>