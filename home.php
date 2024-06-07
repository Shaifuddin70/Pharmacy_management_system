<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pharmacy Management System</title>


    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;

        }

        body {
            background-image: url(image/home-bg.jpg);
            background-size: cover;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 20px;
            background-color: #03274d;
            box-shadow: 0 2px 4px;

        }

        .left .logo {
            height: 50px;
            border-radius: 50px;

        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            font-size: 30px;
            /* color: white; */

        }

        .btn {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: bisque;
            font-weight: 600;
            color: black;
            text-decoration: none;
        }

        .btn:hover {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #074d97;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .left {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 24px;
            margin-left: 200px;
            color: white;

        }

        .right {
            margin-right: 200px;
        }


        .left p {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="left">
                <img class="logo" src="image/logo.jpg" alt="logo">
                <p>Pharmacy Management System</p>
            </div>

            <div class="right">
                <a class="btn" href="index.php">Employee Login</a>
                <a class="btn" href="index.php">Admin Login</a>
            </div>

        </div>
        <div class="main">
            <h1>Welcome to Pharmacy Management System</h1>
            <p>*One platform to manage your all operations*</p>
        </div>
    </div>
</body>

</html>