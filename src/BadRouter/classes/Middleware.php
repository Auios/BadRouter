<?php
class Middleware {
  public $function;
  public $paths;

  public function __construct($function, $paths) {
    $this->function = $function;
    $this->paths = $paths;
  }
}
