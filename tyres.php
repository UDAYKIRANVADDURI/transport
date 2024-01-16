<!-- tyres.php updated -->
<?php 
session_start();
?>
<html>

<head>
<title>TYRES REPAIRS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> TYRES REPAIRS</h1>
    </header>
    <br><br><br>
    <script>
    function back() {
        location.href = "Transport.php";
    }
    </script>
    <form action="" method="post">
        <div class="myDiv" id="d">
            <label style="color:red;">Registration Number</label><input type="text" name="busid"/>
            <br><br>
            <label>Select Category of Bus</label>
                <select name="category" id="bus">
                    <option value="vvit">vvit</option>
                    <option value="viva">viva</option>
                </select><br><br>
                <label for="from">From Date</label>
            <input type="date" name="fromdate" min="2007-01-01" max="2050-12-31" value="<?php echo $_POST['fromdate'] ?? ''; ?>"> <br><br>
            <label for="to">To Date (Optional)</label><input type="date" name="todate" min="2007-01-01" max="2050-12-31" value="<?php echo $_POST['todate'] ?? ''; ?>"><br><br>

            <label>
                <input type="radio" name="type" value="New tyre" onclick="onRadioButtonClick()">
                New Tyre
            </label>
            <label>
                <input type="radio" name="type" value="Old tyre" onclick="onRadioButtonClick()">
                Rebutton Tyre
            </label>
            <div id="data1" style="display: none;">
                <p>--------------------------------------------------------------------------------------------------
                </p>
                <label>Distributor name:</label><input type="text" name="DistributorName" id="dis" /><br><br>
                <label>Tyre name:</label><input type="text" name="TyreName" id="tname" /><br><br>
                <label>Company:</label><input type="text" name="Company" id="company" /><br><br>
                <label>Tyre size:</label><input type="number" id="size" name="TyreSize" /><br><br>
                <label>Tyre cost:</label><input type="number" name="TyreCost" id="tcost" oninput="calculate()" /><br><br>
                <label>Quantity :</label><input type="number" id="qty" name="Quantity" oninput="calculate()" /><br><br>
                <label for="totalval"> Total Value:</label><input type="text" id="totval" name="TotalValue" />
            </div>
            <div id="data2" style="display: none;">
                <p>--------------------------------------------------------------------------------------------------
                </p>
                <label>Distributor name</label><input type="text" name="DistributorName" id="dis" /><br><br>
                <label>Tyre name</label> <input type="text" name="TyreName" id="tname" /><br><br>
                <label>Company </label><input type="text" name="Company" id="company" /><br><br>
                <label>Tyre size</label> <input type="number" id="size" name="TyreSize" /><br><br>
                <label>Tyre cost</label> <input type="number" name="TyreCost" id="tcost1"
                    oninput="calculater()" /><br><br>
                <label>Quantity </label><input type="number" id="qty1" name="Quantity" oninput="calculater()" /><br><br>
                <label for="totalval"> Total Value</label><input type="text" id="totval1" name="TotalValue" />
            </div>
            <br><br>
            <a>
            <button type="submit" class="btn" value="Submit" name="insert">Submit</button><span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
            <button type="submit" class="btn" value="Display" name="display">Display</button>
</a>


        </div>
    </form>

    <script>
    function onRadioButtonClick() {
        var selectedValue = document.querySelector('input[name="type"]:checked').value;
        var div1=document.getElementById("d");
        div1.style.height="720px";

        if (selectedValue === "New tyre") {
            var data1 = document.getElementById("data2");
            data1.style.display = "block";
            var data2 = document.getElementById("data1");
            data2.style.display = "none";
        } else if (selectedValue === "Old tyre") {
            var data2 = document.getElementById("data2");
            data2.style.display = "block";
            var data1 = document.getElementById("data1");
            data1.style.display = "none";
        }
    }

    function calculate() {
        var cost = document.getElementById("tcost").value;
        var q = document.getElementById("qty").value;
        var total = parseInt(cost) * parseInt(q);
        document.getElementById("totval").value = total;
    }

    function calculater() {
        var cost = document.getElementById("tcost1").value;
        var q = document.getElementById("qty1").value;
        var total = parseInt(cost) * parseInt(q);
        document.getElementById("totval1").value = total;
    }
    </script>
</body>

</html>
<?php 
ob_start();
        if(isset($_POST['display'])){
            $conn=new mysqli("localhost","root","","transport");
            if($conn->connect_error){
                die("connection failed");
            }
                else{
                    $busid=$_POST["busid"];
                    $from=$_POST["fromdate"];
                    $to=$_POST["todate"];
                    if(!empty($from) && !empty($to)){
                    if($busid!=""){
                         // to display specified bus details between given dates from user interface
                    $query=mysqli_query($conn,"select * from tyres where busid='$busid' and fromdate between '$from' and '$to'");
                    }
                    else{
                         // to display all tuples between given dates from user interface
                        $query=mysqli_query($conn,"select * from tyres where fromdate between '$from' and '$to'");
                    }
                    // delete option is enabled for admin , users do not have any access.
                    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
            $row=mysqli_fetch_array($rs);
            $username=$row["username"];
            $password=$row["password"];
            // using session storage for getting username and password from login page.
            if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
                
                    echo "<table border='1'>
                 <tr>
                 <th>busid</th>
                 <th>category</th>
                 <th>from</th>
                 <th>type</th>
                 <th>DistributorName</th>
                 <th>TyreName</th>
                 <th>Company</th>
                 <th>TyreSize</th> 
                 <th>TyreCost</th> 
                 <th>Quantity</th>
                 <th>TotalValue</th>
                 <th>DELETION</th> 
                 </tr>
                 ";
                 // while loop for displaying data one after the another.
                 while($row= mysqli_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>" . $row['busid'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['fromdate'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['DistributorName'] . "</td>";
                    echo "<td>" . $row['TyreName'] . "</td>";
                    echo "<td>" . $row['Company'] . "</td>";
                    echo "<td>" . $row['TyreSize'] . "</td>";
                    echo "<td>" . $row['TyreCost'] . "</td>";
                    echo "<td>" . $row['Quantity'] . "</td>";
                    echo "<td>" . $row['TotalValue'] . "</td>"; 
                    echo "<td><a href='?delete=".$row["busid"]."& date=".$row["fromdate"]."'>Delete</a></td>";
                    echo "</tr>";
                    }
                    echo "</table>";
               }
               // delete is disabled for users. so below code will execute .
            else{
                echo "<table border='1'>
                 <tr>
                 <th>busid</th>
                 <th>category</th>
                 <th>from</th>
                 <th>type</th>
                 <th>DistributorName</th>
                 <th>TyreName</th>
                 <th>Company</th>
                 <th>TyreSize</th> 
                 <th>TyreCost</th> 
                 <th>Quantity</th>
                 <th>TotalValue</th>
                 </tr>
                 ";
                 while($row= mysqli_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>" . $row['busid'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['fromdate'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['DistributorName'] . "</td>";
                    echo "<td>" . $row['TyreName'] . "</td>";
                    echo "<td>" . $row['Company'] . "</td>";
                    echo "<td>" . $row['TyreSize'] . "</td>";
                    echo "<td>" . $row['TyreCost'] . "</td>";
                    echo "<td>" . $row['Quantity'] . "</td>";
                    echo "<td>" . $row['TotalValue'] . "</td>"; 
                    echo "</tr>";
                    }
                    echo "</table>";
            } }
            else{
                echo '<script> alert("Please insert from date and to date");  </script>';
            }
          }
        }
        if(isset($_GET["delete"])) {
            $conn = mysqli_connect("localhost", "root", "", "transport");
            $delete_id = $_GET["delete"];
            $date=$_GET["date"];
            $res = mysqli_query($conn, "DELETE FROM tyres WHERE busid='$delete_id' and fromdate='$date'");
            if($res){
                echo "<script type='text/javascript'> alert('Record Deleted Successfully'); window.location.href = 'tyres.php';</script>";
            }else{
                echo '<script type="text/javascript"> alert("Error while deleting")</script>';
            }
        }
        ob_end_flush();
        ?>
<?php
 if(isset($_POST['insert'])){
    if(isset($_POST['type'])){
    $_selected=$_POST['type'];
    $_s=$_POST["category"];
    $busid=$_POST["busid"];
    $category=$_s;
    $from=$_POST["fromdate"];
    $type=$_selected;
    $DistributorName=$_POST["DistributorName"];
    
    $TyreName=$_POST["TyreName"];
    $Company=$_POST["Company"];
    $TyreSize=$_POST["TyreSize"];
    $TyreCost=$_POST["TyreCost"];
    $Quantity=$_POST["Quantity"];
    $TotalValue=$_POST["TotalValue"];

    $conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
die("Connection Failed:".$conn->connect_error);
}
else{
    // to check whether all details are filled or not
    if(!empty($busid) && !empty($category) && !empty($from) && !empty($type) && !empty($DistributorName) 
    && !empty($TyreName) && !empty($Company) && !empty($TyreSize) && !empty($TyreCost) && !empty($Quantity) 
    && !empty($TotalValue) ){
    $sql = "SELECT * FROM `masterdemo` WHERE busid='$busid'";
    $qry = mysqli_query($conn, $sql);
    $result = mysqli_num_rows($qry);
    // insert only when busid present in masterdemo table
    if($result == 1) {
$query="INSERT INTO tyres  VALUES ('$busid','$category','$from','$type','$DistributorName','$TyreName','$Company','$TyreSize','$TyreCost','$Quantity','$TotalValue')";
$rs=mysqli_query($conn,$query);  
if($rs){
  echo '<script> alert("data Inserted");  </script>';
}
else{
    echo '<script type="text/javascript"> alert("Error while uploading detials...")</script>';
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