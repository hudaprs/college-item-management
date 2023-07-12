<?php

namespace App\Exports;

use App\Target;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class TargetsExport implements FromView, ShouldAutoSize
{
    use Exportable;
    protected $project;
    protected $sprint;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function view(): View
    {
        return view('pages.reports.excel.targets', [
            'project' => $this->project,
            'targets' => Target::all()
        ]);
    }
}