<?php

?>




<script>

/* load unit data */
$.ajax({
	url: "api/pull_units.php",
	success: function(result){
    	try {
    		units_data = JSON.parse(result);
		} catch (e) {
			units_data = [];
		}
  	}}
);

$.ajax({
	url: "api/pull_levels.php",
	success: function(result){
    	try {
    		levels_data = JSON.parse(result);
		} catch (e) {
			levels_data = [];
		}
	}}
);

</script>