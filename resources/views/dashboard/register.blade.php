@extends('layout')
@section('content')
    <form method="POST" action="{{route('register.post')}}" class="logreg">
        @csrf
        <center>
            <div class="container2">
                <img class="logocoi" src="assets/img/spaceturtles.png" alt="">
                <p class="deskcoi2">Complete your information</p>
                <p><input type="text" name="name" placeholder="Nama Lengkap" required></p>
                <p><input type="text" name="username" placeholder="Username" required></p>
                <p><input type="email" name="email" placeholder="Email" required></p>
                <p><input type="password" name="password" placeholder="Password" required></p>
                <p class="readterms">Read the <b>Terms & Conditions</b></p>                
                <p><button name="submit">Submit</button></p>
                <p class="before-hmu"> You already have an account? <a href="/" class="hmu">hmu</a> now</p>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
            </div>
        </center>
    </form>
</body>
</html>
@endsection