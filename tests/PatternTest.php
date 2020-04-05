<?php

class PatternTest extends TestCase
{
  private $lastAcesst = "b2003";
  public function testMe()
  {
    $arr = ["b", "200", "3"];
    $this->assertEquals($this->access(), $arr);
  }

  public function testIncrement()
  {
    $int = (int) ($this->access()[1] += 1);
    $inc = substr_replace(
      $this->lastAcesst,
      $int,
      strlen($this->access()[0]),
      strlen($this->access()[1])
    );
    $this->assertEquals($inc, "b2013");
  }

  public function access()
  {
    $matches = [];
    $pattern = '/(\w)(\d{3,})(\w)/';
    preg_match($pattern, $this->lastAcesst, $matches);
    array_shift($matches);
    return $matches;
  }

  public function testArray()
  {
    $x = ["a" => "a"];
    $a = [1];
    foreach ($a as $key) {
      $x[$key] = "x";
    }

    $this->assertEquals($x, [
      "a" => "a",
      1 => "x"
    ]);
  }
}
