<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html style="overflow: hidden auto;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
        content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>テンプレート表示テスト</title>
    <style type="text/css">
        body {
            background: #ffffff;
            font-family: 'Segoe UI', 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', 'Arial', 'メイリオ', Meiryo, 'MS Sans Serif', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        img {
            border: none;
            vertical-align: bottom;
        }

        a {
            color: #0000ff;
            text-decoration: none;
        }
        table td {
            margin: 0;
            padding: 0;
            text-align: left;
            line-height: 1.3;
            text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .container{
            margin: 0 auto;
        }


        @media only screen and (min-width:768px) {
            table td {
                font-size: 12px;
            }
        }
    </style>
</head>

<body class="mail-base-body" style="background-color: #FFFFFF">
    {!!$data['mail_content_html']!!}
</body>

</html>
