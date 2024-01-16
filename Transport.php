<?php
$conn=new mysqli("localhost","root","","transport");
if($conn->connect_error)
{
  die("Connection Failed:".$conn->connect_error);
}
else{
  $q1="INSERT INTO scrap SELECT busid,enginenumber,chassis, IF(status='active','inactive','inactive') status,expirydate FROM status s WHERE s.expirydate<now()-interval 1 day;";
  $res=mysqli_query($conn,$q1);
  $q2=mysqli_query($conn,"delete from status where expirydate < now()-interval 1 day;");
  $q3=mysqli_query($conn,"delete from masterdemo where busid in (select busid from scrap);");
}
?>
<?php
 if(isset($_POST['logout']))
 {
    session_start();
    unset($_SESSION);
    session_destroy();
    echo "<script>setTimeout(\"location.href = 'http://localhost/login1.php';\",1000);</script>";
    exit;
 }
?>

<html>
<title>VVIT Transportation</title>
<link rel="apple-touch-icon" sizes="180x180" href="faviconio/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="faviconio/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="faviconio/favicon-16x16.png">


<style>
    button {
    position: absolute;
    bottom: 40px;
    left: 98%;
    margin-left: -104.5px; 
    width: 100px;
    height: 50px;
    border-radius:10px;
    background:#aa492e;
    color:#fff;
    border:2px solid #fff;
    font-weight:bold;
    font-size: 15px;
    cursor: pointer;
}

    #background-video {
        height: 100vh;
        width: 100vw;
        object-fit: cover;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: -1;
    }
    
    .viewport-header {
        height: 67vh;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    h1 {
        text-shadow: 1.5px 1.5px 4px black;
        text-align: center;
        font-weight: bold;
        font-size: 50px;
        font-style: normal;
        color: Tomato;
        font-family: Serif;
    }
    
    h2 {
        text-shadow: 1.5px 1.5px 4px black;
        text-align: center;
        font-weight: bold;
        font-size: 50px;
        font-style: normal;
        color: Tomato;
        font-family: Serif;
    }
    
    h1 {
        font-family: 'Syncopate', sans-serif;
        color: #aa492e;
        /* #4a3a27 */
        text-transform: uppercase;
        letter-spacing: 3vw;
        line-height: 1.2;
        font-size: 2.3vw;
        text-align: center;
    }
    
    h1 span {
        display: block;
        font-size: 5.5vw;
        letter-spacing: -0.3vw;
    }
    
    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    a {
        margin: 0;
        text-align: center;
        padding: 12px 15px 12px 15px;
        color: white;
        display: block;
        text-decoration: none;
    }
    
    .mainMenu {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }
    
    #w {
        padding: 20px;
    }
    
    .mainMenu>li {
        display: inline-block;
        margin-left: 2px;
        width: 300px;
    }
    
    li:hover>a {
        background: #809185;
        cursor: pointer;
    }
    
    .subMenu {
        position: absolute;
        display: none;
    }
    
    .subMenu li {
        border-top: 1px solid #575F6A;
        border-bottom: 1px solid #6B727C;
        position: relative;
    }
    
    .mainMenu>li:hover .subMenu {
        display: block;
    }
    
    .SuperSubMenu {
        position: absolute;
        top: 0;
        right: 0;
        -ms-transform: translate(100%, 0);
        -webkit-transform: translate(100%, 0);
        transform: translate(100%, 0);
        display: none;
    }
    
    .subMenu li:hover>.SuperSubMenu {
        display: block;
    }
    
    #myVideo {
        position: fixed;
        right: 0;
        bottom: 0;
        top: 0;
        left: 0;
        min-width: 100vw;
        min-height: 100vh;
    }
    
    .navbar {
        background: #aa492e;
        border-radius: 20px;
        position: sticky;
        top: 0px;
    }
    
    .subMenu li a {
        background-color: #aa492e;
    }
    
    li:hover>a {
        background: #809185;
        cursor: pointer;
    }
</style>

<body>
    <!--
<video autoplay muted loop id="myVideo">
<source src="video2.mp4" type="video/mp4">
</video>

<header><h1>SOCIAL EDUCATION TRUST</h1>
<h2>SOCIAL TRANSPORT</h2>
</header>
-->
    <form>
        <video id="background-video" autoplay loop muted poster="">
        <source src="video2.mp4" type="video/mp4">
      </video>
    </form>
    <nav class="navbar">
        <ul class="mainMenu">
            <li><a id="w" href="#">Vehicle info</a>
                <ul class="subMenu">
                    <li><a href="RC.php">Registration certificate</a></li>
                    <li><a href="fitness.php">Fitness Certificate</a></li>
                    <li><a href="permit.php">Permit</a></li>
                    <li><a href="eib.php">EIB</a></li>
                    <li><a href="insurance.php">Insurance</a></li>
                    <li><a href="pollution.php">Pollution</a></li>
                    <li><a href="status.php">Status</a></li>
                    

                </ul>
            </li>
            <li><a id="w" href="#">oils and Lubricants</a>
                <ul class="subMenu">
                    <li><a href="diesel.php">Diesel</a>
                       
                    </li>

                    <li><a href="#">Lubricants</a>
                        <ul class="SuperSubMenu">
                            <li><a href="coolent.php">Coolent</a></li>
                            <li><a href="gearoil.php">Gear Oil</a></li>
                            <li><a href="stearingoil.php">Steering Oil</a></li>
                            <li><a href="greasing.php">Greasing Oil</a></li>
                            
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a id="w" href="accidents.php">Accidents</a></li>

            <li><a href="#">Repairs and Maintenance</a>
                <ul class="subMenu">
                    <li><a href="EngineRepairs.php">Engine Repairs</a></li>
                    <li><a href="#">Electrical Repairs</a>
                        <ul class="SuperSubMenu">
                            <li><a href="battery.php">Battery cost</a></li>
                            <li><a href="selfmotordinamo.php">self motor and dinamo cost</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Body Repairs</a>
                        <ul class="SuperSubMenu">
                            <li><a href="springs.php">Springs Cost</a></li>
                            <li><a href="paints.php">Painting cost</a></li>
                            <li><a href="glasswork.php">Glass work cost</a></li>
                            <li><a href="seats.php">Seats cost</a></li>
                            <li><a href="tyres.php">Tyres cost</a></li>
                            <li><a href="stickering.php">Stikkering cost</a></li>
                        </ul>

                    </li>

                    
                </ul>

            </li>

            <li><a href="#" id="w">Taxes</a>
                <ul class="subMenu">
                    <li><a href="periodicaltaxes.php">Periodical Tax</a>
                        

                    </li>

                    <li><a href="quarterlytaxes.php">Quarterly Tax</a>
                        
                    </li>
                    <li><a href="stoppage.php">Stoppage</a>
                        
                    </li>
                    
                        
                    
                </ul>

            </li>
            <li><a href="#">REPORTS</a>
                <ul class="subMenu">
                    <li>
                    <li><a href="BodyRepairReport.php">Body Repair Report</a></li>
                    <li><a href="dieselreport.php">Diesel Report</a></li>
                    <li><a href="ElectricalRepairsReport.php">Electrical Repair Report</a></li>
                    <li><a href="EngineRepairReport.php">Engine Repair Report</a></li>
                    <li><a href="insurancereport.php">Insurance Report</a></li>
                    <li><a href="lubricantsreport.php">Lubricants Report</a></li>
                    <li><a href="MonthlyReport.php">Monthly Report</a></li>
                    <li><a href="othersReport.php" id="w">Others Report</a> </li>
                    <li><a href="stoppagereport.php">Stoppage Report</a></li>
                    <li><a href="customizedreport.php" style="background-color:yellow;color:black;">Cummulative Report</a></li>
                    <li><a href="estimatedexp.php">Estimated Expenditure</a></li>
                   
                   
                   
                 
                    </li>
                        </ul>

                    </li>
            

            </li>
        
                    <li><a href="others.php" id="w">Others</a> </li>
                   
                  

        </ul>
    </nav>

    <header class="viewport-header">
        <h1>SOCIAL TRANSPORT
            <span>SOCIAL EDUCATIONAL TRUST</span>
        </h1>
    </header>
    <form method="post" action="">
    <button name="logout">Logout</button>
    </form>


    <script>
        function open() {
            location.href = "certi.html";
        }
    </script>
</body>

</html>
