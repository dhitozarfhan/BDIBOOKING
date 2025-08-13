<?php

namespace App\Models;

use App\Enums\LinkType;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;

class Navigation extends Model
{
    //
    use NodeTrait;
    use InteractsWithTree;

    protected $fillable = ['navigation_type_id', 'link_type_id', 'article_id', 'name', 'path', 'target_blank', 'is_active'];

    protected $casts = [
        'name' => 'json',
    ];

    public function getLinkAttribute(): string
    {
        return '';
        // return $this->link_type_id == LinkType::Article->value ?;
    }

    public function getScopeAttributes(): array
    {
        return ['navigation_type_id'];
    }

    public static function getTreeLabelAttribute() : string
    {
        return 'name_locale';
    }

    public function getNameLocaleAttribute(): string
    {
        return $this->name[app()->getLocale()] ?? '';
    }

    // public function getTreeCaption(): ?string
    // {
    //     return $this->link;
    // }
}
