<?php

namespace Model;

class Note
{
  function __construct(public int $id, public string $content, public string $author, public string $createdAt, public ?string $updatedAt = null)
  {
  }
}