<html>
<head>
     <title>Assignment-1 </title>
     
</head>
<body>

<h1>INDEX Page</h1>

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


//selecting table

$table = "SELECT * FROM harsha ORDER BY id;";
$result = mysqli_query($conn,$table);
$allnames = array();

    
   if (mysqli_num_rows($result) > 0) {
     while($row = mysqli_fetch_assoc($result)) {
                    
                   $fname = $row['IP']."-".$row['COMMUNITY']."-".$row['PORT'].".rrd";
                   if ($row['filteredint']>0){
                   $filtint = split ("\,",$row['filteredint']);
                   foreach($filtint as $int){
                    $bytesin = "bytesIn".$int;
                   $bytesout = "bytesOut".$int;
                 
                   $opts = array( "--start", "-1d",
                   "--title= interface ".$int." ".$row['IP']."  ".$row['sysName'],
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
               $file = $row['IP']."-".$row['COMMUNITY']."-".$row['PORT'];
              
               $ret = rrd_graph ($file."-".$int.".png",$opts);
               
                 
                 
                 if( !is_array($ret) )
  {
    $err = rrd_error();
    echo "rrd_graph() ERROR: $err\n";
  }
  echo "<div style=\"float: left; margin-right:30px;\">";
  echo "<a href = 'details.php?id=".$row['id'].",".$int."' ><img src= cd.php?fname=".$file."-".$int.".png > </a>";
  echo "</div>";             
                   }

}
}
}
?>

</body>
</html>
