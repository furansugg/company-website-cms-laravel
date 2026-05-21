<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true),
                    Forms\Components\TextInput::make('slug')
                        ->maxLength(255)
                        ->helperText('Auto-generated from title if left blank.'),
                    Forms\Components\Textarea::make('excerpt')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('content')
                        ->rows(12)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Featured image')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('pages')
                        ->disk('public')
                        ->imageEditor(),
                ]),
            Forms\Components\Section::make('Publishing')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            Page::STATUS_DRAFT => 'Draft',
                            Page::STATUS_PUBLISHED => 'Published',
                            Page::STATUS_ARCHIVED => 'Archived',
                        ])
                        ->default(Page::STATUS_DRAFT)
                        ->required(),
                    Forms\Components\DateTimePicker::make('published_at'),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3),
            Forms\Components\Section::make('SEO')
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(255),
                    Forms\Components\Textarea::make('meta_description')->rows(2)->maxLength(500),
                    Forms\Components\FileUpload::make('og_image')
                        ->image()
                        ->directory('og')
                        ->disk('public'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable()->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => Page::STATUS_DRAFT,
                        'success' => Page::STATUS_PUBLISHED,
                        'warning' => Page::STATUS_ARCHIVED,
                    ]),
                Tables\Columns\TextColumn::make('author.name')->label('Author')->toggleable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Page::STATUS_DRAFT => 'Draft',
                        Page::STATUS_PUBLISHED => 'Published',
                        Page::STATUS_ARCHIVED => 'Archived',
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
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
