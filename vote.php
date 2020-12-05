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
    <div class="container-fluid" >
     <div class="title col-sm-offset-1 col-sm-10">
        <form action="vote.php" method="get">
         <div class="form-group">
          <p><input id="vote1" type="submit" class="btn btn-lg btn-success" value="Vote One" name="vote1"></p>
         </div>
        
         <div class="form-group" >
          <p><input id="vote2" type="submit" class="btn btn-lg btn-success" value="Vote Two" name="vote2"></p>
         </div>
            
         <div class="form-group">
          <p><a href="mainpage.html" class="btn btn-lg btn-success" id="main">Main Page</a></p>
        </div>
        </form>
        <?php
        //mysqli_connect(host, username, password, dbname);
        $link = @mysqli_connect("localhost","root","","student_app") or  die("Error in connection: ".mysqli_connect_error());

        ?>
        <?php
            //if vote one
            $sqlSum ="SELECT SUM(vote) FROM finalists";
            $resultSum = mysqli_query($link, $sqlSum); 
            $rowSum = mysqli_fetch_row($resultSum);
            $idSum = (int)$rowSum[0];
        
            //if sum is total 10, declare winner, else update
            if($idSum >= 10){
                //declare winner
                $sqlWin = "INSERT INTO awardwinner(ID, first, last)
                            SELECT ID,firstname,lastname FROM finalists WHERE vote = (SELECT MAX(vote) from finalists)";
              if(mysqli_query($link, $sqlWin)){
                    echo"<p>Winner Has Already Been Selected</p>";
                }else{
                    echo "<div class='alert alert-warning'>Unable to execute:".$sql16.". ".mysqli_error($link). "</div>";
                }
              ?>  
              <script>
                  document.getElementById("vote1").disabled = true; 
                  document.getElementById("vote2").disabled = true;
              </script>
         <?php
                  
            }else{
            if(isset($_GET["vote1"])){
                $sqlId1 = "SELECT ID from finalists ORDER BY ID ASC LIMIT 1";
                $resultOne = mysqli_query($link, $sqlId1);
                $row1 = mysqli_fetch_row($resultOne);
                $id1 = (int)$row1[0];
                
                
                $sqlUpOne = "Update finalists
                             SET vote = vote+1
                             Where ID = '$id1'";
                if(mysqli_query($link, $sqlUpOne)){
                    echo"<p>Created</p>";
                }else{
                    echo "<div class='alert alert-warning'>Unable to execute:".$sqlUpOne.". ".mysqli_error($link). "</div>";
                }
                               
                
            }
        
            if(isset($_GET["vote2"])){
                $sqlId2 = "SELECT ID from finalists ORDER BY ID DESC LIMIT 1";
                $resultTwo = mysqli_query($link, $sqlId2);
                $row2 = mysqli_fetch_row($resultTwo);
                $id2 = (int)$row2[0];
                
                
                $sqlUpTwo = "Update finalists
                             SET vote = vote+1
                             Where ID = '$id2'";
                
                if(mysqli_query($link, $sqlUpTwo)){
                    echo"<p>Created</p>";
                }else{
                    echo "<div class='alert alert-warning'>Unable to execute:".$sqlUpTwo.". ".mysqli_error($link). "</div>";
                }
            }
         }
        ?>
        </div>
        </div>
    </body>
</html>

