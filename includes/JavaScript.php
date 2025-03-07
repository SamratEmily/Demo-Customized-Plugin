<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1 style="color: red;">This is a heading</h1>
    <button>Change Color</button>

    <script>
        const button = getElementBySelector('button');
        const heading = getElementBySelector('heading');

        button.addEventListener('click', ()=>{heading.style.color = 'blue'});

    </script>
</body>
</html>