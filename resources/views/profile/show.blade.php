@extends('layouts.master')

@section('title')
    <title>Profile</title>
@endsection

@section('content')
    @if(Auth::user()->isAdmin())
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/profile">Profiles</a></li>
            <li><a href="/profile/create">Create</a></li>
        </ol>
    @else
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/profile/create">Create</a></li>
        </ol>
    @endif

    <h1>{{ $profile->fullName() }}</h1>
    <hr>
    <div class="panel panel-default">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                @if(Auth::user()->adminOrCurrentUserOwns($profile))
                <th>
                    Edit
                </th>
                @endif
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td> {{ $profile->id }} </td>
                <td><a href="/profile/{{ $profile->id }}/edit"> {{ $profile->fullName() }} </a></td>
                <td>{{ $profile->showGender($profile->gender) }}</td>
                <td>{{ $profile->birthdate->format('m-d-Y') }}</td>
                @if(Auth::user()->adminOrCurrentUserOwns($profile))
                <td>
                    <a href="/profile/{{ $profile->id }}/edit">
                        <button type="button" class="btn btn-default">Edit</button>
                    </a>
                </td>
                @endif
                <td>
                    <div class="form-group">
                        <form action="{{ url('/profile/'.$profile->id) }}" class="form" role="form" method="POST">
                            <input type="hidden" name="_method" value="delete">
                                {{ csrf_field() }}
                            <input type="submit" class="btn btn-danger" value="Delete" onclick="return ConfirmDelete();">
                        </form>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function ConfirmDelete(){
            var x = confirm("Are you sure you want to delete?");
            return x;
        }
    </script>
@endsection