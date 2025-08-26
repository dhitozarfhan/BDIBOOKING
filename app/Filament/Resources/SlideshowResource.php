<?php

namespace App\Filament\Resources;

use App\Enums\ArticleType;
use App\Enums\LinkType as EnumsLinkType;
use App\Filament\Resources\SlideshowResource\Pages;
use App\Models\Article;
use App\Models\LinkType;
use App\Models\Slideshow;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SlideshowResource extends Resource
{
    use Translatable;

    protected static ?string $model = Slideshow::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function getModelLabel(): string
    {
        return __('Slideshow');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('image')
                            ->label(__('Image'))
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->directory(config('services.disk.slideshow.image')),
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->maxLength(255),
                        Textarea::make('description')->label(__('Description')),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([                        
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
                                    if ($get('link_type_id') == EnumsLinkType::External->value && !filter_var($value, FILTER_VALIDATE_URL)) {
                                        $fail(__('URL is not valid'));
                                    }
                                }
                            ]),

                        Toggle::make('target_blank')
                            ->label(__('Open in new tab ?'))
                            ->default(false)
                            ->inline(false),

                        Toggle::make('is_active')
                            ->label(__('Is Active ?'))
                            ->default(true)
                            ->inline(false),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('image')->label(__('Image'))
                    ->square()
                    ->size(50)
                    ->default(asset('images/no-image.jpg')),
                TextColumn::make('title')->label(__('Title'))
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('title->' . app()->getLocale(), $direction))
                    ->wrap()->searchable(),
                TextColumn::make('description')->label(__('Description'))
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('description->' . app()->getLocale(), $direction))
                    ->wrap()->searchable(),
                TextColumn::make('linkType.name')->label(__('Link Type'))->sortable(),
                ToggleColumn::make('target_blank')->label(__('Open in new tab ?'))->sortable(),
                ToggleColumn::make('is_active')->label(__('Is Active ?'))->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlideshows::route('/'),
            'create' => Pages\CreateSlideshow::route('/create'),
            'edit' => Pages\EditSlideshow::route('/{record}/edit'),
        ];
    }
}
