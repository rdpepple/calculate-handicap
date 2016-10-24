<?php
$gc_entry = strval($_GET['gc']);
$gc_params = explode("-", $gc_entry);

$gc_name = $scr_params[0];
$gc_slope = intval($gc_params[1]);
$gc_rating = floatval($gc_params[2]);

// Connect to handicap_data db, table golf_courses
$db_server = "localhost";
$db_user = "root";
$db_pw = "root";

// Connect to handicap_data db, table golf_courses
$db_con = new mysqli($db_server,$db_user,$db_pw,"handicap_data");

// Check connection
if ($db_con->connect_error) {
	print "Failed to connect to handicap_data db (golf_courses table): " . $db_con->connect_error . "<br>";
}

// sql to add golf course to golf_courses table
$gc_getnames_sql = "SELECT name FROM golf_courses WHERE name = '".$gc_name."'";
$gc_names = $db_con->query($gc_getnames_sql);

// Add new golf course if not already in golf_courses table
if ($gc_names->num_rows == 0) {
    $gc_add_sql = "INSERT INTO golf_courses (name,slope,rating)
                   VALUES ('" . $gc_name . "', '" . $gc_slope . "', '" . $gc_rating . "')";
    $db_con->query($gc_add_sql);

// sql to create golf_courses select list
    $gc_getname_sql = "SELECT name FROM golf_courses";
    $new_gc_names = $db_con->query($gc_getname_sql);

    $gc_name_string = "";
    if ($new_gc_names->num_rows > 0) {

// Build name array
        $i = 0;
        while($new_gc_name = $new_gc_names->fetch_assoc()) {
            $courses[$i] = $new_gc_name[name];
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
    echo $gc_name_string;
} else {
	echo "GC alread in DB";
}

$db_con->close;

?>