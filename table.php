<?php 
session_start();
//echo $_SESSION["database_name"];
mysql_connect("localhost","root","");
mysql_select_db($_SESSION["database_name"]);
?>
<?php 
 include_once('head_file.php');
 ?>

<div class="container well" style="margin-top:20px">
<center><h1>Select Table from Database : <font style="color:red"><?php echo $_SESSION["database_name"]; ?></h1></center>
<table  class="table table-bordered">
<?php 

$query="show tables";
$resulteset=mysql_query($query);
while($row=mysql_fetch_assoc($resulteset))
{
	
	/*extract($row);
	print("<pre>");
	print_r($row);
	print("</pre>");
	*/
	//echo "<br><a href='crud.php?table_name='$row['Tables_in_'.$_SESSION['database_name']]'>".$row['Tables_in_'.$_SESSION["database_name"]]."</a>";
	?>
	<tr><td>
	<a href='crud.php?table_name=<?php echo $row['Tables_in_'.$_SESSION["database_name"]]; ?>'><?php echo $row['Tables_in_'.$_SESSION["database_name"]]; ?></a>
	</td></tr>
	<?php
}
?>
</table>
</div>

