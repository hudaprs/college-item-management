<?php

namespace App\Http\Controllers\Managements;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Level;
use App\Division;
use App\project;
use App\User;
use App\Client;
use App\Po;
use App\Sprint;
use App\Target;
use App\Price;
use DataTables;

class DataTablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Gate::allows('C-LEVEL'))
                return $next($request);
            abort(403, 'Unauthorized');
        });
    }

    public function pricesDataTables()
    {
        $prices = Price::orderBy('price', 'asc')->get();
        return DataTables::of($prices)
            ->addColumn('action', function ($prices) {
                return view('layouts.inc._action', [
                    'model' => $prices,
                    'url_show' => route('prices.show', $prices->id),
                    'url_edit' => route('prices.edit', $prices->id),
                    'url_destroy' => route('prices.destroy', $prices->id),
                ]);
            })
            ->editColumn('price', function ($prices) {
                return "Rp. " . number_format($prices->price) . "-,";
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function pricesTrashedDataTables()
    {
        $prices = Price::orderBy('price', 'asc')->onlyTrashed()->get();
        return DataTables::of($prices)
            ->addColumn('action', function ($prices) {
                return view('layouts.inc._action_trashed', [
                    'model' => $prices,
                    'url_restore' => route('prices.restore', $prices->id),
                    'url_delete_permanent' => route('prices.delete-permanent', $prices->id)
                ]);
            })
            ->editColumn('price', function ($prices) {
                return "Rp. " . number_format($prices->price) . "-,";
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function levelsDataTables()
    {
        $levels = Level::orderBy('name', 'asc')->get();
        return DataTables::of($levels)
            ->addColumn('action', function ($levels) {
                return view('layouts.inc._action_temp', [
                    'model' => $levels,
                    'url_show' => route('levels.show', $levels->id),
                    'url_edit' => route('levels.edit', $levels->id),
                    'url_destroy' => route('levels.destroy', $levels->id),
                ]);
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }


    public function employeesDataTables()
    {
        $employees = Employee::with('employee_has_level', 'employee_has_division')->orderBy('name', 'asc')->get();
        return DataTables::of($employees)
            ->addColumn('action', function ($employees) {
                return view('layouts.inc._action', [
                    'model' => $employees,
                    'url_show' => route('employees.show', $employees->id),
                    'url_edit' => route('employees.edit', $employees->id),
                    'url_destroy' => route('employees.destroy', $employees->id)
                ]);
            })
            ->addColumn('level', function ($employees) {
                return $employees->level_id !== null ? $employees->employee_has_level->name : 'No Level';
            })
            ->addColumn('division', function ($employees) {
                return $employees->division_id !== null ? $employees->employee_has_division->name : 'No Division';
            })
            ->addIndexColumn()->rawColumns(['action', 'level', 'division'])->make(true);
    }

    public function divisionsDataTables()
    {
        $divisions = Division::orderBy('name', 'asc')->get();
        return DataTables::of($divisions)
            ->addColumn('action', function ($divisions) {
                return view('layouts.inc._action_temp', [
                    'model' => $divisions,
                    'url_show' => route('divisions.show', $divisions->id),
                    'url_edit' => route('divisions.edit', $divisions->id),
                    'url_destroy' => route('divisions.destroy', $divisions->id),
                ]);
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function projectsDataTables()
    {
        $projects = project::orderBy('name', 'asc')->with('user_create_project', 'user_update_project', 'po_has_projects')->get();
        return DataTables::of($projects)
            ->addColumn('action', function ($projects) {
                return
                        // Done Button
                    ($projects->statusproject == null || $projects->statusproject == 0 ? '<a href="' . route('project.update-status', $projects->id) . '" class="btn btn-sm btn-success" id="btn-done"><em class="fa fa-check"></em></a> ' : null) .
                    // Show Button 
                    '<a href="' . route('projects.show', $projects->id) . '" class="btn btn-sm btn-primary" id="btn-show" title="Detail ' . $projects->name . '"><em class="fa fa-eye"></em></a> ' .
                    // Edit Button
                    '<a href="' . route('projects.edit', $projects->id) . '" class="btn btn-sm btn-warning" id="btn-modal-show" title="Edit ' . $projects->name . '"><em class="fa fa-edit"></em></a> ' .
                    // Delete Button
                    '<a href="' . route('projects.destroy', $projects->id) . '" class="btn btn-sm btn-danger" id="btn-destroy"><em class="fa fa-trash"></em></a> ';
            })
            ->addColumn('client_name', function ($projects) {
                return $projects->client_id !== null ? $projects->project_has_client->name : 'No Client Yet';
            })
            ->editColumn('actual_date', function ($projects) {
                return $projects->actual_date !== null ? date('m-d-Y', strtotime($projects->actual_date)) : 'No Date Implemented';
            })
            ->editColumn('target_date', function ($projects) {
                return $projects->target_date !== null ? date('m-d-Y', strtotime($projects->target_date)) : 'No Date Implemented';
            })
            ->editColumn('statusproject', function ($projects) {
                if ($projects->statusproject == 1):
                    return "DONE";
                elseif ($projects->statusproject == 0):
                    return "UNDONE";
                else:
                    return "No Status";
                endif;

            })
            ->addIndexColumn()->rawColumns(['action', 'client_name'])->make(true);
    }

    public function employeesTrashedDataTables()
    {
        $employees = Employee::with('employee_has_level', 'employee_has_division')->orderBy('name', 'asc')->onlyTrashed()->get();
        return DataTables::of($employees)
            ->addColumn('action', function ($employees) {
                return view('layouts.inc._action_trashed', [
                    'model' => $employees,
                    'url_restore' => route('employees.restore', $employees->id),
                    'url_delete_permanent' => route('employees.delete-permanent', $employees->id)
                ]);
            })
            ->addColumn('level', function ($employees) {
                return $employees->level_id !== null ? $employees->employee_has_level->name : 'No Level';
            })
            ->addColumn('division', function ($employees) {
                return $employees->division_id !== null ? $employees->employee_has_division->name : 'No Division';
            })
            ->addIndexColumn()->rawColumns(['action', 'level', 'division'])->make(true);
    }

    public function projectsTrashedDataTables()
    {
        $projects = project::orderBy('name', 'asc')->with('user_create_project', 'user_update_project', 'po_has_projects')->onlyTrashed()->get();
        return DataTables::of($projects)
            ->addColumn('action', function ($projects) {
                return view('layouts.inc._action_trashed', [
                    'model' => $projects,
                    'url_restore' => route('projects.restore', $projects->id),
                    'url_delete_permanent' => route('projects.delete-permanent', $projects->id)
                ]);
            })
            ->addColumn('client_name', function ($projects) {
                return $projects->client_id !== null ? $projects->project_has_client->name : 'No Client Yet';
            })
            ->editColumn('actual_date', function ($projects) {
                return $projects->actual_date !== null ? date('m-d-Y', strtotime($projects->actual_date)) : 'No Date Implemented';
            })
            ->editColumn('target_date', function ($projects) {
                return $projects->target_date !== null ? date('m-d-Y', strtotime($projects->target_date)) : 'No Date Implemented';
            })
            ->addIndexColumn()->rawColumns(['action', 'client_name'])->make(true);
    }

    public function usersDataTables()
    {
        $users = User::with('user_has_division', 'user_has_level')->orderBy('name', 'asc')->get();
        return DataTables::of($users)
            ->addColumn('action', function ($users) {
                return view('layouts.inc._action', [
                    'model' => $users,
                    'url_show' => route('users.show', $users->id),
                    'url_edit' => route('users.edit', $users->id),
                    'url_destroy' => route('users.destroy', $users->id)
                ]);
            })
            ->editColumn('image', function ($users) {
                return "<img src='" . asset('images/users_images/' . $users->image) . "' width='48px'>";
            })
            ->addColumn('division', function ($users) {
                return $users->division_id !== null ? $users->user_has_division->name : 'No Division';
            })
            ->addIndexColumn()->rawColumns(['action', 'division', 'image'])->make(true);
    }

    public function usersTrashedDataTables()
    {
        $users = User::onlyTrashed()->with('user_has_division', 'user_has_level')->orderBy('name', 'asc')->get();
        return DataTables::of($users)
            ->addColumn('action', function ($users) {
                return view('layouts.inc._action_trashed', [
                    'model' => $users,
                    'url_restore' => route('user.restore', $users->id),
                    'url_delete_permanent' => route('user.delete-permanent', $users->id),
                ]);
            })
            ->editColumn('image', function ($users) {
                return "<img src='" . asset('images/users_images/' . $users->image) . "' width='48px'>";
            })
            ->addColumn('division', function ($users) {
                return $users->division_id !== null ? $users->user_has_division->name : 'No Division';
            })
            ->addIndexColumn()->rawColumns(['action', 'division', 'image'])->make(true);
    }

    public function clientsDataTables()
    {
        $clients = Client::orderBy('name', 'asc')->get();
        return DataTables::of($clients)
            ->addColumn('action', function ($clients) {
                return view('layouts.inc._action', [
                    'model' => $clients,
                    'url_show' => route('clients.show', $clients->id),
                    'url_edit' => route('clients.edit', $clients->id),
                    'url_destroy' => route('clients.destroy', $clients->id),
                ]);
            })
            ->addIndexColumn('action')->rawColumns(['action'])->make(true);
    }

    public function clientsTrashedDataTables()
    {
        $clients = Client::onlyTrashed()->orderBy('name', 'asc')->get();
        return DataTables::of($clients)
            ->addColumn('action', function ($clients) {
                return view('layouts.inc._action_trashed', [
                    'model' => $clients,
                    'url_restore' => route('client.restore', $clients->id),
                    'url_delete_permanent' => route('client.delete-permanent', $clients->id),
                ]);
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function posDataTables()
    {
        $pos = Po::with('user_create_po', 'user_update_po')->orderBy('name', 'asc')->get();
        return DataTables::of($pos)
            ->addColumn('action', function ($pos) {
                return view('layouts.inc._action', [
                    'model' => $pos,
                    'url_show' => route('pos.show', $pos->id),
                    'url_edit' => route('pos.edit', $pos->id),
                    'url_destroy' => route('pos.destroy', $pos->id),
                ]);
            })
            ->editColumn('statusPo', function ($pos) {
                if ($pos->statusPo == 1):
                    return "ACTIVE";
                else:
                    return "INACTIVE";
                endif;
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function posTrashedDataTables()
    {
        $pos = Po::onlyTrashed()->with('user_create_po', 'user_update_po')->orderBy('name', 'asc')->get();
        return DataTables::of($pos)
            ->addColumn('action', function ($pos) {
                return view('layouts.inc._action_trashed', [
                    'model' => $pos,
                    'url_restore' => route('po.restore', $pos->id),
                    'url_delete_permanent' => route('po.delete-permanent', $pos->id),
                ]);
            })
            ->editColumn('statusPo', function ($pos) {
                if ($pos->statusPo == 1):
                    return "ACTIVE";
                else:
                    return "INACTIVE";
                endif;
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function sprintsDataTables()
    {
        $sprints = Sprint::with('user_create_sprint', 'user_update_sprint')->get();
        return DataTables::of($sprints)
            ->addColumn('action', function ($sprints) {
                return view('layouts.inc._action', [
                    'model' => $sprints,
                    'url_show' => route('sprints.show', $sprints->id),
                    'url_edit' => route('sprints.edit', $sprints->id),
                    'url_destroy' => route('sprints.destroy', $sprints->id),
                ]);
            })
            ->editColumn('start_date', function ($sprints) {
                return $sprints->start_date !== null ? date('m-d-Y', strtotime($sprints->start_date)) : 'No Date';
            })
            ->editColumn('finish_date', function ($sprints) {
                return $sprints->finish_date !== null ? date('m-d-Y', strtotime($sprints->finish_date)) : 'No Date';
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function sprintsTrashedDataTables()
    {
        $sprints = Sprint::with('user_create_sprint')->onlyTrashed()->get();
        return DataTables::of($sprints)
            ->addColumn('action', function ($sprints) {
                return view('layouts.inc._action_trashed', [
                    'model' => $sprints,
                    'url_restore' => route('sprints.restore', $sprints->id),
                    'url_delete_permanent' => route('sprints.delete-permanent', $sprints->id)
                ]);
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }

    public function targetsDataTables()
    {
        $targets = Target::with('user_create_target', 'user_update_target', 'target_has_sprints', 'target_has_projects')->get();
        return DataTables::of($targets)
            ->addColumn('action', function ($targets) {
                return view('layouts.inc._action', [
                    'model' => $targets,
                    'url_show' => route('targets.show', $targets->id),
                    'url_edit' => route('targets.edit', $targets->id),
                    'url_destroy' => route('targets.destroy', $targets->id),
                ]);
            })
            ->editColumn('status', function ($targets) {
                return $targets->status !== null ? $targets->status : 'No Status';
            })
            ->addIndexColumn()->rawColumns(['action'])->make(true);
    }
}