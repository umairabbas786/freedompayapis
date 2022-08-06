<?php
    namespace MineSQL;

    class CoinPayments
    {

        private $secretKey, $merchantId, $formFields;
        // for all available POST fields navigate to: https://www.coinpayments.net/merchant-tools-simple
        public $requiredFields = ['merchant', 'item_name', 'currency', 'amountf', 'cmd', 'reset'];

        const ENDPOINT = 'https://www.coinpayments.net/index.php';

        //Chainable setters
        public function setMerchantId($id)
        {
            $this->merchantId = $id;
            return $this;
        }

        //Chainable setters
        public function setSecretKey($secret)
        {
            $this->secretKey = $secret;
            return $this;
        }

        public function setFormElement($name, $value)
        {
            $this->formFields[$name] = $value;
        }

        public function createForm()
        {
            echo '<div id="container">
                <img src="coinpayments.jpg">
            </div>';
            //Automatically set merchant field
            $this->setFormElement('merchant', $this->merchantId);
            $this->setFormElement('cmd', '_pay');
            $this->setFormElement('reset', 1);

            $formFields = $this->formFields;

            // This checks and ensures that the required fields (listed above in the class properties)
            // is in the payment configuration with CoinPayments::setFormElement()
            foreach($this->requiredFields as $field)
            {
                // Checks if there is an entry into the given form fields
                if(!array_key_exists($field, $formFields))
                {
                    //there is not an entry for a required field. Throw an error.
                    throw new Exception($field.' value is required for form creation.');
                }
            }

            // Start the creation of a new form
            $form = '<form action="'.self::ENDPOINT.'" method="post" id="coinpayments-form" class="d-flex justify-content-center">';

            //Cycle through all the fields given and create hidden post fields.
            foreach($formFields as $name => $value) {
                $form .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
            }

            //create a generic button to forward the user to the coinpayments gateway
            $form.='<button type="submit"  name="coinPaymentsBtn" class="btn btn-primary btn-lg CoinPayments" style=" margin-top:80%;"><i class="fa fa-circle-o-notch fa-spin" id="loader"></i> Proceed to coinpayment</button></form>';

            return $form;

        }

        //dependancy injection for $_POST & $_SERVER
        public function listen(array $post, array $server)
        {
            $merchantId = $this->merchantId;
            $secretKey = $this->secretKey;

            if(!isset($post['ipn_mode']) || !isset($post['merchant']))
            {
                $this->callbackError(400, 'Missing POST data from callback.');
                return false;

            }


            if($post['ipn_mode'] == 'httpauth')
            {
                //Verify that the http authentication checks out with the users supplied information
                if($server['PHP_AUTH_USER']!=$merchantId || $server['PHP_AUTH_PW']!=$secretKey)
                {
                    $this->callbackError(401, 'Unauthorized HTTP Request.');

                    return false;
                }

            }
            elseif($post['ipn_mode'] == 'hmac')
            {
                // Create the HMAC hash to compare to the recieved one, using the secret key.
                $hmac = hash_hmac("sha512", file_get_contents('php://input'), $secretKey);

                if($hmac != $server['HTTP_HMAC']) {

                     $this->callbackError(401, 'Unauthorized HMAC Request.');

                    return false;
                }

            }
            else
            {

                $this->callbackError(402, 'Unknown or Malformed Request.');

                return false;
            }

            // Passed initial security test - now check the status
            $status = intval($post['status']);
            $statusText = $post['status_text'];

            if($post['merchant']!=$merchantId)
            {
                $this->callbackError(403, 'Mismatching merchant ID.');

                return false;
            }

            if($status < 0 )
            {
                // There has been an error with the payment - throw an error
                $this->callbackError($status, $statusText);
                return false;
            }
            elseif($status == 0)
            {
                // the payment is pending
                return false;
            }
            elseif($status>=100 || $status == 2)
            {
                // the payment has been successful
                return true;
            }

        }

        private function callbackError(int $errorCode, string $errorMessage)
        {
            throw new Exception('#'.$errorCode.' There was a problem establishing integrity with the request: '.$errorMessage);
        }


    }
    ?>
    <html>

    <head >
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                crossorigin="anonymous"></script>
    <style>
    .tablink {
        background-color: #2b77f2;
        color: white;
        position: static;
        border: groove;
        width=100px
        padding: 30px 30px;
        font-size: 17px;
        left: 50%;
        margin-left: -240px;
        margin-right: -240px;
        margin-top: -50x;
             }
    .topleft {
        position: absolute;
        top: 16px;
        left: 3px;
        font-size: 18px;
             }
    body{
        height:100%;
    }
    /* Sass Variables */
    /* Basic */
    body {
        background: #f1f1f1;
    }
    /* PayPal Logo */
    #container {
        position: absolute;
        width: 250px;
        text-align: center;
        padding: 20px;
        left: 45%;
        top: 35%;
        margin-left: -100px;
        margin-top: -100px;
        box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1), 0px 1px 5px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background: #fafafa;
        transition: all 0.3s ease-in-out;
        cursor: pointer;
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
    </head>

    <body>

    <script>
        $(document).ready(function(){
            $('#coinpayments-form').submit(function() {
                $('#loader').css('visibility', 'visible');
                $('#loader').show();
            });
        })
    </script>

    </body>

    </html>
