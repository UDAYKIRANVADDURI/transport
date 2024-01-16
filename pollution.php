<!-- pollution.php -->
<html>

<head>
    <title>POLLUTION CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">
</head>

<body>

    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1>POLLUTION CERTIFICATE </h1>
    </header><br><br>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <main>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="myDiv">
                <h style="margin-left:88px;">
                    <label for="bus" style="color:red;">Vehicle No</label>
                    <input type="text" id="txt" name="vehno" required><span>&emsp;</span>
                    <input id="btn3" type="submit" name="submit" value="Display"> <br> <br>
                </h>
                <label for="">Type of vehicle </label>
                <input type="text" name="vehtype" id="txt"><br> <br>
                <label for="" id="l4">Fuel Type </label>
                <input type="text" name="fuel" id="txt"><br> <br>
                <label for="">Registered Date</label>
                <input type="date" name="date" id="txt"><br> <br>
                <label for="">Time</label>
                <input type="time" name="time" id="txt"><br> <br>
                <label for="">Valid Upto</label>
                <input type="date" name="validupto" id="txt"><br> <br>
                <label style="margin-right:37%;">Photo of Vehicle</label>
                <input type="file" name="vehpdf" id="f1" style="margin-top:-2.85%;margin-left:47%;"><br><br><br>
                <input type="submit" value="Upload" id="btn1" name="pollutionfile" />

            </div>
        </form><br><br>
    </main>
</body>

</html>
<?php
// Check if the submit button has been clicked
            if(isset($_POST['submit'])){
                $conn = mysqli_connect("localhost", "root", "", "transport");
                // Get the ID input value
                $busid = $_POST['vehno'];

                 // to get the above data from the table
                $query = "select validupto,vehtype,fuel,time from pollution where vehno = '$busid'";
                $res=mysqli_query($conn,$query);
            
                // Check if the bus ID exists in the table
                if(mysqli_num_rows($res) == 0) {
                    echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
                } else{
                    // Search for PDF files in the "pdfs" directory with a file name that contains the ID
                    $pdfs = glob('pollutionpdf/*' . $busid . '*.pdf');
            
                    session_start();
                    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
                    $row=mysqli_fetch_array($rs);
                    $username=$row["username"];
                    $password=$row["password"];

                    //if it matches the username and password of admin
                    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

                        // Display the PDFs in a table or list
                    echo "<table>";
                    echo "<tr><th>BUS ID</th><th>DATE</th><th>VALID UPTO</th><th>TIME</th><th>VEHICLE TYPE</th><th>FUEL</th><th>FILE</th><th>DELETION</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);
                        //getting data from table
                        $expiry=$row1['validupto'];
                        $vehtype=$row1['vehtype'];
                        $fuel=$row1['fuel'];
                        $time=$row1['time'];
                        $filename = basename($pdf);
                        $issuedate = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
            
                        echo "<tr><td>" . $busid . "</td><td>" . $issuedate . "</td><td>" . $expiry . "</td><td>" . $time . "</td><td>" . $vehtype . "</td><td>" . $fuel . "</td><td><a href='" . $pdf . "'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
                    }
                    echo "</table>";
                }else{
                    // Display the PDF files in a table or list without the delete option - not admin
                    echo "<table>";
                    echo "<tr><th>Bus ID</th><th>Registered Date</th><th>Valid-upto Date</th><th>File</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);
                        $to_date=$row1['validupto'];
                        $filename = basename($pdf);
                        $from_date = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
                    
                        echo "<tr><td>" . $busid . "</td><td>". $from_date ."</td><td>". $to_date . "</td><td><a href='" . $pdf . "'>Download</a></td></tr>";   
                    }
                    echo "</table>";
                }
            }  
        }     
if(isset($_GET['delete'])) { //checks if the 'delete' parameter is present in the URL
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_file = $_GET['delete'];
    $bus_id = substr($delete_file,0,strpos($delete_file, '_') );
    $date = substr($delete_file, strpos($delete_file, '_') + 1, -4);

    // selects the validupto of the bus from the 'ollutionp' table for the specified bus ID and date.
    $query = "select validupto from pollution where vehno = '$bus_id' and date='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'pollution' table.
    $qry = "DELETE from pollution where vehno='$bus_id' and date='$date'";
    $res = mysqli_query($conn,$qry);
    if($res) {// If the query to delete the record(s) from the pollution table was successful
        if(file_exists('pollutionpdf/' . $delete_file)) {
            unlink('pollutionpdf/' . $delete_file);
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'pollution.php';</script>";
        } else {
            echo "<p style='text-align:center; color:green;'>Record deleted successfully.</p>";
        }
    } else {
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    } 

}
//check if new values are submitted in pollution table
if(isset($_POST['pollutionfile'])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");

    //retrieve values from the form
    $pdf_name = $_FILES['vehpdf']['name'];
    $pdf_temp = $_FILES['vehpdf']['tmp_name'];
    $reg = $_POST['vehno'];
    $type=$_POST['vehtype'];
    $fuel = $_POST['fuel'];
    $date = $_POST['date'];
    $time=$_POST['time'];
    $valid = $_POST['validupto'];
    $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION));
    $allowed_ext = array("pdf");
    $folder = 'pollutionpdf/';

    //// Check if any of the form fields are empty
    if(empty($reg) || empty($type) || empty($fuel) || empty($date) || empty($time) || empty($valid) || empty($pdf_name)){
        echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
    } else if(in_array($pdf_ext, $allowed_ext)) {

        // if busid exists in masterdemo
        $sql_check = "SELECT * FROM `masterdemo` WHERE `busid`='$reg'";
        $result_check = mysqli_query($conn, $sql_check);
        if(mysqli_num_rows($result_check) == 0) {
            echo '<script type="text/javascript">alert("Enter valid Bus ID!!")</script>';
        }else{

            //moving file
            move_uploaded_file($pdf_temp, $folder.$reg.'_'.$date.'.pdf');
            $sql1="INSERT INTO `pollution`(`vehno`, `vehtype`, `fuel`, `date`, `time`, `validupto`, `vehpdf`) VALUES ('$reg','$type','$fuel','$date','$time','$valid','$pdf_name')";
            $qry = mysqli_query($conn, $sql1);
            if ($qry) {
                echo '<script type="text/javascript"> alert("Details Uploaded Successfully")</script>';
            } else {
                echo '<script type="text/javascript">alert("Error")</script>';
            }
        } 
    } else {

        //if not in pdf format
        echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
    }
}

      

?>