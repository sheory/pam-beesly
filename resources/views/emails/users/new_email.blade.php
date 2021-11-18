@extends('emails.layouts.mail')

@section('template_title')
    Confirmação de cadastro
@endsection

@section('title')
    Seja bem vindo {{$name}}!
@endsection

@section('text')
    Geramos a senha abaixo pra você, porém ela <br>vai servir somente  para o primeiro acesso.<br>
    Logo após realizar o login, você poderá colocar a senha que preferir.
@endsection

@section('token')
    {{$password}}
@endsection

@if($user->role != \App\Enums\UserRoles::COMPANY)
@section('showStoreButtons')@endsection
@else

@section('button_url')
    {{config('appconfig.app.url_site')}}
@endsection
@section('button_text')
    Entrar
@endsection

@endif

