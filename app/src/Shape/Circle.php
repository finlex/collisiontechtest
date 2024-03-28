<?php

declare(strict_types=1);

namespace App\Shape;

use App\Value\Vector2;

final class Circle extends AbstractShape
{
    public readonly Vector2 $midpoint;

    public readonly int $radius;
    
    public function __construct (array $points, array $edges) {
        parent::__construct($points, $edges);

        // Calculate midpoint of shape from average midpoint of edges
        // Needed for simple circle boundary checking
        $totalx = 0;
        $totaly = 0;
        foreach ($this->edges as $edge) {
            $midpoint = $edge->midpoint();
            $totalx += $midpoint->x;
            $totaly += $midpoint->y;
        }

        $this->midpoint = new Vector2(
            (int) round($totalx / count($this->edges)),
            (int) round($totaly / count($this->edges))
        );

        // We'll need a circle boundary radius to detect against
        // This will be the farthest point from the middle
        // Example shapes are all regular polygons so all points should
        // be about the same distance from middle anyway
        // However, this should handle irregular polygons also
        $max = 0;
        foreach ($this->points as $point) {
            $distance = distanceBetween($this->midpoint, $point);
            if ($distance > $max) {
                $max = $distance;
            }
        }
        $this->radius = $max;
    }
}