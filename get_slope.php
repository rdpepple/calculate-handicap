<?php
$gc = strval($_GET['gc']);

// Create 'golf_courses' db, and 'score_history' db
$db_server = "localhost";
$db_user = "root";
$db_pw = "root";

// Connect to handicap_data db, table golf_courses
$db_con = new mysqli($db_server,$db_user,$db_pw,"handicap_data");

// Check connection
if ($db_con->connect_error) {
	print "Failed to connect to handicap_data db (golf_courses table): " . $db_con->connect_error . "<br>";
}

// sql to create golf_courses table
$gc_getslopes_sql = "SELECT slope FROM golf_courses WHERE name = '".$gc."'";
$gc_slopes = $db_con->query($gc_getslopes_sql);

while($gc_slope = $gc_slopes->fetch_assoc()) {
      $return_slope = $gc_slope[slope];
}

// Close DB connection
$db_con->close();

echo $return_slope;

?>