
<?php 
header('Access-Control-Allow-Origin: *');  
echo json_encode($this->get('formats', []));
echo "|";
echo json_encode($this->get('streams', []));
?>
