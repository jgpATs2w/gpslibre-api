<?php

use \db\DB;

class DBTest extends \AppTestCase
{

  public function test_db(){

    //select as array
    DB::query("select * from logger");
    $logs= DB::getArray();
    $this->assertTrue(is_string($logs[0]['message']), "la tabla logger debe contener al menos una fila, y el mensaje deberá llegar como string");

    //select as array of objects
    DB::query("select * from logger");

    $logs= DB::getObjects();

    $this->assertTrue(is_string($logs[0]->message), "la tabla logger debe contener al menos una fila, y el mensaje deberá llegar como string");

    //select as single value
    DB::query("select 'totem'");
    $single= DB::getSingle();

    $this->assertSame($single, "totem", "no devuelve un valor aislado");

    //select and walk over results efficiently
    DB::query("select * from logger");

    $n=0;
    while( $row= DB::$result->fetch()){
      $n++;
    }

    $this->assertGreaterThan(0, $n, "no se ha ejecutado la función recursiva en cada fila");

    //Uncomment to test error
    //DB::query("select * from tablaInexistente");

  }
}

?>
