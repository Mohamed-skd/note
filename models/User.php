<?php

namespace Model;

class User
{
  function __construct(public int $id, public string $name)
  {
  }
}