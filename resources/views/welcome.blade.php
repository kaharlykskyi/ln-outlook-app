@extends('layout')

@section('content')
<div class="jumbotron">
  <h1>Outlook service</h1>
  <p>First you need to sing up with Outlook account. Please click Connect button below</p>
  <p>
    <a class="btn btn-lg btn-success" href="/signin" role="button" id="connect-button">Connect</a>
  </p>
</div>
@endsection