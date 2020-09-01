<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ config('app.name') }} : Certificate of Completion</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'Lobster Two';
        }

        body, h1, h2, h3, h4, span, div {
            font-family: 'Lobster Two', cursive;
        }

        body {
            margin: 0px;
            color: #37231a;
        }

        .main-border {
            border: 20px solid darkred;
        }

        .row {
            position: relative;
        }

        .main-border .row h1 {
            font-size: 80px;
        }

        .banner {
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
        }

        .badge-img {
            right: 0;
            top: 0;
        }

        .logo {
            left: 40%;
            position: absolute;
            bottom: 22%;
            right: 0;
            display: inline-block;
            margin: auto;
            width: 200px;
            height: auto;
        }

        .wrapper {
            position: absolute;
            left: 0;
            top: 50%;
            right: 0;
            margin: auto;
        }

        .text-block {
            position: absolute;
            right: 0;
            margin: auto;
            top: 40%;
            left: 0;
            text-align: center;
        }

        .text-block p {
            line-height: 1;
            margin-top: 30px;
            font-size: 30px;
            opacity: 0.9;
        }

        .font-weight-bold {
            font-weight: bolder;
        }
    </style>
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid px-0" style="height: 500px">
    <div style="position: relative;text-align: center"
         class="row h-100 justify-content-center text-center position-relative m-0">
        <img class="logo" src="{{public_path('storage/logos/'.config('nav_logo'))}}">

        <div class="col-12 text-block align-self-center">
            <p class="text-center mb-0">This is to certify that <span class="font-weight-bold" style="font-family: DejaVu Sans;">{{$data['name']}}</span> successfully completed
            </p>
            <p style="word-wrap: break-word;white-space: nowrap"><span class="font-weight-bold" style="font-family: DejaVu Sans;">{{$data['course_name']}}</span>
            </p>
            <p  style="word-wrap: break-word;white-space: nowrap">on <span class="font-weight-bold" style="font-family: DejaVu Sans;">{{config('app.name')}}</span> online course on <span class="font-weight-bold">{{$data['date']}}</span></p>
        </div>
        <img width="100%" src="{{public_path('images/certificate.jpg')}}">
    </div>
</div>
</body>
</html>