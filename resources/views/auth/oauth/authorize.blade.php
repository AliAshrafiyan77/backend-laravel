@extends('auth.layout.app')
@section('title' , 'ورود به حصاب کاربری')
@section('content')
    <form action="{{route('passport.authorizations.approve')}}" method="POST">
        @csrf
        <button class="bg-blue-600">approve</button>
    </form>
    <form action="{{route('passport.authorizations.deny')}}" method="POST">
        @csrf
        <button class="bg-amber-400">deny</button>
    </form>
@endsection
