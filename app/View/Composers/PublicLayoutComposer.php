<?php

namespace App\View\Composers;

use App\Models\CompanyProfile;
use App\Models\Menu;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PublicLayoutComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'siteSettings' => SiteSetting::current(),
            'companyProfile' => CompanyProfile::current(),
            'headerMenus' => Menu::active()->location(Menu::LOCATION_HEADER)
                ->whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->active()])
                ->orderBy('sort_order')
                ->get(),
            'footerMenus' => Menu::active()->location(Menu::LOCATION_FOOTER)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
