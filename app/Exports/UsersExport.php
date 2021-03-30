<?php

namespace App\Exports;

use App\Models\Auth\User\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

    public function query()
    {
        return User::query();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->mobile_number,
            $user->created_at,
            implode(",", $user->roles->pluck('name')->toArray())
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Mobile',
            'Created At',
            'Role'
        ];
    }
}