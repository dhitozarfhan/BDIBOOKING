<?php

namespace App\Filament\Forms\Components;

use App\Models\Classification;
use App\Models\Location;
use App\Models\Folder;
use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TreeSelect extends Select
{
    protected string $titleAttribute = 'code_name';
    protected Model | Closure | string | null $model = null;
    protected int | Closure | null $depth = null;
    protected bool $restrictDepthSelection = false;
    
    public static function make(string $name): static
    {
        $static = parent::make($name);
        
        // Set default configuration for tree select
        $static->searchable()
            ->preload()
            ->native(false);
            
        return $static;
    }
    
    public function titleAttribute(string $attribute): static
    {
        $this->titleAttribute = $attribute;
        return $this;
    }
    
    public function model(Model | Closure | string | null $model = null): static
    {
        $this->model = $model;
        return $this;
    }
    
    public function depth(int | Closure | null $depth = null): static
    {
        $this->depth = $depth;
        return $this;
    }
    
    public function restrictDepthSelection(bool | Closure $condition = true): static
    {
        $this->restrictDepthSelection = $condition;
        return $this;
    }
    
    protected function getDepth()
    {
        return $this->evaluate($this->depth);
    }
    
    protected function shouldRestrictDepthSelection()
    {
        return $this->evaluate($this->restrictDepthSelection);
    }
    
    protected function getModelClass(): string
    {
        if ($this->model) {
            $model = $this->evaluate($this->model);
            return is_string($model) ? $model : get_class($model);
        }
        
        // Auto-detect based on field name
        return match($this->getName()) {
            'location_id' => Location::class,
            'folder_id' => Folder::class,
            default => Classification::class, // Default to Classification for classification_id and other cases
        };
    }
    
    public function getTreeOptions(): array
    {
        $modelClass = $this->getModelClass();
        
        // Special handling for Folder model (show hierarchical information)
        if ($modelClass === Folder::class) {
            return $modelClass::with(['classification.ancestors', 'location.ancestors'])
                ->get(['id', 'classification_id', 'location_id', 'name'])
                ->mapWithKeys(fn (Model $item): array => [
                    $item->getKey() => $this->formatFolderTitle($item),
                ])
                ->all();
        }
        
        $buildTitle = function (Model $item): string {
            // Handle the code_name accessor properly
            if ($this->titleAttribute === 'code_name') {
                $title = $item->code . ' ' . $item->name;
            } else {
                $title = $item->getAttribute($this->titleAttribute);
            }
            
            $depth = $item->getAttribute('depth') ?? 0;
            
            $prefix = Str::repeat(
                string: '- ',
                times: $depth,
            );
            
            return trim("{$prefix} {$title}");
        };
        
        $query = $modelClass::query()->withDepth()->defaultOrder();
        
        $items = $query
            ->get(['id', 'code', 'name', '_lft', '_rgt', 'parent_id']) // Select necessary columns
            ->mapWithKeys(function (Model $item) use ($buildTitle) {
                return [$item->getKey() => $buildTitle($item)];
            })
            ->all();

        // Jika restrictDepthSelection aktif dan depth ditentukan, nonaktifkan opsi yang tidak sesuai
        if ($this->shouldRestrictDepthSelection() && $this->getDepth() !== null) {
            $allowedIds = $modelClass::query()
                ->withDepth()
                ->whereRaw('(select count(1) - 1 from ' . (new $modelClass)->getTable() . ' as d where ' . (new $modelClass)->getTable() . '._lft between d._lft and d._rgt) = ?', [$this->getDepth()])
                ->pluck('id')
                ->toArray();
                
            // Nonaktifkan opsi yang tidak sesuai
            $this->disableOptionWhen(fn (string $value): bool => !in_array((int) $value, $allowedIds));
        }

        return $items;
    }
    
    protected function formatFolderTitle(Model $folder): string
    {
        $classificationPath = $this->buildHierarchyPath($folder->classification, 'code');
        $locationPath = $this->buildHierarchyPath($folder->location, 'code');
        $description = $folder->description ?? '';
        
        // Format: Classification--Location--Description
        $parts = array_filter([$classificationPath, $locationPath, $description]);
        return implode('--', $parts) ?: "Folder #{$folder->id}";
    }
    
    protected function buildHierarchyPath(?Model $model, string $attribute): string
    {
        if (!$model) {
            return '';
        }
        
        // Get ancestors ordered from root to parent
        $ancestors = $model->ancestors()->defaultOrder()->get();
        
        // Build the hierarchical path
        $path = [];
        
        // Add ancestors codes
        foreach ($ancestors as $ancestor) {
            $path[] = $ancestor->{$attribute};
        }
        
        // Add the current item's code
        $path[] = $model->{$attribute};
        
        // Join with dots
        return implode('.', $path);
    }
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->options(fn () => $this->getTreeOptions());
    }
}