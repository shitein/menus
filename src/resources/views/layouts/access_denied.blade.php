@extends('layouts.custom')

@section('content')
<!doctype html>
<title>Access Denied!</title>
<style>
    h1 {
        font-size: 50px;
    }

    body {
        font: 20px Helvetica, sans-serif;
        color: #333;
    }

    article {
        text-align: left;
        margin: 0 auto;
    }

    a {
        color: #dc8100;
        text-decoration: none;
    }

    a:hover {
        color: #333;
        text-decoration: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <article>
                <h1>Access Denied!</h1>
                <div>
                    <p>You don't have permission to access this page.</p>
                    <p><a href="{{url('/')}}">Return to the Home</a></p>
                </div>
            </article>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

@endsection