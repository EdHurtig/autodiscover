<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
   $_REQUEST['purge']=true;
} else {
   $_REQUEST['get_hosts'] = true; 
}
include '../index.php';
