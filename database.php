<?php

class Database
{
	public $servername = "localhost";
	public $username = "root";
	public $password = "";
	public $dbname = "employees";	
	public $conn = NULL; 
	public $sql = NULL; 
	public $result = NULL; 
	public $row = NULL; 
	public $rows = NULL; 
	
	public function __construct(){
		$this->kapcsolodas();
	}
	public function __destruct(){
		$this->kapcsolatbontas();
	}

	public function kapcsolodas(){
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($this->conn->connect_error) {
		  die("Connection failed: " . $this->conn->connect_error);
		}
		$this->conn->query("SET NAMES UTF8;");	
	}

	public function kapcsolatbontas(){
		$this->conn->close();	
	}

	public function employee_select(){
		
		$this->sql = "SELECT employees.emp_no as 'id',
						employees.first_name as 'fname',
						employees.last_name as 'lname',
						employees.birth_date as 'birth_date',
						employees.gender as 'gender',
						employees.hire_date as 'hire_date',
						departments.dept_name as 'dep_name',
						salaries.salary as 'salary',
						titles.title as 'title' 
						FROM ((((employees LEFT JOIN dept_manager ON dept_manager.emp_no = employees.emp_no)
							LEFT JOIN departments ON dept_manager.dept_no = departments.dept_no) 
							INNER JOIN salaries ON salaries.emp_no = employees.emp_no) 
							INNER JOIN titles ON titles.emp_no = employees.emp_no) 
							WHERE salaries.from_date = (SELECT MAX(salaries.from_date) FROM salaries where salaries.emp_no = employees.emp_no) 
								and titles.from_date = (SELECT MAX(titles.from_date) FROM titles where titles.emp_no = employees.emp_no)";
		$this->result = $this->conn->query($this->sql);

		echo "<table id='myTable'>
			<thead>
										  <tr>
										    <th>név</th>
										    <th>születésnap</th>
										    <th>neme</th>
										    <th>felvétel napja</th>
										    <th>fizetés</th>
										    <th>osztály</th>
										    <th>beosztás</th>
										    <th>Adatváltoztatás</th>
										    <th>Törlés</th>
										  </tr>
										  </thead>";
		if ($this->result->num_rows > 0) {
		  while($this->row = $this->result->fetch_assoc()) {
				

							
										 echo"<tr>
										    <td>" . $this->row['fname'] . "</td>
										    <td>" . $this->row['lname'] . "</td>
										    <td>" . $this->row["birth_date"] . "</td>
										    <td>" . $this->row["gender"] . "</td>
										    <td>" . $this->row["hire_date"] . "</td>
										    <td>" . $this->row["dep_name"] . "</td>
										    <td>" . $this->row["title"] . "</td>

										    <td><form method='POST'>
												<input type='hidden' name='input_id' value='".$this->row["id"]."'>
												<input type='hidden' name='action' value='cmd_delete_employee'>
												<input type='submit' value='Dolgozó törlése'>
												</form>
											</td>

										    <td><form method='POST'>
												<input type='hidden' name='input_id' value='".$this->row["id"]."'>
												<input type='hidden' name='action' value='cmd_updateform_employee'>
												<input type='submit' value='Dolgozó adatainak változtatása'>
												</form>
											</td>

										  </tr>";
										
					
		  }
		}
		echo"</table>";
	}

	public function employee_delete($id){
			$this->sql = "DELETE FROM
							employees
						  WHERE
							emp_no  = $id;";
			if ($this->conn->query($this->sql)){
			} else {
				echo "<p>Sikertelen törlés</p>";
			}
		}
		public function employee_update_form($id){
		$this->sql = "SELECT emp_no,birth_date, first_name, last_name, gender, hire_date FROM employees WHERE emp_no  = $id";
		$this->result = $this->conn->query($this->sql);

		if ($this->result->num_rows > 0) {
		  while($this->row = $this->result->fetch_assoc()) {
			echo "<fieldset>
			<legend>Dolgozó adatainak frissítése űrlap</legend>
			<form method='POST'>
				Vezetéknév:<br />
				<textarea name='update_employee_fname'>" .$this->row['first_name']."</textarea><br />
				Keresztnév:<br />
				<textarea name='update_employee_lname'>" .$this->row['last_name']."</textarea><br />
				Add meg a dolgozó születésnapját:<br />
				<input type='date' name='update_employee_birthdate' value=".$this->row['birth_date']."><br />
				<br />
				Neme: <br/>


				Férfi:<input type='radio' ".(($this->row['gender']=='M')?"checked":"")." name='update_employee_gender'  value='M'><br />

				Nő:<input type='radio' ".(($this->row['gender']=='F')?"checked":"")." name='update_employee_gender' value='F'><br />


				<br />
				Add meg a dolgozó felvételének napját:<br />
				<input type='date' name='update_employee_hiredate' value=".$this->row['hire_date']."><br />
				<br />";

				$idofemployee = $this->row['emp_no'];
				echo "<input type='hidden' name='input_id' value='" . $idofemployee . "'>
				<input type='hidden' name='action' value='cmd_update_employee'>
				<input type='submit' value='Adatok frissítése'>
			</form>
		</fieldset>";			
		  }
		} else {
		  echo "0 results";
		}
	}
	public function employee_update($id,$firstname,$lastname,$birthdate,$hiredate,$gender){

		$this->sql = "UPDATE
						employees 
						SET 
						first_name = '$firstname',
						last_name = '$lastname',
						birth_date = '$birthdate',
						hire_date = '$hiredate',
						gender = '$gender'
						
						WHERE emp_no = '$id'";

		if ($this->conn->query($this->sql)){
		} else {
			echo "<p>Sikertelen Frissítés</p>";
			echo $this->sql;
		}
	}
	
	
}
?>