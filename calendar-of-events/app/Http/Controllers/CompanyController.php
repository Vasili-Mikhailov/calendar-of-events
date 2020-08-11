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

class CompanyController extends Controller
{
    use ValidatesRequests;
    public function index(Request $request)
    {
        $companies = Company::all();

        return view('company.index', ['companies' => $companies]);
    }

    public function create()
    {
        return view('company.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:companies'
        ]);

        $company = new Company;
        $company->name = $request->name;
        $company->save();
        //linking a user to a new company
        $user = $request->user();
        $user->company_id = $company->id;
        $user->save();

        return redirect()->route('companies.show', $company->id);
    }

    public function show($id)
    {
        $company = Company::find($id);
        $companyName = $company->name;
        $companyId = $id;

        return view('company.show', ['companyName' => $companyName, 'companyId' => $companyId]);
    }

    public function edit($id, Request $request)
    {
      $company = Company::find($id);
      $companyName = $company->name;
      $companyId = $id;

      return view('company.edit', ['companyName' => $companyName, 'companyId' => $companyId]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:companies'
        ]);

        $company = Company::find($id);
        $company->name = $request->name;
        $company->save();

        return redirect()->route('companies.show', $id);
    }

    public function destroy($id)
    {
      Company::destroy($id);

      return redirect()->route('companies.index');
    }
}
