<?php
    //libraries
    include "../../include/db.php";
    include "../../include/functions.php";

    $error = [];
    $error['status'] = 'error';

    //declaring variables;
    const USER_ID = 'user_id';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const PAYER_EMAIL = 'payer_email';
    const ITEM_NUMBER = 'item_number';
    const AMOUNT = 'amount';

    //validating inputs
    foreach ([USER_ID, FIRST_NAME, LAST_NAME, PAYER_EMAIL, ITEM_NUMBER, AMOUNT] as $item) {
        validateGetInputs($item);
    }

    $itemNumber = uniqid();
    ?>


    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Paypal Integration Test</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                crossorigin="anonymous"></script>
    </head>
    <style>
        body{
            height:100%;
        }
        .child{
            padding-top: 100%;
        }
        /* Sass Variables */
        /* Basic */
        body {
            background: #f1f1f1;
        }

        /* PayPal Logo */
        #container {
            position: absolute;
            width: 200px;
            height: 200px;
            left: 50%;
            top: 35%;
            margin-left: -100px;
            margin-top: -100px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1), 0px 1px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background: #fafafa;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        #container:after {
            content: "";
            position: absolute;
            width: 10px;
            height: 10px;
            left: 56px;
            bottom: 52.05px;
            background: #1934AB;
            transform: skew(-10deg);
        }
        #container:hover {
            box-shadow: 0px 15px 40px 5px rgba(0, 0, 0, 0.2), 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        #paypal {
            position: relative;
            display: block;
            width: 30px;
            height: 100px;
            left: 50%;
            top: 50%;
            margin-left: -55px;
            margin-top: -60px;
            background: rgba(0, 30, 162, 0.9);
            border-radius: 5px 0px 5px 5px;
            transform: skew(-10deg) scaleY(1.15);
            filter: drop-shadow(30px 20px 0px #009cde);
        }
        #paypal:before {
            content: "";
            position: absolute;
            width: 0px;
            height: 0px;
            top: 65px;
            left: 30px;
            border-top: 2px solid rgba(0, 30, 162, 0.9);
            border-left: 2px solid rgba(0, 30, 162, 0.9);
            border-right: 4px solid transparent;
            border-bottom: 4px solid transparent;
        }
        #paypal:after {
            content: "";
            position: absolute;
            width: 60px;
            height: 65px;
            left: 29.5px;
            background: inherit;
            border-radius: 0px 40px 55px 0px;
        }
        #loader{
            visibility: hidden;
        }
    </style>
    <body>

    <div class="container d-flex justify-content-center align-items-center parent">
        <div class="child">
            <div id="container">
                <span id="paypal"></span>
            </div>
            <form class="paypal" action="process.php" method="POST" id="paypal_form">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="UK" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="first_name" value="<?php echo $_GET[FIRST_NAME]?>" />
                <input type="hidden" name="last_name" value="<?php echo $_GET[LAST_NAME]?>" />
                <input type="hidden" name="payer_email" value="<?php echo $_GET[EMAIL]?>" />
                <input type="hidden" name="item_number" value="<?php echo $itemNumber;?>" / >
                <input type="hidden" name="item_name" value="<?php echo $itemNumber;?>" / >
                <input type="hidden" name="amount" value="<?php echo $_GET[AMOUNT]?>" / >
                <input type="hidden" name="currency_code" value="USD" / >
                <input type="hidden" name="user_id" value="<?php echo $_GET[USER_ID]?>" / >
                <button type="submit" name="submit" class="btn btn-primary btn-lg"><i class="fa fa-circle-o-notch fa-spin" id="loader"></i> Proceed To Payment</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#paypal_form').submit(function() {
                $('#loader').css('visibility', 'visible');
                $('#loader').show();
            });
        })
    </script>

    </body>
    </html>

