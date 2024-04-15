@extends('layouts.app')
@section('content')

<h1>Bentornato {{$user -> name}}!</h1>
<br><br>
<h2>I tuoi consulenti sono:</h2>
<ol>
    @foreach ($consultants as $consultant)
    <li>
        <h3>{{$consultant -> name}} {{$consultant -> lastname}}</h3>
    </li>
    @endforeach
    </ul>

    @endsection