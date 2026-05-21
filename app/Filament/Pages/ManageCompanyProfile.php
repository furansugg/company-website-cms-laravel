<?php

namespace App\Filament\Pages;

use App\Models\CompanyProfile;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageCompanyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Site Content';

    protected static ?int $navigationSort = 40;

    protected static ?string $title = 'Company Profile';

    protected static string $view = 'filament.pages.manage-company-profile';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(CompanyProfile::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identity')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->directory('company')
                            ->disk('public'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Story')
                    ->schema([
                        Forms\Components\Textarea::make('about')->rows(4)->columnSpanFull(),
                        Forms\Components\Textarea::make('vision')->rows(3),
                        Forms\Components\Textarea::make('mission')->rows(3),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Contact')
                    ->schema([
                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\Textarea::make('address')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Social media')
                    ->schema([
                        Forms\Components\KeyValue::make('social_media')
                            ->keyLabel('Platform')
                            ->valueLabel('URL')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data')
            ->model(CompanyProfile::current());
    }

    public function save(): void
    {
        CompanyProfile::current()->update($this->form->getState());

        Notification::make()
            ->title('Company profile updated')
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
