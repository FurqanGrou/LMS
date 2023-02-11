<?php

namespace App\Http\Livewire;

use App\Exports\RegularStudentsReport;
use App\Jobs\RegularStudentsJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use App\Jobs\ExportJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Jobs\AppendQueryToSheet;
use Maatwebsite\Excel\Jobs\QueueExport;
use Maatwebsite\Excel\Jobs\StoreQueuedExport;

class Export extends Component
{
    public $batchId;
    public $exporting = false;
    public $exportFinished = false;
    public $date_from;
    public $date_to;
    public $file_name;

    public function export()
    {
        $this->exporting = true;
        $this->exportFinished = false;
        $this->file_name = rand() . '-regular-students-report' . '.xlsx';

        $batch = Bus::batch([
            new RegularStudentsJob(['date_from' => $this->date_from, 'date_to' => $this->date_to, 'file_name' => $this->file_name]),
        ])->onQueue('export-regular-students')->dispatch();

        // $files =   Storage::allFiles('public/regular-students-reports');

        // // Delete Files
        // Storage::delete($files);

        $this->batchId = $batch->id;
    }

    public function getExportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }

        return Bus::findBatch($this->batchId);
    }

    public function updateExportProgress()
    {
        $this->exportFinished = Storage::exists('public/' . $this->file_name);

        if ($this->exportFinished) {
            $this->exporting = false;
        }
    }

    public function downloadExport()
    {
        return Storage::download('public/' . $this->file_name);
    }

    public function render()
    {
        return view('livewire.export');
    }
}
