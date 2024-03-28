const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');

const drawShape = (shape) => {
    context.beginPath();
    for (var i = 0; i < shape.points.length; i++) {
        if (0 === i) {
            context.moveTo(shape.points[i].x, shape.points[i].y);
            continue;
        }

        context.lineTo(shape.points[i].x, shape.points[i].y);
    }
    context.lineTo(shape.points[0].x, shape.points[0].y);

    context.strokeStyle = '#000000';
    if (true === shape.collision) {
        context.strokeStyle = '#cc0000';
    }
    context.lineWidth = 2;
    context.stroke();
}

document.addEventListener('DOMContentLoaded', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const shapes = JSON.parse(shapeData);
    for (const shape of shapes) {
        drawShape(shape);
    }
});