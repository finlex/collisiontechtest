class Vector2 {
    constructor (x, y) {
        this.x = x;
        this.y = y;
    }

    clone () {
        return new Vector2(this.x, this.y);
    }

    compare (vector) {
        return this.x === vector.x && this.y === vector.y;
    }
}

class Shape {
    constructor (points, edges) {
        this.points = points;
        this.edges = edges;
    }
}

class Ui {
    constructor () {
        this.playground = document.getElementById('playground');
        this.context = this.playground.getContext('2d');

        this.mousePosition = new Vector2(0, 0);
        this.startPosition = new Vector2(0, 0);
        this.newShape = null;

        this.shapes = [];
    }

    init () {
        this.resize();
        setInterval(this.draw.bind(this), 10);

        window.addEventListener('mousemove', this.mouseMove.bind(this));
        window.addEventListener('mousedown', this.mouseDown.bind(this));
        window.addEventListener('mouseup', this.mouseUp.bind(this));
        document.getElementById('export').addEventListener('click', this.export.bind(this));
        document.getElementById('undo').addEventListener('click', this.undo.bind(this));
    }

    resize () {
        this.playground.width = window.innerWidth;
        this.playground.height = window.innerHeight;
    }

    mouseMove (event) {
        this.mousePosition = new Vector2(event.clientX, event.clientY);
    }

    mouseDown (event) {
        if ('playground' !== event.target.id) {
            return;
        }

        this.startPosition = this.mousePosition.clone();
        this.newShape = true;
    }

    mouseUp () {
        this.startPosition = new Vector2(0, 0);
        if (true === this.newShape instanceof Shape) {
            this.shapes.push(this.newShape);
        }
        this.newShape = null;
    }

    radiansAngle = (point1, point2) => {
        return Math.atan2(point2.y - point1.y, point2.x - point1.x);
    }

    distanceBetween = (point1, point2) => {
        const a2 = Math.abs(point2.x - point1.x) ** 2;
        const b2 = Math.abs(point2.y - point1.y) ** 2;
        return Math.sqrt(a2 + b2);
    }

    draw () {
        this.context.clearRect(0, 0, playground.width, playground.height);

        for (const shape of this.shapes) {
            this.drawShape(shape);
        }

        if (null !== this.newShape && false === this.mousePosition.compare(this.startPosition)) {
            const sides = document.getElementById('sides-select').value;
            const shape = this.createShape(sides);
            this.drawShape(shape);
            
            this.newShape = shape;
        }
    }

    drawShape (shape) {
        this.context.beginPath();
        for (var i = 0; i < shape.points.length; i++) {
            if (0 === i) {
                this.context.moveTo(shape.points[i].x, shape.points[i].y);
                continue;
            }

            this.context.lineTo(shape.points[i].x, shape.points[i].y);
        }
        this.context.lineTo(shape.points[0].x, shape.points[0].y);

        this.context.strokeStyle = '#000000';
        this.context.lineWidth = 2;
        this.context.stroke();
    }

    createShape (sides) {
        let angle = this.radiansAngle(this.startPosition, this.mousePosition);
        const radius = this.distanceBetween(this.startPosition, this.mousePosition);
        
        const points = [];
        const edges = [];
    
        for (var i = 0; i < sides; i++) {
            const pointX = Math.round(this.startPosition.x + radius * Math.cos(angle));
            const pointY = Math.round(this.startPosition.y + radius * Math.sin(angle));
    
            if (0 !== i) {
                edges.push([
                    new Vector2(points[i-1].x, points[i-1].y),
                    new Vector2(pointX, pointY)
                ]);
            }
    
            angle += (Math.PI * 2) / sides;
    
            points.push(new Vector2(pointX, pointY));
        }
    
        return new Shape(points, edges);
    }

    undo () {
        this.shapes.pop();
    }

    export () {
        const out = [];
        for (const shape of this.shapes) {
            out.push(shape.points);
        }
        console.log(JSON.stringify(out));
    }
}

const ui = new Ui();

const load = () => {
    ui.init();
}

const resize = () => {
    ui.resize();
}