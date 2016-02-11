<?php
require_once 'config.php';
if (defined('AUTH') && AUTH != $_REQUEST['auth']) {
   die('Not Authorized');
}
header('Content-Type: text/plain; charset=ASCII');
if ( isset( $_REQUEST['host'] ) ) {
	$host = preg_replace( '/[^a-zA-Z\.-_]/', '', $_REQUEST['host'] );
	if ( isset( $_REQUEST['private_ip'] ) ) {
		$private_ip = preg_replace( '/[^0-9a-fA-F\:\.]/', '', $_REQUEST['private_ip'] );
	} else {
		$private_ip = '';
	}


	$query = mysql_query( $sql = sprintf( "INSERT INTO hosts (hostname, public_ip, private_ip) VALUES  ('%s', '%s', '%s')", mysql_real_escape_string( $host ), mysql_real_escape_string( $_SERVER['REMOTE_ADDR'] ), $private_ip ), $db );
	mysql_close( $db );
	die( json_encode( array( 'success' => true ) ) );
} elseif ( isset( $_REQUEST['get_hosts'] ) ) {
	$sql     = "SELECT t1.*
FROM `hosts` t1
INNER JOIN
(
    SELECT max(id) MaxID, hostname, private_ip, public_ip
    FROM `hosts`
    GROUP BY hostname
) t2
  ON t1.hostname = t2.hostname
  AND t1.id = t2.MaxID
order by t1.id desc";
	$query   = mysql_query( $sql, $db );
	$results = array();

	while ( $record = mysql_fetch_array( $query, MYSQL_ASSOC ) ) {
		$results[] = $record;
	}
	$format = 'json';
	if ( isset($_REQUEST['format'])) {
		$format = $_REQUEST['format'];
	}
	switch ($format) {
		case 'hosts': die( implode("\r\n", array_map(function ($r) { return "{$r['public_ip']}\t{$r['hostname']}\t# {$r['datetime']}"; }, $results ))); 
		case 'json': die( json_encode( $results ) . "\n" );
	}

} elseif (isset($_REQUEST['purge'])) {
	// Purges everything
	$query = mysql_query('DELETE FROM hosts');
	
        die( json_encode( array( 'success' => $query === true ) ) );
} else {
	die( $_SERVER['REMOTE_ADDR'] . "\n" );
}
