<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Employee;
use App\project;
use App\User;
use App\Sprint;
use App\Price;
use App\Client;
use App\Po;

class TrashedController extends Controller
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

    public function prices()
    {
        return view('pages.managements.prices.soft_deletes.index');
    }

    public function restorePrice($id)
    {
        $price = Price::withTrashed()->findOrFail($id);
        if ($price->trashed()) {
            $price->restore();
            return response()->json([
                'message' => 'Price Restored'
            ], 200);
        }
    }

    public function deletePermanentPrice($id)
    {
        $price = Price::withTrashed()->findOrFail($id);
        if ($price->trashed()) {
            $price->forceDelete();
            return response()->json([
                'message' => 'Price Deleted Permanently'
            ], 200);
        }
    }

    public function employees()
    {
        return view('pages.managements.employees.soft_deletes.index');
    }

    public function restoreEmployee($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        if ($employee->trashed()) {
            $employee->restore();
            return response()->json([
                'message' => 'Employee Restored'
            ], 200);
        }
    }

    public function deletePermanentEmployee($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        if ($employee->trashed()) {
            $employee->forceDelete();
            return response()->json([
                'message' => 'Employee Deleted Permanent'
            ], 200);
        }
    }

    public function projects()
    {
        return view('pages.managements.projects.soft_deletes.index');
    }

    public function restoreproject($id)
    {
        $project = project::withTrashed()->findOrFail($id);
        if ($project->trashed()) {
            $project->restore();
            return response()->json([
                'message' => 'project Restored'
            ], 200);
        }
    }

    public function deletePermanentproject($id)
    {
        $project = project::withTrashed()->findOrFail($id);
        if ($project->trashed()) {
            $project->forceDelete();
            return response()->json([
                'message' => 'project Deleted Permanently'
            ], 200);
        }
    }

    public function users()
    {
        return view('pages.managements.users.soft_deletes.index');
    }

    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
        }

        if ($user) {
            return response()->json([
                'message' => 'User Restored',
                'data' => $user
            ], 200);
        }
    }

    public function deletePermanentUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->forceDelete();
        }

        if ($user) {
            return response()->json([
                'message' => 'User deleted permanently',
                'data' => $user
            ], 200);
        }
    }

    public function sprints()
    {
        return view('pages.managements.sprints.soft_deletes.index');
    }

    public function restoreSprint($id)
    {
        $sprint = Sprint::withTrashed()->findOrFail($id);
        if ($sprint->trashed()) {
            $sprint->restore();
            return response()->json([
                'message' => 'Sprint Restored'
            ], 200);
        }
    }

    public function deletePermanentSprint($id)
    {
        $sprint = Sprint::withTrashed()->findOrFail($id);
        if ($sprint->trashed()) {
            $sprint->forceDelete();
            return response()->json([
                'message' => 'Sprint Deleted Permanently'
            ], 200);
        }
    }

    public function clients()
    {
        return view('pages.managements.clients.soft_deletes.index');
    }

    public function restoreClient($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        if ($client->trashed()) {
            $client->restore();
            return response()->json([
                'message' => 'Client Restored'
            ], 200);
        }
    }

    public function deletePermanentClient($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        if ($client->trashed()) {
            $client->forceDelete();
            return response()->json([
                'message' => 'Client Deleted Permanently'
            ], 200);
        }
    }

    public function pos()
    {
        return view('pages.managements.pos.soft_deletes.index');
    }

    public function restorePo($id)
    {
        $po = Po::withTrashed()->findOrFail($id);
        if ($po->trashed()) {
            $po->restore();
            return response()->json([
                'message' => 'Po Restored'
            ], 200);
        }
    }

    public function deletePermanentPo($id)
    {
        $po = Po::withTrashed()->findOrFail($id);
        if ($po->trashed()) {
            $po->forceDelete();
            return response()->json([
                'message' => 'Po Deleted Permanently'
            ], 200);
        }
    }
}