@extends('layouts.app')

@section('title', 'Event info')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Company information') }}</div>

                <div class="card-body">
                    <b>Name:</b> {{ $companyName }}
                </div>
                <div class="card-body">
                    <form action="{{ route('companies.destroy', $companyId ) }}" method="post">
                        @csrf
                        <a class="btn btn-success" href="{{ route('companies.edit', $companyId )}}">{{ __('Edit') }}</a>
                        <input type="hidden" name="_method" value="Delete">
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
