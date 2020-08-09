@extends('layouts.app')

@section('title', 'Event info')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Event information') }}</div>

                <div class="card-body">
                    <b>Name:</b> {{ $project }} <br>
                    <b>Price:</b> {{ $price }} <br>
                    <b>Type:</b> {{ $type }} <br>
                    <b>Date:</b> {{ $date }} <br>
                    <b>Company:</b> {{ $company->name }} <br>
                    <b>Employee:</b> {{ $employee->name }} <br>
                    <b>Shift:</b> {{ $shift }} shift <br>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.destroy', $id ) }}" method="post">
                        @csrf
                        <a class="btn btn-success" href="{{ route('events.edit', $id )}}">{{ __('Edit') }}</a>
                        <input type="hidden" name="_method" value="Delete">
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
