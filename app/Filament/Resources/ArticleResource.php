<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Article')
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
                        ->rows(14)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Taxonomy')
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')->required(),
                        ])
                        ->createOptionUsing(fn (array $data) => Category::create($data)->id),
                    Forms\Components\Select::make('tags')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')->required(),
                        ])
                        ->createOptionUsing(fn (array $data) => Tag::create($data)->id),
                ])
                ->columns(2),
            Forms\Components\Section::make('Featured image')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('articles')
                        ->disk('public')
                        ->imageEditor(),
                ]),
            Forms\Components\Section::make('Publishing')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            Article::STATUS_DRAFT => 'Draft',
                            Article::STATUS_REVIEW => 'Review',
                            Article::STATUS_PUBLISHED => 'Published',
                            Article::STATUS_ARCHIVED => 'Archived',
                        ])
                        ->default(Article::STATUS_DRAFT)
                        ->required(),
                    Forms\Components\DateTimePicker::make('published_at'),
                ])
                ->columns(2),
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable()->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => Article::STATUS_DRAFT,
                        'warning' => Article::STATUS_REVIEW,
                        'success' => Article::STATUS_PUBLISHED,
                        'danger' => Article::STATUS_ARCHIVED,
                    ]),
                Tables\Columns\TextColumn::make('author.name')->label('Author')->toggleable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('views')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Article::STATUS_DRAFT => 'Draft',
                        Article::STATUS_REVIEW => 'Review',
                        Article::STATUS_PUBLISHED => 'Published',
                        Article::STATUS_ARCHIVED => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
