<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors','on');


#provide DATABSE details
$db_name = 'PROVIDE_DB_NAME';
mysql_connect('localhost', 'root', '');
mysql_select_db('information_schema');

# change paramaters as per your need
$search_term= 'www.clusterstogo.com';
$replace_term = 'www.newwebasitedomain.com';







$sql = "
		SELECT table_schema, table_name, column_name 
		FROM COLUMNS 
		WHERE table_schema = '$db_name' ";
	
$result  = mysql_query($sql);


while($raw = mysql_fetch_assoc($result))
{
	$data[] = 	$raw;
}



//print_r($data);

mysql_select_db($db_name);
foreach($data as $k => $v)
{
	$found = 0;
	
	$table_name = $v['table_name'];
	$column_name = $v['column_name'];
	
	$sql = "SELECT * FROM $table_name WHERE $column_name LIKE '%$search_term%'";
	
	$resource = mysql_query($sql);
	
	
	if($resource)
	{
	$found = mysql_num_rows($resource);

	if($found)
	{
		echo $table_name.' => '.$column_name.'<br/>';
		
		$sql = "SELECT * FROM $table_name WHERE $column_name LIKE '%$search_term%'";
		$resource = mysql_query($sql);
		
		if($resource)
		{
			while($raw = mysql_fetch_assoc($resource))
			{
				$final_data[] = $raw;
			}
			
			echo '<pre>';
			print_r($final_data);
			echo '</pre>';
			
			$got_it = 1;
		}
		

		
		
		$update = "UPDATE $table_name SET $column_name = REPLACE($column_name, '$search_term', '$replace_term')";
		mysql_query($update);
	}
	}
}

if(!isset($got_it))
{
	echo 'Nothing found';
}