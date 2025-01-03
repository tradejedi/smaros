<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:import-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import characteristics from a JSON file';

    public function handle()
    {
        $characteristics = Storage::disk('seeders')->json('option.json');

        // Обработка вариантов (variants)
        foreach ($characteristics['variants'] as $name => $values) {
            if (is_array($values) && count($values) == 2 && is_numeric($values[0]) && is_numeric($values[1])) {
                // Это диапазон
                $this->createRangeAttribute($name, $values);
            } elseif (is_array($values)) {
                // Это список
                $this->createListAttribute($name, $values);
            }
        }

        // Обработка одиночных характеристик (labels, price)
        $labels = Arr::dot($characteristics['labels']);
        foreach ($labels as $key => $item) {
            $key = explode('.', $key)[0];
            $this->createSingleAttribute($item, $key);
        }

        $prices = Arr::dot($characteristics['price']);
        foreach ($prices as $item) {
            $this->createSingleAttribute($item, 'price');
        }

        $this->info('Attributes have been successfully imported!');
    }

    protected function createSingleAttribute(string $name, string $key): void
    {
        Attribute::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'group' => $key,
            'type' => 'boolean', // Тип "одиночная характеристика"
        ]);
    }

    protected function createRangeAttribute($name, $values): void
    {
        $characteristic = Attribute::create([
            'name' => $name,
            'group' => 'variants',
            'slug' => Str::slug($name),
            'type' => 'range',
            'min_value' => $values[0],
            'max_value' => $values[1],
        ]);

    }

    protected function createListAttribute($name, $values): void
    {
        // Создаем характеристику типа "список"
        $characteristic = Attribute::create([
            'name' => $name,
            'group' => 'variants',
            'slug' => Str::slug($name),
            'type' => 'list',
        ]);

        // Добавляем каждое значение списка в `characteristic_predefined_values`
        foreach ($values as $value) {
            AttributeValue::create([
                'attribute_id' => $characteristic->id,
                'value' => $value,
            ]);
        }
    }
}
