<?php

class LoggerTest extends \AppTestCase
{

  public function test_logs(){

    \logger\error('tests', 'phpunit');

    $last= \db\query_single("select message from logger order by logtime desc limit 1");

    $this->assertSame($last,'phpunit');

    \db\query("delete from logger where message = 'phpunit'");
  }
}

?>
