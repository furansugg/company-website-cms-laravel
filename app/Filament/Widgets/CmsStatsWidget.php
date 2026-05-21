<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pages', Page::count())
                ->description(Page::published()->count().' published')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Articles', Article::count())
                ->description(Article::published()->count().' published')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
            Stat::make('Unread messages', ContactMessage::where('status', ContactMessage::STATUS_UNREAD)->count())
                ->description(ContactMessage::count().' total')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
            Stat::make('Active services', Service::active()->count())
                ->description(Service::count().' total')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),
        ];
    }
}
