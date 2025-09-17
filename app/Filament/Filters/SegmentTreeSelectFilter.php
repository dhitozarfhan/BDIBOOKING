<?php

namespace App\Filament\Filters;

use App\Models\Segment;
use Closure;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SegmentTreeSelectFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->options($this->getTreeOptions());
    }
    
    protected function getTreeOptions(): array
    {
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
        
        return Segment::query()
            ->withDepth()
            ->defaultOrder()
            ->get(['id', 'code', 'name', '_lft', '_rgt', 'parent_id']) // Select necessary columns
            ->mapWithKeys(fn (Model $item): array => [
                $item->getKey() => $buildTitle($item),
            ])
            ->all();
    }
}