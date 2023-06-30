<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">





    <meta charset="utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        html,
        body {
            overflow: hidden;
            height: 100%;

            font-size: 15px;
            margin: 0;
            padding: 0;
            min-height: 100%;
        }



        body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css'>

    <style>
        body {
            background: #fbf3ff;
        }


        .container {
            position: absolute;
            max-width: 800px;
            /* height: 500px; */
            margin: auto;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .myRightCtn {
            position: relative;
            background-image: linear-gradient(45deg, #f046ff, #9b00e8);
            border-radius: 25px;
            height: 100%;
            padding: 25px;
            color: rgb(192, 192, 192);
            font-size: 12px;


            display: flex;
            justify-content: center;
            align-items: center;



        }

        .myLeftCtn {
            position: relative;
            background: #fff;
            border-radius: 25px;
            height: 100%;
            padding: 25px;
            padding-left: 50px;
        }

        .myLeftCtn header {
            color: blueviolet;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .row {
            height: 100%;

        }

        .myCard {
            position: relative;
            background: #fff;
            height: 100%;
            border-radius: 25px;
            -webkit-box-shadow: 0px 10px 40px -10px rgba(0, 0, 0, 0.7);
            -moz-box-shadow: 0px 10px 40px -10px rgba(0, 0, 0, 0.7);
            box-shadow: 0px 10px 40px -10px rgba(0, 0, 0, 0.7);


        }


        .myRightCtn header {
            color: #fff;
            font-size: 44px;
        }

        .box {
            position: relative;
            margin: 20px;
            margin-bottom: 100px;

        }

        .myLeftCtn .myInput {
            width: 260px;
            border-radius: 25px;
            padding: 10px;
            padding-left: 50px;
            border: none;
            -webkit-box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
            -moz-box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
            box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
        }

        .myLeftCtn .myInput:focus {
            outline: none;
        }

        .myForm {
            position: relative;
            margin-top: 50px;

        }


        .myLeftCtn .butt {
            background: linear-gradient(45deg, #bb36fd, #9b00e8);
            color: #fff;
            width: 230px;
            border: none;
            border-radius: 25px;
            padding: 10px;
            -webkit-box-shadow: 0px 10px 41px -11px rgba(0, 0, 0, 0.7);
            -moz-box-shadow: 0px 10px 41px -11px rgba(0, 0, 0, 0.7);
            box-shadow: 0px 10px 41px -11px rgba(0, 0, 0, 0.7);
        }

        .myLeftCtn .butt:hover {
            background: linear-gradient(45deg, #c85bff, #b726ff);

        }

        .myLeftCtn .butt:focus {
            outline: none;
        }


        .myLeftCtn .fas {
            position: relative;
            color: #bb36fd;
            left: 36px;
        }

        .butt_out {
            background: transparent;
            color: #fff;
            display: flex;
            margin: auto;
            border: 2px solid#fff;
            border-radius: 25px;
            padding: 10px;
            -webkit-box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
            -moz-box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
            box-shadow: 0px 10px 49px -14px rgba(0, 0, 0, 0.7);
        }

        .butt_out:hover {
            border: 2px solid#eecbff;
        }


        .butt_out:focus {
            outline: none;
        }
    </style>



</head>

<body translate="no">
    <div class="container">
        <div class="myCard">
            <div class="row">
                <div class="col-md-6">
                    <div class="myRightCtn">
                        <div class="box">
                            <a href="/"><img src="{{env('APP_LOGO')}}" class="logo m-auto d-flex" style="height: 100px;"></a>
                           
                            <p></p>
                            <a href="/login"><input type="button" class="butt_out" value="Login Now" /></a>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="myLeftCtn">

                        <form class="myForm text-center needs-validation" action="/register" method="post" onsubmit="return validateForm(this)"
                            novalidate>
                            @csrf
                            <header>Create new account</header>
                            <div class="form-group">
                                <i class="fas fa-user"></i>
                                <input class="myInput" type="text" placeholder="Name" name="name"
                                    id="name" required>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <i class="fas fa-envelope"></i>
                                <input class="myInput" placeholder="Email ID" name="email" type="text" id="email" required>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form-group">
                                <i class="fas fa-user"></i>
                                <input class="myInput" type="tel" name="mobile" placeholder="Mobile number"
                                    id="username" required>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                           

                            <div class="form-group">
                                <i class="fas fa-lock"></i>
                                <input class="myInput" type="password" name="password" id="password" placeholder="Password" required>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <div class="form-group">
                                <label><input id="check_1" name="check_1" type="checkbox" required /><small> I read and
                                        agree to Terms & Conditions</small>
                                    <div class="invalid-feedback">You must check the box.</div>
                                </label>
                            </div>

                            <input type="submit" class="butt" value="CREATE ACCOUNT">

                        </form>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
    <div style="position: absolute; top:0; left:0;">
        <a href="/"><i style="font-size:xx-large;" class="fa fa-arrow-left"></i>
           <label style="font-size: xx-large;"> Back to Home</label>
        </a>
    </div>
    <script
        src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js">
    </script>



</body>

</html>
