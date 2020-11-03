<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'TuteBuddy LMS') }}</title>
    <style>
        body {
            background-color: lightblue;
            background-image: url("http://live.tutebuddy.com/images/background2.jpg");
            background-repeat: no-repeat;
            background-size: auto;
        }
        .logo {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -120px;
            margin-top: -40px;
        }
    </style>
</head>

<body onload="redirect()">
    <input type="hidden" id="live_url" value="{{ $join_room }}">
    <img src="http://live.tutebuddy.com/images/tutebuddy.png" width="240" alt="Online Learning Platform" class="logo" />
</body>

<script>
    function redirect() {
        var url = document.getElementById('live_url').value.replace(/\\\//g, "/");
        var result = url.substring(1, url.length - 1);
        console.log(result);
        location.href = result;
    }
</script>

</html>