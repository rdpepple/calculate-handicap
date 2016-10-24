<?php

// Get score history from score_history database, do calculations and populate scores div area

$db_server = "localhost";
$db_user = "root";
$db_pw = "root";

// Connect to handicap_data db, table score_history
$db_con = new mysqli($db_server,$db_user,$db_pw,"handicap_data");

// Check connection
if ($db_con->connect_error) {
	print "Failed to connect to handicap_data db (score_history table): " . $db_con->connect_error . "<br>";
}

// sql to retrieve score_history table
$get_scores_sql = "SELECT * FROM score_history order by id desc limit 20";
$score_history = $db_con->query($get_scores_sql);

if ($score_history->num_rows > 0) {
// Build score arrays
    $i = 0;
    while($score = $score_history->fetch_assoc()) {
        $score_dates[$i] = $score[date_played];
        $score_courses[$i] = $score[course];
        $score_slopes[$i] = $score[slope];
        $score_ratings[$i] = $score[rating];
        $scores_adj[$i] = $score[score];
        $i++;
    }

    for ($j=0;$j<20;$j++) {
         $score_diff = (($scores_adj[$j] - $score_ratings[$j])*113.0)/$score_slopes[$j];
         $score_differentials[$j] = $score_diff;
    }
    
    $scores_used = getUsedScoreIndices($score_differentials);

    $score_diff_total = 0.0;
    for ($k=0;$k<10;$k++) {
         $score_diff_total += $score_differentials[$scores_used[$k]];
    }

    $handicap = ($score_diff_total/10.0)*0.96;
    $handicap_display = sprintf("%.1f",$handicap);

    echo $handicap_display;
}

// Close DB connection
$db_con->close();

function getUsedScoreIndices($diffs) {
    $highest_score = 0;
    $highest_score_index = -1;

// seed index array with first 10 scores
   for ($i=0;$i<10;$i++) {
        if ($diffs[$i] > $highest_score) {
            $highest_score = $diffs[$i];
            $highest_score_index = $i;
        }
        $used_diffs[$i] = $i;
   }

// process remaining 10 scores and replace when lower scores are found, plus
// set highest score to new value due to removal of previous highest score
   for ($j=10;$j<20;$j++) {
        if ($diffs[$j] < $highest_score) {
            $used_diffs[$highest_score_index] = $j;

// set new highest score value and index
            $highest_score = 0;
            for ($k=0;$k<10;$k++) {
                 if ($diffs[$used_diffs[$k]] > $highest_score) {
                     $highest_score = $diffs[$used_diffs[$k]];
                     $highest_score_index = $k;
                 }
            }
        }
   }

   return $used_diffs;
}
?>