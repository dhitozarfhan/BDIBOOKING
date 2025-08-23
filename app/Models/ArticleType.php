<?php

namespace App\Models;

use App\Enums\ArticleType as EnumsArticleType;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    public function getTranslationAttribute(): string
    {
        return $this->id == EnumsArticleType::Blog->value ? 'Blog' : (
            $this->id == EnumsArticleType::News->value ? 'News' : (
                $this->id == EnumsArticleType::Gallery->value ? 'Gallery' : (
                    $this->id == EnumsArticleType::Page->value ? 'Page' : 'Public Information'
                )
            )
        );
    }
}
