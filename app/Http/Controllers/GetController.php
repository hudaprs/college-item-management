<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\project as Manajemen BarangResource;
use App\User;
use App\Employee;
use App\Po;
use App\project;
use App\Client;
use App\Sprint;
use App\Target;
use App\Division;
use App\Level;

class GetController extends Controller
{
	public function getAllPo(Request $request)
    {
        $keyword = $request->get('q');
        $pos = Po::where('name', 'LIKE', '%' . $keyword . '%')->orderBy('name', 'asc')->get();
        return $pos;
    }

    public function getPo($id)
    {
        $po = Po::orderBy('name', 'asc')->findOrFail($id);
        return $po;
    }

    // public function getManajemen Barangs() 
    // {
    //     $projects = project::with('target_has_Manajemen Barangs', 'Manajemen Barang_employee')->orderBy('name', 'asc')->paginate(10);
    //     return Manajemen BarangResource::collection($projects);
    // }

    public function getManajemen Barangs(Request $request)
    {
        $year = $request->get('year');
        if($year) {
            return project::with('target_has_Manajemen Barangs', 'Manajemen Barang_employee', 'po_has_Manajemen Barangs', 'Manajemen Barang_has_client')->whereYear('created_at', $year)->orderBy('name', 'asc')->get();
        }
        return project::with('target_has_Manajemen Barangs', 'Manajemen Barang_employee', 'po_has_Manajemen Barangs', 'Manajemen Barang_has_client')->orderBy('name', 'asc')->get();
    }

    public function getManajemen Barang($id) 
    {
        $project = project::with('po_has_Manajemen Barangs', 'user_create_Manajemen Barang', 'Manajemen Barang_sprint')->findOrFail($id);
        return $project;
    }

    public function getManajemen BarangByPo($id)
    {
        $project = project::with('po_has_Manajemen Barangs', 'user_create_Manajemen Barang', 'Manajemen Barang_sprint', 'target_has_Manajemen Barangs')->where('po_id', $id)->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')->get();
        return $project;
    }

    public function getSprints(Request $request)
    {
        return Sprint::with('sprint_has_targets')->orderBy('name', 'asc')->get();
    }

    public function getSprint($id)
    {
        $sprint = Sprint::with('Manajemen Barang_sprint', 'sprint_has_targets')->findOrFail($id);
        return $sprint;
    }

    public function getSprintByManajemen Barang($id){
        $sprint = Sprint::with('Manajemen Barang_sprint','sprint_has_targets')->whereHas('Manajemen Barang_sprint',function($q) use($id){
            $q->where('Manajemen Barang_id',$id);
        })->get();
        return $sprint;
    }

    public function getClients()
    {
        return Client::orderBy('name', 'asc')->get();
    }

    public function getEmployees(Request $request)
    {
        $keyword = $request->get('q');
        return Employee::where('name', 'LIKE', '%' . $keyword . '%')->orderBy('name', 'asc')->get();
    }

    public function getEmployeesDev(){
        return Employee::with('employee_has_level')->where('level_id',8)->get();
    }

    public function getTargets()
    {
        return Target::with('target_has_sprints')->get();
    }

    public function getDivisions()
    {
        return Division::orderBy('name','asc')->get();
    }

    public function getLevels()
    {
        return Level::orderBy('name','asc')->get();
    }

    public function countPos()
    {
        return Po::orderBy('name', 'asc')->count();
    }

    public function countManajemen Barangs()
    {
        return project::orderBy('name', 'asc')->count();
    }

    public function countSprints()
    {
        return Sprint::orderBy('name', 'asc')->count();
    }

    public function countTargets()
    {
        return Target::orderBy('name', 'asc')->count();
    }

    public function countFinishedTargets() 
    {
        return Target::where('status', 'DONE')->count();
    }

    public function getManajemen BarangsArchived(){
        $projects = project::where('statusManajemen Barang',1)->get(); 
        return $projects;
    }
}
