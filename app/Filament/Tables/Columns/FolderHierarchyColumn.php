<?php

namespace App\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FolderHierarchyColumn extends TextColumn
{
    protected string | Closure | null $codeAttribute = 'id';
    
    public function codeAttribute(string | Closure | null $attribute): static
    {
        $this->codeAttribute = $attribute;
        return $this;
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->formatStateUsing(function (Model $record) {
            // Get the relationship name from the column name
            $relationshipName = Str::before($this->getName(), '.');
            
            // Get the related model
            $relatedModel = $record->{$relationshipName};
            
            if (!$relatedModel) {
                return '';
            }
            
            // For folder, we'll display the ID directly since it doesn't have a hierarchical structure
            // like classification or location
            return $relatedModel->id;
        });
    }
}