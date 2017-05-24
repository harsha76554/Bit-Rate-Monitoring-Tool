


<html>
<head>
      
</head>
<body>
<?php 
//$host = "194.47.151.125";
//$username = "et2536t7";
//$password = "konko";
//$database = "et2439t7";
//$port = "3306";
require 'db.php';
// Create connection
$conn = mysqli_connect($host, $username,$password,$database,$port);

// Check connection
if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
} 

$get = explode (",",$_GET["id"]);
$id = $get[0];
$int = $get[1];


if(isset($id) && isset($int) ) {

$result = mysqli_query($conn,"SELECT * FROM harsha where id='$id'");

while($row = mysqli_fetch_array($result)) 
{ 
 $file = $row['IP']."-".$row['COMMUNITY']."-".$row['PORT'];
 $fname = $row['IP']."-".$row['COMMUNITY']."-".$row['PORT'].".rrd";
 $bytesin = "bytesIn".$int;
 $bytesout = "bytesOut".$int;
 
                    echo "<table>";
                    echo "<tr><td>system-Contact=". $row['sysContact']."</td></tr>";
                    echo "<tr><td>system-Name=". $row['sysName']."</td></tr>";
                    echo "<tr><td>sys-Location=". $row['sysLocation']."<td></tr>";
                    echo "<tr><td>IP=". $row['IP']."<td></tr>";
                    echo "</table>";

  create_graph("".$file."-day.png","-1d","daily-".$row['IP']."-".$int." ".$row['sysName'],$fname,$bytesin,$bytesout);
  create_graph("".$file."-week.png","-1w","weekly-".$row['IP']."-".$int." ".$row['sysName'],$fname,$bytesin,$bytesout);
  create_graph("".$file."-month.png","-1m","monthly-".$row['IP']."-".$int." ".$row['sysName'],$fname,$bytesin,$bytesout);
  create_graph("".$file."-year.png","-1y","yearly-".$row['IP']."-".$int." ".$row['sysName'],$fname,$bytesin,$bytesout);
  echo "<div style=\"float: left; margin-right:30px;\">";
  echo "<img src=  cd.php?fname=".$file."-day.png >";
  echo "</div>";
  echo "<div style=\"float: left; margin-right:30px;\">";
  echo "<img src=  cd.php?fname=".$file."-week.png >";
  echo "</div>";
  echo "<div style=\"float: left; margin-right:30px;\">";
  echo "<img src=  cd.php?fname=".$file."-month.png >";
  echo "</div>";
  echo "<div style=\"float: left; margin-right:30px;\">";
  echo "<img src=  cd.php?fname=".$file."-year.png >";
  echo "</div>";                
  
  
} 

}

function create_graph($output,$start,$title,$fname,$bytesin,$bytesout){
  
                  
                  $opts = array( "--start", $start,
                   "--title= $title",
                    "--vertical-label=B/sec",
                 "DEF:".$bytesin."=".$fname.":".$bytesin.":AVERAGE",
                 "DEF:".$bytesout."=".$fname.":".$bytesout.":AVERAGE",
                 "AREA:".$bytesin."#00FF00:In traffic",
                 "LINE1:".$bytesout."#0000FF:Out traffic\\r",
                 "CDEF:inbits=".$bytesin.",8,*",
                 "CDEF:outbits=".$bytesout.",8,*",
                 "COMMENT:\\n",
                 "GPRINT:inbits:AVERAGE:Avg In traffic\: %6.2lf %Sbps",
                 "COMMENT:  ",
                 "GPRINT:inbits:MAX:Max In traffic\: %6.2lf %Sbps\\r",
                 "GPRINT:outbits:AVERAGE:Avg Out traffic\: %6.2lf %Sbps",
                 "COMMENT: ",
                 "GPRINT:outbits:MAX:Max Out traffic\: %6.2lf %Sbps\\r",
                 "GPRINT:inbits:LAST:last In traffic\: %6.2lf %Sbps\\r",
                 "GPRINT:outbits:LAST:last Out traffic\: %6.2lf %Sbps\\r"
                 
               );
            
            $ret = rrd_graph($output,$opts);
            if(! $ret){
             echo "<b>Graph error:</bg>".rrd_error()."\n";
             
            }
  
  }

mysqli_close($conn); 
?>
</body>
