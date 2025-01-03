<?php

namespace App\View\Components\Blocks;

use App\Models\Contact;
use App\Services\ContactButtonService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContactButtons extends Component
{
    public Contact $contact;
    protected ContactButtonService $contactButtonService;

    /**
     * Создаём компонент
     */
    public function __construct(Contact $contact, ContactButtonService $contactButtonService)
    {
        // Если не загружен contactType, подгружаем
        $contact->load('contactType');

        $this->contact = $contact;
        $this->contactButtonService = $contactButtonService;
    }

    /**
     * Возвращает массив кнопок для данного контакта
     */
    public function buttons(): array
    {
        return $this->contactButtonService->getContactButtons($this->contact);
    }

    /**
     * Возвращает шаблон компонента
     */
    public function render(): View|Closure|string
    {
        return view('components.blocks.contact-buttons');
    }
}
