 <?php
    //mysqli_connect(host, username, password, dbname);
    $link = @mysqli_connect("localhost","root","","student_app") or  die("Error in connection: ".mysqli_connect_error());
               
 ?>
<?php
$xml = simplexml_load_file('registerData.xml') or die("Error: Cannot create object");
//$data = $xml->student;
foreach($xml->children() as $row){
    $ID = $row->ID;
    $first = $row->first;
    $last = $row->last;
    $email = $row->email;
    $status = $row->status;
    $gender = $row->gender;
    $DOB = $row->DOB;
    $cum_GPA = $row->cum_GPA;
    $sem_GPA = $row->sem_GPA;
    $credit = $row->credit;
    $bill_payed = $row->bill_payed;
    
    $sqlData = "INSERT INTO `register`(`ID`, `first`, `last`, `email`, `status`, `gender`, `DOB`, `cum_GPA`, `sem_GPA`, `credit`, `bill_payed`) VALUES ('".$ID."','".$first."','".$last."','".$email."','".$status."','".$gender."','".$DOB."','".$cum_GPA."','".$sem_GPA."','".$credit."','".$bill_payed."')";
//    
//      if(mysqli_query($link, $sqlData)){
//                   echo "<div class='alert alert-success'> Data added successfully to the database table! </div>";
//               }else{
//                  echo "<div class='alert alert-warning'>Unable to execute:".$sqlData.". ".mysqli_error($link). "</div>";
//               }
    mysqli_query($link,$sqlData);
 }
?>