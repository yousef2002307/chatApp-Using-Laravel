@extends('layouts.app')

@section('content')
<section class="sec1" data-id="{{Auth::user()->id}}">
<div class="container">
   
    <div class="row">
        <div class="col-md-6 names" style="cursor: pointer">
       @foreach ($theselectedusers as   $key => $user)
     
       @if ($key === 0)
       <div class="mb-3 bg-primary" data-id="{{$user[1]}}">
        
        @else
        <div class="mb-3" data-id="{{$user[1]}}">
       @endif
       
        <div class="name ">
         
         <h2>{{$user[0]}}</h2>
        </div>
        <span class="span1">{{$messages[$key][1] ? "you" : $user[0]}} : </span> <span class="span2">{{$messages[$key][0]}}</span>
     </div>
       @endforeach
        </div>
        <div class="col-md-6 messages  ">
            <div class="divs d-flex">
            @foreach ($theRightMessages as $item)
                @if ($item->from_user === Auth::user()->id)
                <div class="left" > <span data-id="{{$item->id}}"> {{$item->message}}</span></div>
                @else
                <div class="right"><span data-id="{{$item->id}}"> {{$item->message}}</span></div>
                @endif
            @endforeach
        </div>
        <form class="form" method="post" action="{{route("addM")}}">
            @csrf
            @method("post")
            <input type="hidden" name="id" class="hidden2" value=""/>
            <input class="form-control" type="text" name="text" class="text" style="border: 2px solid #1f1717;"/>
           
            <input class="btn btn-danger mx-auto d-block text-center"  type="submit"/>
        </form>
       
        </div>
    </div>
</div>
</section>
@endsection
