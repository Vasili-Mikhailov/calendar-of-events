@extends('layouts.app')

@section('title', 'Create event')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Event') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('CheckFormFirstStep') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="project" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="project" value="{{ old('project') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="price" value="{{ old('price') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type of Work') }}</label>

                            <div class="col-md-6">
                                <input type="text" name="type" value="{{ old('type') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input type="date" name="date" class="form-control">
                            </div>
                        </div>
                        <div class="form-groupe row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

                            <div class="col-md-6">
                                <select name="company" class="selectpicker">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"> {{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
