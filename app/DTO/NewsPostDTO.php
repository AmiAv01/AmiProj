<?php

namespace App\DTO;


use DateTime;

final class NewsPostDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly DateTime $dateTime)
    {
    }
}
