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
                string: '- ',
                times: $depth,
            );
            
            return trim("{$prefix} {$title}");
        };
        
        $query = Segment::query()->withDepth()->defaultOrder();
        
        $items = $query
            ->get(['id', 'code', 'name', '_lft', '_rgt', 'parent_id']) // Select necessary columns
            ->mapWithKeys(function (Model $item) use ($buildTitle) {
                return [$item->getKey() => $buildTitle($item)];
            })
            ->all();

        // Jika restrictDepthSelection aktif dan depth ditentukan, nonaktifkan opsi yang tidak sesuai
        if ($this->shouldRestrictDepthSelection() && $this->getDepth() !== null) {
            $allowedIds = Segment::query()
                ->withDepth()
                ->whereRaw('(select count(1) - 1 from ' . (new Segment)->getTable() . ' as d where ' . (new Segment)->getTable() . '._lft between d._lft and d._rgt) = ?', [$this->getDepth()])
                ->pluck('id')
                ->toArray();
                
            // Nonaktifkan opsi yang tidak sesuai
            $this->disableOptionWhen(fn (string $value): bool => !in_array((int) $value, $allowedIds));
        }

        return $items;
    }
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->options(fn () => $this->getTreeOptions());
    }
}