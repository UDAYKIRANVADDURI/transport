<!-- insurance.php -->
<html>

<head>
    <title>INSURANCE CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">

</head>

<body>

    <header>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; cursor:pointer;">
            <h1> INSURANCE CERTIFICATE </h1>
    </header>
    <script>
    function back() {
        location.href = "Transport.php"
    }
    </script>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="myDiv">
                <h style="margin-left:80;">
                    <label for="bus" style="color:red;">Registration Number </label>
                    <input type="text" id="txt" name="id" required><span>&emsp;</span><button id="btn1" type="submit"
                        name="submit" value="Display">Display</button> <br><br>
                </h>
                <label id="l2" for="">Policy No </label>
                <input type="text" name="policy_no" id="txt"> <br> <br>

                <label>From </label>
                <input type="date" name="from_date" id="from_date"> <br> <br>
                <label>To </label>
                <input type="date" name="to_date" id="to_date"> <br>
                <br>
                <label id="l3" for="">Total Premium </label>
                <input type="number" name="total_prem" id="txt"> <br> <br><br>
                <input type="file" name="uploadfile" id="f1">
                <button type="submit" id="btn2" name="insurancefile" value="Uplaod">Upload
                </button><br><br>

            </div>
        </form>
    </main>
</body>

</html>
<?php
// Check if the submit button has been clicked
            if(isset($_POST['submit'])){
                $conn = mysqli_connect("localhost", "root", "", "transport");
                // Get the ID input value
                $busid = $_POST['id'];
                $query = "select todate,policyno,totalprem from insurance where id = '$busid'";
                $res = mysqli_query($conn,$query);
            
                if(mysqli_num_rows($res) == 0){
                    echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
                } else {
                    // Search for PDF files in the "pdfs" directory with a file name that contains the ID
                    $pdfs = glob('insurancepdf/*' . $busid . '*.pdf');
                    
                    session_start();
                    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
                    $row=mysqli_fetch_array($rs);
                    $username=$row["username"];
                    $password=$row["password"];

                    // if username and password gets matched with admin
                    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){
                    // Display the PDFs in a table or list
                    echo "<table>";
                    echo "<tr><th>BUS ID</th><th>POLICY NO</th><th>FROM DATE</th><th>TO DATE</th><th>TOTAL PREMIUM</th><th>File</th><th>Deletion</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);
                        //getting data from table
                        $to_date=$row1['todate'];
                        $policyno=$row1['policyno'];
                        $totalprem=$row1['totalprem'];
                        $filename = basename($pdf);
                        $from_date = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
            
                        echo "<tr><td>" . $busid . "</td><td>" . $policyno . "</td><td>" . $from_date . "</td><td>" . $to_date . "</td><td>" . $totalprem . "</td><td><a href='" . $pdf . "'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
                    }
                    echo "</table>";
                }else{
                     // Display the PDF files in a table or list without the delete option - not admin
                    echo "<table>";
                    echo "<tr><th>BUS ID</th><th>POLICY NO</th><th>FROM DATE</th><th>TO DATE</th><th>TOTAL PREMIUM</th><th>File</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);
                        $to_date=$row1['todate'];
                        $policyno=$row1['policyno'];
                        $totalprem=$row1['totalprem'];
                        $filename = basename($pdf);
                        $from_date = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
                    
                        echo "<tr><td>" . $busid . "</td><td>" . $policyno . "</td><td>" . $from_date . "</td><td>" . $to_date . "</td><td>" . $totalprem . "</td><td><a href='" . $pdf . "'>Download</a></td>";   
                    }
                    echo "</table>";
                }
            } }
if(isset($_GET['delete'])) { //checks if the 'delete' parameter is present in the URL
    $conn = mysqli_connect("localhost", "root", "", "transport");
    $delete_file = $_GET['delete'];
    $bus_id = substr($delete_file,0,strpos($delete_file, '_') );
    $date = substr($delete_file, strpos($delete_file, '_') + 1, -4);
    // selects the todate of the bus from the 'insurance' table for the specified bus ID and date.
    $query = "select todate from insurance where id = '$bus_id' and fromdate='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'insurance' table.
    $qry = "DELETE from insurance where id='$bus_id' and fromdate='$date'";
    $res = mysqli_query($conn,$qry);
    if($res) {// If the query to delete the record(s) from the insurance table was successful
        if(file_exists('insurancepdf/' . $delete_file)) {
            unlink('insurancepdf/' . $delete_file);
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'insurance.php';</script>";
        } else {
            echo "<p style='text-align:center; color:green;'>Record deleted successfully.</p>";
        }
    } else {
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    } 
}

//check if new values are submitted in insurance table
if (isset($_POST['insurancefile'])) {
    $conn = mysqli_connect("localhost", "root", "", "transport");
    // Retrieve values from the form
    $pdf_name = $_FILES['uploadfile']['name'];
    $pdf_ext = strtolower(pathinfo($pdf_name, PATHINFO_EXTENSION));
    $allowed_ext = array("pdf");
    $pdf_temp = $_FILES['uploadfile']['tmp_name'];
    $pdf_type = $_FILES['uploadfile']['type'];
    $pdf_size = $_FILES['uploadfile']['size'];
    $busid = $_POST['id'];
    $policy = $_POST['policy_no'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $prem = $_POST['total_prem'];
    $folder = 'insurancepdf/';

    // Check if the bus ID exists in the `masterdemo` table
    $check_query = "SELECT * FROM `masterdemo` WHERE `busid` = '$busid'";
    $check_result = mysqli_query($conn, $check_query);
    if(mysqli_num_rows($check_result) == 0) {
        echo '<script type="text/javascript"> alert("Enter valid Bus ID!!")</script>';
    } else {
        // Check if any of the form fields are empty
        if(empty($busid) || empty($policy) || empty($from_date) || empty($to_date) || empty($prem) || empty($pdf_name)){
            echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
        } else if(in_array($pdf_ext, $allowed_ext)) {
            move_uploaded_file($pdf_temp, $folder.$busid.'_'.$from_date.'.pdf');

            // Insert the form values into the `insurance` table
            $sql = "INSERT INTO `insurance`(`id`, `policyno`, `fromdate`, `todate`, `totalprem`, `pdf`) VALUES ('$busid','$policy','$from_date','$to_date','$prem','$pdf_name')";
            $qry = mysqli_query($conn, $sql);
            if ($qry) {
                echo '<script type="text/javascript"> alert("Details uploaded successfully")</script>';
            } else {
                echo '<script type="text/javascript">alert("Error")</script>';
            }
        } else {
            echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
        }
    }
}

?>