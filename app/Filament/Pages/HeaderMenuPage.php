<?php

namespace App\Filament\Pages;

use App\Enums\ArticleType;
use App\Enums\LinkType as EnumsLinkType;
use App\Enums\NavigationType as EnumsNavigationType;
use App\Models\Article;
use App\Models\LinkType;
use App\Models\Navigation;
use Closure;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class HeaderMenuPage extends TreePage
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public function getTitle(): string
    {
        return __('Header Navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('Header Navigation');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('Menu');
    }
    
    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public static function getModel(): string|QueryBuilder
    {
        return Navigation::scoped(['navigation_type_id' => EnumsNavigationType::Header->value]);
    }

    public static function getCreateForm(): array
    {
        $schemas = [];
        $locales = config('services.locale.available');
        foreach ($locales as $locale) {
            $schemas[] = TextInput::make("name.{$locale}")
                ->label(__('Name') . ' (' . strtoupper($locale) . ')')
                ->maxLength(255)
                ->required();
        }
        return array_merge($schemas, [
            Radio::make('link_type_id')
                ->inline()
                ->inlineLabel(false)
                ->label(__('Link Type'))
                ->options(LinkType::all()->pluck('name', 'id'))
                ->required()
                ->live(),
            Select::make('article_id')
                ->label(__('Article'))
                ->visible(fn (Get $get): bool => $get('link_type_id') == EnumsLinkType::Article->value)
                ->getSearchResultsUsing(fn (string $search): array => Article::selectRaw('CONCAT(title->>\'id\', \' <span style="--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);" class="gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1.5 min-w-[theme(spacing.5)] py-0.5 tracking-tight fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-info w-max">\', article_types.name, \'</span>\') AS label, articles.id')
                    ->join('article_types', 'articles.article_type_id', '=', 'article_types.id')
                    ->where('title', 'ILIKE', "%{$search}%")
                    ->whereIn('article_type_id', [
                        ArticleType::Blog->value,
                        ArticleType::News->value,
                        ArticleType::Gallery->value,
                        ArticleType::Page->value,
                        ArticleType::Information->value
                    ])
                    ->orderBy('title->id')
                    ->where('is_active', true)->limit(15)->pluck('label', 'id')->toArray()
                )
                ->getOptionLabelUsing(fn ($value): string => Article::find($value)?->title ?? '')
                ->allowHtml()
                ->searchable()
                ->requiredIf('link_type_id', EnumsLinkType::Article->value)
                ->validationMessages([
                    'required_if' => __('Article is required'),
                ]),

            TextInput::make('path')
                ->label(__('Link'))
                ->visible(fn (Get $get): bool => $get('link_type_id') == EnumsLinkType::Internal->value || $get('link_type_id') == EnumsLinkType::External->value)
                ->maxLength(255)
                ->requiredIf('link_type_id', [EnumsLinkType::Internal->value, EnumsLinkType::External->value])
                ->validationMessages([
                    'required_if' => __('Path is required'),
                ])
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        //check fail only if link_type_id is External and not valid url
                        if ($get('link_type_id') == EnumsLinkType::External->value && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $fail(__('URL is not valid'));
                        }
                    }
                ]),

            Toggle::make('target_blank')
                ->label(__('Open in new tab ?'))
                ->default(false)
                ->inline(false)

        ]);
    }

    public static function getEditForm(): array
    {
        return static::getCreateForm();
    }

    public static function getInfolistColumns(): array
    {
        return [
            //
        ];
    }
}
