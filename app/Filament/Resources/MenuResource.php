<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Site Content';

    protected static ?int $navigationSort = 70;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('url')
                ->required()
                ->maxLength(255)
                ->helperText('Use absolute (/about) or full URL (https://...).'),
            Forms\Components\Select::make('location')
                ->options([
                    Menu::LOCATION_HEADER => 'Header',
                    Menu::LOCATION_FOOTER => 'Footer',
                ])
                ->default(Menu::LOCATION_HEADER)
                ->required(),
            Forms\Components\Select::make('parent_id')
                ->label('Parent menu')
                ->relationship(
                    'parent',
                    'label',
                    fn ($query, $record) => $query->whereNull('parent_id')->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                )
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Toggle::make('open_in_new_tab'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('url')->limit(40)->toggleable(),
                Tables\Columns\BadgeColumn::make('location'),
                Tables\Columns\TextColumn::make('parent.label')->label('Parent')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options([
                        Menu::LOCATION_HEADER => 'Header',
                        Menu::LOCATION_FOOTER => 'Footer',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
