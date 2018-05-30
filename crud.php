<?php 

session_start();
//echo $_SESSION["database_name"];
mysql_connect("localhost","root","");
mysql_select_db($_SESSION["database_name"]);

$table_name = $_GET["table_name"];

$table_query="show tables";
$table_res=mysql_query($table_query);
$table_name_array=array();
while($table_row=mysql_fetch_assoc($table_res))
{
	
	$table_name_array[] = $table_row['Tables_in_'.$_SESSION["database_name"]]; 
	
}
?>
<style type="text/css">
td
{
	padding-right: 20px;
	padding-bottom: 10px;
}
</style>
 <?php 
 include_once('head_file.php');
 ?>
<div class="container well" style="margin-top:20px">
<form method="post" action="crud_view.php">
<center><h1>Table : <font style="color:red"><?php echo $table_name; ?></font></h1></center>
<table  class="table table-bordered">
<tr>
	<td><label>Function Name</label></td>
	<td><input type="text" class="form-control" id="txt_func_name" name="txt_func_name">

	<input type="hidden" class="form-control" id="txt_table_name" name="txt_table_name" value="<?php echo $_GET["table_name"]; ?>">
</tr>
<tr>
	<td><label>View Page Name(Source File)</label></td>
	<td><input type="text" class="form-control" id="txt_view_page_name" name="txt_view_page_name">
</tr>

<tr>
	<td><label>View Page Title</label></td>
	<td><input type="text" class="form-control" id="txt_view_page_title" name="txt_view_page_title">
</tr>

<tr>
	<td><label>Edit Js Function</label></td>
	<td><input type="text" class="form-control" id="txt_edit_js_func_name" name="txt_edit_js_func_name">
</tr>
<tr>
	<td><label>Page Title</label></td>
	<td><input type="text" class="form-control" id="txt_title" name="txt_title">
</tr>
</table>
<table  class="table" width="50%">
<thead>
	<th>Field Name
	</th>
	<th>Control Name
	</th>
	<th>Control Label
	</th>
	<th>Control Type
	</th>
	<th>(Refer. Tables)<br>(Array Values)</th>
</thead>
<tbody>
<?php




$query="show columns from ".$table_name;

$resultset=mysql_query($query);

$cnt=0;
while($row=mysql_fetch_assoc($resultset))
{
	extract($row);
	/*print("<pre>");
	print_r($row);
	print("</pre>");*/
	if($row['Extra']=="auto_increment")
	{
		//echo "<td>Auto increment field-----".$row['Field']."</td>";
		echo "<tr>";
		echo "<td>".$row['Field']."</td>";
		echo "<td>Primary Key";
		echo "<input type='hidden' id='primary_field' name='primary_field' value=".$row['Field'].">";
		echo "</td>";
		echo "</tr>";

	}
	else
	{
		echo "<tr>";
		echo "<td >".$row['Field']."</td>";

		echo "<td>
		<input type='hidden' id='field_name[]' name='field_name[]' value=".$row['Field'].">
		<input type='text' id='control_name[]' name='control_name[]' class='form-control'>
		</td>";
		echo "<td>
		<input type='text' id='control_label[]' name='control_label[]' class='form-control'>
		</td>";
		echo "<td>";
			echo "<select class='form-control' id='cmb_control_type[]' name='cmb_control_type[]' onchange='check_type(this.value,".$cnt.")'>";
				echo "<option>Text</option>";
				echo "<option>Textarea</option>";
				echo "<option>Select</option>";
				echo "<option>Password</option>";
				echo "<option>File</option>";
				echo "<option>Radio</option>";
				echo "<option>Checkbox</option>";
			echo "</select>";
		echo "</td>";
		echo "<td><select class='form-control' id='table_selected[".$cnt."]' name='table_selected[".$cnt."]'  style='visibility:hidden;display:none' onchange='filldiv(this.value,".$cnt.");'>";
		echo "<option>--Select Any--</option>";
		foreach ($table_name_array as $table) {
			echo $table;
			echo "<option>".$table."</option>";
			# code...
		}
		echo "</select>";
		echo "<div id='field_div[".$cnt."]' name='field_div[".$cnt."]'  style='visibility:hidden;display:none'></div>";
		?>
		<input type='text' class='form-control' style="visibility:hidden;display:none" id='radio_array[<?php echo $cnt; ?>]'  name='radio_array[<?php echo $cnt; ?>]' placeholder='"on","off"'>
		<div id="file_div[<?php echo $cnt; ?>]" name="file_div[<?php echo $cnt; ?>]" style="visibility:hidden;display:none" >
			<select class='form-control'  id='cmb_file[<?php echo $cnt; ?>]' name='cmb_file[<?php echo $cnt; ?>]'>
				<option>image</option>
				<option>file</option>
			</select>
			<input type='text' class="form-control" placeholder="path to save" id='txt_file_path[<?php echo $cnt; ?>]'  name='txt_file_path[<?php echo $cnt; ?>]' >
			<input type='text' class="form-control" placeholder="Allowed Types(doc|docx|pdf)" id='txt_file_extension[<?php echo $cnt; ?>]'  name='txt_file_extension[<?php echo $cnt; ?>]' >
			
		</div>
		<?php
		echo "</td>";
		echo "</tr>";
		$cnt++;
	}
	
	
}

?>
<tr>
<td colspan="4">
<input type="submit" class="btn btn-info" value="Generate CRUD">
</td>
</tr>
</tbody>
</table>
</form>
</div>

<script type="text/javascript">
function check_type(control_type,cnt)
{
	if(control_type=="Select" || control_type=="Checkbox")
	{
		document.getElementById('file_div['+cnt+']').style.visibility="hidden";
		document.getElementById('file_div['+cnt+']').style.display="none";

		document.getElementById('radio_array['+cnt+']').style.visibility="hidden";
		document.getElementById('radio_array['+cnt+']').style.display="none";


		document.getElementById('field_div['+cnt+']').style.visibility="visible";
		document.getElementById('field_div['+cnt+']').style.display="block";


		document.getElementById('table_selected['+cnt+']').style.visibility="visible";
		document.getElementById('table_selected['+cnt+']').style.display="block";
	}
	else if(control_type=="Radio")
	{
		document.getElementById('file_div['+cnt+']').style.visibility="hidden";
		document.getElementById('file_div['+cnt+']').style.display="none";

		document.getElementById('field_div['+cnt+']').style.visibility="hidden";
		document.getElementById('field_div['+cnt+']').style.display="none";

		document.getElementById('table_selected['+cnt+']').style.visibility="hidden";
		document.getElementById('table_selected['+cnt+']').style.display="none";
	
		document.getElementById('radio_array['+cnt+']').style.visibility="visible";
		document.getElementById('radio_array['+cnt+']').style.display="block";
		
	}
	else if(control_type=="File")
	{

		document.getElementById('field_div['+cnt+']').style.visibility="hidden";
		document.getElementById('field_div['+cnt+']').style.display="none";

		document.getElementById('table_selected['+cnt+']').style.visibility="hidden";
		document.getElementById('table_selected['+cnt+']').style.display="none";
	
		document.getElementById('radio_array['+cnt+']').style.visibility="hidden";
		document.getElementById('radio_array['+cnt+']').style.display="none";

		document.getElementById('file_div['+cnt+']').style.visibility="visible";
		document.getElementById('file_div['+cnt+']').style.display="block";
		
	}
	else
	{
		document.getElementById('file_div['+cnt+']').style.visibility="hidden";
		document.getElementById('file_div['+cnt+']').style.display="none";

		document.getElementById('field_div['+cnt+']').style.visibility="hidden";
		document.getElementById('field_div['+cnt+']').style.display="none";

		document.getElementById('radio_array['+cnt+']').style.visibility="hidden";
		document.getElementById('radio_array['+cnt+']').style.display="none";

		document.getElementById('table_selected['+cnt+']').style.visibility="hidden";
		document.getElementById('radio_array['+cnt+']').style.display="none";
		//document.getElementById('table_selected['+cnt+']').disabled=true;	
	}
}

/*function filldiv(table_name,cnt)
{
	document.getElementById('field_div['+cnt+']').innerHTML=table_name;
}
*/
</script>

<script type='text/javascript'>
      
     function getXMLHTTP() { //fuction to return the xml http object
        var xmlhttp=false;  
        try{
            xmlhttp=new XMLHttpRequest();
        }
        catch(e)    {       
            try{            
                xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e){
                try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch(e){
                    xmlhttp=false;
                }
            }
        }
            
        return xmlhttp;
    }

    function filldiv(table_name,cnt)
    { 
        var strURL='ajax.php?table_name='+table_name+'&cnt='+cnt;

        //alert(strURL);
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                    //alert(req.responseText);                      
                        document.getElementById('field_div['+cnt+']').innerHTML=req.responseText;   
                             
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }               
            }           
            req.open("GET", strURL, true);
            req.send(null);
            
        }
        
    }

    function setEmail(email)
    {
      document.getElementById('txt_email').value=email;
    }

</script>

