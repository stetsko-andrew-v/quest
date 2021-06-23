@extends("layouts.main")
@section("title", "Головна")
@section("sidenav")
    <li class="active"><a href="{{route('index')}}" class="waves-effect">Головна</a></li>
    <li><a href="{{route('about')}}" class="waves-effect">Про квест</a></li>
@endsection
@section("content")
    <div class="card blue-grey lighten-5">
        <div class="card-content dark-text">
            <center><span class="card-title" v-if="question.Message=='question'">@if(Auth::user())Питання <span>@{{ question.question.number }}</span> @else Увійдіть @endif</span>
                <span class="card-title" v-if="question.Message=='no questions for show'">Вітаємо! Ви завершили квест!</center>
            @if(Auth::user()) <p v-if="question.Message=='question'" v-html="question.question.text"></p> @else <p>Шановний учасник, увійдіть в аккаунт <a href="#auth" class="modal-trigger">Увійти</a></p> @endif
        </div>
    </div>
    @if(Auth::user())
    <div class="container">
        <p v-if="question.question.type==1" v-for="variant in question.variants">
            <label>
                <input class="with-gap" name="question" type="radio" v-model="answer" v-bind:value="variant.id" />
                <span v-html="variant.variant_name"></span>
            </label>
        </p>
        <p v-if="question.question.type==2" v-for="variant in question.variants">
            <label>
                <input type="checkbox" class="filled-in" v-model="answer" v-bind:value="variant.id" name="@{{$variant->id}}" />
                <span v-html="variant.variant_name"></span>
            </label>
        </p>
    </div>
    <a v-if="question.Message=='question'" class="waves-effect waves-light btn-large" :disabled="answer.length == 0" v-on:click="post_answer()" style="width:100%;">Продовжити</a>
    @else
    <div id="auth" class="modal">
        <form method="POST" action="{{ route('reg_test') }}">
            <div class="modal-content">
                <h4>Зареєструйтеся</h4>
                @csrf
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate" required>
                        <label for="name">Прізвище ім'я</label>
                    </div>
                </div>
                ПОПЕРЕДЖЕННЯ! На квест буде дано 20 хв. Опісля натиску розпочати, час почнеться
            </div>
            <div class="modal-footer">
                <button type="submit" class="waves-effect waves-green btn-flat">Розпочати</button>
                <a class="modal-close waves-effect waves-red btn-flat">Скасувати</a>
            </div>
        </form>
        </div>
    <script>
        $(document).ready(function(){
            $('.tabs').tabs();
        });
    </script>
    @endif
@endsection
