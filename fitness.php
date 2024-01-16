<!-- fitness.php -->
<html>
<head>
    <title>FITNESS CERTIFICATE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="hello/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="hello/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="hello/favicon-16x16.png">
    <link rel="manifest" href="hello/site.webmanifest">
    <link rel="stylesheet" href="project.css">
    <style>
h1 {
    text-shadow: 1px 2px 1px brown;
    font-weight: bold;
    color: black;
    margin:auto;
}
</style>
</head>

<body>
    <header >
        <h1> FITNESS CERTIFICATE </h1>
        <span style="display:flex;"> <img src="home.png" onclick="back()" style="height:50px; margin-top:-50px; cursor:pointer;"></span>  
    </header><br><br><br>
    <script>
function back()
{
 location.href="Transport.php"
}
</script>
    <main>
        <div class="myDiv">
        <form action="" method="post" enctype="multipart/form-data">
            <h style="margin-left:85px;">
            <label id="l1" name="bus_id" for="bus" style="color:red;">Vehicle No  </label>
            <input type="text" id="txt" name = "id" required><span>&emsp;</span>
            <input id="btn1" type="submit" name="submit" value="Display">  <br> <br> </h>
            <label id="" name="" for="bus" >FC Number  </label>
            <input type="text" id="txt" name = "fc_no"><br> <br>
            <label id="" name="" for="bus">Issue Date  </label>
            <input type="date" id="txt" name = "issue_date" ><br> <br>
            <label id="" name="" for="bus">Expiry Date </label>
            <input type="date" id="txt" name = "expiry_date" > 
            <br> <br><br>
            <input type="file" name="uploadfile" id="f1"><span>&emsp;</span>
            <input type="submit" id="btn1" name="fitnessfile" value="Upload"><span>&emsp;&emsp;</span>
            <br> <br>
        </form>
        </div><br><br>
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

                // to get the  expiry date from the table
                $query = "select expirydate,fcnumber from fitness where id = '$busid'";
                $res=mysqli_query($conn,$query);
            
                // Check if the bus ID exists in the table
                if(mysqli_num_rows($res) == 0) {

                    // Display an error message if the bus ID is not found
                    echo "<script type='text/javascript'> alert('Bus ID not found!!!'); </script>";
                } else {

                    // Search for PDF files in the "pdfs" directory with a file name that contains the ID
                    $pdfs = glob('fitnesspdf/*' . $busid . '*.pdf');
            
                   // Check if the user is logged in as admin
                    session_start();
                    $rs=mysqli_query($conn,"SELECT username,password from login WHERE typeofuser='admin'");
                    $row=mysqli_fetch_array($rs);
                    $username=$row["username"];
                    $password=$row["password"];

                    //if it matches the username and password of admin
                    if($_SESSION["uname"]==$username && $_SESSION["pwd"]==$password){

                        // Display the PDFs in a table or list
                    echo "<table>";
                    echo "<tr><th>Bus ID</th><th>FC NUMBER</th><th>ISSUE DATE</th><th>EXPIRY DATE</th><th>FILE </th><th>DELETION</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);

                        // Get the expiry date from the database
                        $expiry=$row1['expirydate'];
                        $fcnumber=$row1['fcnumber'];
                        $filename = basename($pdf);

                        // Get the issued date from the filename
                        $issuedate = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
            
                        // Display the PDF file information in a table row
                        echo "<tr><td>" . $busid . "</td><td>" . $fcnumber . "</td><td>" . $issuedate . "</td><td>" . $expiry . "</td><td><a href='" . $pdf . "'>Download</a></td><td> <a href='?delete=" . $combined_name . "'>Delete</a></td></tr>";
                    }
                    echo "</table>";
                }else{
                    // Display the PDF files in a table or list without the delete option - not admin
                    echo "<table>";
                    echo "<tr><th>Bus ID</th><th>FC NUMBER</th><th>ISSUE DATE</th><th>EXPIRY DATE</th><th>FILE</th></tr>";
                    foreach ($pdfs as $pdf ) {
                        $row1=mysqli_fetch_array($res);
                        $to_date=$row1['expirydate'];
                        $fcnumber=$row1['fcnumber'];
                        $filename = basename($pdf);
                        $from_date = substr($filename, strpos($filename, '_') + 1, -4);
                        $combined_name = basename($pdf);
                    
                        echo "<tr><td>" . $busid . "</td><td>" . $fcnumber . "</td><td>". $from_date ."</td><td>". $to_date . "</td><td><a href='" . $pdf . "'>Download</a></td></tr>";   
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

    // selects the expirydate of the bus from the 'fitness' table for the specified bus ID and date.
    $query = "select expirydate from fitness where id = '$bus_id' and issuedate='$date'";
    $res1 = mysqli_query($conn,$query);

    // to delete the record for the specified bus ID and date from the 'fitness' table.
    $qry = "DELETE from fitness where id='$bus_id' and issuedate='$date'";
    $res = mysqli_query($conn,$qry);
    if($res) { // If the query to delete the record(s) from the fitness table was successful

        //Check if the file associated with the deleted record exists in the fitnesspdf directory
        if(file_exists('fitnesspdf/' . $delete_file)) {

            // if file exists delete it from the fitnesspdf directory
            unlink('fitnesspdf/' . $delete_file);

            //file and record both deleted successfully
            echo "<script type='text/javascript'> alert('File and record deleted Successfully'); window.location.href = 'http://localhost/form123/fitness.php';</script>";
        } else {
            //if the file associated with the deleted record does not exist
            echo "<p style='text-align:center; color:green;'>Record deleted successfully but not file.</p>";
        }
    } else {
        // if not successful
        echo "<p style='text-align:center; color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    } 
}

// Check if the form for adding a new fitness record is submitted
if (isset($_POST['fitnessfile'])) {

    //establishes a connection to the transport database
        $conn = mysqli_connect("localhost", "root", "", "transport");

        // Retrieve values from the form
        $file = $_FILES['uploadfile']['name'];
        $busid = $_POST['id'];
        $fc = $_POST['fc_no'];
        $pdf_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // get the file extension
        $allowed_ext = array("pdf"); // allowed file extensions
        $issuedate = $_POST['issue_date'];
        $expirydate = $_POST['expiry_date'];
        $filetmpname = $_FILES['uploadfile']['tmp_name'];
        $folder = 'fitnesspdf/'; //folder

        // Check if any of the form fields are empty
        if(empty($busid) || empty($fc) || empty($issuedate) || empty($expirydate) || empty($file)){
            echo '<script type="text/javascript"> alert("Details not inserted. Please fill all the details")</script>';
        } else if(in_array($pdf_ext, $allowed_ext)) {
            // Establish connection to the transport database
            $conn = mysqli_connect("localhost", "root", "", "transport");

            // Check if `busid` exists in `masterdemo` table
            $check_sql = "SELECT * FROM `masterdemo` WHERE `busid`='$busid'";
            $check_query = mysqli_query($conn, $check_sql);
            $num_rows = mysqli_num_rows($check_query);
    
            if($num_rows == 0){
                // If `busid` does not exist in `masterdemo` table
                echo '<script type="text/javascript"> alert("Enter valid Bus ID!!!")</script>';
            } else {
                // Move the uploaded file to a folder 
                move_uploaded_file($filetmpname, $folder.$busid.'_'.$issuedate.'.pdf');

                // Insert the form values into the `fitness` table
                $insert_sql = "INSERT INTO `fitness`(`id`, `fcnumber`, `issuedate`, `expirydate`, `pdf`) VALUES ('$busid','$fc','$issuedate','$expirydate','$file')";
                $insert_query = mysqli_query($conn, $insert_sql);

                if ($insert_query) {
                    echo '<script type="text/javascript"> alert("Details uploaded successfully")</script>';
                } else {
                    echo '<script type="text/javascript"> alert("Error while uploading details...")</script>';
                }
            }
        } else {
            // if uploaded file is not in pdf format
            echo '<script type="text/javascript"> alert("Invalid file type. Only PDF files are allowed.")</script>';
        }
}
?>