@extends('layouts.app')

@section('title', 'All events')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Select Month') }}</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('events.index') }}">
                        @csrf
                        <div class="form-group row">


                            <div class="col-md-6">
                                <input type="month" name="month" class="form-control">
                            </div>
                            <div class="col-md-6  text-md-left">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Select') }}
                                </button>
                            </div>
                        </div>
                    </form>
                 </div>
              </div>
              <div class="card-deck">
                  @if(isset($companies))
                    @foreach($companies as $company)
                        <div class="card">
                            <div class="card-header"> {{ $company->name }} </div>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <th scope="col">Date</th>
                                    <th scope="col">1 shift</th>
                                    <th scope="col">2 shift</th>
                                    <th scope="col">3 shift</th>
                                </thead>

                                    <tbody>
                                    @foreach($dates as $date)
                                        <tr>
                                            <th scope="row"> {{ $date }} </th>
                                            <td>
                                                @foreach($events as $event)
                                                    @if($event->company_id == $company->id
                                                    and $event->date == $date
                                                    and $event->shift == 1)
                                                        {{ $event->name }}<hr>
                                                        {{ $event->price }}<hr>
                                                        {{ $event->type }}<hr>
                                                        {{ $event->employee }}<hr>
                                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-link btn-sm">Open</a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                              @foreach($events as $event)
                                                  @if($event->company_id == $company->id
                                                  and $event->date == $date
                                                  and $event->shift == 2)
                                                      {{ $event->name }}<hr>
                                                      {{ $event->price }}<hr>
                                                      {{ $event->type }}<hr>
                                                      {{ $event->employee }}<hr>
                                                      <a href="{{ route('events.show', $event->id) }}" class="btn btn-link btn-sm">Open</a>
                                                  @endif
                                              @endforeach
                                            </td>
                                            <td>
                                              @foreach($events as $event)
                                                  @if($event->company_id == $company->id
                                                  and $event->date == $date
                                                  and $event->shift == 3)
                                                      {{ $event->name }}<hr>
                                                      {{ $event->price }}<hr>
                                                      {{ $event->type }}<hr>
                                                      {{ $event->employee }}<hr>
                                                      <a href="{{ route('events.show', $event->id) }}" class="btn btn-link btn-sm">Open</a>
                                                  @endif
                                              @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
