<?php

declare(strict_types=1);

namespace App\Value;

/**
 * Contains two Vector2 points, connecting to form an edge of a shape
 */
final class Edge
{
    public function __construct(
        public readonly Vector2 $pointOne,
        public readonly Vector2 $pointTwo
    ) {
    }

    public function midpoint(): Vector2
    {
        $x = (int) round(($this->pointOne->x + $this->pointTwo->x) / 2);
        $y = (int) round(($this->pointOne->y + $this->pointTwo->y) / 2);

        return new Vector2($x, $y);
    }
}