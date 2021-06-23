<!DOCTYPE html>
<html lang="uk">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title") - Квест до дня ліцею</title>
    <style>
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <ul id="slide-out" class="sidenav sidenav-fixed">
        @yield("sidenav")
    </ul>
    <header id="header">
        <nav class="green">
            <div class="nav-wrapper">
                <div class="container">
                    <a href="{{route("index")}}" class="brand-logo">Квест до дня ліцею</a>
                    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <ul class="right">
                        <li><i class="material-icons left">alarm</i>@{{timeLeft}}</li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main id="app">
        @yield("content")
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            M.Sidenav.init(document.querySelectorAll('.sidenav'), []);
        });
        document.addEventListener('DOMContentLoaded', function() {
            M.Modal.init(document.querySelectorAll('.modal'), []);
            @if(!Auth::user())
            $('#auth').modal('open');
            @endif
        });
        var intervalTimer;

        var header = new Vue({
            el: '#header',
            data: {
                timeLeft: '--:--',
                endTime: '0'
            },
            methods: {
                setTime(seconds) {
                    clearInterval(intervalTimer);
                    this.timer(seconds);
                },
                timer(seconds) {
                    const now = Date.now();
                    const end = now + seconds * 1000;
                    this.displayTimeLeft(seconds);

                    this.selectedTime = seconds;
                    // this.initialTime = seconds;
                    this.displayEndTime(end);
                    this.countdown(end);
                },
                countdown(end) {
                    // this.initialTime = this.selectedTime;
                    intervalTimer = setInterval(() => {
                        const secondsLeft = Math.round((end - Date.now()) / 1000);
                        if(secondsLeft === 0) {
                            this.endTime = 0;
                            app.question = {"Message": "no questions for show"}
                        }
                        if(secondsLeft < 0) {
                            clearInterval(intervalTimer);
                            return;
                        }
                        this.displayTimeLeft(secondsLeft)
                    }, 1000);
                },
                displayTimeLeft(secondsLeft) {
                    const minutes = Math.floor((secondsLeft % 3600) / 60);
                    const seconds = secondsLeft % 60;

                    this.timeLeft = `${zeroPadded(minutes)}:${zeroPadded(seconds)}`;
                },
                displayEndTime(timestamp) {
                    const end = new Date(timestamp);
                    const hour = end.getHours();
                    const minutes = end.getMinutes();

                    this.endTime = `${hourConvert(hour)}:${zeroPadded(minutes)}`
                },
            }
        })
        function zeroPadded(num) {
            // 4 --> 04
            return num < 10 ? `0${num}` : num;
        }
        function hourConvert(hour) {
            // 15 --> 3
            return (hour % 12) || 12;
        }
        var app = new Vue({
            el: "#app",
            data:{
                question: [],
                answer: []
            },
            methods:{
                post_answer: function (){
                    answer = this.answer
                    $.ajax({
                        url: "{{route("post_answer")}}",
                        type: "post",
                        data: {answer: answer},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data){
                            $.get({
                            url: "{{route("show_question")}}",
                            success: function (data) {
                                app.question = data;
                                app.answer=[]
                            }
                        })
                        }
                    })

                }
            }
        })
        @if(Auth::user())
            var time_can = $.get({
                url: "{{route("time_can")}}",
                success: function (data){
                    header.setTime(data);
                }
            });
            var question = $.get({
                url: "{{route("show_question")}}",
                success: function (data){
                    app.question = data;
                }
            })
        @endif
    </script>
</body>
</html>

