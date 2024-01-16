<!-- accidents updated -->
<?php
session_start();
?>
<?php


    if(isset($_POST['insert'])){
      
      if(isset($_POST['type']) && $_POST['type']=="Accident")
      { 
        $busid=$_POST["busid"];
        $driver=$_POST["driver"];
        $phnum=$_POST["phno"];
        $date=$_POST["fromdate"];
        $category=$_POST["category"];
        $type=$_POST["type"];
        $victim=$_POST["victim"];
        $repair=$_POST["repaircost"];
        $file = $_FILES['firpdf']['name'];
        $filetmpname = $_FILES['firpdf']['tmp_name'];
        $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed_ext = array("pdf");
        $folder = 'fir_folder/';
        $conn=new mysqli("localhost","root","","transport");
        if($conn->connect_error)
        {
          die("Connection Failed:".$conn->connect_error);
        }
else{
  if(!empty($busid) &&  !empty($driver) && !empty($phnum) && !empty($date) && !empty($category) && !empty($type) 
  && !empty($victim) && !empty($repair)){ 
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
      if(isset($_POST['option']) && $_POST['option']=="yes"){
        if( in_array($pdf_ext, $allowed_ext)) {
  move_uploaded_file($filetmpname, $folder.$busid.'_'.$date.'.pdf');
  $query="INSERT INTO `accidents` VALUES('$busid','$driver','$phnum','$date','$category','$file','$type','$victim','$repair',' ',' ')";
  $rs=mysqli_query($conn,$query);  
  if($rs){
    echo '<script> alert("Details submitted");  </script>';
 }
 else{
  echo '<script> alert("Error while uploading");  </script>';
 }
}
else {
  echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
}
}
else if(isset($_POST['option']) && $_POST['option']=="no"){
  $query="INSERT INTO `accidents` VALUES('$busid','$driver','$phnum','$date','$category','$file','$type','$victim','$repair',' ',' ')";
  $rs=mysqli_query($conn,$query);  
  if($rs){
    echo '<script> alert("Details submitted");  </script>';
 }
 else{
  echo '<script> alert("Error while uploading");  </script>';
 }
}
else{
  echo '<script> alert("please select whether FIR is available or not");  </script>';
}
  }
  else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
  }
  }
  else{
    echo '<script> alert("Please insert all the fields");  </script>';
  }
  }
} 
         
else if(isset($_POST['type']) && $_POST['type']=="Injuries")
{
        $busid=$_POST["busid"];
        $driver=$_POST["driver"];
        $phnum=$_POST["phno"];
        $date=$_POST["fromdate"];
        $category=$_POST["category"];
        $type=$_POST["type"];
        $excras=$_POST["excrasia"];
        $descri=$_POST["description"];
        $file = $_FILES['firpdf']['name'];
        $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed_ext = array("pdf");
        $filetmpname = $_FILES['firpdf']['tmp_name'];
        $folder = 'fir_folder/';
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  if(!empty($busid) &&  !empty($driver) && !empty($phnum) && !empty($date) && !empty($category) && !empty($type) 
  && !empty($excras) && !empty($descri)){ 
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
      if( isset($_POST['option']) && $_POST['option']=="yes"){
        if( in_array($pdf_ext, $allowed_ext)) {
      move_uploaded_file($filetmpname, $folder.$busid.'_'.$date.'.pdf');
  $query="INSERT INTO `accidents` VALUES ('$busid','$driver','$phnum','$date','$category','$file','$type',' ',' ','$excras','$descri')";
$rs=mysqli_query($conn,$query);  
        if($rs){
          echo '<script> alert("Details submitted");  </script>';
       }
       else{
        echo '<script> alert("Error while uploading");  </script>';
       }
      }
        else {
          echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
        }
      }
        else if(isset($_POST['option']) && $_POST['option']=="no"){
          $query="INSERT INTO `accidents` VALUES ('$busid','$driver','$phnum','$date','$category','$file','$type',' ',' ','$excras','$descri')";
          $rs=mysqli_query($conn,$query); 
          if($rs){
            echo '<script> alert("Details submitted");  </script>';
         }
         else{
          echo '<script> alert("Error while uploading");  </script>';
         }
        }
        else{
          echo '<script> alert("please select whether FIR is available or not");  </script>';
        }
  }
  else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
  }
}
else{
  echo '<script> alert("Please insert all the fields");  </script>';
} 
}
}
else{
  echo '<script> alert("Please insert all the fields");  </script>';
}
    }
?>

<html>

<head>
    <title>ACCIDENT DETAILS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
    <style>
    </style>
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>ACCIDENT DETAILS</h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="myDiv" id="d">
            <label for="bus" style="color:red;">Registration Number</label>
            <input type="text" name="busid" class="btn1" /> <br><br>
            <label for="">Driver name </label>
            <input type="text" name="driver" id="dri" /><br><br>
            <label for="">Driver Phno </label>
            <input type="number" name="phno" id="phnum" /><br><br>
            <label for="">From date </label>
            <input type="date" name="fromdate" id="dt"><br><br>
            <label for="">To date (Optional) </label>
            <input type="date" name="todate" id="dt" /><br>
            <p class="p2">
            <p class="p1"><label for="bus">Select Category </label>
                <select name="category">
                    <option value="vvit">vvit</option>
                    <option value="viva">viva</option>
                </select><br><br>
                <label for="bus">FIR </label>
                <input type="radio" name="option" value="yes" onclick="showFileInput()"> Yes
                <input type="radio" name="option" value="no" onclick="hideFileInput()"> No <br><br>
                <input type="file" accept="application/pdf" name="firpdf" id="fileInput" style="display:none;">
            <p class="p2">
                <input type="radio" name="type" class="rd" value="Accident" onclick="onRadioButtonClick()">
                <label> Accident</label>
                <input type="radio" name="type" class="rd" value="Injuries" onclick="onRadioButtonClick()">
                <label>Injuries</label>
            </p>
            <a id="data1" style="display: none;" class="p2">
                <p>---------------------------------------------------------------------------------------------------------------------------
                </p>
                <p class="p2">
                    <p class="p1">
                        <label for="bus">Victim </label>
                        <select name="victim" id="bus">
                            <option value="Four Wheeler">Four wheeler</option>
                            <option value="Two Wheeler">Two Wheeler</option>
                            <option value="Six Wheeler">Six Wheeler</option>
                        </select>
                    </p>
                </p>
                <label for="">Our Repair Cost </label>
                <input type="number" id="rcost" name="repaircost" /><br>
            </a>

            <a id="data2" style="display: none;" class="p2">
                <p>----------------------------------------------------------------------------------------------------------------------------
                </p>
                <label for="Amount">Excrasia </label>
                <input type="text" id="amut" class="btn1" name="excrasia" /><br><br>
                <label for="">Description </label>
                <textarea id="detls" name="description"></textarea>
            </a>
            <a>
                <button type="submit" value="Submit" class="btn" name="insert">Submit</button>
                <button type="submit" value="Display" class="btn" name="display">Display</button>
            </a>
        </div>
    </form>
    <script>
    function onRadioButtonClick() {
        var selectedValue = document.querySelector('input[name="type"]:checked').value;
        var div1 = document.getElementById("d");
        div1.style.height = "85%";
        if (selectedValue === "Accident") {
            var data1 = document.getElementById("data1");
            data1.style.display = "block";
            var data2 = document.getElementById("data2");
            data2.style.display = "none";
        } else if (selectedValue === "Injuries") {
            var data2 = document.getElementById("data2");
            data2.style.display = "block";
            var data1 = document.getElementById("data1");
            data1.style.display = "none";
        }
    }

    function showFileInput() {
        document.getElementById("fileInput").style.display = "block";
    }

    function hideFileInput() {
        document.getElementById("fileInput").style.display = "none";
    }
    </script>
</body>

</html>
<?php
  if(isset($_POST['display'])){
    $conn=new mysqli("localhost","root","","transport");
    if($conn->connect_error){
        die("connection failed");
    }
        else{
          $busid=$_POST["busid"];
          $fromdate=$_POST["fromdate"];
          $todate=$_POST["todate"];
          if(!empty($fromdate) && !empty($todate)){
            if($busid!=""){
              // to display specified bus data between given dates from user interface
            $query=mysqli_query($conn,"select * from accidents where busid='$busid' and date between '$fromdate' and '$todate'");
            }
            else{
              // to display all tuples between given dates from user interface
              $query=mysqli_query($conn,"select * from accidents where date between '$fromdate' and '$todate'");
            }
            // delete option is enabled for admin , users do not have any access.
            $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
      $row=mysqli_fetch_array($rs);
      $username=$row["username"];
      $password=$row["password"];
      // using session storage for getting username and password from login page.
      if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
            echo "<table >
         <tr>
         <th>busid</th>
         <th> Driver Name</th>
         <th> Driver Phnumber</th>
         <th> Date </th>       
         <th>Category</th>
         <th>Type</th>
         <th>Victim</th>
        <th>Repairs cost</th>
       <th>Excrasia</th>
       <th>Description</th>
       <th>FILE</th>
       <th>DELETION</th> 
         </tr>
         ";
         // while loop for displaying data one after the another.
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['driver'] . "</td>";
            echo "<td>" . $row['phno'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
           echo "<td>" . $row['category'] . "</td>"; 
           echo "<td>" . $row['type'] . "</td>";
           echo "<td>" . $row['victim'] . "</td>";
           echo "<td>" . $row['repaircost'] . "</td>";
          echo "<td>" . $row['excrasia'] . "</td>";
          echo "<td>" . $row['description'] . "</td>";
          echo "<td><a href='fir_folder/" . $row['busid'] . "_" . $row['date'] . ".pdf'>Download</a></td>";
          echo "<td><a href='?delete=".$row["busid"]."& date=".$row["date"]."'>Delete</a></td>";
          echo "</tr>";
            }
            echo "</table>";
       }
       // delete is disabled for users so below code will execute .
       else{
        echo "<table >
        <tr>
        <th>busid</th>
        <th> Driver Name</th>
        <th> Driver Phnumber</th>
        <th> Date </th>       
        <th>Category</th>
        <th>Type</th>
        <th>Victim</th>
       <th>Repairs cost</th>
      <th>Excrasia</th>
      <th>Description</th>
      <th>FILE</th>
        </tr>
        ";
        while($row= mysqli_fetch_array($query)){
           echo "<tr>";
           echo "<td>" . $row['busid'] . "</td>";
           echo "<td>" . $row['driver'] . "</td>";
           echo "<td>" . $row['phno'] . "</td>";
           echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['category'] . "</td>";
          echo "<td>" . $row['type'] . "</td>";
          echo "<td>" . $row['victim'] . "</td>";
          echo "<td>" . $row['repaircost'] . "</td>";
         echo "<td>" . $row['excrasia'] . "</td>";
         echo "<td>" . $row['description'] . "</td>";
         echo "<td><a href='fir_folder/" . $row['busid'] . "_" . $row['date'] . ".pdf'>Download</a></td>"; 
         echo "</tr>";
           }
           echo "</table>";
    }
  }
  //if fromdate or todate is not given
       else{
        echo '<script> alert("Please insert from date and to date");  </script>';
       }
    }
  } 

  //to delete desired tuple from database
  if(isset($_GET["delete"])) { // Check if the "delete" parameter is set in the URL

    // Establish a connection to the database
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // Get the values of "delete" and "date" parameters from the URL
    $delete_id = $_GET["delete"];
    $fromdate = $_GET["date"];

    // Delete the record from the "accidents" table
    $res = mysqli_query($conn, "DELETE FROM accidents WHERE busid='$delete_id' and date='$fromdate'");

    // Check if the query was successful
    if($res){
        // Display a success message and redirect to the "accidents.php" page
        echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'accidents.php';</script>";
    }else{
      // Display an error message
        echo '<script type="text/javascript"> alert("Error while deleting")</script>';
    }
  }
  ?>