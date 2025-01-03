<?php

namespace App\View\Components;

use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\View\Component;
use Illuminate\View\View;

class ProfileDetail extends Component
{
    public Profile $profile;
    public $profileAttributes;
    public $prices;
    public $comments;
    public $similarProfiles;
    public $metro;
    public $profileBooleanAttributes;

    protected ProfileService $profileService;

    /**
     * Создаём компонент, принимаем объект Profile
     */
    public function __construct(Profile $profile, ProfileService $profileService)
    {
        $this->profile = $profile;
        $this->profileService = $profileService;

        $this->profileAttributes = $this->profileService->getProfileAttributes($this->profile);
        $this->profileBooleanAttributes = $this->profileService->getBooleanProfileAttributes($this->profile);

        $this->prices = $this->profileService->getPrices($this->profile);
        $this->comments = $this->profileService->getComments($this->profile);
        $this->similarProfiles = $this->profileService->getSimilarProfiles($this->profile);
        $this->metro = $this->profileService->getMetroStations($this->profile);
    }

    /**
     * Логика, если нужно, можно дописать методы для шаблона.
     */

    /**
     * Возвращаем шаблон компонента.
     */
    public function render(): View
    {
        return view('components.profile-detail');
    }
}
