<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

trait OptionTrait
{
    public Collection $options;
    public Collection $cities;
    public Collection $districts;
    public Collection $metros;
    public string $filePath;

    public function __construct()
    {
        $this->metros = collect([]);
    }

    public function setFileName(string $name): self
    {
        $this->filePath = "{$name}.json";
        return $this;
    }

    public function options(): self
    {
        $this->setFileName('option');
        $this->options = $this->getFileContent();
        return $this;
    }

    public function metros(): self
    {
        $this->setFileName('metro');
        $metros = $this->getFileContent();
        $this->metros = $this->transformMetros($metros);
        return $this;
    }

    public function cities(): self
    {
        if($this->metros->isEmpty()) {
            $this->metros();
        }

        $this->cities = $this->metros->keys();
        return $this;
    }

    public function districts(): self
    {
        $this->setFileName('district');
        $this->districts = collect($this->getFileContent());
        return $this;
    }

    private function transformMetros(Collection $metros): Collection
    {
        return $metros->mapWithKeys(function (array $item) {
            $lines = collect($item['lines'])
                ->flatMap(fn($line) => collect($line['stations'])->pluck('name'));

            return [$item['name'] => $lines];
        });
    }

    public function open(string $key): Collection
    {
        return $this->$key()->$key;
    }

    private function saveCitiesToFile(array $data): self
    {
        $data = Storage::json($this->source);
        $data = $this->transformMetros($data);
        $data = collect($data)->keys();

        Storage::put('seeders/city.json', $data);
        return $this;
    }

    private function getFileContent(): Collection
    {
        return collect(Storage::disk('seeders')->json($this->filePath));
    }
}
