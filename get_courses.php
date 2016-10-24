<?php
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
$gc_getname_sql = "SELECT name FROM golf_courses";
$gc_names = $db_con->query($gc_getname_sql);

$gc_name_string = "";
if ($gc_names->num_rows > 0) {
// Build name array
    $i = 0;
    while($gc_name = $gc_names->fetch_assoc()) {
        $courses[$i] = $gc_name[name];
        $i++;
    }

    sort($courses);

    foreach ($courses as $gc_name) {
        if ($gc_name_string === "") {
            $gc_name_string = '<option value="' . $gc_name . '">' . $gc_name . '</option><br>';
        } else {
            $gc_name_string = $gc_name_string . '<option value="' . $gc_name . '">' . $gc_name . '</option><br>';
        }
    }
}

// Close DB connection
$db_con->close();

echo $gc_name_string;

?>