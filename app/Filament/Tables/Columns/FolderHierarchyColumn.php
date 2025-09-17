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
            $folder = $record->{$relationshipName};
            
            if (!$folder) {
                return '';
            }
            
            // Load classification and location with ancestors if not already loaded
            $folder->loadMissing(['classification.ancestors', 'location.ancestors']);
            
            // Build classification path
            $classificationPath = $this->buildHierarchyPath($folder->classification, 'code');
            
            // Build location path
            $locationPath = $this->buildHierarchyPath($folder->location, 'code');
            
            // Format: classification - location
            if ($classificationPath && $locationPath) {
                return "{$classificationPath} - {$locationPath}";
            } elseif ($classificationPath) {
                return $classificationPath;
            } elseif ($locationPath) {
                return $locationPath;
            } else {
                return "Folder #{$folder->id}";
            }
        });
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
}