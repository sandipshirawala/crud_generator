<?php 
/*print("<pre>");
print_r($_POST["cmb_control_type"]);
print_r($_POST["table_selected"]);
print_r($_POST["cmb_ref_title"]);
print_r($_POST["cmb_ref_value"]);
print("</pre>");*/

?>
<h1><a href="table.php">Back</a></h1>

<br>
<br>
<!--<script type="text/javascript">
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  alert("Content is Copied")
}

</script>
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<button onclick="copyToClipboard('#copydiv')">Copy TEXT 1</button>-->
<?php 
 //include_once('head_file.php');
 ?>
<!--<div class="container well">-->
<?php 
/*print("<pre>");
print_r($_POST);
print("</pre>");
*/
$create = "";
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	//echo "<br>".$_POST["field_name"][$i];
	//echo "<br>".$_POST["control_name"][$i];

	if($_POST["cmb_control_type"][$i]=="File")
	{
			$create=$create.'
			if($_FILES["'.$_POST["control_name"][$i].'"]["error"]==0)
			{
				$newname = $_FILES["'.$_POST["control_name"][$i].'"]["name"];
				$newname = $this->generate_random_name($newname);
				
				$config["file_name"]=$newname;
				$config["upload_path"]="'.$_POST["txt_file_path"][$i].'";';

				if($_POST["cmb_file"][$i]=="image")
				{
					$create=$create.'
					$config["allowed_types"]="gif|jpg|png|bmp|jpeg|ico|jpeg";';
				}
				else
				{
					$create=$create.'
					$config["allowed_types"]="'.$_POST["txt_file_extension"][$i].'";';
				}
				
				
				$create=$create.'
				$config["max_width"]="102400";
				$config["max_height"]="76800";
				$config["max_size"]=1024*1024*2;
				
				$this->load->library("upload");
				$this->upload->initialize($config);
				$this->upload->do_upload("'.$_POST["control_name"][$i].'");

				$data["'.$_POST["field_name"][$i].'"]=$newname;';

				if($_POST["cmb_file"][$i]=="image")
				{
					$create=$create.'
					$this->smart_resize_image("'.$_POST["txt_file_path"][$i].'".$newname,262,200,true, "'.$_POST["txt_file_path"][$i].'thumb/".$newname,false,false);';
				}


				$create=$create.'
			}
			';
	}
	else
	{
		$create = $create.'$data["'.$_POST["field_name"][$i].'"]='.'$this->input->post("'.$_POST["control_name"][$i].'");';
	}
}

//echo $create;
$function_name=$_POST["txt_func_name"];
$table_name=$_POST["txt_table_name"];
$primary_field=$_POST["primary_field"];
$page_name=$_POST["txt_view_page_name"];
$page_title=$_POST["txt_view_page_title"];

$edit_js_func_name=$_POST["txt_edit_js_func_name"];

$title=$_POST["txt_title"];


$join_query='';
for($i=0;$i<count($_POST["cmb_control_type"]);$i++)
{
	if($_POST["cmb_control_type"][$i]=="Select")
	{
		$refer_table = $_POST["table_selected"][$i];
		$join_query=$join_query.'$this->db->join("'.$refer_table.'","'.$table_name.'.'.$_POST["field_name"][$i].'='.$refer_table.'.'.$_POST["cmb_ref_value"][$i].'");';
	}
}




	


$code = '<?php 
public function '.$function_name.'($param1="",$param2="",$param3="")
{
	if($param1=="create")
	{
		'.$create.'
		$this->db->insert("'.$table_name.'",$data);
		redirect(base_url()."admin/'.$function_name.'");
	}
	if($param1=="edit" && $param2=="do_update")
	{
		'.$create.'
		$this->db->where("'.$primary_field.'",$param3);
		$this->db->update("'.$table_name.'",$data);
		redirect(base_url()."admin/'.$function_name.'");
	}
	else if($param1=="edit")
	{
		$page_data["edit_profile"]=$this->db->get_where("'.$table_name.'",array("'.$primary_field.'"=>$param2));
	}
	if($param1=="delete")
	{
		$this->db->where("'.$primary_field.'",$param2);
		$this->db->delete("'.$table_name.'");
		redirect(base_url()."admin/'.$function_name.'");
	}

	/* paging starts here */
	$per_page=$_SESSION["per_page"];
	$this->db->limit($per_page,$param1);
	'.$join_query.'
	$page_data["resultset"]=$this->db->get("'.$table_name.'");

	$resultset=$this->db->get("'.$table_name.'");
	$total_rows=$resultset->num_rows();
	$page_data["paging_string"]=$this->paging_init("'.$function_name.'",$total_rows,$per_page);


	$page_data["start_position"]=intval($param1)+1;	
	$page_data["page_title"]="'.$page_title.'";
	$page_data["page_name"]="'.$page_name.'";

	$this->load->view("admin/index",$page_data);
}
?>';

//highlight_string($code);


// CONTROLLER PART COMPLETED END


//echo "<center><h1>Add View Page Code</h1></center>";


$add_code='
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add '.$title.'</h4>
        </div>
        <div class="modal-body">
            <!-- Add Modal Form -->
                <div class="row">
                    <div class="col-lg-12">
<form role="form" method="post" action="<?php echo base_url(); ?>admin/'.$function_name.'/create" enctype="multipart/form-data">';
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	//echo "<br>".$_POST["field_name"][$i]."-".$_POST["cmb_control_type"][$i];
	$add_code=$add_code.'
	<div class="form-group">
				<label>'.$_POST["control_label"][$i].'</label>';
	if($_POST["cmb_control_type"][$i]=="Text")
	{
		$add_code=$add_code.'
				<input class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
	}

	if($_POST["cmb_control_type"][$i]=="Textarea")
	{
		$add_code=$add_code.'
				<textarea class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" rows="3"></textarea>';
	}

	if($_POST["cmb_control_type"][$i]=="Password")
	{
		$add_code=$add_code.'
				<input type="password" class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
	}

	if($_POST["cmb_control_type"][$i]=="File")
	{
		if($_POST["cmb_file"][$i]=="image")
		{
			$add_code=$add_code.'
				<input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
		}
		else
		{
			$add_code=$add_code.'
				<input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Allowed files-('.$_POST["txt_file_extension"][$i].')';
		}
	}

	if($_POST["cmb_control_type"][$i]=="Radio")
	{
		/*$add_code=$add_code.'
				<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Yes';*/
		$add_code=$add_code.'
				<?php 
				$radio_array=array('.$_POST['radio_array'][$i].');
				for($i=0;$i<count($radio_array);$i++)
				{
					?>
					<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $radio_array[$i]; ?>"><?php echo $radio_array[$i]; ?>
					<?php
				}
				?>';
	}


	if($_POST["cmb_control_type"][$i]=="Select")
	{

		$add_code=$add_code.'
				<select class="form-control"  id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">
				<?php 
				$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
				foreach($select_res->result() as $select_row)
				{
					echo "<option value=".$select_row->'.$_POST["cmb_ref_value"][$i].'.">".$select_row->'.$_POST["cmb_ref_title"][$i].'."</option>";
				}
				?>';
		$add_code =$add_code.'
				</select>';
    }
    if($_POST["cmb_control_type"][$i]=="Checkbox")
	{
		$add_code=$add_code.'
				<?php 
				$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
				foreach($select_res->result() as $select_row)
				{
					?>
						<div class="checkbox">
	                    	<label>
	                    		<input value="<?php echo $select_row->'.$_POST["cmb_ref_value"][$i].';?>" type="checkbox"><?php echo $select_row->'.$_POST["cmb_ref_title"][$i].';?>
	                    	</label>
                  	  </div>
                  	<?php
				}
				?>';
		
    }
	$add_code=$add_code.'
	</div>';	
}
$add_code=$add_code.'
	<button type="submit" class="btn btn-success">Submit</button>
    <button type="reset" class="btn btn-default">Reset</button>
</form>';
$add_code=$add_code.'</div>
                    
                </div>
            <!-- Add Modal Form Ends -->
        </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>';

//highlight_string($add_code);





// ADD FORM FOR VIEW PAGES COMPLETED





//echo "<br><br><center><h1>Edit View Page Code</h1></center>";


$edit_code='<?php 
public function '.$edit_js_func_name.'($id)
{
	$edit_profile=$this->db->get_where("'.$table_name.'",array("'.$primary_field.'"=>$id));

	if(isset($edit_profile))
	{
		foreach($edit_profile->result() as $row)
		{
	?>
	<form role="form" method="post" action="<?php echo base_url(); ?>admin/'.$function_name.'/edit/do_update/<?php echo $row->'.$primary_field.' ;?>"  enctype="multipart/form-data">';
	for($i=0;$i<count($_POST["field_name"]);$i++)
	{
		//echo "<br>".$_POST["field_name"][$i]."-".$_POST["cmb_control_type"][$i];
		$edit_code=$edit_code.'
		<div class="form-group">
					<label>'.$_POST["control_label"][$i].'</label>';
		if($_POST["cmb_control_type"][$i]=="Text")
		{
			$edit_code=$edit_code.'
					<input class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $row->'.$_POST["field_name"][$i].' ;?>">';
		}

		if($_POST["cmb_control_type"][$i]=="Textarea")
		{
			$edit_code=$edit_code.'
					<textarea class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" rows="3"><?php echo $row->'.$_POST["field_name"][$i].' ;?></textarea>';
		}

		if($_POST["cmb_control_type"][$i]=="Password")
		{
			$edit_code=$edit_code.'
					<input type="password" class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $row->'.$_POST["field_name"][$i].' ;?>">';
		}

		if($_POST["cmb_control_type"][$i]=="File")
		{
			if($_POST["cmb_file"][$i]=="image")
			{
				$edit_code=$edit_code.'<br><img src="<?php echo base_url(); ?>'.$_POST["txt_file_path"][$i].'<?php echo $row->'.$_POST["field_name"][$i].'; ?>" width="200px"><input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
			}
			else
			{
				$edit_code=$edit_code.'<br><a href="<?php echo base_url(); ?>'.$_POST["txt_file_path"][$i].'<?php echo $row->'.$_POST["field_name"][$i].'; ?>" ><?php echo $row->'.$_POST["field_name"][$i].' ;?></a><input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Allowed files-('.$_POST["txt_file_extension"][$i].')';
			}
			
		}

		if($_POST["cmb_control_type"][$i]=="Radio")
		{
			/*$edit_code=$edit_code.'
					<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Yes';*/

			$edit_code=$edit_code.'
				<?php 
				$radio_array=array('.$_POST['radio_array'][$i].');
				for($i=0;$i<count($radio_array);$i++)
				{
					
					if($radio_array[$i]==$row->'.$_POST["field_name"][$i].')
	                {
						?>
						<input type="radio" checked id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $radio_array[$i]; ?>"><?php echo $radio_array[$i]; ?>
						<?php
					}
					else
					{
						?>
						<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $radio_array[$i]; ?>"><?php echo $radio_array[$i]; ?>
						<?php
					}
					?>
					<?php
				}
				?>';
		}


		if($_POST["cmb_control_type"][$i]=="Select")
		{

			$edit_code=$edit_code.'
					<select class="form-control"  id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">
					<?php 
					$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
					foreach($select_res->result() as $select_row)
					{
						if($select_row->'.$_POST["cmb_ref_value"][$i].' == $row->'.$_POST["field_name"][$i].')
						{
							echo "<option value=".$select_row->'.$_POST["cmb_ref_value"][$i].'." selected>".$select_row->'.$_POST["cmb_ref_title"][$i].'."</option>";
						}
						else
						{
							echo "<option value=".$select_row->'.$_POST["cmb_ref_value"][$i].'.">".$select_row->'.$_POST["cmb_ref_title"][$i].'."</option>";
						}
					}
					?>';
			$edit_code =$edit_code.'
					</select>';
	    }
	    if($_POST["cmb_control_type"][$i]=="Checkbox")
		{
			$edit_code=$edit_code.'
					<?php 
					$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
					foreach($select_res->result() as $select_row)
					{
						?>
							<div class="checkbox">
		                    	<label>
		                    		<input value="<?php echo $select_row->'.$_POST["cmb_ref_value"][$i].';?>" type="checkbox"><?php echo $select_row->'.$_POST["cmb_ref_title"][$i].';?>
		                    	</label>
	                  	  </div>
	                  	<?php
					}
					?>';
			
	    }
		$edit_code=$edit_code.'
		</div>';	
	}
	$edit_code=$edit_code.'
		<button type="submit" class="btn btn-success">Submit</button>
		<button type="reset" class="btn btn-default">Reset</button>
	</form>
	<?php 
		}
	}
}
?>';

//highlight_string($edit_code);





/// EDIT FORM FOR VIEW PAGES COMPLETED



//echo "<br><br><center><h1>List View Page Code</h1></center>";


$list_code='

<div id="page-wrapper">
	<div class="container-fluid">
	<!-- Page Heading -->
		<div class="row">
		    <div class="col-lg-12">
		        <h1 class="page-header">'.$title.' List
			        <label style="float:right">
			        	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New '.$title.'</button>
			        </label>
		        </h1>
		        <!--<ol class="breadcrumb">
		        <li>
		            <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
		        </li>
		        <li class="active">
		            <i class="fa fa-edit"></i> Forms
		        </li>
		        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New Test</button>
		        </ol>-->
		    </div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
			  	<table class="table table-bordered table-hover table-striped">
			      	<thead>';
      	//$add_code=$add_code.'<th>'.$_POST["primary_field"]."</th>";

      	$list_code=$list_code.'
      	  <th>#</th>';
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	$list_code=$list_code.'
			<th>'.$_POST["control_label"][$i].'</th>';
}
	$list_code=$list_code.'
		  <th>Action</th></thead>';

	$list_code=$list_code.'<tbody>
    	<?php
        if(!isset($start_position))
        {
            $i=1;
        }
        else
        {
            $i=$start_position;
        }
        foreach($resultset->result() as $result_row)
        {
        ?>
        <tr>
        	<td><?php echo $i; ?></td>';
        for($i=0;$i<count($_POST["field_name"]);$i++)
        {
        	if($_POST["cmb_control_type"][$i]=="Select")
			{
				$list_code=$list_code.'
    	    	<td><?php echo $result_row->'.$_POST["cmb_ref_title"][$i].'; ?></td>';
			}
			else if($_POST["cmb_control_type"][$i]=="File")
			{
				if($_POST["cmb_file"][$i]=="image")
				{
					$list_code=$list_code.'
			<td><img src="<?php echo base_url(); ?>'.$_POST["txt_file_path"][$i].'thumb/<?php echo $result_row->'.$_POST["field_name"][$i].'; ?>" width="60px"></td>';
				}
				else
				{
					$list_code=$list_code.'
			<td><a href="<?php echo base_url(); ?>'.$_POST["txt_file_path"][$i].'<?php echo $result_row->'.$_POST["field_name"][$i].'; ?>"><?php echo $result_row->'.$_POST["field_name"][$i].'; ?></a></td>';
				}
			}
			else
			{
	        	$list_code=$list_code.'
    	    <td><?php echo $result_row->'.$_POST["field_name"][$i].'; ?></td>';
    	    }
        }

        $list_code=$list_code.'
        	<td>
				<a class="btn btn-success" class="btn btn-info" data-toggle="modal" data-target="#editModal" onclick="get_edit_data(<?php echo $result_row->'.$primary_field.'; ?>);"><em class="fa fa-pencil"></em></a>
                <a class="btn btn-danger confirm-delete" data-id="<?php echo $result_row->'.$primary_field.'; ?>"><em class="fa fa-trash-o"></em></a>
            </td>';

    $list_code=$list_code.'
    	</tr>
    	<?php
        	$i++;
        } 
        ?>
        </tbody>';
	$list_code =$list_code.'
	  </table>
	  <ul class="pagination hidden-xs pull-right">
      <?php 
        if(isset($paging_string))
        {
            echo $paging_string; 
        }
       ?>
      </ul>';
$list_code=$list_code.'
	</div>
	</div>
   </div>
</div>
<!-- /.container-fluid -->
</div>';



$list_code = $list_code.'
<script type="text/javascript">
function confirmDelete()
{
  return confirm("Are you sure you want to delete this?");
}
</script>';

$list_code = $list_code.'
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit '.$title.'</h4>
        </div>
        <div class="modal-body">
            <!-- Edit Modal Form -->
                <div class="row">
                    <div class="col-lg-12" id="edit_div">
                    </div>
                </div>
            <!-- Edit Modal Form Ends -->
        </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>';

$list_code=$list_code.'
<script type="text/javascript">
            var controller = "ajax/'.$edit_js_func_name.'";
            var base_url = "<?php echo base_url(); ?>";

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

    function get_edit_data(primary_id)
    {       
        var strURL=base_url+controller+"/"+primary_id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    // only if "OK"
                    if (req.status == 200) {
                    //alert(req.responseText);                      
                        document.getElementById("edit_div").innerHTML=req.responseText;                       
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }               
            }           
            req.open("GET", strURL, true);
            req.send(null);
            
        }
    }

    
</script>';

$list_code=$list_code.'
<div id="deleteModal"  class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" data-dismiss="modal" aria-hidden="true" class="close">X</a>
                 <h3>Delete '.$title.'</h3>
            </div>
                <div class="modal-body">
                    <p>You are about to delete.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                        <a href="#" id="btnYes" class="btn btn-sm btn-danger">Yes</a>
                        <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-success">No</a>
                    
                </div>
            
        </div>
    </div>
</div>';

$list_code=$list_code."
<script>
$('#deleteModal').on('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.danger');
})

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    $('#deleteModal').data('id', id).modal('show');
});

$('#btnYes').click(function() {
    // handle deletion here
    var id = $('#deleteModal').data('id');
    //$('[data-id='+id+']').remove();
    window.location=base_url+'admin/".$function_name."/delete/'+id;
    $('#deleteModal').modal('hide');
});
</script>
";


//highlight_string($list_code);




/// LIST VIEW AND JS PART ALL THE THINGS COMPLETED

?>
<!--</div>-->

<center><h1>Controller page Coding</h1></center>
<?php highlight_string($code); ?>
<br><br><br>
<center><h1>Edit page Coding - For AJAX Page</h1></center>
<?php highlight_string($edit_code); ?>
<br><br><br>

<center><h1>Add & List page Coding(Select All the Part)</h1></center>
<?php 
highlight_string($add_code); 
highlight_string($list_code);
?>
<br><br><br>