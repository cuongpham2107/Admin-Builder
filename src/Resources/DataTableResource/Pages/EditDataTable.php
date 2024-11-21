<?php

namespace CuongPham2107\AdminBuilder\Resources\DataTableResource\Pages;

use CuongPham2107\AdminBuilder\Resources\DataTableResource;
use CuongPham2107\AdminBuilder\Services\Database\DatabaseServiceInterface;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDataTable extends EditRecord
{
    protected $databaseTableAnalyser;

    protected static string $resource = DataTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Xoá'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['old_table_name_model'] = $data['name'];
        $data['old_table_column_model'] = $data['table_column'];

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->databaseTableAnalyser = app(DatabaseServiceInterface::class);
        $results = $this->compareArrays($this->data['old_table_column_model'], $data['table_column']);
        if (count($results['added']) > 0) {

            foreach ($results['added'] as $value) {
                $message = $this->databaseTableAnalyser->addColumn($data['name'], $value);
                $notification = $message['status'] ? Notification::make()->success() : Notification::make()->warning();
                $notification->title($message['title'])
                    ->body($message['message'])
                    ->sendToDatabase(Auth::user());
            }
        }

        if (count($results['deleted']) > 0) {
            foreach ($results['deleted'] as $value) {
                $message = $this->databaseTableAnalyser->dropColumn($data['name'], $value['name']);
                $notification = $message['status'] ? Notification::make()->success() : Notification::make()->warning();
                $notification->title($message['title'])
                    ->body($message['message'])
                    ->sendToDatabase(Auth::user());
            }
        }

        if (count($results['modified']) > 0) {
            // $results['modified'] =[
            // 0 => [
            //          'old' => $old,
            //          'new' => $new
            //      ]
            // ],
            // ...
            foreach ($results['modified'] as $value) {
                $message = $this->databaseTableAnalyser->updateColumn(
                    $data['name'],
                    $value['old']['name'],
                    $value['new']
                );
                $notification = $message['status'] ? Notification::make()->success() : Notification::make()->warning();
                $notification->title($message['title'])
                    ->body($message['message'])
                    ->sendToDatabase(Auth::user());
            }
        }

        return $data;
    }

    protected function compareArrays($original, $updated)
    {
        $added = [];
        $deleted = [];
        $modified = [];
        // Tìm cột bị xóa
        foreach ($original as $key => $value) {
            $found = false;
            foreach ($updated as $updatedKey => $updatedValue) {
                if ($value['name'] === $updatedValue['name']) {
                    $found = true;
                    if ($value !== $updatedValue) {
                        $modified[] = [
                            'old' => $value,
                            'new' => $updatedValue,
                        ];
                    }

                    break;
                }
            }
            if (! $found) {
                $deleted[] = $value;
            }
        }
        // Tìm cột được thêm
        foreach ($updated as $updatedValue) {
            $found = false;
            foreach ($original as $value) {
                if ($value['name'] === $updatedValue['name']) {
                    $found = true;

                    break;
                }
            }
            if (! $found) {
                $added[] = $updatedValue;
            }
        }

        return [
            'added' => $added,
            'deleted' => $deleted,
            'modified' => $modified,
        ];
    }
}
