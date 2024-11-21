<?php

namespace CuongPham2107\AdminBuilder\Resources;

use CuongPham2107\AdminBuilder\Forms\Components\TableRepeater;
use CuongPham2107\AdminBuilder\Models\DataTable;
use CuongPham2107\AdminBuilder\Resources\DataTableResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class DataTableResource extends Resource
{
    protected static ?string $model = DataTable::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationLabel = 'Database';

    protected static ?string $navigationGroup = 'Tools';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Table detail')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Table Name')
                            ->required()
                            ->placeholder('Enter the table name'),
                        TableRepeater::make('table_columns')
                            ->label('Attributes')
                            ->description('Attributes define what data is available on the model. Relationships are configured separately, so foreign keys don’t need to be defined as attributes.')
                            ->defaultItems(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'string' => 'String',
                                        'increments' => 'Increments',
                                        'timestamp' => 'Timestamp',
                                    ]),
                                Forms\Components\Checkbox::make('nullable')
                                    ->default(false),
                                Forms\Components\Checkbox::make('unique')
                                    ->default(false),
                                Forms\Components\TextInput::make('max_length'),
                                Forms\Components\TextInput::make('default'),
                            ])
                            ->afterStateHydrated(function ($component, $state) {
                                if (isset($state)) {
                                    $fisrt_key = array_key_first($state);
                                    $state[$fisrt_key] = [
                                        'name' => 'id', // Set your default value here
                                        'type' => 'increments', // Set default type if needed
                                        'nullable' => false,
                                        'unique' => false,
                                        'max_length' => null,
                                        'default' => null,
                                    ];
                                    $component->state($state);
                                }
                            })
                            ->addActionLabel('New Attribute')
                            ->extraActions([
                                Forms\Components\Actions\Action::make('created_at_and_updated_at')
                                    ->label('Add Timestamps')
                                    ->action(function ($component) {
                                        $currentState = $component->getState();
                                        $newState = [
                                            'created_at' => [
                                                'name' => 'created_at',
                                                'type' => 'timestamp',
                                                'nullable' => false,
                                                'unique' => false,
                                                'max_length' => null,
                                                'default' => null,
                                            ],
                                            'updated_at' => [
                                                'name' => 'updated_at',
                                                'type' => 'timestamp',
                                                'nullable' => false,
                                                'unique' => false,
                                                'max_length' => null,
                                                'default' => null,
                                            ],
                                        ];
                                        $component->state(array_merge($currentState, $newState));
                                    }),
                            ]),

                        TableRepeater::make('relationships')
                            ->label('Relationships')
                            ->description('Relationships define how models are related to each other. They are used to define foreign keys and other relationships between models.')
                            ->defaultItems(0)
                            ->addActionLabel('New Relationship')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'belongsTo' => 'Belongs To',
                                        'hasOne' => 'Has One',
                                        'hasMany' => 'Has Many',
                                        'belongsToMany' => 'Belongs To Many',
                                    ]),
                                Forms\Components\Select::make('related_model')
                                    ->label('Model')
                                    ->options([
                                        'user' => 'User',
                                    ]),
                                Forms\Components\Checkbox::make('nullable')
                                    ->default(false),
                            ]),

                    ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schema')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('collation')
                    // ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('engine')
                    // ->searchable()
                    ->sortable(),

            ])
            ->filters([
                // DataTableModelFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    self::createResource(),
                    Tables\Actions\Action::make('edit_resource')
                        ->label('Edit Resource')
                        ->icon('heroicon-o-pencil-square'),
                    Tables\Actions\Action::make('delete_resource')
                        ->label('Delete Resource')
                        ->icon('heroicon-o-trash'),
                ])
                    ->label('Resources')
                    ->link()
                    ->icon('heroicon-o-code-bracket'),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::SixExtraLarge)
                    ->slideOver()
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDataTable::route('/'),
            'create' => Pages\CreateDataTable::route('/create'),
            'edit' => Pages\EditDataTable::route('/{record}/edit'),
        ];
    }

    public static function createResource(): Tables\Actions\Action
    {
        return
            Tables\Actions\Action::make('create_resource')
                ->label('Create Resource')
                ->icon('heroicon-o-plus')
                ->requiresConfirmation()
                ->action(function (array $arguments, DataTable $record) {
                    dd($record->table_column);
                });
    }
}
