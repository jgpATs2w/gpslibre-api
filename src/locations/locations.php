<?php
namespace locations;

function read($who, $from='', $to=''){
  $q= "SELECT * FROM locations WHERE who = '$who' ";
  if($from!='')
    $q= "AND logtime > '$from'";
  if($to!='')
    $q= "AND logtime < '$to'";

  \db\query($q);

  $locations= \db\get_array_full();

  return $locations;
}

function create($who, $lat, $lon, $batt='', $ts_remote='NULL'){
  \db\prepare("INSERT INTO locations (who, lat, lon, batt) VALUES (?,?,?,?)");
  \db\execute([$who, $lat, $lon, $batt]);

  return \db\last_id();
}

function readById($id){
  $q= "SELECT * FROM locations WHERE id = $id ";

  \db\query($q);

  $locations= \db\get_array_full();

  return count($locations)>0? $locations[0] : null;
}

?>
