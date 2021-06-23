@extends('layouts.main')
@section("title","Про квест")
@section("sidenav")
    <li><a href="{{route('index')}}" class="waves-effect">Головна</a></li>
    <li class="active"><a href="{{route('about')}}" class="waves-effect">Про квест</a></li>
@endsection
@section("content")
    <div class="container">
        Данний квест розроблений до чергового дня ліцею. Написаний з застосовунням:
        <ul class="collection">
            <li class="collection-item">PHP 7.4 - мова логіки квесту</li>
            <li class="collection-item">Laravel 8.x - фреймворк для логіки</li>
            <li class="collection-item">MaterializeCSS 1.0.0 - фреймворк дизайну сайту</li>
            <li class="collection-item">Vue.JS 2.0 - фреймворк front-end</li>
            <li class="collection-item">jQuery - бібліотека для JavaScript</li>
            <li class="collection-item">JetBrains PHPStorm - редактор коду</li>
        </ul>
        Розробники:
        <ul class="collection">
            <li class="collection-item">Стецко Андрій Всеволодович - учень 9-А класу, розробник платформи, дизайнер</li>
            <li class="collection-item">Косован Василь Михайлович - вчитель інформатики, редактор, модератор</li>
        </ul>
        <center><img src="https://lyceum1.cv.ua/wp-content/uploads/2021/04/logotyp.-liczej-№1-1.png" style="width: 90%; height: 90%"></center>
    </div>
@endsection
