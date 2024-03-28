# Collision detection technical test

For Iris Finney

## Overview

To be fair to other candidates - I might have had a small advantage with this test as I have experimented with collision detection a little in the past for some JavaScript games. I didn't re-use any previous code (besides a simple php autoloader), but I was aware of possible solutions ahead of starting.

Given the requirement for detecting collisions between different types of shapes, I would have liked to try building a SAT (separating axis theorem) detection system. However, this requires a lot of vector-based calculations so PHP isn't really the right language for it. Also, having not tried it before, it'd likely take far too long.

Instead, I opted for simple boundary checking with rectangles where possible, and circles for all other shapes. This method is a lot easier to build, however it comes at the cost of accuracy for any irregular polygon shapes, or even regular polygons with low numbers of sides such as triangles or rotated squares. An example of this can be seen in `$shapeData2` shapes (variables explained below).

### Time

The initial request noted it would be good to keep a record of time taken.

For the PHP code - the actual collision detection portion of the test - I would say this took me around 2.5 hours total, 30 minutes or so of which was refactoring code after I felt I had reached a good solution.

It was suggested this should be doable in an hour - after getting started I felt this was actually quite challenging to do within an hour, but that could be because I attempted build a solution for many types of shapes from the outset and not just rectangles which would have been quicker.

I also spent an additional hour or two building the shape generator tool. I'm noting this separately as it was outside of scope, and originally just built for my own convenience in generating shape data, but wanted to mention anyway to keep things fair.

## How to view

I have provided a simple docker-compose.yml file in the project root which can be used to spin up a web server and php containers for this project. When running, it can be viewed at http://127.0.0.1

## Pages

### index.php (app/public/index.php)

The index page is a visualisation of the shapes and whether they are colliding or not.

Inside `app/public/index.php` you can find a few different `$shapeData<x>` variables with different sets of shapes. The project defaults to `$shapeData1`, loaded on line 66. This can be changed to view the different sets, i.e. `$shapeData2`, `$shapeData3` or `$shapeData4`.

### draw.html (app/public/draw.html)

The draw.html page is a small tool I built for generating shape data. I figured it's worth including in the project, even though it's outside scope, in case you want to generate your own shape data to test against my solution.

To use, you can select the number of sides for the polygon in the top-right corner. Then click on the browser at the midpoint of the shape and drag to create.

You can undo created shapes with the 'Undo' button in the top-right.

When you're happy with the shapes, you can click 'export' in the top-right to log the shape data to the console. This can then be copied into a new variable in the `index.php` file, and loaded in the same way as the other `$shapeData<x>` examples.

### Additional notes for drawing shapes

You'll notice that there's no option for a 1-sided polygon, i.e. a circle. Instead, you can create a 100-sided polygon which is enough sides to appear as a circle. This is much more performant since we're not working with vectors here.

If you want to create a square, there are two options. A square with any rotation away from the x/y axis on the edges will be assigned a circle boundary for collision, as with any other polygon. If you are careful when drawing the square, and ensure the edges are pixel-perfect parallel with axis (a bit fiddly, but not too tough) then the shape will be treated as an axis aligned rectangle and will have a square boundary instead.