<?php

namespace App\Helpers;

use App\Pattern;

class BibliobigrafiRelationship
{
  private $pattern;
  private $patternModify;
  private $str;
  /***
   * @return int $pattern
   */
  public function getPattern(): string
  {
    return $this->patternModify;
  }

  /**
   * @param int $str
   * @return void
   */
  public function setPattern(): void
  {
    $this->pattern = Pattern::findOrFail($this->str);
  }

  public function modifyPattern(int $str): void
  {
    $this->str = $str;
    $this->initPattern();
  }

  /**
   *
   */
  private function initPattern(): void
  {
    $this->setPattern();
    $this->patternModify = "{$this->pattern["prefix"]}{$this->incrementMiddle()}{$this->pattern["suffix"]}";
    $this->updatePattern();
  }

  private function incrementMiddle(): string
  {
    $middle = $this->pattern["middle"];
    $count = $middle;
    $inc = (int) ($count += 1);
    return substr_replace(
      $middle,
      $inc,
      strlen($middle) - strlen((string) $inc)
    );
  }

  /**
   *
   */
  private function updatePattern(): void
  {
    $pattern = Pattern::findOrFail($this->str);
    $pattern->last_pattern = $this->patternModify;
  }
}
