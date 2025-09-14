<?php

namespace App\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SegmentHierarchyColumn extends TextColumn
{
    protected string | Closure | null $codeAttribute = 'code';
    
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
                
            // Get ancestors ordered from root to parent
            $ancestors = $relatedModel->ancestors()->defaultOrder()->get();
            
            // Get the code attribute name
            $codeAttribute = $this->evaluate($this->codeAttribute);
            
            // Build the hierarchical path
            $path = [];
            
            // Add ancestors codes
            foreach ($ancestors as $ancestor) {
                $path[] = $ancestor->{$codeAttribute};
            }
            
            // Add the current item's code
            $path[] = $relatedModel->{$codeAttribute};
            
            // Join with dots
            return implode('.', $path);
        });
    }
}