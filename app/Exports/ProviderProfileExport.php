<?php

namespace App\Exports;

use App\Models\Auth\User\User;
use App\Models\ProviderProfile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProviderProfileExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

    public function query()
    {
        return ProviderProfile::query();
    }

    public function map($provider): array
    {
        return [
            $provider->id,
            $provider->user->name,
            $provider->primary_category->title,
            $provider->document_url,
            $provider->price,
            $provider->price_type,
            $provider->address,
            $provider->latitude,
            $provider->longitude,
            $provider->about,
            implode(",", $provider->subcategories->pluck('title')->toArray())
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Category',
            'Document Url',
            'Price',
            'Price Type',
            'Address',
            'Latitude',
            'Longitude',
            'About',
            'Sub Categories'
        ];
    }
}