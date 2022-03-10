<?php
include("database.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dolgozók</title>
</head>
<body>
	<?php 
		if(isset($_POST["action"]) and $_POST["action"]=="cmd_updateform_employee"){
			if( isset($_POST["input_id"]) and
				is_numeric($_POST["input_id"])){
					?>
								<div>
									<?php
										$dolgozo_updateform = new Database();
										$dolgozo_updateform->employee_update_form($_POST["input_id"]);
									?>
								</div>
					<?php
								
				}
		}
		if(isset($_POST["action"]) and $_POST["action"]=="cmd_update_employee"){
		if(isset($_POST["input_id"]) and
		is_numeric($_POST["input_id"]) and
		
		isset($_POST["update_employee_fname"]) and
		!empty($_POST["update_employee_fname"]) and
		isset($_POST["update_employee_lname"]) and
		!empty($_POST["update_employee_lname"]) and
		isset($_POST["update_employee_birthdate"]) and
		!empty($_POST["update_employee_birthdate"]) and
		isset($_POST["update_employee_gender"]) and
		isset($_POST["update_employee_hiredate"]) and
		!empty($_POST["update_employee_hiredate"]) ){
			$employee_update = new Database();
			$employee_update->employee_update($_POST["input_id"],
											   $_POST["update_employee_fname"],
											   $_POST["update_employee_lname"],
											   $_POST["update_employee_birthdate"],
											   $_POST["update_employee_hiredate"],
											   $_POST["update_employee_gender"]);
			?>
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<?php
								$employee_update->employee_update_form($_POST["input_id"]);
							?>
						</div>
					</div>
				</div>
			<?php
			
			
		}else{
			echo "Üresen hagyott mező(k) miatt nem lehet frissíteni az adatokat";
		}
}
if(isset($_POST["action"]) and $_POST["action"]=="cmd_delete_employee"){
			if( isset($_POST["input_id"]) and
				is_numeric($_POST["input_id"])){

					$dolgozo_delete = new Database();
					$dolgozo_delete->employee_delete($_POST["input_id"]);
								
				}
		}

		$dolgozo_select = new Database();
		$dolgozo_select->employee_select();
	?>
</body>
</html>