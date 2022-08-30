<html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <base href="{{asset('')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <title>オプトアウト</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('adminLTE/images/favicon.ico')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/unsubemail.css')}}">
    <link rel="stylesheet" href="{{asset('adminLTE/plugins/fontawesome-free/css/all.min.css')}}">
</head>
<body id="optout">
    <div class="wrap">
        <p class="sample-title">テスト送信用のサンプル画面</p>
        <p>ここでは、テスト送信のためサンプル画面を表示しています。<br>実際に本番配信した際には、下記のような配信停止手続き用画面が表示されます。</p>
        <div class="sample-cap">
            <p class="sample-cap-title">実際のオプトアウト画面</p>
            <div class="content" style="background-color: #fff;">
                <form class="form-unsubemail" action="{!! route('post.unsubemail') !!}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="customer_id" value="{!! $data->customer_id !!}">
                    <h4 class="text-left title-content">※Content Data</h4>
                    <table>
                        <tr>
                            <td class="col-4">Full Name</td>
                            <td class="col-8">{!! $data->firstname !!} {!!$data->lastname !!}</td>
                        </tr>
                        <!-- <tr>
                            <td>Customer URL</td>
                            <td>{!! $data->email !!}</td>
                        </tr> -->
                        <tr>
                            <td>Customer TEL</td>
                            <td>{!! substr($data->telephone, 0, 2)!!}-{!!substr($data->telephone, 2, 4)!!}-{!!substr($data->telephone, 4, 6) !!}</td>
                        </tr>
                        <tr>
                            <td>Customer Email</td>
                            <td>{!! $data->email !!}</td>
                        </tr>
                    </table>
                    @if ($data->newsletter !== 0)
                        <button class="btn btn-blue" type="submit">Submit</button>
                    @endif
                </form>
               @if(Session::has('success'))
                    <p class="msg-success">※{{Session::get('success')}}</p>
                @endif
            </div>
            <p class="title-bottom">※実際の画面で「配信を停止する」を押したユーザーはメール受信可否フラグが「拒否」となります。</p>
        </div>
    </div>
</body>
</html>
