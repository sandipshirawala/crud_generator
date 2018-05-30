<?php 
session_start();
mysql_connect("localhost","root","");

if(isset($_POST["btn_submit"]))
{
	$_SESSION["database_name"]  = 	$_POST["cmb_database"];
	header("Location:table.php");
}
$query="show databases";
$resultset=mysql_query($query);

?>
<?php 
 include_once('head_file.php');
 ?>
<div class="container well" style="margin-top:20px">
<center><h1>Select Database</h1></center>
<form method="post">
<?php
echo "<select id='cmb_database' name='cmb_database' class='form-control'>";
while($row=mysql_fetch_assoc($resultset))
{
	echo "<option>".$row['Database']."</option>";
}
echo "</select>";
?>
<br>
<input type="submit" id="btn_submit" name="btn_submit" class="btn btn-info" value="Select Database">
</form>
</div>