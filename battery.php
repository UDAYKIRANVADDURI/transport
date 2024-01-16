<!-- battery.php -->
<?php
    session_start();
?>
<?php
ob_start();
    if(isset($_POST['insert'])){
        error_reporting(E_ERROR | E_PARSE);
        //if not selected either new battery or old battery
        if(!$_POST['type']){
          echo '<script> alert("Please select any one of the type");  </script>';
        }
        // if type is new battery
       elseif($_POST['type']=="New Battery")
      {
        $busid=$_POST["busid"];
        $_s=$_POST["category"];
        $category=$_s;
        $from=$_POST["from"];
        $type=$_POST['type'];
        $distributor1=$_POST["distributor1"];
        $model1=$_POST["model1"];
        $capacity1=$_POST["capacity1"];
        $battery=$_POST["batterY"];
        $cost=$_POST["cost"];
        $total=$_POST['cost'];
        $file = $_FILES['invoicefile']['name'];
        $filetmpname = $_FILES['invoicefile']['tmp_name'];
        $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // get the file extension
        $allowed_ext = array("pdf"); // allowed file extensions
        $folder = 'invoicefolder/';
      // to check whether all details are filled or not
      if(!empty($busid) && !empty($_s) && !empty($category) && !empty($from)&& !empty($type) && !empty($distributor1) && !empty($model1) && !empty($capacity1) && !empty($battery) && !empty($cost) && !empty($file)){
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  if(in_array($pdf_ext, $allowed_ext)) {
  $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
      move_uploaded_file($filetmpname, $folder.$busid.'_'.$from.'.pdf');
      $query="INSERT INTO `battery`(`busid`, `category`, `from`,  `type`, `distributor`, `model`, `capacity`, `batterynumber`, `cost`, `newcost`, `oldcost`, `total`, `pdf`) VALUES ('$busid','$category','$from','$type','$distributor1','$model1','$capacity1','$battery','$cost','','','$total','$file')";
      $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("Details submitted successfully");  </script>';
   }
   else{
    echo '<script> alert("Error! while uploading details");  </script>';
   }
  }
  else{
    echo '<script type="text/javascript"> alert("Enter a valid Bus ID!!")</script>';
  }
}
else{
  echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
}
}
      }   
else{
  echo '<script type="text/javascript"> alert("Details not submitted. Please fill all the details")</script>';
}
}
// if type is old battery
elseif($_POST['type']=="Old Battery")
{
  $type=$_POST["type"];
        $busid=$_POST["busid"];
        $_s=$_POST["category"];
        $category=$_s;
        $from=$_POST["from"];
        $type=$_POST['type'];
        $distributor=$_POST["distributor"];
        $model=$_POST["model"];
        $capacity=$_POST["capacity"]; 
        $battery=$_POST["battery"];
        $newcost=$_POST["newcost"];
        $oldcost=$_POST["oldcost"];
        $total=$_POST["total"];
        $file = $_FILES['invoicefile']['name'];
        $filetmpname = $_FILES['invoicefile']['tmp_name'];
        $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // get the file extension
    $allowed_ext = array("pdf"); // allowed file extensions
        $folder = 'invoicefolder/';
        // to check whether all details are filled or not
        if(empty($busid) || empty($_s)  || empty($category) || empty($from)  || empty($type) || empty($distributor) || empty($model) || empty($capacity) || empty($battery) || empty($newcost) || empty($oldcost) || empty($total) || empty($file) || empty($type)){
          echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
        }
        else if(in_array($pdf_ext, $allowed_ext)) {
        $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
      move_uploaded_file($filetmpname, $folder.$busid.'_'.$from.'.pdf');
  $query=" INSERT INTO `battery`(`busid`, `category`, `from`,  `type`, `distributor`, `model`, `capacity`, `batterynumber`, `cost`, `newcost`, `oldcost`, `total`, `pdf`) VALUES ('$busid','$category','$from','$type','$distributor','$model','$capacity','$battery','','$newcost','$oldcost ', '$total','$file')";
$rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("Details submitted successfully");  </script>';
   }
   else{
    echo '<script> alert("Error! while uploading details");  </script>';
   }
  }
  else{
    echo '<script type="text/javascript"> alert("Enter a valid Bus iD")</script>';
  }
    }
}else {
  echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
}
}
}
?>

<html>

<head>
    <title>BATTERY COST</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
</style>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>BATTERY COST</h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <form action="" method="post" enctype="multipart/form-data">
        <p class="p2">
        <div class="myDiv" id="d">
            <label for="bus" style="color:red;">Registration Number</label>
            <input type="text" name="busid" class="btn1" />
            <p class="p2">
                <label for="bus">Select Category</label>
                <select name="category" id="bus">
                    <option value="vvit">vvit</option>
                    <option value="viva">viva</option>
                </select>
            </p>
            </p>
            <label for="from"> From Date</label>
            <input type="date" name="from" min="2007-01-01" max="2050-12-31"> <br><br>
            <label for="from"> To Date (Optional)</label>
            <input type="date" name="to" min="2007-01-01" max="2050-12-31"> <br><br>
            <label for="">Invoice</label>
            <input type="file" accept="application/pdf" name="invoicefile" id="f1"><br><br>
            <label>
                <input type="radio" name="type" class="rd" value="New Battery" onclick="onRadioButtonClick()">
                New Battery
            </label>
            <label>
                <input type="radio" name="type" class="rd" value="Old Battery" onclick="onRadioButtonClick()">
                Old Battery
            </label>

            <a id="data1" style="display: none;" class="p2">
                <p>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                </p>
                <label for="Company Name">Battery Distributor </label>
                <input type="text" id="companyname" class="btn1" name="distributor1" /><br><br>
                <label for="modelofbattery">Model of Battery</label><textarea id="description"
                    name="model1"></textarea><br><br>
                <label for="batcap">Battery Capacity</label><input type="number" id="batcap" name="capacity1" /><br><br>
                <label for="">Battery Number</label>
                <input type="text" id="btnu" name="batterY" /><br><br>

                <label for="costofbattery">Battery Cost</label> <input type="number" id="costb" name="cost" /><br><br>

            </a>
            <a id="data2" style="display: none;" class="p2">
                <p>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                </p>

                <label for="Company Name">Battery Distributor </label>
                <input type="text" id="companyname" class="btn1" name="distributor" /><br><br>
                <label for="modelofbattery">Model of Battery</label><textarea id="description"
                    name="model"></textarea><br><br>
                <label for="batcap">Battery Capacity</label><input type="number" id="batcap" name="capacity" /><br><br>
                <label for="batno"> Battery Number</label>
                <input type="text" id="btnu" name="battery" /><br><br>

                <label for="newbat">New Battery Price</label><input type="number" id="newbat" name="newcost"
                    oninput="calculate()" /><br><br> <label for="oldbat">Old Battery Price:</label><input type="number"
                    id="oldbat" name="oldcost" oninput="calculate()" /><br><br> <label for="totalval"> Total
                    Value:</label><input type="number" id="totval" class="btn1" name="total" /><br><br>
            </a><br>

            <br>
            <a>
                <button type="submit" value="Submit" class="btn"
                    name="insert">Submit</button><span>&emsp;&emsp;&emsp;</span>
                <button type="submit" value="Display" name="display" class="btn">Display</button>
            </a>


        </div>
    </form><br><br>
    <script>
    function onRadioButtonClick() {
        var selectedValue = document.querySelector('input[name="type"]:checked').value;
        var div1 = document.getElementById("d");
        div1.style.height = "750px";
        if (selectedValue === "New Battery") {
            var data1 = document.getElementById("data1");
            data1.style.display = "block";
            var data2 = document.getElementById("data2");
            data2.style.display = "none";
        } else if (selectedValue === "Old Battery") {
            var data2 = document.getElementById("data2");
            data2.style.display = "block";
            var data1 = document.getElementById("data1");
            data1.style.display = "none";
        }
    }

    function calculate() {

        var input1 = document.getElementById("newbat").value;
        var input2 = document.getElementById("oldbat").value;
        var result = parseInt(input1) - parseInt(input2);
        document.getElementById("totval").value = result;
    }
    </script>
</body>
</html>
<?php
if(isset($_POST['display'])){
  $busid=$_POST["busid"];
  $from=$_POST["from"];
  $to=$_POST["to"];
    $conn=new mysqli("localhost","root","","transport");
    if($conn->connect_error){
        die("connection failed");
    }
        else{
          if(!empty($from) && !empty($to)){
            if($busid==''){
               // to display all tuples between given dates from user interface
            $query=mysqli_query($conn,"SELECT * FROM `battery` WHERE `from` BETWEEN '$from' AND '$to'");
            }
            else{
               // to display specified bus data between given dates from user interface
              $query=mysqli_query($conn,"SELECT * FROM `battery` WHERE busid='$busid' and `from` BETWEEN '$from' AND '$to'");
            }
            // delete option is enabled for admin , users do not have any access.
            $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
            $row=mysqli_fetch_array($rs);
            $username=$row["username"];
            $password=$row["password"];
             // using session storage for getting username and password from login page.
            if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
              echo "<table>
         <tr>
         <th>busid</th>
         <th>category</th>
         <th>from</th>
         <th>type</th>
         <th>distributor</th>
         <th>model</th>
         <th>capacity</th>
         <th>batterynumber</th>
         <th> cost</th>
        <th> new cost</th>
       <th> old cost</th>
        <th> total </th>
        <th> FILE </th>
        <th>DELETION</th>
         </tr>
         ";
         // while loop for displaying data one after the another.
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['from'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['distributor'] . "</td>";
            echo "<td>" . $row['model'] . "</td>";
            echo "<td>" . $row['capacity'] . "</td>"; 
            echo "<td>" . $row['batterynumber'] . "</td>"; 
            echo "<td>" . $row['cost'] . "</td>"; 
           echo "<td>" . $row['newcost'] . "</td>"; 
          echo "<td>" . $row['oldcost'] . "</td>"; 
          echo "<td>" . $row['total'] . "</td>"; 
          echo "<td><a href='invoicefolder/" . $row['busid'] . "_" . $row['from'] . ".pdf'>Download</a></td>";
          echo "<td><a href='?delete=".$row["busid"]."&date=".$row["from"]."'>Delete</a></td>";
            echo "</tr>";
            }
            echo "</table>";
       }
       // delete is disabled for users so below code will execute .
       else{
          echo "<table>
         <tr>
         <th>busid</th>
         <th>category</th>
         <th>from</th>
         <th>type</th>
         <th>distributor</th>
         <th>model</th>
         <th>capacity</th>
         <th>batterynumber</th>
         <th> cost</th>
        <th> new cost</th>
       <th> old cost</th>
        <th> total </th>
        <th> FILE </th>
         </tr>
         ";
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['from'] . "</td>";
            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['distributor'] . "</td>";
            echo "<td>" . $row['model'] . "</td>";
            echo "<td>" . $row['capacity'] . "</td>"; 
            echo "<td>" . $row['batterynumber'] . "</td>"; 
            echo "<td>" . $row['cost'] . "</td>"; 
           echo "<td>" . $row['newcost'] . "</td>"; 
          echo "<td>" . $row['oldcost'] . "</td>"; 
          echo "<td>" . $row['total'] . "</td>"; 
          echo "<td><a href='invoicefolder/" . $row['busid'] . "_" . $row['from'] . ".pdf'>Download</a></td>";
            echo "</tr>";
            }
            echo "</table>";
       }
      }
      //if fromdate or todate is not given
       else{
        echo '<script> alert("please enter from date and to date");  </script>';
       }
  }
}
//to delete desired tuple from database
if(isset($_GET["delete"])) {
  $conn = mysqli_connect("localhost", "root", "", "transport");
  $delete_id = $_GET["delete"];
  $date = $_GET["date"];
  $res = mysqli_query($conn, "DELETE FROM battery WHERE busid='$delete_id' and `from`='$date'");
  if($res){
      echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'battery.php';</script>";
  }else{
      echo '<script type="text/javascript"> alert("Error while deleting")</script>';
  }
}

 ?>