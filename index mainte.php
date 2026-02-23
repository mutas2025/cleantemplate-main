<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/css/font.css"><!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="dist/img/splogo.png">
    <title>MAINTENANCE</title>


    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            background: white;
            color: black;
            font-family: 'Inconsolata', monospace;
            font-size: 100%;
        }

        .maintenance {
            text-transform: uppercase;
            margin-bottom: 1rem;
            font-size: 3rem;
        }

        .container {
            display: table;
            margin: 0 auto;
            max-width: 1024px;
            width: 100%;
            height: 100%;
            align-content: center;
            position: relative;
            box-sizing: border-box;

            .what-is-up {
                position: absolute;
                width: 100%;
                top: 50%;
                transform: translateY(-50%);
                display: block;
                vertical-align: middle;
                text-align: center;
                box-sizing: border-box;

                .spinny-cogs {
                    display: block;
                    margin-bottom: 2rem;

                    .fa {
                        &:nth-of-type(1) {
                            @extend .fa-spin-one;
                        }

                        &:nth-of-type(3) {
                            @extend .fa-spin-two;
                        }
                    }
                }
            }
        }

        @-webkit-keyframes fa-spin-one {
            0% {
                -webkit-transform: translateY(-2rem) rotate(0deg);
                transform: translateY(-2rem) rotate(0deg);
            }

            100% {
                -webkit-transform: translateY(-2rem) rotate(-359deg);
                transform: translateY(-2rem) rotate(-359deg);
            }
        }

        @keyframes fa-spin-one {
            0% {
                -webkit-transform: translateY(-2rem) rotate(0deg);
                transform: translateY(-2rem) rotate(0deg);
            }

            100% {
                -webkit-transform: translateY(-2rem) rotate(-359deg);
                transform: translateY(-2rem) rotate(-359deg);
            }
        }

        .fa-spin-one {
            -webkit-animation: fa-spin-one 1s infinite linear;
            animation: fa-spin-one 1s infinite linear;
        }

        @-webkit-keyframes fa-spin-two {
            0% {
                -webkit-transform: translateY(-.5rem) translateY(1rem) rotate(0deg);
                transform: translateY(-.5rem) translateY(1rem) rotate(0deg);
            }

            100% {
                -webkit-transform: translateY(-.5rem) translateY(1rem) rotate(-359deg);
                transform: translateY(-.5rem) translateY(1rem) rotate(-359deg);
            }
        }

        @keyframes fa-spin-two {
            0% {
                -webkit-transform: translateY(-.5rem) translateY(1rem) rotate(0deg);
                transform: translateY(-.5rem) translateY(1rem) rotate(0deg);
            }

            100% {
                -webkit-transform: translateY(-.5rem) translateY(1rem) rotate(-359deg);
                transform: translateY(-.5rem) translateY(1rem) rotate(-359deg);
            }
        }

        .fa-spin-two {
            -webkit-animation: fa-spin-two 2s infinite linear;
            animation: fa-spin-two 2s infinite linear;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="what-is-up">

            <div class="spinny-cogs">
                <i class="fa fa-cog" aria-hidden="true"></i>
                <i class="fa fa-5x fa-cog fa-spin" aria-hidden="true"></i>
                <i class="fa fa-3x fa-cog" aria-hidden="true"></i>
            </div>
            <h1 class="maintenance">Under Maintenance</h1>
            <h2>Our developers are hard at work updating your system. Please wait while we do this. We have also made the spinning cogs to distract you.</h2>
        </div>
        <img class="profile-user-img img-fluid border-0 " style="mix-blend-mode: multiply;width: 181px;position: absolute;right: 418px;height: 160px;bottom: 148px;" src="dist/img/itcsologo.png" alt="User profile picture">
    </div>



</body>

</html>