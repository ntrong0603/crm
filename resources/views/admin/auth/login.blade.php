@extends('admin.layout')
@section('titlePage', 'ログイン')
@section('main')
<div style="width: 100vw; height: 100vh; position: fixed; top: 0; left: 0; background-color: #1d7584; color: #ffffff;">
    <div id="login">
        <div id="title">
            <img src="{{asset('images/img_login.png')}}" alt="" srcset="">
        </div>
        <div id="form-login">
            @if(session('error_login'))
            <div class="alert alert-danger" role="alert">
                {{session('error_login')}}
            </div>
            @endif
            <form method="post" class="auth">
                @csrf
                <div class="items item-input form-group
                @if ($errors->get('user_name'))
                    has-error
                @endif">
                    <label for="user_name">ログインID：</label>
                    <input type="text" name="user_name" id="user_name" placeholder="User Name"
                        value="{{ old('user_name') }}" autofocus>
                    @if ($errors->get('user_name'))
                    @foreach ($errors->get('user_name') as $err)
                    <span class="help-block"><i class="fas fa-exclamation-triangle"></i> {{$err}}</span>
                    @endforeach
                    @endif
                </div>
                <div class="items item-input form-group
                @if ($errors->get('password'))
                    has-error
                @endif">
                    <label for="password">パスワード：</label>
                    <input type="password" name="password" id="password" placeholder="Password"
                        autocomplete="current-password">
                    @if ($errors->get('password'))
                    @foreach ($errors->get('password') as $err)
                    <span class="help-block"><i class="fas fa-exclamation-triangle"></i> {{$err}}</span>
                    @endforeach
                    @endif
                </div>
                <div class="items">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                    <div class="button-sb">
                        <button type="submit" style="color: #ffffff">ログイン</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('libraryjs')
<script src="{{asset('js/login.js')}}"></script>
@endsection
