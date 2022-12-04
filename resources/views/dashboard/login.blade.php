@extends('layout')
@section('content')
    <form action="{{route('loginauth')}}" method="post" class="logreg">
        @csrf
        <center>
            <div class="container">
                @if(Session::get('success'))
                <div class="alert alert-danger w-80">
                    {{ Session::get('success')}}
                </div>
                @endif
            @if(Session::get('fail'))
                <div class="alert alert-danger w-80">
                    {{ Session::get('fail')}}
                </div>
            @endif

            @if(Session::get('notAllowed'))
            <div class="alert alert-danger w-80">
                {{ Session::get('notAllowed')}}
            </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                 @endforeach
                </div>
                    @endif
                <img class="logocoi" src="assets/img/spaceturtles.png" alt="">
                <div class="deskcoi">
                    <p class="judul-login">Space Turtles formed after 13 year old Kevin Parker and Dominic<br>Simper from Perth, got together as a band. They stuck to<br>bedroom recordings until 2007, when Jay Watson joined them.</p>
                </div>
                <input type="hidden" name="tujuan" value="LOGIN">
                <p><input class="usernamecoi" type="text" name="username" placeholder="Username" required></p>
                <p><input class="passwordcoi" type="password" name="password" placeholder="Password" required></p>
                <p><button name="submit">Login</button></p>
                <p class="before-hmu">Don't have an account yet? <a href="register" class="hmu">hmu</a></p>
            </div>
        </center>
    </form>
</body>
</html>
@endsection