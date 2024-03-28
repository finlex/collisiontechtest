<?php

declare(strict_types=1);

namespace App\Value;

/**
 * A simple class containing x and y values
 */
final class Vector2
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }
}
