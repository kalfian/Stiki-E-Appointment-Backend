<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use App\Models\FileLog;

class ImportUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileId)
    {
        //
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $file = FileLog::find($this->fileId);
        $type = $file->import_type;

        $import = new UserImport($type, $file->id, $file->use_default_password);
        Excel::import($import, $file->file_path, $file->disk);

    }
}
