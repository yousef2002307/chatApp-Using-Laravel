<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta class="crsf" name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="{{ route('editpro') }}">
                                        edit profile
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('js/jq.js')}}"></script>
    <script>
    $(document).ready(function(){
       function scrolling(){
        let scroll = $(".form").offset().top;
        $("html,body").animate({
            scrollTop : scroll
        },300);
    }
    scrolling();
        
        let parentDiv = $(".names");
        let mainid = $(".sec1").attr('data-id')
        $(".hidden2").val($('.names').children().first().attr("data-id"))
        $("body").on('click','.names div.mb-3',function(){
            let userid = $(this).attr("data-id");
           setTimeout(() => {
            scrolling();
           }, 2000);
            $(".hidden2").val(userid);
           
            if(!$(this).hasClass("bg-primary")){
               $(this).addClass("bg-primary");
            }
            $(this).siblings("div").removeClass('bg-primary');
            ///////////////////here teh ajax call//////////////////////////////////////////////
            $.ajaxSetup({
                headers : {
                    'X-CSRF-TOKEN' :$("meta.crsf").attr("content")
                }
            });
            let div = document.querySelector(".divs.d-flex");
            div.textContent = '';
            console.log(div)
            //the above code sends a header with the request containg the crsf token
            $(".col-md-6.messages").css("display","none")
            $.ajax({
            method: "post",
            url: `{{url('/test2/${userid}')}}`, // that is blade function to go to specific route
            data : {id:$(this).attr("data-id")}, //the data you sed to index method
            success: function (response) {
                console.log(response) // the response from the method meaning after the return from the route and you can access it and shoe on the pae
                response.map(ele => {
                    if(ele.from_user == mainid){
                        div.innerHTML += `
                <div class="left" > <span data-id='${ele.id}'> ${ele.message}</span></div>
                `;
                }else{
                    div.innerHTML += `
                <div class="right"><span data-id='${ele.id}'> ${ele.message}</span></div>
                `;
                }
                })
                $(".col-md-6.messages").css("display","block")
            }
            });
            
        });
        $("body").on('submit','.form',function(e){
            if($(this).find("input[type='text']").val() === ''){
                return;
                e.preventDefault();
            }
           
        e.preventDefault();
updatingModel()

        });
     setInterval(() => {
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' :$("input[type='hidden']").attr("value")
            }
    });
//the above code sends a header with the request containg the crsf token
       $.ajax({
        method: "post",
        url: "{{url('addM')}}", // that is blade function to go to specific route
//or you can pass a parameter to url func like this
//  url: `{{url('/test2/${userid}')}}`

data : {case:2}, //the data you sed to index method
        success: function (response) {
            console.log(response)
           if(response.success === 'no'){
            console.log("no2");
            return
           }else{
            console.log( response.left)
            document.querySelector(".divs.d-flex").innerHTML = '';
           $res = response.result
           $res.map($item => {
            
            if ($item.from_user == mainid){
                document.querySelector(".divs.d-flex").innerHTML += `
                <div class="left" > <span data-id="${$item.id}"> ${$item.message}</span></div>
           `
                
           }else{
            document.querySelector(".divs.d-flex").innerHTML += `
            <div class="right" > <span data-id="${$item.id}"> ${$item.message}</span></div>
           `
               
           }
           });
           $("input[type='text']").val("")
           //////////////////////////////////////////////////////////////////////////////////////////////////////////
           let left = response.left;
           let names = document.querySelector(".names");
           names.innerHTML = '';
           left[0].map(($user,$key)=> {
            console.log($key)
 
       names.innerHTML += `
       
       <div class="mb-3 ${$key === 0 ? 'bg-primary' : ''}" data-id="${$user[1]}">
        <div class="name ">
         
         <h2>${$user[0]}</h2>
        </div>
        <span class="span1">${left[1][$key][1] ? "you" : $user[0]} : </span> <span class="span2">${left[1][$key][0]}</span>
     </div>
     `
           });
           }
        }
       });

     },500);
    
        function updatingModel(){
            $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' :$("input[type='hidden']").attr("value")
            }
    });
//the above code sends a header with the request containg the crsf token
       $.ajax({
        method: "post",
        url: "{{url('addM')}}", // that is blade function to go to specific route
//or you can pass a parameter to url func like this
//  url: `{{url('/test2/${userid}')}}`

        data : {text:$("input[type='text']").val(),id:parseInt($(".hidden2").val())}, //the data you sed to index method
        
        success: function (response) {
            console.log( response.left)
            document.querySelector(".divs.d-flex").innerHTML = '';
           $res = response.result
           $res.map($item => {
            
            if ($item.from_user == mainid){
                document.querySelector(".divs.d-flex").innerHTML += `
                <div class="left" > <span data-id="${$item.id}"> ${$item.message}</span></div>
           `
                
           }else{
            document.querySelector(".divs.d-flex").innerHTML += `
            <div class="right" > <span data-id="${$item.id}"> ${$item.message}</span></div>
           `
               
           }
           });
           $("input[type='text']").val("")
           //////////////////////////////////////////////////////////////////////////////////////////////////////////
           let left = response.left;
           let names = document.querySelector(".names");
           names.innerHTML = '';
           left[0].map(($user,$key)=> {
            console.log($key)
 
       names.innerHTML += `
       
       <div class="mb-3 ${$key === 0 ? 'bg-primary' : ''}" data-id="${$user[1]}">
        <div class="name ">
         
         <h2>${$user[0]}</h2>
        </div>
        <span class="span1">${left[1][$key][1] ? "you" : $user[0]} : </span> <span class="span2">${left[1][$key][0]}</span>
     </div>
     `
           });
      
          
        }
       });
        }
        
    });
        
        
        
        
        
        
     </script>   
        
</body>
</html>
