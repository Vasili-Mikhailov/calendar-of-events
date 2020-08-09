@extends('layouts.app')

@section('title', 'Step two')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Step two') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('events.update', $eventId) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-groupe row">
                            <label for="employee" class="col-md-4 col-form-label text-md-right">{{ __('Employee') }}</label>

                            <div class="col-md-6">
                                <select name="employee" class="selectpicker">
                                    @foreach ($employees as $employee)
                                        @if($employee->id == $employeeId)
                                            <option value="{{ $employee->id }}" selected> {{ $employee->name }}</option>
                                        @else
                                            <option value="{{ $employee->id }}"> {{ $employee->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-groupe row">
                            <label for="shift" class="col-md-4 col-form-label text-md-right">{{ __('Shift') }}</label>

                            <div class="col-md-6">
                                <select name="shift" class="selectpicker">
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->name ?? $shift}}">{{ $shift->name ?? $shift}} shift</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit') }}
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
