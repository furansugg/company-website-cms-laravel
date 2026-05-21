<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Site Content';

    protected static ?int $navigationSort = 60;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', ContactMessage::STATUS_UNREAD)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->disabled(),
            Forms\Components\TextInput::make('email')->disabled(),
            Forms\Components\TextInput::make('phone')->disabled(),
            Forms\Components\TextInput::make('subject')->disabled(),
            Forms\Components\Textarea::make('message')->disabled()->rows(8)->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->options([
                    ContactMessage::STATUS_UNREAD => 'Unread',
                    ContactMessage::STATUS_READ => 'Read',
                    ContactMessage::STATUS_REPLIED => 'Replied',
                    ContactMessage::STATUS_ARCHIVED => 'Archived',
                ])
                ->required(),
            Forms\Components\DateTimePicker::make('replied_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->limit(40)->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => ContactMessage::STATUS_UNREAD,
                        'gray' => ContactMessage::STATUS_READ,
                        'success' => ContactMessage::STATUS_REPLIED,
                        'warning' => ContactMessage::STATUS_ARCHIVED,
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        ContactMessage::STATUS_UNREAD => 'Unread',
                        ContactMessage::STATUS_READ => 'Read',
                        ContactMessage::STATUS_REPLIED => 'Replied',
                        ContactMessage::STATUS_ARCHIVED => 'Archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('View'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
