<!-- paints -->
<?php 
session_start();
?>
<html>

<head>
<title>PAINT COST</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> PAINT COST</h1>
    </header>
    <br><br><br>
    <script>
    function back() {

        location.href = "Transport.php";
    }
    </script>
    <form action="" method="post">
        <div class="myDiv" id="d">
            <label style="color:red;">Registration Number</label><input type="text" name="busid" id="txt" /><br><br>
            <label>Select Category Of Bus</label>
            <select name="category" id="bus">
                <option value="vvit">vvit</option>
                <option value="viva">viva</option>
            </select><br><br>

            <label for="from"> Date</label>
            <input type="date" name="fromdate" min="2007-01-01" max="2050-12-31" id="txt"
                ><br><br>
            <label for="to">To date</label><input type="date" name="todate" min="2007-01-01" max="2050-12-31" id="txt"
                ><br><br>

            <label>
                <input type="radio" name="exampleRadio" value="Full Paint" onclick="onRadioButtonClick()">
                Full Paint
            </label>
            <label>
                <input type="radio" name="exampleRadio" value="Part Paint" onclick="onRadioButtonClick()">
                Part Paint
            </label>

            <div id="data1" style="display: none;">
                <p>----------------------------------------------------------------------------
                </p>
                <label>Painter name</label><input type="text" name="PainterName" id="painter" /><br><br>
                <label>Company</label><input type="text" name="Company" id="company" /><br><br>
                <label>Paint Cost</label><input type="number" name="PainterCost" id="cost" /><br><br>
                <label>Total Value</label><input type="number" id="totval" name="TotalValue" />
            </div>
            <div id="data2" style="display: none;">
                <p>------------------------------------------------------------------------------------------------------
                </p>
                <label>Painter name</label><input type="text" name="PainterName" id="painter" /><br><br>
                <label>Company</label><input type="text" name="Company" id="company" /><br><br>
                <label>Paint Cost</label><input type="number" name="PainterCost" id="cost" /><br><br>
                <label>Total Value</label><input type="number" id="totval" name="TotalValue" />
            </div><br><br>
            <a>
            <button type="submit" class="btn" value="Submit"
                name="insert" />Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
            <button type="submit" class="btn" value="Display" name="display" />Display</button>
</a>

        </div>
    </form>
</body>
<script>
function onRadioButtonClick() {
    var selectedValue = document.querySelector('input[name="exampleRadio"]:checked').value;
    var div1 = document.getElementById("d");
    div1.style.height = "600px";
    if (selectedValue === "Full Paint") {
        var data1 = document.getElementById("data2");
        data1.style.display = "block";
        var data2 = document.getElementById("data1");
        data2.style.display = "none";
    } else if (selectedValue === "Part Paint") {
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
<section>
    <?php
ob_start();
  if(isset($_POST['display'])){
    
    $busid=$_POST["busid"];
    $fromdate=$_POST["fromdate"];
    $todate=$_POST["todate"];
 if(!empty($todate)&&!empty($fromdate)){
 $conn=new mysqli("localhost","root","","transport");
    if($conn->connect_error){
        die("connection failed");
    }
        else{
            // display when busid and dates are given
          if($busid!=""){
            $query=mysqli_query($conn,"select * from paints where busid='$busid' and fromdate between '$fromdate' and '$todate'");
          } // display when only dates are given
          else{
            $query=mysqli_query($conn,"select * from paints  where fromdate between '$fromdate' and '$todate'");
          }
$rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
            $row=mysqli_fetch_array($rs);
            $username=$row["username"];
            $password=$row["password"]; // using session to get username and apssword 
            if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){ // deletion only available to admin
            
          //  $query=mysqli_query($conn,"select * from paints");
            echo "<table border='5' style='border: 3px solid black; color:black; padding:3px;background-color:white;border-radius:10px;'>
            <tr>
         <th>Busid</th>
         <th>category</th>
         <th>Date</th>
         <th>type</th>
         <th>PainterName</th>
         <th>Company</th>
         <th>PainterCost</th>
         <th>TotalValue</th> 
       <th>DELETION</th> 
         </tr>
         ";
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['fromdate'] . "</td>";

            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['PainterName'] . "</td>";
            echo "<td>" . $row['Company'] . "</td>";
            echo "<td>" . $row['PainterCost'] . "</td>";
            echo "<td>" . $row['TotalValue'] . "</td>"; 
            echo "<td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td>";

            echo "</tr>";
            }
            echo "</table>";
       }
 else{   // display for users without deletion option 
 echo "<table border='5' style='border: 3px solid black; color:black; padding:3px;background-color:white;border-radius:10px;'>
            <tr>
         <th>Busid</th>
         <th>category</th>
         <th>Date</th>
         <th>type</th>
         <th>PainterName</th>
         <th>Company</th>
         <th>PainterCost</th>
         <th>TotalValue</th> 
       
         </tr>
         ";
         while($row= mysqli_fetch_array($query)){
            echo "<tr>";
            echo "<td>" . $row['busid'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['fromdate'] . "</td>";

            echo "<td>" . $row['type'] . "</td>";
            echo "<td>" . $row['PainterName'] . "</td>";
            echo "<td>" . $row['Company'] . "</td>";
            echo "<td>" . $row['PainterCost'] . "</td>";
            echo "<td>" . $row['TotalValue'] . "</td>"; 
           

            echo "</tr>";
            }
            echo "</table>";
}
        }

 }

else{
  echo '<script> alert("Data cannot be displayed\nplease fill the fields\nBusid, From Date, and To Date"); </script>';
}
       }
       if(isset($_GET["delete"])) {
        $conn = mysqli_connect("localhost", "root", "", "transport");
        $delete_id = $_GET["delete"];
        $date=$_GET["date"];
        $res = mysqli_query($conn, "DELETE FROM paints WHERE busid='$delete_id' and fromdate='$date'");
        if($res){
            echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'paints.php';</script>";
        }else{
            echo '<script type="text/javascript"> alert("Error while deleting")</script>';
        }
    }
    ob_end_flush();
              ?>
</section>
</html>
<?php
    if(isset($_POST['insert'])){
        if(isset($_POST['exampleRadio'])){
        $_selected=$_POST['exampleRadio'];
        $_s=$_POST["category"];
        $busid=$_POST["busid"];
        $category=$_s;
        $fromdate=$_POST["fromdate"];
       
        $type=$_selected;
        $PainterName=$_POST["PainterName"];
     
        $Company=$_POST["Company"];
        $PainterCost=$_POST["PainterCost"];
        $TotalValue=$_POST["TotalValue"];
        // to check all the details are filled or not 
        if(!empty($busid)&&!empty($_s)&&!empty($category)&&!empty($fromdate)&&!empty($type)&&!empty($PainterName)&&!empty($Company)&&!empty($PainterCost)&&!empty($TotalValue)){

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
  $query="INSERT INTO paints  VALUES ('$busid','$category','$fromdate','$type','$PainterName','$Company','$PainterCost','$TotalValue')";
    $rs=mysqli_query($conn,$query);  
   if($rs){
      echo '<script> alert("data Inserted");  </script>';
   }
}
else{
    echo '<script type="text/javascript"> alert("Bus ID not found")</script>';
}
}
    }
else{
  echo '<script> alert("Please insert all the fields");  </script>';
}
  }
  else{
    echo '<script> alert("Please select a type ");  </script>';
  }
}
?>