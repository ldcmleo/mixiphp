<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .main {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .debug h1 {
            padding: 15px 12px;
            background-color: #a1262a;
            color: white;
        }
        .debug p {
            padding: 15px 12px;
            font-size: 18px;
        }
    </style>
    <title>Error 500</title>
</head>
<body>
    <?php if(DEBUG_MODE): ?>
        <div class="debug">
            <h1>ERROR - <span>DEBUG MODE ACTIVE</span></h1>
            <p><?php echo $data["error"]; ?></p>
        </div>
    <?php else: ?>
        <div class="main">
            <div>
                <h1>Error 500</h1>
                <p>Internal Server Error</p>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>