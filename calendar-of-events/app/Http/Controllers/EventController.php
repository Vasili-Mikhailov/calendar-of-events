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

        //checks the completion of the month field
        if($request->has('month')){
            $month = $request->month;
            $month = explode('-', $month);
            //events of the month with users and companies
            $events = DB::table('events')
                ->join('companies', 'company_id', '=', 'companies.id')
                ->join('users', 'user_id', '=', 'users.id')
                ->select('events.*', 'users.name as employee')
                ->whereYear('date', $month[0])
                ->whereMonth('date', $month[1])
                ->get();
            $companies = Company::all();
            //creates an array of days of the month
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

    //function to check the first part of the event creation and editing form
    public function checkFormFirstStep(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'type' => 'required',
            'date'=> 'required|date|after:today',
            'price' => 'required'
        ]);

        //adds all data of the first form to the session
        $request->session()->put('project', $request->project);
        $request->session()->put('type', $request->type);
        $request->session()->put('date', $request->date);
        $request->session()->put('company', $request->company);
        $request->session()->put('price', $request->price);

        return redirect()->route('GetFormSecondStep');
    }

    //function to create the second part of the event creation and editing form
    public function getFormSecondStep(Request $request)
    {
        //using the data of the first form, we get the data to create the second form
        $employees = Company::find($request->session()->get('company'))->users()->get();
        $shifts = Company::find($request->session()->get('company'))
            ->shifts()
            ->where('date', $request->session()->get('date'))
            ->get();
        //get a collection of unoccupied shifts
        $baseShifts = collect([1, 2, 3]);
        $shifts = $shifts->pluck('name');
        $shifts = $baseShifts->diff($shifts);
        //check to add the current shift for the edit form
        if($request->session()->has('shift')){
            $oldShift = $request->session()->get('shift');
            if(!$shifts->contains($oldShift)){
                $shifts->push($oldShift);
                $shifts = $shifts->sort();
                $shifts->values()->all();
                $request->session()->forget('shift');
            }
        }
        //if this is an edit form passes the data of the event being edited
        if($request->session()->has('action')){
            $eventId = $request->session()->get('id');
            $employeeId = $request->session()->get('employeeId');
            return view('event.editFormSecondStep', [
                'employees' => $employees,
                'shifts' => $shifts,
                'employeeId' => $employeeId,
                'eventId' => $eventId
            ]);
        }

        return view('event.createFormSecondStep', [
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
        //creating a new event
        $event = new Event;
        $event->name = $request->session()->get('project');
        $event->price = $request->session()->get('price');
        $event->type = $request->session()->get('type');
        $event->company_id = $request->session()->get('company');
        $event->user_id = $request->employee;
        $event->date = $request->session()->get('date');
        $event->shift = $request->shift;
        $event->save();

        //creating a new shift
        $shift = new Shift;
        $shift->name = $request->shift;
        $shift->date = $request->session()->get('date');
        $shift->company_id = $request->session()->get('company');
        $shift->event_id = $event->id;

        $shift->save();
        //deleting session data
        $request->session()
        ->forget(['project', 'price', 'type', 'company', 'date', 'employee', 'id',]);

        return redirect()->route('events.index');
    }

    public function show($id)
    {
        //getting event data
        $event = Event::find($id);
        $company = Company::find($event->company_id);
        $employee = User::find($event->user_id);

        return view('event.show', [
            'event' => $event,
            'company' => $company,
            'employee' => $employee,
            'id' => $id
        ]);
    }

    public function edit($id, Request $request)
    {
        $companies = Company::all();
        //getting event data
        $event = Event::find($id);
        $company = Company::find($event->company_id);
        $companyId = $company->id;

        $request->session()->put('id', $id);
        $request->session()->put('shift', $event->shift);
        $request->session()->put('enployeeId', $event->user_id);
        //data for the edit form
        $request->session()->put('action', 'edit');

        return view('event.edit', [
          'event' => $event,
          'companyId' => $companyId,
          'companies' => $companies,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'employee' => 'required',
            'shift' => 'required'
        ]);
        //event updates
        $event = Event::find($id);
        $event->name = $request->session()->get('project');
        $event->price = $request->session()->get('price');
        $event->type = $request->session()->get('type');
        $event->company_id = $request->session()->get('company');
        $event->user_id = $request->employee;
        $event->date = $request->session()->get('date');
        $event->shift = $request->shift;
        $event->save();

        //event updates
        $shift = Shift::where('event_id', $id)->first();
        $shift->name = $request->shift;
        $shift->date = $request->session()->get('date');
        $shift->company_id = $request->session()->get('company');
        $shift->event_id = $event->id;
        $shift->save();
        //deleting unnecessary session data
        $request->session()
        ->forget(['project', 'price', 'type', 'company', 'date', 'action', 'employee', 'id',]);

        return redirect()->route('events.show', $event->id);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        $shiftDestroy = Shift::where('event_id', $id)->delete();

        return redirect()->route('events.index');
    }
}
