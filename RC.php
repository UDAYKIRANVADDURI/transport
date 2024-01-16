<!-- RC.php -->
<html>
<head>
    <!-- VVIT Favicon -->
    <title>RC CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">
</head>
<body>
    <header>
    <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">  
    <h1>RC CERTIFICATE </h1> 
    </header><br><br>
    <script>
        function back()
        {
            location.href="Transport.php"
        }
    </script>
    <main>
        <!-- RC form - html code -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="myDiv">
            <h style="margin-left:105px;">
            <label for="bus" style="color:red;">RC Number  </label>
            <input type="text" id="txt" name = "id" required><span>&emsp;</span>
            <input id="btn3" type="submit" name="submit" value="Download"> <br> <br></h>
            <label for="" id="l2">Date Of Registration  </label>
            <input type="date" name="rgdate" id="txt" ><br> <br>
            <label for="" id="l3">Registration Valid Upto  </label>
            <input type="date" name="valid" id="txt"><br> <br>
            <label for="" id="l4">Registered Owner  </label>
            <input type="text" name="owner" id="txt"><br> <br>
            <label for="" id="l5">Hypothecated to  </label>
            <input type="text" name="hyp" id="txt"><br> <br>
            <label for="" id="l6">Hypothecated Date  </label>
            <input type="date" name="hypda" id="txt"><br> <br>
            <label for="" id="l7">Chassis Number  </label>
            <input type="text" name="chasno" id="txt"><br> <br>
            <label for="" id="l8">Engine Number  </label>
            <input type="text" name="eno" id="txt"><br> <br>
            <label for="" >Vehicle Class </label>
            <input type="text" name="vehcl" id="txt"><br> <br>
            <label for="" >Seating Capacity </label>
            <input type="number" name="cap" id="txt"><br> <br>
            <label for="">Present Meter Reading</label>
            <input type="number" name="PMR" id="txt"><br> <br><br>
            <input type="file" name="pdf_file" id="f1">
            <input type="submit" value="Upload" id="btn1" name="rcfile" /><span>&emsp;&emsp;</span>
            </div><br><br>
        </form>
        <section>
        </section>
    </main>
</body>
</html>
<?php
// Check if the submit button has been clicked
if(isset($_POST['submit'])){

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "transport");
    // Get the ID input value
    $busid = $_POST['id'];

    // to get the above data from the table
    $query = "select regvalidupto, regowner, hypto, hypdate, chasno, engno, vehclass, seatcap, pmr from rc where regno = '$busid'";
    $res=mysqli_query($conn,$query);
            
    // Check if the bus ID exists in the table
    if(mysqli_num_rows($res) == 0) {

        // Display an error message if the bus ID is not found
        echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
    } else{
        // Search for PDF files in the "pdfs" directory with a file name that contains the ID
        $pdfs = glob('rcpdf/*' . $busid . '*.pdf');
            
        // Check if the user is logged in as admin
        session_start();
        $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
        $row=mysqli_fetch_array($rs);
        $username=$row["username"];
        $password=$row["password"];

        // if it matches the username and password of admin
        if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

        // Display the PDFs in a table or list
        echo "<table>";
        echo "<tr><th>REG NO</th><th>DATE OF REG</th><th>REG VALID UPTO</th><th>REG OWNER</th><th>HYP TO</th><th>HYP DATE</th><th>CHASSIS NO</th><th>ENGINE NO</th><th>VEHICLE CLASS</th><th>SEAT CAPACITY</th><th>PRESENT METER READING</th><th>FILE</th><th>DELETION</th></tr>";
        foreach ($pdfs as $pdf ) {
            $row1=mysqli_fetch_array($res);
            // Get the data the database
            $expiry=$row1['regvalidupto'];
            $owner=$row1['regowner'];
            $hypto=$row1['hypto'];
            $hypdate=$row1['hypdate'];
            $chasno=$row1['chasno'];
            $engno=$row1['engno'];
            $vehclass=$row1['vehclass'];
            $seatcap=$row1['seatcap'];
            $pmr=$row1['pmr'];
            $filename = basename($pdf);

            // Get the issued date from the filename
            $issuedate = substr($filename, strpos($filename, '_') + 1, -4);
            $combined_name = basename($pdf);
            
            // Display the PDF file information in a table row
            echo "<tr><td>" . $busid . "</td><td>" . $issuedate . "</td><td>" . $expiry . "</td><td>" . $owner . "</td><td>" . $hypto . "</td><td>" . $hypdate . "</td><td>" . $chasno . "</td><td>" . $engno . "</td><td>" . $vehclass . "</td><td>" . $seatcap . "</td><td>" . $pmr . "</td><td><a href='" . $pdf . "' target='_blank'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
        }
        echo "</table>";
        }else{
            // Display the PDF files in a table or list without the delete option - not admin
            echo "<table>";
            echo "<tr><th>REG NO</th><th>DATE OF REG</th><th>REG VALID UPTO</th><th>REG OWNER</th><th>HYP TO</th><th>HYP DATE</th><th>CHASSIS NO</th><th>ENGINE NO</th><th>VEHICLE CLASS</th><th>SEAT CAPACITY</th><th>PRESENT METER READING</th><th>FILE</th></tr>";
            foreach ($pdfs as $pdf ) {
                $row1=mysqli_fetch_array($res);
                $expiry=$row1['regvalidupto'];
                $owner=$row1['regowner'];
            $hypto=$row1['hypto'];
            $hypdate=$row1['hypdate'];
            $chasno=$row1['chasno'];
            $engno=$row1['engno'];
            $vehclass=$row1['vehclass'];
            $seatcap=$row1['seatcap'];
            $pmr=$row1['pmr'];
                $filename = basename($pdf);
                $issuedate = substr($filename, strpos($filename, '_') + 1, -4);
                $combined_name = basename($pdf);
                    
                echo "<tr><td>" . $busid . "</td><td>" . $issuedate . "</td><td>" . $expiry . "</td><td>" . $owner . "</td><td>" . $hypto . "</td><td>" . $hypdate . "</td><td>" . $chasno . "</td><td>" . $engno . "</td><td>" . $vehclass . "</td><td>" . $seatcap . "</td><td>" . $pmr . "</td><td><a href='" . $pdf . "'>Download</a></td></tr>";   
            }
            echo "</table>";
        }
    }  
}     
if(isset($_GET['delete'])) { //checks if the 'delete' parameter is present in the URL

    // establishes a connection to the database -> 'transport'
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // retrieves the name of the file to be deleted from the 'delete' parameter in the URL
    $delete_file = $_GET['delete'];

    // extracts the bus ID from the file name by getting the substring from beginning of the file till before (_)
    $bus_id = substr($delete_file,0,strpos($delete_file, '_') );

    // date from the file name - from +1(after _) to -4(before .pdf)
    $date = substr($delete_file, strpos($delete_file, '_') + 1, -4);

    // selects the regvalidupto of the bus from the 'rc' table in the database for the specified bus ID and date.
    $query = "select regvalidupto from rc where regno = '$bus_id' and datereg='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'rc' table.
    $qry = "DELETE from rc where regno='$bus_id' and datereg='$date'";

    // selects all records from the 'rc' table for the specified bus ID
    $query = "select * from `rc` where regno = '$bus_id'";
    $res = mysqli_query($conn,$query);

    // checks if there is only 1 record in the result set
    if(mysqli_num_rows($res) == 1){
        
        // fetches the row from $res1 and stores it in $row1.
        $row1=mysqli_fetch_array($res1);

        // retrieves the 'regvalidupto' date from the row in $row1 and stores it in $to_date.
        $to_date=$row1['regvalidupto'];

        // to delete the record for the specified bus ID from the 'masterdemo' table.
        $q = "DELETE from `masterdemo` where busid='$bus_id'";
        $res = mysqli_query($conn,$q);

        //to delete the record for the specified bus ID from the 'status' table.
        $qs = "DELETE from `status` where busid='$bus_id'";
        $res4 = mysqli_query($conn,$qs);


        // executes the above $qry and stores the result set in $res3
        $res3=mysqli_query($conn, $qry);
    }
    else{
        //if there are more than 1 records for the bus in the 'rc' table


        $res3=mysqli_query($conn, $qry);

        //selects the regvalidupto date of the latest record for the specified bus ID from the 'rc' table
        $q2 = "SELECT regvalidupto from `rc` where regno='$bus_id' order by  regvalidupto DESC limit 1";
        $res = mysqli_query($conn,$q2);
        $row1=mysqli_fetch_array($res);
        $to_date=$row1['regvalidupto'];

        //update the expirydate in the 'masterdemo' table for the specified bus ID with date
        $q2 = "UPDATE `masterdemo` SET expirydate='$to_date' WHERE busid='$bus_id'";
        $res2 = mysqli_query($conn,$q2);
    }
    if($res3) { // If the query to delete the record(s) from the rc table was successful

        //Check if the file associated with the deleted record exists in the rcpdf directory
        if(file_exists('rcpdf/' . $delete_file)) {

            // if file exists delete it from the rcpdf directory
            unlink('rcpdf/' . $delete_file);

            //file and record both deleted successfully
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'RC.php';</script>";
        } else {
            //if the file associated with the deleted record does not exist
            echo "<p style='text-align:center; color:green;'>Record deleted successfully but not file.</p>";
        }
    } else {
        // if not successful
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    }
}

// Check if the form for adding a new RC record is submitted
if(isset($_POST['rcfile'])) {

    //establishes a connection to the transport database
    $conn = mysqli_connect("localhost", "root", "", "transport");

    // Retrieve values from the form
    $pdf_name = $_FILES['pdf_file']['name'];
    $pdf_temp = $_FILES['pdf_file']['tmp_name'];
    $reg = $_POST['id'];
    $date = $_POST['rgdate'];
    $valid = $_POST['valid'];
    $own = $_POST['owner'];
    $hypoth = $_POST['hyp'];
    $hypothda = $_POST['hypda'];
    $chass = $_POST['chasno'];
    $eng = $_POST['eno'];
    $veh = $_POST['vehcl'];
    $capa = $_POST['cap'];
    $pmr = $_POST['PMR'];
    $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION)); // get the file extension
    $allowed_ext = array("pdf"); // allowed file extensions
    $folder = 'rcpdf/'; //folder

    // Check if any of the form fields are empty
    if(empty($reg) || empty($date) || empty($valid) || empty($own) || empty($hypoth) || 
    empty($hypothda) || empty($chass) || empty($eng) || empty($veh) || empty($capa) || empty($pdf_name)){
        echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
        }

        // Check if the uploaded file is a PDF file
        else if(in_array($pdf_ext, $allowed_ext)) {

            // Move the uploaded file to a folder 
            move_uploaded_file($pdf_temp, $folder.$reg.'_'.$date.'.pdf');
    
        $conn = mysqli_connect("localhost", "root", "", "transport");

         // Insert the form values into the `rc` table
        $sql1="INSERT INTO `rc` VALUES ('$reg','$date','$valid','$own','$hypoth','$hypothda','$chass','$eng','$veh','$capa','$pmr','$pdf_name')";
        $qry = mysqli_query($conn, $sql1);

        // Check if the `masterdemo` table already contains a record for the given bus ID
        $q1 = "SELECT * from `masterdemo` where busid = '$reg' ";
        $res = mysqli_query($conn,$q1);

        $res2='';
        // If a record exists for the bus ID, update its expiry date in the `masterdemo` table
        if(mysqli_num_rows($res) > 0){
            $q2 = "UPDATE `masterdemo` SET expirydate='$valid' WHERE busid='$reg'";
            $res2 = mysqli_query($conn,$q2);
            
        }
         // If no record exists for the bus ID, insert a new record into the `masterdemo` table
        else{
        $sql2="INSERT INTO `masterdemo` values('$reg','$valid','$chass','$eng','$veh','$capa','$pmr');";
        $res2 = mysqli_query($conn, $sql2);
        }

        // Insert a new record into the `status` table with the status 'active'
        $statusquery = mysqli_query($conn,"insert into `status` values('$reg','$eng','$chass','active','$valid');");

        // Check if all the queries were successful
      if ($qry && $res2 && $statusquery) {
          echo '<script type="text/javascript"> alert("Details Uploaded Successfully")</script>';
      }
      else {
          echo '<script type="text/javascript"> alert("Sorry, there was an error while uploading.")</script>';
      }
    }
      // if uploaded file is not in pdf format
      else {
        echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
    }
}
?>