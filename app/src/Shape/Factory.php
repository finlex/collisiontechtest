<?php

declare(strict_types=1);

namespace App\Shape;

use App\Value\Edge;
use App\Value\Vector2;

final class Factory
{
    public static function createFromPoints (array $pointData): AbstractShape
    {
        // Ideally would have additional validation here to check point data structure is as expected

        // Create a Vector2 for all points, record edges
        $points = [];
        $edges = [];
        foreach ($pointData as $point) {
            $points[] = new Vector2(
                (int) round($point['x']),
                (int) round($point['y'])
            );

            // Once we have more than 1 point, record edges as previous two points
            $count = count($points);
            if (1 < $count) {
                $edges[] = new Edge(
                    $points[$count - 2],
                    $points[$count - 1],
                );
            }
        }

        // Add final edge from last point to first
        $edges[] = new Edge(
            $points[count($points) - 1],
            $points[0]
        );

        // Create Rectangle if possible, otherwise Circle
        if (true === self::isAxisAlignedRectangle($points)) {
            return new Rectangle($points, $edges);
        }

        return new Circle($points, $edges);
    }

    private static function isAxisAlignedRectangle(array $points): bool
    {
        // Check if shape is axis aligned rectangle
        // i.e. edges are parallel to axis, not rotated at all like a diamond
        // If so, can do more accurate detection with points instead of circle boundary
        // so we'll handle with separate shape object
        
        // Record all x and y values
        $xValues = [];
        $yValues = [];
        foreach ($points as $point) {
            $xValues[] = $point->x;
            $yValues[] = $point->y;
        }

        // If amount of unique x and y values are both 2, is true
        // i.e. both axis are straight
        if (2 === count(array_unique($xValues)) && 2 === count(array_unique($yValues))) {
            return true;
        }

        return false;
    }
}
