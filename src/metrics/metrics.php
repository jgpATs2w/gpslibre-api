<?php
namespace metrics;

function increase($name){

  $q= "INSERT INTO metrics (name, value) VALUES ('$name', 1)
        ON DUPLICATE KEY UPDATE value = value + 1";

  \db\query($q);
}

function set($name, $value){
  $q= "INSERT INTO metrics (name, value) VALUES ('$name', '$value')
        ON DUPLICATE KEY UPDATE value = '$value'";

  \db\query($q);
}

function get($name){
  $q= "select * from metrics where name='$name'";

  return \db\query_single($q);
}

?>
