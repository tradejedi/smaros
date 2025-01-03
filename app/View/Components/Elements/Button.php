<?php

namespace App\View\Components\Elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;


class Button extends Component
{
    /**
     * Тип кнопки (primary, secondary, warning, danger, icon, loading, minimal).
     * @var string
     */
    public string $type;

    /**
     * Размер кнопки (sm, md, lg).
     * @var string
     */
    public string $size;

    /**
     * Текст кнопки.
     * @var string
     */
    public string $text;

    /**
     * Тип элемента (ссылка, кнопка, input).
     * @var string
     */
    public string $elementType;

    /**
     * Дополнительные классы для кнопки.
     * @var string|null
     */
    public ?string $extraClasses;

    /**
     * Дополнительные атрибуты для кнопки.
     * @var array
     */
    public $attributes;

    /**
     * Иконка
     * @var string|null
     */
    public ?string $icon;

    /**
     * Создание нового компонента.
     * @param string $type
     * @param string $size
     * @param string $text
     * @param string $elementType
     * @param ?string $icon
     * @param string|null $extraClasses
     * @param array $attributes
     */
    public function __construct(
        string $type = 'primary',
        string $size = 'md',
        string $text = '',
        string $elementType = 'a',
        ?string $icon = 'si-2k',
        ?string $extraClasses = null,
        array $attributes = []
    ) {
        $this->type = $type;
        $this->size = $size;
        $this->text = $text;
        $this->elementType = $elementType;
        $this->extraClasses = $extraClasses;
        $this->icon = $icon;
    }

    /**
     * Получить шаблон компонента.
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.elements.button');
    }

    /**
     * Генерация классов для кнопки.
     * @return string
     */
    public function classes(): string
    {
        $baseClasses = 'px-4 py-2 rounded-xl focus:outline-none focus:ring';

        $typeClasses = match ($this->type) {
            'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-300',
            'secondary' => 'bg-gray-500 text-white hover:bg-gray-300 focus:ring-gray-400',
            'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-300',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
            'icon' => 'p-2 bg-gray-100 text-gray-500 hover:bg-gray-200 focus:ring-gray-300',
            'loading' => 'bg-blue-600 text-white flex items-center justify-center',
            'minimal' => 'text-blue-600 hover:underline',
            default => '',
        };

        $sizeClasses = match ($this->size) {
            'sm' => 'px-2 py-1 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-6 py-3 text-lg',
            default => '',
        };
        return trim("$baseClasses $typeClasses $sizeClasses {$this->extraClasses}");
    }
}
