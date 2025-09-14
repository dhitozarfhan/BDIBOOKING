<?php

namespace App\Filament\Filters;

use App\Models\Folder;
use Closure;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FolderTreeSelectFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->options($this->getTreeOptions());
    }
    
    protected function getTreeOptions(): array
    {
        // For folders, we'll just list them as they are (not hierarchical)
        // But we'll format them nicely
        return Folder::query()
            ->get(['id'])
            ->mapWithKeys(fn (Model $item): array => [
                $item->getKey() => "Folder #{$item->id}",
            ])
            ->all();
    }
}