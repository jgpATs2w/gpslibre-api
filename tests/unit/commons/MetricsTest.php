<?php

class MetricsTest extends \AppTestCase
{

  public function test_metrics(){

    \metrics\increase("tests");

    $nTests= \metrics\get("tests");

    $this->assertGreaterThan(0, $nTests,'metric of tests should have at least one');

  }
}

?>
