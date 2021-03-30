<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Auth\User\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AppointmentExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

    public function query()
    {
        return Appointment::query();
    }

    public function map($appointment): array
    {
        return [
            $appointment->id,
            $appointment->date,
            $appointment->time_from,
            $appointment->time_to,
            $appointment->status,
            $appointment->created_at,
            $appointment->provider->user->name,
            $appointment->user->name
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Time From',
            'Time To',
            'Status',
            'Created at',
            'Provider Name',
            'Appointed By'
        ];
    }
}