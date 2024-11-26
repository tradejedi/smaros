<?php

namespace App\Services;

use App\Models\Contact;

class ContactButtonService
{
    /**
     * Возвращает массив кнопок для данного контакта
     * в зависимости от slug типа (whatsapp, telegram, phone, и т.д.).
     */
    public function getContactButtons(Contact $contact): array
    {
        $buttons = [];

        // Убеждаемся, что подгружен тип (если не подгружен, вызвать $contact->load('contactType'))
        $typeSlug = optional($contact->contactType)->slug;
        $value    = $contact->value;

        switch ($typeSlug) {
            case 'telefon':
                $buttons[] = [
                    'label' => 'Позвонить ' . $value,
                    'url'   => 'tel:' . $value,
                    'icon'  => 'heroicon-s-phone',
                    'class' => 'text-gray-500',
                ];
                break;

            case 'telegram':
                $buttons[] = [
                    'label' => 'Открыть Telegram',
                    'url'   => 'https://t.me/' . $value,
                    'icon'  => 'si-telegram',
                    'class' => 'text-blue-500',
                ];
                break;

            case 'poles':
                $buttons[] = [
                    'label' => 'Написать в WhatsApp',
                    'url'   => 'https://polee.me/' . $value,
                    'icon'  => 'si-polestar',
                    'class' => 'text-green-500',
                ];
                break;

            case 'viber':
                $buttons[] = [
                    'label' => 'Написать в Viber',
                    'url'   => 'https://viber.com/' . $value,
                    'icon'  => 'si-viber',
                    'class' => 'text-purple-500',
                ];
                break;

            case 'instagram':
                $buttons[] = [
                    'label' => 'Написать в Instagram',
                    'url'   => 'https://instagram.com/' . $value,
                    'icon'  => 'si-instagram',
                    'class' => 'text-pink-500',
                ];
                break;

            // можно добавить другие кейсы: viber, instagram, и т.п.

            default:
                // Если неизвестный тип, возможно, просто текст без ссылки
                $buttons[] = [
                    'label' => 'Контакт: ' . $value,
                    'url'   => '#', // или пустая ссылка
                    'icon'  => 'heroicon-s-phone',
                ];
                break;
        }

        return $buttons;
    }
}
