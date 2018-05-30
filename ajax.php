<?php 
session_start();
//echo $_SESSION["database_name"];
mysql_connect("localhost","root","");
mysql_select_db($_SESSION["database_name"]);

$table_name = $_GET["table_name"];
$cnt = $_GET["cnt"];

$query="show columns from ".$table_name;
//echo $query;
$resultset=mysql_query($query);

$query2="show columns from ".$table_name;
//echo $query;
$resultset2=mysql_query($query2);

?>

Title Field:
<select id='cmb_ref_title[<?php echo $cnt; ?>]' name='cmb_ref_title[<?php echo $cnt; ?>]' class='form-control'>
<?php 
while($row=mysql_fetch_assoc($resultset))
{
	extract($row);
	?>
	<option><?php echo $row['Field']; ?></option>
	<?php
}
?>
</select>

Value Field :
<select id='cmb_ref_value[<?php echo $cnt; ?>]' name='cmb_ref_value[<?php echo $cnt; ?>]' class='form-control'>
<?php 
while($row2=mysql_fetch_assoc($resultset2))
{
	?>
	<option><?php echo $row2['Field']; ?></option>
	<?php
}
?>
</select>