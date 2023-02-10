<?php

namespace App\Imports;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

class DropoutDatesImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $student_number = trim($row['rkm_altalb']);
        $section = $row['alksm'];
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['tarykh_alanktaaa']));

        if (!empty($student_number) && !empty($section) && !empty($date)){
            $dropout_date = Carbon::parse($date)->format('Y-m-d');
            $user = User::query()->where('section', '=', $section == 'بنات' ? 'female' : 'male')->where('student_number', '=', $student_number)->first();
            if ($user){
                $user->update([
                    'dropout_date' => $dropout_date,
                ]);
            }
        }
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }


}
