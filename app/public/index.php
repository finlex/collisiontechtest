<?php

declare(strict_types=1);

require(__DIR__ . '/../src/misc/autoload.php');

use App\Shape\Circle;
use App\Shape\Factory;
use App\Shape\Rectangle;
use App\Value\Vector2;

// Function using pythagoras theorem to calculate distance between two coordinate points
function distanceBetween(Vector2 $point1, Vector2 $point2): int {
    $aSquared = abs($point2->x - $point1->x) ** 2;
    $bSquared = abs($point2->y - $point1->y) ** 2;
    return (int) sqrt($aSquared + $bSquared);
}

// Various axis aligned squares, some colliding
$shapeData1 = '
    [
        [{"x":95,"y":90},{"x":29,"y":90},{"x":29,"y":24},{"x":95,"y":24}],
        [{"x":181,"y":85},{"x":131,"y":85},{"x":131,"y":35},{"x":181,"y":35}],
        [{"x":321,"y":100},{"x":249,"y":100},{"x":249,"y":28},{"x":321,"y":28}],
        [{"x":402,"y":129},{"x":312,"y":129},{"x":312,"y":39},{"x":402,"y":39}],
        [{"x":582,"y":130},{"x":488,"y":130},{"x":488,"y":37},{"x":582,"y":37}],
        [{"x":547,"y":94},{"x":519,"y":94},{"x":519,"y":66},{"x":547,"y":66}]
    ]
';

// Various normal polygons using circle boundary checking
// Has example of downside of this detection method - two triangles that don't intersect
// but are detected as such due to crossing the generated circle boundary
$shapeData2 = '
    [
        [{"x":198,"y":162},{"x":139,"y":197},{"x":79,"y":164},{"x":78,"y":96},{"x":137,"y":61},{"x":197,"y":94}],
        [{"x":269,"y":237},{"x":226,"y":252},{"x":183,"y":235},{"x":161,"y":195},{"x":170,"y":151},{"x":205,"y":122},{"x":251,"y":123},{"x":286,"y":153},{"x":293,"y":198}],
        [{"x":529,"y":177},{"x":498,"y":188},{"x":466,"y":178},{"x":446,"y":151},{"x":446,"y":118},{"x":465,"y":91},{"x":496,"y":80},{"x":528,"y":90},{"x":548,"y":117},{"x":548,"y":150}],
        [{"x":582,"y":238},{"x":560,"y":247},{"x":536,"y":241},{"x":521,"y":223},{"x":519,"y":198},{"x":532,"y":178},{"x":554,"y":169},{"x":578,"y":175},{"x":593,"y":193},{"x":595,"y":218}],
        [{"x":753,"y":145},{"x":655,"y":148},{"x":701,"y":61}],[{"x":740,"y":215},{"x":666,"y":216},{"x":703,"y":151}],
        [{"x":453,"y":469},{"x":412,"y":491},{"x":369,"y":472},{"x":357,"y":427},{"x":385,"y":390},{"x":432,"y":388},{"x":462,"y":423}],[{"x":429,"y":453},{"x":407,"y":462},{"x":387,"y":450},{"x":383,"y":427},{"x":399,"y":410},{"x":422,"y":411},{"x":436,"y":431}]
    ]
';

// Combination of axis-aligned squares and normal polygons
$shapeData3 = '
    [
        [{"x":197,"y":194},{"x":79,"y":194},{"x":79,"y":76},{"x":197,"y":76}],
        [{"x":354,"y":281},{"x":231,"y":316},{"x":159,"y":210},{"x":238,"y":109},{"x":358,"y":153}],
        [{"x":644,"y":217},{"x":544,"y":217},{"x":544,"y":117},{"x":644,"y":117}],
        [{"x":669,"y":224},{"x":619,"y":258},{"x":558,"y":257},{"x":510,"y":220},{"x":493,"y":161},{"x":513,"y":104},{"x":563,"y":70},{"x":624,"y":71},{"x":672,"y":108},{"x":689,"y":167}],
        [{"x":872,"y":155},{"x":832,"y":155},{"x":832,"y":115},{"x":872,"y":115}],
        [{"x":967,"y":204},{"x":939,"y":230},{"x":900,"y":229},{"x":874,"y":201},{"x":875,"y":162},{"x":903,"y":136},{"x":942,"y":137},{"x":968,"y":165}]
    ]
';

// All shapes combined for fun
$shapeData4 = json_encode(
    array_merge(
        json_decode($shapeData1, true),
        json_decode($shapeData2, true),
        json_decode($shapeData3, true)
    )
);

$shapeData = json_decode($shapeData1, true);

$shapes = [];
// Process shape points to generate objects for easier processing later
foreach ($shapeData as $shapePoints) {
    $shapes[] = Factory::createFromPoints($shapePoints);
}

// Loop through shapes to check if any collisions
foreach ($shapes as $x => $shape) {
    // If we've already identified a collision on this shape we can skip
    if (true === $shape->hasCollision()) {
        continue;
    }

    // Loop through other shapes
    foreach ($shapes as $y => $check) {
        // Skip checking against the same shape
        if ($x === $y) {
            continue;
        }

        // If both shapes are axis aligned rectangles
        if (true === $shape instanceof Rectangle
         && true === $check instanceof Rectangle) {
            // Check if one shape's boundaries are inside the other on x axis
            // To do so we can just check if both left edges are left of both right edges
            // If so, must be collision on x-axis
            // Example:
            // 
            //    L1   R1       L2   R2
            //
            // Or
            //
            //    L2   R2       L1   R1
            //
            // Edges above as expected for two rectangles not intersecting
            //
            //    L1    L2   R1    R2
            //
            // Or
            //
            //    L2     L1  R1     R2
            //
            // Edges above show intersection or one containing the other
            // Both cases have both left edges to left of both right edges
            $collisionX = min($shape->right, $check->right) > max($shape->left, $check->left);
            
            // We can then do the same for top / bottom
            $collisionY = min($shape->top, $check->top) > max($shape->bottom, $check->bottom);

            // If both are true, shapes are overlapping
            if (true === $collisionX && true === $collisionY) {
                $shape->setCollision(true);
                // We can set the checked shape here as well, so no need to check later
                $check->setCollision(true);
            }

            continue;
        }

        // If both shapes are circle boundaries
        if (true === $shape instanceof Circle
         && true === $check instanceof Circle) {
            // Check if the edge of one circle intersects with another
            // To do so we want to calculate the distance between the middle of both
            // Then check if the combined radius is greater than the distance
            // If so, must be some overlap
            $distance = distanceBetween($shape->midpoint, $check->midpoint);
            if ($distance <= $shape->radius + $check->radius) {
                $shape->setCollision(true);
                $check->setCollision(true);
            }

            continue;
        }

        // If neither of above, we're checking an axis aligned rectangle against a circle boundary
        // For this, we can check if any point of the rectangle falls within the circle
        // Or, viewed another way, if the distance from a point to the middle of the circle
        // is less than the radius of the circle, it must be inside
        $rectangle = $shape instanceof Rectangle ? $shape : $check;
        $circle = $shape instanceof Circle ? $shape : $check;
        
        foreach ($rectangle->points as $point) {
            $distance = distanceBetween($point, $circle->midpoint);
            if ($distance <= $circle->radius) {
                $rectangle->setCollision(true);
                $circle->setCollision(true);
                break;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Collisions</title>
        <style>
            body {
                margin: 0;
                padding: 0;
            }

            #canvas {
                height: 100%;
                position: absolute;
                width: 100%;
            }
        </style>
        <script>
        const shapeData = '<?php echo json_encode($shapes); ?>';
        </script>
    </head>
    <body>
        <canvas id="canvas"></canvas>
        <script src="/index.js"></script>
    </body>
</html>