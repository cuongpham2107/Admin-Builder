<?php

namespace CuongPham2107\AdminBuilder\Resources\DataTableResource\Pages;

use CuongPham2107\AdminBuilder\Resources\DataTableResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageDataTable extends ManageRecords
{
    protected static string $resource = DataTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->handleCreateAction(),
        ];
    }

    public function handleCreateAction(){
        return 
            \Filament\Actions\CreateAction::make()
                ->modalWidth(MaxWidth::SixExtraLarge);
                // ->slideOver()
                // ->action(function (array $data): void {
                //     // dd($data);
                // });
    }
    
}
