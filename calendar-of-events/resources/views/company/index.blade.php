@extends('layouts.app')

@section('title', 'All events')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> {{ __('Companies list') }} </div>
                <div class="card-body">
                  @foreach($companies as $company)
                      <div>
                          {{ $company->name }} <a class="btn btn-link btn-sm" href="{{ route('companies.show', $company->id) }}">{{ __('Show more') }}</a><br>
                      </div>
                  @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
