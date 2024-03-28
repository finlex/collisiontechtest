<?php

declare(strict_types=1);

namespace App\Shape;

final class Rectangle extends AbstractShape
{
    public readonly int $left;

    public readonly int $right;

    public readonly int $bottom;

    public readonly int $top;

    public function __construct (array $points, array $edges) {
        parent::__construct($points, $edges);

        // For rectangle vs rectangle checks we'll want simple boundary values
        $left = null;
        $right = null;
        $bottom = null;
        $top = null;

        // Loop through each points set and search for the lowest & highest values for each x and y
        foreach ($points as $point) {
            if (null === $left || $point->x < $left) {
                $left = $point->x;
            }

            if (null === $right || $point->x > $right) {
                $right = $point->x;
            }

            if (null === $bottom || $point->y < $bottom) {
                $bottom = $point->y;
            }

            if (null === $top || $point->y > $top) {
                $top = $point->y;
            }
        }

        $this->left = $left;
        $this->right = $right;
        $this->bottom = $bottom;
        $this->top = $top;
    }
}
