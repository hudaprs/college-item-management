<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Division;
use App\Level;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            if(Gate::allows('C-LEVEL')) return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    
    public function index()
    {
        return view('pages.managements.employees.index');
    }

    
    public function create()
    {
        $employee = new Employee;
        $divisions = Division::orderBy('name', 'asc')->get();
        $levels = Level::orderBy('name', 'asc')->get();
        return view('pages.managements.employees.create', compact('employee', 'divisions', 'levels'));
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required',
            'name' => 'required',
            'level' => 'required',
            'division' => 'required',
            'join_date' => 'required',
            'phone' => 'required',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'company' => 'required',
            'address' => 'required'
        ]);

        $employee = new Employee;
        $employee->nip = $request->input('nip');
        $employee->name = ucwords($request->input('name'));
        $employee->level_id = $request->input('level');
        $employee->division_id = $request->input('division');
        $employee->join_date = $request->input('join_date');
        $employee->phone = $request->input('phone');
        $employee->place_of_birth = $request->input('place_of_birth');
        $employee->date_of_birth = $request->input('date_of_birth');
        $employee->email = strtolower($request->input('email'));
        $employee->gender = ucwords($request->input('gender'));
        $employee->company = $request->input('company');
        $employee->address = $request->input('address');
        $employee->save();

        if($employee) {
            return response()->json([
                'message' => 'Employee Has Been Created',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Create Employee'
            ], 400);
        }
    }
    
    public function show($id)
    {
        $employee = Employee::with('employee_has_level', 'employee_has_division')->findOrFail($id);
        return view('pages.managements.employees.show', compact('employee'));
    }

    
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $divisions = Division::orderBy('name', 'asc')->get();
        $levels = Level::orderBy('name', 'asc')->get();
        return view('pages.managements.employees.create', compact('employee', 'divisions', 'levels'));
    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nip' => 'required',
            'name' => 'required',
            'level' => 'required',
            'division' => 'required',
            'join_date' => 'required',
            'phone' => 'required',
            'place_of_birth' => 'required',
            'date_of_birth' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'company' => 'required',
            'address' => 'required'
        ]);

        $employee = Employee::findOrFail($id);
        $employee->nip = $request->input('nip');
        $employee->name = ucfirst($request->input('name'));
        $employee->level_id = $request->input('level');
        $employee->division_id = $request->input('division');
        $employee->join_date = $request->input('join_date');
        $employee->phone = $request->input('phone');
        $employee->place_of_birth = $request->input('place_of_birth');
        $employee->date_of_birth = $request->input('date_of_birth');
        $employee->email = strtolower($request->input('email'));
        $employee->gender = $request->input('gender');
        $employee->company = $request->input('company');
        $employee->address = $request->input('address');
        $employee->save();

        if($employee) {
            return response()->json([
                'message' => 'Employee Has Been Updated',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed To Update Employee'
            ], 400);
        }
    }

    // Destroy Employee
    public function destroy($id)
    {
         $employee = Employee::findOrFail($id);
         $employee->delete();

         if($employee) {
            return response()->json([
                'message' => 'Employee Has Been Removed'
            ], 200);
         } else {
            return response()->json([
                'message' => 'Failed To Remove Employee'
            ], 400);
         }
    }
}
