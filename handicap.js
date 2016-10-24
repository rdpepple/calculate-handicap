function loadXMLDoc(docname) {
	if(window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	} else {
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xhttp.open("GET",docname,false);
	xhttp.send("");
	return xhttp.responseXML;
}

function populateFormWithSavedCourse() {

    var gc = document.score_entry.gc_list.options[document.score_entry.gc_list.selectedIndex].value;
    document.score_entry.gc_name.value = gc;

// build golf course selection list
   var slp_xhttp;
   slp_xhttp = new XMLHttpRequest();
   slp_xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
           document.score_entry.gc_slope.value = this.responseText;
       }
   }
   slp_xhttp.open("GET", "get_slope.php?gc="+gc, true);
   slp_xhttp.send();

   var rating_xhttp;
   rating_xhttp = new XMLHttpRequest();
   rating_xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
           document.score_entry.gc_rating.value = this.responseText;
       }
   }
   rating_xhttp.open("GET", "get_rating.php?gc="+gc, true);
   rating_xhttp.send();

}

function postScoreToDB() {

// Add golf course if it's not already in the golf courses table
   var gc = document.score_entry.gc_name.value + "-" + document.score_entry.gc_slope.value +
            "-" + document.score_entry.gc_rating.value;

   var add_gc_xhttp;
   add_gc_xhttp = new XMLHttpRequest();
   add_gc_xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
           if (this.responseText !== "GC already in DB") {
               document.getElementById("gc_list").innerHTML = this.responseText;
           }
       }
    }
   add_gc_xhttp.open("GET", "add_course.php?gc="+gc, true);
   add_gc_xhttp.send();

// Add score to score_history DB table, and regenerate the handicap and score table

// Strip golf course name to name only (remove :<tee box>)
   var $gc_name = document.score_entry.gc_name.value;
   var $gc_name_tokens = explode(":",$gc_name);
   $gc_name = $gc_name_tokens[0];
   var scr = $gc_name + "-" + 
             document.score_entry.datepicker.value + "-" +
             document.score_entry.gc_slope.value + "-" +
             document.score_entry.gc_rating.value + "-" +
             document.score_entry.gc_score.value;

   var add_scr_xhttp;
   add_scr_xhttp = new XMLHttpRequest();
   add_scr_xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
           document.getElementById("gc_list").innerHTML = this.responseText;
       }
    }
   add_scr_xhttp.open("GET", "add_score.php?scr="+scr, true);
   add_scr_xhttp.send();
   
}

function clearForm() {
    document.score_entry.gc_name.value = "";
    document.score_entry.gc_slope.value = "";
    document.score_entry.gc_rating.value = "";
    document.score_entry.score.value = "";
    document.score_entry.date.value = "";
}


