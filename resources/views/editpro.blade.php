@extends('layouts.app')

@section('content')
<form method="POST" action="{{route("update")}}">
    @csrf
    @method("post")
    @if (Session::has("success"))
        <div class="alert alert-success">
            {{Session::get("success")}}
        </div>
    @endif
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Email address</label>
      <input value='{{$user->email}}' name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword12" class="form-label">username</label>
        <input  name="name" type="text"value='{{$user->name}}' class="form-control" id="exampleInputPassword12">
      </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
@endsection
