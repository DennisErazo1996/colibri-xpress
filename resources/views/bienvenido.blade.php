<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{asset('img/favicon-colibri.png')}}">
    <title>Bienvenido</title>

    <link  href="assets/css/custom.css" rel="stylesheet" />
</head>
<body id="welcome">
   
    <img id="logo-colibri-xpress" src="{{asset('img/logo-colibri-xpress-blanco.png')}}" alt="">
    
    <div class="card">
        <h1>¡Bienvenido/a, {{$data['firstname']}}!</h1>
        {{-- <h1>¡Bienvenido/a, Dennis Armando!</h1> --}}
    <p>Gracias por crear tu casillero, hemos enviado un correo a <strong>{{$data['email']}}</strong><br>
    {{-- <p>Gracias por crear tu casillero, hemos enviado un correo a <strong>derazo@unag.edu.hn</strong><br> --}}
        con la información de nuestra dirección en Miami, Florida. </p>
        <p>Para cualquier consulta puedes escribirnos a nuestro Whatsapp: <a href="https://wa.me/+50492136696">+50492136696</a></p>
        <a class="btn btn-default w-30" href="{{route('login')}}"><i class="fi fi-ss-undo"></i> Regresar</a>
    </div>

    <footer>&copy; Todos los derechos reservados. Colibrí Xpress.</footer>
</body>
</html>