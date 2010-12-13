<?php require_once('Connections/localhost.php'); ?>

<?php

$return_arr = array();
mysql_select_db($database_localhost, $localhost);


/* If connection to database, run sql statement. */

	$fetch = mysql_query("SELECT * FROM Sectors where Sector_Name like '%" . $_GET['term'] . "%'"); 

	/* Retrieve and store in array the results of the query.*/
	while ($row = mysql_fetch_array($fetch, MYSQL_BOTH)) {
	
		$row_array['id'] = $row['Sector_ID'];
		$row_array['label'] = $row['Sector_Name'];

        array_push($return_arr,$row_array);
    }



/* Free connection resources. */
mysql_close($localhost);

/* Toss back results as json encoded array. */
echo json_encode($return_arr);


?>