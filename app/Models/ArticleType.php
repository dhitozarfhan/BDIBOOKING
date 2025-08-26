<?php

namespace App\Models;

use App\Enums\ArticleType as EnumsArticleType;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    public function getTranslationAttribute(): string
    {
        return $this->id == EnumsArticleType::News->value ? 'News' : (
                $this->id == EnumsArticleType::Gallery->value ? 'Gallery' : (
                    $this->id == EnumsArticleType::Page->value ? 'Page' : 'Public Information'
                )
            );
    }

    public function getNameTranslationAttribute(): string
    {
        return match($this->id) {
            EnumsArticleType::News->value => __('News'),
            EnumsArticleType::Gallery->value => __('Gallery'),
            EnumsArticleType::Page->value => __('Page'),
            EnumsArticleType::Information->value => __('Public Information'),
            default => __('Article'),
        };
    }
}
