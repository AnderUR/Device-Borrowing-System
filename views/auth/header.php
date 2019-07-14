<!DOCTYPE html>

<html class="no-js" lang="en">

<head>
    <style>
        @media only screen and (max-width: 500px) {
            .buttonSection>button:not(:first-child) {
                margin-top: 20px;
            }

            .content>label:not(:first-child) {
                margin-top: 20px;
            }
        }

        body {
            margin: 0;
            height: 100%;
            font-size: 18px;
            background-image: url('/LibServices/deviceLoan_assets/img/bgAdmin.png');
            background-color: lightblue;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        h1 {
            text-align: center;
            color: white;
            margin: 0;
        }

        .container {
            display: grid;
            grid-row-gap: 20px;
            position: relative;
        }

        .item1,
        .item2,
        .item3 {
            justify-self: center;
        }

        .hidden {
            display: none;
        }

        .content {
            margin: 20px;
            padding: 20px;
            border-style: double;
            border-width: 5px;
            border-color: white;
            text-align: center;
        }

        div.content p {
            color: #ffb124;
            font-size: 22px;
            text-align: center;
            cursor: pointer;
        }

        .center {
            text-align: center;
        }

        #titleTop {
            font-size: 15px;
            position: absolute;
            top: 5px;
            left: 120px;
        }

        #titleBottom {
            font-size: 17px;
            position: absolute;
            top: 40px;
            left: 170px;
        }

        .logoTitle {
            color: white;
        }

        label {
            display: inline-block;
            color: white;
        }

        input {
            display: block;
            font-size: inherit;
        }

        input:focus {
            background-color: #FAEBD7;
            outline: none;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .buttonSection {
            text-align: center;
            margin-top: 20px;
        }

        button[type="submit"],
        input[type="submit"] {
            background-color: #ffb124;
            border-radius: 4px;
            box-shadow: 0px 4px 10px #f4cb95;
            border: 1px solid black;
            padding: 5px;
            width: 110px;
            font-size: 18px;
            color: white;
        }
    </style>
</head>

<body>
    <div>
        <img style="width: 396px; height: 88px;" src="/LibServices/deviceLoan_assets/img/logotop.png" width="100px" />
        <div id="titleTop" class="logoTitle">
            <span>Device Borrow System</span>
        </div>
        <div id="titleBottom" class="logoTitle">
            <span>Login</span>
        </div>
    </div>

    <div style="color: orange; font-size: 25px; text-align: center;"><?= $message ?></div>
