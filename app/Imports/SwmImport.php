<?php

namespace App\Imports;

use App\Models\TaxPaymentInfo\TaxPayment;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SwmImport implements ToModel, WithHeadingRow, WithChunkReading,WithValidation, SkipsOnError, WithColumnFormatting, WithMapping, WithBatchInserts, SkipsOnFailure, ShouldQueue
{
    use Importable;
    use RemembersChunkOffset;
    use SkipsFailures;

    public function model(array $row)
    {
        $chunkOffset = $this->getChunkOffset();
        return new TaxPayment([
            "tax_code" => $row['tax_code'],
            "owner_name" => $row['owner_name']?$row['owner_name']:null,
            "owner_gender" => $row['owner_gender']?$row['owner_gender']:null,
            "owner_contact" => $row['owner_contact']?$row['owner_contact']:null,
            #"last_payment_date" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_payment_date']),
            "last_payment_date" => ($row['last_payment_date']),
        ]);
    }

   public function chunkSize(): int
    {
        return 1000;
    }
    public function batchSize(): int
    {
        return 1000;
    }
    /**
    * @return array
    */
    public function rules(): array {

         return [
            'tax_code' => [
                'required',
                'string',
                //'unique:pgsql.taxpayment_info.tax_payments,tax_code',
            ],
            'owner_name' => [
                'nullable',
                'string',
            ],
            'owner_gender' => [
                'nullable',
                'string',
            ],
            'owner_contact' => [
                'nullable',
                'integer',
            ],
            'last_payment_date' => [
                 'required',
                'date_format:Y-m-d',

                ],

        ];
    }
    public function map($row): array
    {
        return [
           "tax_code" => $row['tax_code'],
            "owner_name" => $row['owner_name'],
            "owner_gender" => $row['owner_gender'],
            "owner_contact" => $row['owner_contact'],
            "last_payment_date" => date('Y-m-d', strtotime($row['last_payment_date'])),
        ];
    }
     /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'last_payment_date' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }


    public function onError(\Throwable $e) {

    }

}
