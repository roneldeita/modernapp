<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Thanks for creating an account. Please follow the link to verify your email address<br/>
            {{ url('register/verify/' . $confirmation_code) }}

        </div>

    </body>
</html>