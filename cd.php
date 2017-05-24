<?php

$fname = $_GET["fname"];

if(isset($fname)){
                  $file = fopen($fname,"rb");
                  fpassthru($file);
                  
}
unlink($fname);
                  


?>
