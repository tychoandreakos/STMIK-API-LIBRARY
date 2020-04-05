<?php

namespace App\Helpers;

use App\Pattern;

class BibliobigrafiRelationship
{
  private $pattern;
  private $str;
  private $newPattern;
  private $patternId;
  public function modifyPattern($str)
  {
    $this->str = $str;
    $this->findPattern();
  }

  public function getPattern()
  {
    $this->updatePattern();
    return $this->preventNull();
  }

  public function preventNull()
  {
    return is_null($this->patternId) ? $this->pattern : $this->patternId;
  }

  private function updatePattern()
  {
    try {
      $pattern = Pattern::find($this->str);
      $pattern->last_pattern = $this->joinPattern();
      $pattern->save();
    } catch (\Throwable $th) {
      return $th;
    }
  }

  private function joinPattern()
  {
    $int = (int) ($this->newPattern[1] += 1);
    $inc = substr_replace(
      $this->pattern,
      $int,
      strlen($this->newPattern[0]),
      strlen($this->newPattern[1])
    );

    $this->patternId = $inc;
    return $inc;
  }

  private function processPattern()
  {
    $matches = [];
    $pattern = '/(\w)(\d{3,})(\w)/';
    preg_match($pattern, $this->pattern, $matches);
    array_shift($matches);
    $this->newPattern = $matches;
  }

  private function findPattern()
  {
    $this->pattern = Pattern::findOrFail($this->str)->last_pattern;
    $this->processPattern();
  }
}
