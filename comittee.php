<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="styling.css" type="text/css">
    </head>
    <body>
    
    <div class="title col-sm-offset-1 col-sm-10">
    <form action="comittee.php" method="get">
        <div class="form-group">
        <p><input type="submit" class="btn btn-success btn-lg" value="Start Process" name="process"></p>
        </div>
    </form>
    
        <div id="options">
        <p id="interview"><a class="btn btn-lg btn-success" href="#">Conduct Interview</a></p>
        
        <p id="votepress"> <a class="btn btn-lg btn-success" href="vote.php"> Press to Vote</a></p>
            
        <p><a href="mainpage.html" class="btn btn-lg btn-success" id="main">Main Page</a></p>
        </div>
                
    <?php
    //mysqli_connect(host, username, password, dbname);
    $link = @mysqli_connect("localhost","root","","student_app") or  die("Error in connection: ".mysqli_connect_error());
               
    ?>
        
    <?php
         $sqlfinalist = "CREATE TABLE IF NOT EXISTS finalists(ID INT(10) NOT NULL PRIMARY KEY UNIQUE, firstname CHAR(20) NOT NULL, lastname CHAR(20) NOT NULL, vote INT(10))";
        
    ?>
        
    <?php       
     if(isset($_GET["process"])){
        
        //check student's inputs with registration office data
        $sql5 = "UPDATE applications SET info_status = 1
                WHERE EXISTS(SELECT * FROM  register
                             WHERE applications.ID = register.ID AND applications.firstname = register.first AND applications.lastname = register.last AND applications.email = register.email AND applications.status = register.status AND applications.gender = register.gender AND applications.DOB = register.DOB AND applications.cumGPA = register.cum_GPA AND applications.semGPA = register.sem_GPA AND applications.currCredit = register.credit)";


        //mark the eligible students
        $sql2 = "UPDATE applications SET type = 'eligible' WHERE applications.info_status = 1 AND (applications.email != '' OR applications.status != '' OR applications.gender !='' OR applications.currCredit != 0) AND applications.cumGPA >= 3.2 AND applications.currCredit >= 12 AND (SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),applications.DOB)), '%Y')+0) <= 23";
        

        //mark the non-eligible students
        $sql4 = "UPDATE applications SET type = 'non eligible' WHERE applications.info_status = 1 AND (applications.email = '' OR applications.status = '' OR applications.gender ='' OR applications.currCredit = 0) OR applications.cumGPA < 3.2 OR applications.currCredit < 12 OR (SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),applications.DOB)), '%Y')+0) > 23";
       
         
        //count students with highest cumulitive GPA among the eligible students
        $sql6 = "SELECT COUNT(tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(cumGPA) AS MaxGPA FROM applications) tt2 ON tt.cumGPA = tt2.MaxGPA WHERE tt.type = 'eligible'";
       
        $result = mysqli_query($link, $sql6);
        $row = mysqli_fetch_row($result);
        $num = (int)$row[0];
     
        //max cumGPA
        if($num == 1){
            
            $sql7 = "SELECT (tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(cumGPA) AS MaxGPA FROM applications) tt2 ON tt.cumGPA = tt2.MaxGPA WHERE tt.type = 'eligible'";
            
            $result = mysqli_query($link, $sql7);
            $row1 = mysqli_fetch_row($result);
            $id2 = (int)$row1[0];
            
            $sql8 = "Insert into awardwinner(ID, first, last)
                     Select ID, firstname, lastname FROM applications
                     WHERE ID = '$id2'";

        }else{
            //more with max cumGPA
            $sql9 = "SELECT (tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(cumGPA) AS MaxGPA FROM applications) tt2 ON tt.cumGPA = tt2.MaxGPA WHERE tt.type = 'eligible'";
            
            $valcumGPA = array();
            $result = mysqli_query($link, $sql9);
            while($row2 = mysqli_fetch_array($result, MYSQLI_ASSOC)){
//                echo "<p>".$row2['ID']."</p>";
                $valcumGPA[] = $row2['ID'];
                
            }
        
//            print_r($valcumGPA);
            
            $ids = join("','",$valcumGPA); //all the ideas of highest cumGPA
            //count students with highest semGPA
             $sql10 = "SELECT COUNT(tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(semGPA) AS MaxSGPA FROM applications) tt2 ON tt.semGPA = tt2.MaxSGPA WHERE tt.ID IN ('$ids')";

            $result = mysqli_query($link, $sql10);

            $row = mysqli_fetch_row($result);
            $num = (int)$row[0];
//            echo "<p>Winner: ".$num."</p>";

            if($num == 1){
//                echo"<p>One with highest sem GPA</p>";
                $sql11 = "SELECT (tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(semGPA) AS MaxSGPA FROM applications) tt2 ON tt.semGPA = tt2.MaxSGPA WHERE tt.ID IN ('$ids')";
                
                $result = mysqli_query($link, $sql11);
                $row = mysqli_fetch_row($result);
                $id3 = (int)$row[0];
            
                $sql12 = "Insert into awardwinner(ID, first, last)
                     Select ID, firstname, lastname FROM applications
                     WHERE ID = '$id3'";

            }else{
                
//                echo"<p>More with highest sem GPA</p>";
                $sqlsem = "SELECT (tt.ID) FROM applications AS tt INNER JOIN(SELECT MAX(semGPA) AS MaxSGPA FROM applications) tt2 ON tt.semGPA = tt2.MaxSGPA WHERE tt.ID IN ('$ids')"; //selecting all ids among the ids of highest cumGPA

                $valsemGPA = array();
                $result = mysqli_query($link, $sqlsem);
                
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $valsemGPA[] = $row['ID'];               
                 }
                //val array has all the ids with maximum sem GPA student
//                print_r($valsemGPA);
                $ids2 = join("','",$valsemGPA); //ids of all with highest semGPA
                
                //now check for junior status
                $sql14 = "SELECT COUNT(tt.ID) FROM applications as tt WHERE tt.ID IN ('$ids2') AND tt.status = 'Junior'";

                //check how many junior
                $result = mysqli_query($link, $sql14);
                $row = mysqli_fetch_row($result);
                $num = (int)$row[0];
                
                //one junior student
                if($num == 1){
//                    echo"<p>One Junior Student</p>";
                    //mark as winner
                    $sql15 = "SELECT (tt.ID) FROM applications as tt WHERE tt.ID IN ('$ids2') AND tt.status = 'Junior'";
                
                    $result = mysqli_query($link, $sql15);
                    $row = mysqli_fetch_row($result);
                    $id3 = (int)$row[0];
                    echo $id3;

                    $sql16 = "Insert into awardwinner(ID, first, last)
                         Select ID, firstname, lastname FROM applications
                         WHERE ID = '$id3'";

                }else{  //more junior studnet
//                    echo"<p>More or no Junior Student</p>";
                    //select all the juniors and store the ids into an array
                    $sqljunior = "SELECT (tt.ID) FROM applications as tt WHERE tt.ID IN ('$ids2') AND tt.status = 'Junior'";
                    
                    $valjunior = array();
                    $result = mysqli_query($link, $sqljunior);
                
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
//                        echo "<p>".$row['ID']."</p>";
                        $valjunior[] = $row['ID'];               
                    }
                    
//                    print_r($valjunior);
                    $idsJ = join("','",$valjunior);
                    
                    //now check for female
                    $sqlFemale = "SELECT COUNT(tt.ID) FROM applications as tt WHERE tt.ID IN ('$idsJ') AND tt.gender = 'Female'";
                    

                    //check how many female
                    $result = mysqli_query($link, $sqlFemale);
                    $row = mysqli_fetch_row($result);
                    $num = (int)$row[0];
                    if($num == 1){
//                        echo"<p>One female</p>";
                        $sqlFwinner = "SELECT (tt.ID) FROM applications as tt WHERE tt.ID IN ('$idsJ') AND tt.gender = 'Female'";
                        
                        $result = mysqli_query($link, $sqlFwinner);
                        $row = mysqli_fetch_row($result);
                        $idF = (int)$row[0];

                        $sql16 = "Insert into awardwinner(ID, first, last)
                             Select ID, firstname, lastname FROM applications
                             WHERE ID = '$idF'";
                        

                    }else{
                        //select two youngest and conduct and interview

//                        echo"<p>More Female</p>";
                        $varYoung = array();
                        //get the ids of all female junior students
                        $sqlYoungest = "SELECT (tt.ID) FROM applications as tt WHERE tt.ID IN ('$idsJ') AND tt.gender = 'Female' ORDER BY tt.DOB DESC";
                        
                        $result = mysqli_query($link,$sqlYoungest);
                        
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            $varYoung[] = $row['ID'];   
                        }
                        
                        $sqlfinals = "INSERT into finalists(ID, firstname,lastname, vote)
                                      SELECT ID, firstname, lastname, 0 FROM applications
                                      WHERE applications.ID = '$varYoung[0]' or applications.ID = '$varYoung[1]'";
                        mysqli_query($link, $sqlfinals);
                       
                        echo"<p>One finalist: ".$varYoung[0]."</p>";
                        echo"<p>Second finalist: ".$varYoung[1]."</p>"; 
                        //show an interview button here
                        ?>
                        <script type="text/javascript">
                            document.getElementById("interview").style.display="block";
                        </script>
                        
                        <?php
                        }
                
                        }
                
                    }
     
                }
   
            }
        ?>               
                
        </div>
    </body>
</html>