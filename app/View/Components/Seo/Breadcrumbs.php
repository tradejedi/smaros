<?php

namespace App\View\Components\Seo;

use App\Services\BreadcrumbService;
use Illuminate\View\Component;
use Illuminate\View\View;

class Breadcrumbs extends Component
{
    public array $breadcrumbs;

    public function __construct(private BreadcrumbService $breadcrumbService)
    {
        // Получаем массив крошек из сервиса
        $this->breadcrumbs = $this->breadcrumbService->getBreadcrumbs();
    }

    public function render(): View
    {
        return view('components.seo.breadcrumbs');
    }
}
