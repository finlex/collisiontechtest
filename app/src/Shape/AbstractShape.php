<?php

declare(strict_types=1);

namespace App\Shape;

abstract class AbstractShape implements \JsonSerializable
{
    private bool $collision = false;
    
    public function __construct (
        public readonly array $points,
        public readonly array $edges
    ) {}

    public function jsonSerialize(): array {
        return [
            'points' => $this->points,
            'collision' => $this->collision
        ];
    }

    public function setCollision(bool $value): void
    {
        $this->collision = $value;
    }

    public function hasCollision(): bool
    {
        return $this->collision;
    }
}