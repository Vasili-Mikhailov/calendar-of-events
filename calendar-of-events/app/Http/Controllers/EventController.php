<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\User;
use App\Company;
use App\Shift;
use App\Event;

class EventController extends Controller
{
    use ValidatesRequests;
    public function index(Request $request)
    {
        if($request->has('month')){
            $month = $request->month;
            $month = explode('-', $month);
            $events = DB::table('events')
                ->join('companies', 'company_id', '=', 'companies.id')
                ->join('users', 'user_id', '=', 'users.id')
                ->select('events.*', 'users.name as employee')
                ->whereYear('date', $month[0])
                ->whereMonth('date', $month[1])
                ->get();
            $companies = Company::all();
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month[1], $month[0]);
            $dates = [];
            for($i = 1; $i <= $daysInMonth; $i++){
                if($i < 10){
                    $dates[] = $request->month . '-0' . $i;
                } else {
                    $dates[] = $request->month . '-' . $i;
                }
            }

            return view('event.index', ['companies' => $companies, 'events' => $events, 'dates' => $dates]);
        }

        return view('event.index');
    }

    public function create()
    {
        $companies = Company::all();

        return view('event.create', ['companies' => $companies]);
    }

    public function createCheckFirstPart(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'type' => 'required',
            'date'=> 'required|date|after:today',
            'price' => 'required'
        ]);

        $request->session()->put('project', $request->project);
        $request->session()->put('type', $request->type);
        $request->session()->put('date', $request->date);
        $request->session()->put('company', $request->company);
        $request->session()->put('price', $request->price);

        return redirect()->route('events.GetSecondPart');
    }

    public function createGetSecondPart(Request $request)
    {

        $employees = Company::find($request->session()->get('company'))->users()->get();
        $shifts = Company::find($request->session()->get('company'))
            ->shifts()
            ->where('date', $request->session()->get('date'))
            ->get();
        $baseShifts = collect([1, 2, 3]);
        $shifts = $shifts->pluck('name');
        $shifts = $baseShifts->diff($shifts);
        if($request->session()->has('shift')){
            $oldShift = $request->session()->get('shift');
            if(!$shifts->contains($oldShift)){
                $shifts->push($oldShift);
                $shifts = $shifts->sort();
                $shifts->values()->all();
                $request->session()->forget('shift');
            }
        }
        if($request->session()->has('action')){
            $eventId = $request->session()->get('id');
            $employeeId = $request->session()->get('employeeId');
            return view('event.editStepTwo', [
                'employees' => $employees,
                'shifts' => $shifts,
                'employeeId' => $employeeId,
                'eventId' => $eventId
            ]);
        }
        return view('event.getSecondPart', [
            'employees' => $employees,
            'shifts' => $shifts,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee' => 'required',
            'shift' => 'required'
        ]);

        $event = new Event;
        $event->name = $request->session()->get('project');
        $event->price = $request->session()->get('price');
        $event->type = $request->session()->get('type');
        $event->company_id = $request->session()->get('company');
        $event->user_id = $request->employee;
        $event->date = $request->session()->get('date');
        $event->shift = $request->shift;


        $shift = new Shift;
        $shift->name = $request->shift;
        $shift->date = $request->session()->get('date');
        $shift->company_id = $request->session()->get('company');
        $shift->event_id = $event->id;

        $event->save();
        $shift->save();

        $request->session()->flush();

        return redirect()->route('events.index');
    }

    public function show($id)
    {
        $event = Event::find($id);
        $project = $event->name;
        $price = $event->price;
        $type = $event->type;
        $company = Company::find($event->company_id);
        $employee = User::find($event->user_id);
        $date = $event->date;
        $shift = $event->shift;

        return view('event.show', [
            'project' => $project,
            'price' => $price,
            'type' => $type,
            'company' => $company,
            'employee' => $employee,
            'date' => $date,
            'shift' => $shift,
            'id' => $id
        ]);
    }

    public function edit($id, Request $request)
    {
        $companies = Company::all();
        $event = Event::find($id);
        $project = $event->name;
        $price = $event->price;
        $type = $event->type;
        $company = Company::find($event->company_id);
        $companyId = $company->id;
        $date = $event->date;

        $request->session()->put('id', $id);
        $request->session()->put('shift', $event->shift);
        $request->session()->put('enployeeId', $event->user_id);
        $request->session()->put('action', 'edit');

        return view('event.edit', [
          'project' => $project,
          'price' => $price,
          'type' => $type,
          'companyId' => $companyId,
          'date' => $date,
          'companies' => $companies,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'employee' => 'required',
            'shift' => 'required'
        ]);

        $event = Event::find($id);
        $event->name = $request->session()->get('project');
        $event->price = $request->session()->get('price');
        $event->type = $request->session()->get('type');
        $event->company_id = $request->session()->get('company');
        $event->user_id = $request->employee;
        $event->date = $request->session()->get('date');
        $event->shift = $request->shift;


        $shift = Shift::where('event_id', $id)->get();
        $shift->name = $request->shift;
        $shift->date = $request->session()->get('date');
        $shift->company_id = $request->session()->get('company');
        $shift->event_id = $event->id;

        $shift->save();
        $event->save();

        $request->session()->flush();
    }

    public function destroy($id)
    {
        Event::destroy($id);
        $shiftDestroy = Shift::where('event_id', $id)->delete();

        return redirect()->route('events.index');
    }
}
