<html>
    <head>
        
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styling.css" type="text/css">
    </head>
    <body>
    <div class="title col-sm-offset-1 col-sm-10">
        
        <h1>Applicationn form</h1>

        <form action="projectDesign.php" method="post">
        <div class="form-group">
        <label for="ID">ID:</label>
        <input class="form-control" type="number" placeholder="ID" name="ID" id="ID" maxlength="4">
        </div>
        
        <div class="form-group">
        <label for="firstname">Firstname:</label>
        <input class="form-control" type="text" placeholder="Firstname" name="firstname" id="firstname" maxlength="20">
        </div>
        
        <div class="form-group">
        <label for="lastname">Lastname:</label>
        <input class="form-control" type="text" placeholder="Lastname" name="lastname" id="lastname" maxlength="30">
        </div>
        
        <div class="form-group">
        <label for="email">Email:</label>
        <input class="form-control" type="text" placeholder="Email" name="email" id="email" maxlength="30">
        </div>
        
        <div class="form-group">
        <label for="status">Status:</label>
        <input class="form-control" type="text" placeholder="Status" name="status" id="password" maxlength="40">
        </div>
        
        <div class="form-group">
        <label for="gender">Gender:</label>
        <input class="form-control" type="text" placeholder="Gender" name="gender" id="gender" maxlength="40">
        </div>
        
        <div class="form-group">
        <label for="dob">Date of Birth:</label>
        <input class="form-control" type="text" placeholder="DOB" name="dob" id="dob" maxlength="40">
        </div>
        
        <div class="form-group">
        <label for="credit">Current Credit Hours:</label>
        <input class="form-control" type="text" placeholder="Credit hours" name="credit" id="credit" maxlength="40">
        </div>
        
        <div class="form-group">
        <label for="cumGPA">Cumulitive GPA:</label>
        <input class="form-control" type="text" placeholder="Cum GPA" name="cumGPA" id="cumGPA" maxlength="40">
        </div>
        
        <div class="form-group">
        <label for="semGPA">Semester GPA:</label>
        <input class="form-control" type="text" placeholder="Sem GPA" name="semGPA" id="semGPA" maxlength="40">
        </div>
        
        <div class="form-group">
         <p><input type="submit" class="btn btn-success btn-lg" value="Send Data" name="submit" id="submit"></p>
        </div>
            
        <div class="form-group">
          <p><a href="mainpage.html" class="btn btn-lg btn-success" id="main">Main Page</a></p>
        </div>
        
    </form>
        
    <?php
    //mysqli_connect(host, username, password, dbname);
    $link = @mysqli_connect("localhost","root","","student_app") or  die("Error in connection: ".mysqli_connect_error());
//    var_dump($link);
    
    include 'readXMLtoRegister.php';
    
    echo "<p>Connected to Database Successfully!</p>";
        
    ?>
<!--    <h3>Create a Table</h3>-->
    <?php
     
        $sql = "CREATE TABLE IF NOT EXISTS applications(ID INT(10) NOT NULL PRIMARY KEY UNIQUE, firstname CHAR(20) NOT NULL, lastname CHAR(20) NOT NULL, email VARCHAR(30), status VARCHAR(40), gender VARCHAR(10), DOB VARCHAR(15), cumGPA DECIMAL(10,2), semGPA DECIMAL(10,2), currCredit INT(10), type VARCHAR(20), info_status BOOLEAN )";
    
        if(mysqli_query($link, $sql)){
               echo "<div class='alert alert-success'>Created! </div>";
           }else{
              echo "<div class='alert alert-warning'>Unable to execute:".$sql.". ".mysqli_error($link). "</div>";
           }
       
    ?>
       
       
        <?php
//    <!--    when students submit the form-->

    if(isset($_POST["submit"])){
        
        $id = (isset($_POST['ID']) ? $_POST['ID'] : '');
        $firstname = (isset($_POST['firstname']) ? $_POST['firstname'] : '');  //firstname
        $lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : '');
        $email = (isset($_POST['email']) ? $_POST['email'] : '');//email

        $status = (isset($_POST['status']) ? $_POST['status'] : '');
        $gender = (isset($_POST['gender']) ? $_POST['gender'] : '');
        $date_birth = (isset($_POST['dob']) ? $_POST['dob'] : '');
        $creditHours = (isset($_POST['credit']) ? $_POST['credit'] : '');//credit
        $cum_GPA =  (isset($_POST['cumGPA']) ? $_POST['cumGPA'] : '');//cumGPA
        $semGPA = (isset($_POST['semGPA']) ? $_POST['semGPA'] : '');



        $nofirst = "<p>Please enter your firstname!</p>";
        $nolast = "<p>Please enter your lastname!</p>";
        $noid = "<p>Please enter your student ID!</p>";
        $noCumGPA = "<p>Please enter Cumulitive GPA!</p>";
        $noSemGPA = "<p>Please enter Semester GPA!</p>";
        $error = "";


            if(!$firstname){
                $error .= $nofirst; 
            }

            if(!$lastname){
                $error .= $nolast;
            }

            if(!$id){
                $error .= $noid;
            }

            if(!$cum_GPA){
                $error .= $noCumGPA;
            }

            if(!$semGPA){
                $error .= $noSemGPA;
            }


           if($error){
              echo "<div class='alert alert-danger'>".$error."</div>"; 
           }
            else{


                 $sql = "INSERT INTO applications(ID, firstname, lastname, email, status, gender, DOB, cumGPA, semGPA, currCredit, type, info_status) VALUES('$id','$firstname','$lastname','$email','$status','$gender','$date_birth','$cum_GPA','$semGPA', '$creditHours','', false)";


             }

             if(mysqli_query($link, $sql)){
                   echo "<div class='alert alert-success'> Data added successfully to the database table! </div>";
               }else{
                  echo "<div class='alert alert-warning'>Unable to execute:".$sql.". ".mysqli_error($link). "</div>";
               }
        }

?>
    </div>

    </body>
</html>