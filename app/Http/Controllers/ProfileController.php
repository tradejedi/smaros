<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use \CoolmacJedi\SeoMetaManager\Services\SeoPageResolver;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService){
        $this->profileService = $profileService;
    }

    public function show(Profile $profile, string $slug)
    {
        if ($slug !== $profile->slug) {
            return redirect()->route('profiles.show', [
                'profile' => $profile->id,
                'slug' => $profile->slug,
            ]);
        }
        $profile = $this->profileService->getProfileWithDetails($profile->id);

        $seoTags = app(SeoPageResolver::class)
            ->resolve($profile);

        return view('profile', compact('profile'));
    }
}
