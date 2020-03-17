<?php

/**
 * Melakukan test untuk GMD.
 */
class GmdTest extends TestCase
{
  public function testUuid()
  {
    $user = factory('App\User')->make();
    $this->assertTrue($user);
  }
}
