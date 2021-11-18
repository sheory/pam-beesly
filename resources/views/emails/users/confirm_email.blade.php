@extends('emails.layouts.mail')

@section('template_title')
    Finalizar Cadastro
@endsection

@section('title')
    Bem vindo, {{$name}}!
@endsection

@section('text')
    Para finalizar o cadastro e realizar o seu primeiro acesso clique no botão abaixo:
@endsection

@section('button_url')
    /
@endsection

@section('button_text')
    Finalizar cadastro
@endsection
