<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Site Settings';

    protected static string $view = 'filament.pages.manage-site-settings';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')->required()->maxLength(255),
                        Forms\Components\Textarea::make('site_description')->rows(2)->columnSpanFull(),
                        Forms\Components\FileUpload::make('logo')->image()->directory('site')->disk('public'),
                        Forms\Components\FileUpload::make('favicon')->image()->directory('site')->disk('public'),
                        Forms\Components\ColorPicker::make('primary_color')->default('#2563eb'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Default SEO')
                    ->schema([
                        Forms\Components\TextInput::make('default_meta_title')->maxLength(255),
                        Forms\Components\Textarea::make('default_meta_description')->rows(2),
                        Forms\Components\FileUpload::make('default_og_image')->image()->directory('site')->disk('public'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Advanced')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Textarea::make('robots_txt')->rows(4),
                        Forms\Components\TextInput::make('analytics_id')->helperText('e.g. GA4 measurement ID'),
                    ]),
            ])
            ->statePath('data')
            ->model(SiteSetting::current());
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Site settings updated')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->submit('save'),
        ];
    }
}
