<?php

namespace App\Filament\Filters;

use App\Models\Classification;
use App\Models\Location;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TreeSelectFilter extends SelectFilter
{
    protected Model | Closure | string | null $model = null;
    
    public function relationshipModel(Model | Closure | string | null $model = null): static
    {
        $this->model = $model;
        return $this;
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
            default => Classification::class, // Default to Classification for classification_id and other cases
        };
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->options($this->getTreeOptions());
    }
    
    protected function getTreeOptions(): array
    {
        $modelClass = $this->getModelClass();
        
        $buildTitle = function (Model $item): string {
            // Handle the code_name accessor properly
            $title = $item->code . ' ' . $item->name;
            
            $depth = $item->getAttribute('depth') ?? 0;
            
            $prefix = Str::repeat(
                string: '—',
                times: $depth,
            );
            
            return trim("{$prefix} {$title}");
        };
        
        return $modelClass::query()
            ->withDepth()
            ->defaultOrder()
            ->get(['id', 'code', 'name', '_lft', '_rgt', 'parent_id']) // Select necessary columns
            ->mapWithKeys(fn (Model $item): array => [
                $item->getKey() => $buildTitle($item),
            ])
            ->all();
    }
}