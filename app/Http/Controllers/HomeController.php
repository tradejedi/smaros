<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Profile;
use App\Services\ProfileAttributeService;
use App\Services\ProfileService;
use CoolmacJedi\SeoMetaManager\Services\SeoPageResolver;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(ProfileService $profileService )
    {
        // Получаем пагинатор на 10 записей
        $profiles = $profileService->getLatestProfilesWithPagination(10);

        $page = Page::findOrFail(1);

        return view('welcome', compact(
            'profiles',
            'page'
        ));
    }
}
