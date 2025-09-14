<?php

namespace App\Filament\Forms\Components;

use App\Models\Segment;
use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SegmentTreeSelect extends Select
{
    protected string $titleAttribute = 'code_name';
    
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
    
    public function getTreeOptions(): array
    {
        $buildTitle = function (Model $item): string {
            // Handle the code_name accessor properly
            if ($this->titleAttribute === 'code_name') {
                $title = $item->code . ' ' . $item->name;
            } else {
                $title = $item->getAttribute($this->titleAttribute);
            }
            
            $depth = $item->getAttribute('depth') ?? 0;
            
            $prefix = Str::repeat(
                string: '— ',
                times: $depth,
            );
            
            return trim("{$prefix} {$title}");
        };
        
        return Segment::query()
            ->withDepth()
            ->defaultOrder()
            ->get(['id', 'code', 'name', '_lft', '_rgt', 'parent_id']) // Select necessary columns
            ->mapWithKeys(fn (Model $item): array => [
                $item->getKey() => $buildTitle($item),
            ])
            ->all();
    }
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->options(fn () => $this->getTreeOptions());
    }
}