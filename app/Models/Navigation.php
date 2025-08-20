<?php

namespace App\Models;

use App\Enums\LinkType as EnumsLinkType;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;
use Illuminate\Support\Str;

class Navigation extends Model
{
    //
    use NodeTrait;
    use InteractsWithTree;

    protected $fillable = ['navigation_type_id', 'link_type_id', 'article_id', 'name', 'path', 'target_blank'];

    protected $casts = [
        'name' => 'json',
    ];

    public function getLinkAttribute(): string
    {
        return $this->link_type_id == EnumsLinkType::Article->value ? route('articles.show', ['slug' => Str::kebab($this->article->title).'-'.$this->article_id, 'article_type' => $this->article->articleType->slug]) : (
            $this->link_type_id == EnumsLinkType::Internal->value ? url($this->path) :
            ($this->link_type_id == EnumsLinkType::Empty->value ? '#' : $this->path)
        );
    }

    public function article() {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function linkType() {
        return $this->belongsTo(LinkType::class, 'link_type_id');
    }

    // public function linkType(): string
    // {
    //     return match ($this->link_type_id) {
    //         EnumsLinkType::Article->value => 'article',
    //         EnumsLinkType::Internal->value => 'internal',
    //         EnumsLinkType::Empty->value => 'empty',
    //         default => 'external',
    //     };
    // }

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
