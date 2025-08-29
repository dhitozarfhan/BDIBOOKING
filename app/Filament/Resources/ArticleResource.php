<?php

namespace App\Filament\Resources;

use App\Enums\ArticleType as EnumsArticleType;
use App\Enums\CategoryType;
use App\Enums\PermissionType;
use App\Enums\RoleDefault;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers\ImagesRelationManager;
use App\Models\Article;
use App\Models\ArticleType;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ArticleResource extends Resource
{

    use Translatable;

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::News->value) || Auth::user()->hasPermissionTo(PermissionType::Gallery->value) || Auth::user()->hasPermissionTo(PermissionType::Page->value) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::News->value) || Auth::user()->hasPermissionTo(PermissionType::Gallery->value) || Auth::user()->hasPermissionTo(PermissionType::Page->value) ?? false;
    }

    public static function canView(Model $record): bool
    {
        return (Auth::user()->hasPermissionTo(PermissionType::News->value) && $record->article_type_id == EnumsArticleType::News->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Gallery->value) && $record->article_type_id == EnumsArticleType::Gallery->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Page->value) && $record->article_type_id == EnumsArticleType::Page->value) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return (Auth::user()->hasPermissionTo(PermissionType::News->value) && $record->article_type_id == EnumsArticleType::News->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Gallery->value) && $record->article_type_id == EnumsArticleType::Gallery->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Page->value) && $record->article_type_id == EnumsArticleType::Page->value) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return (Auth::user()->hasPermissionTo(PermissionType::News->value) && $record->article_type_id == EnumsArticleType::News->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Gallery->value) && $record->article_type_id == EnumsArticleType::Gallery->value) ||
               (Auth::user()->hasPermissionTo(PermissionType::Page->value) && $record->article_type_id == EnumsArticleType::Page->value) ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::News->value) || Auth::user()->hasPermissionTo(PermissionType::Gallery->value) || Auth::user()->hasPermissionTo(PermissionType::Page->value) ?? false;
    }
    
    public static function getModelLabel(): string
    {
        return __('Article');
    }
    
    public static function form(Form $form): Form
    {
        $typeIds = [];
        if(Auth::user()->hasPermissionTo(PermissionType::News->value)) {
            $typeIds[] = EnumsArticleType::News->value;
        }
        if(Auth::user()->hasPermissionTo(PermissionType::Gallery->value)) {
            $typeIds[] = EnumsArticleType::Gallery->value;
        }
        if(Auth::user()->hasPermissionTo(PermissionType::Page->value)) {
            $typeIds[] = EnumsArticleType::Page->value;
        }
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpan(2)
                    ->schema([

                        Forms\Components\Grid::make()
                        ->columns(5)
                        ->schema([
                            Forms\Components\Radio::make('article_type_id', fn (Builder $query) => $query->orderBy('id'))
                                ->columnSpan(2)
                                ->label(__('Article Type'))
                                ->options(ArticleType::whereIn('id', $typeIds)->pluck('name', 'id'))
                                ->inline()
                                ->inlineLabel(false)
                                ->required()
                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                ->live(),

                            Forms\Components\Toggle::make('is_active')
                                ->columnSpan(1)
                                ->label(__('Is Active ?'))
                                ->default(true)
                                ->inline(false),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->columnSpan(2)
                                ->label(__('Published At'))
                                ->seconds(false)
                                // ->minutesStep(15)
                                ->default(now())
                                ->required()
                                ->native(false)
                                ->displayFormat('d F Y H:i')
                        ]),

                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('summary')
                            ->label(__('Summary'))
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->label(__('Content'))
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory(config('services.disk.article.content'))
                    ]),
                Forms\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([

                        Forms\Components\FileUpload::make('image')
                                ->label(__('Image'))
                                ->image()
                                ->imageEditor()
                                ->directory(config('services.disk.article.image'))
                                ->required(fn (Get $get): bool => in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Gallery->value])),

                        Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value) ?
                            Forms\Components\Select::make('author_id')
                                ->label(__('Author'))
                                ->default(Auth::user()->id)
                                ->relationship('author', 'name', fn (Builder $query) => $query->where('is_active', true)->orderBy('name'))
                                ->required() : 
                            //set hidden value to Auth::user()->id
                            Forms\Components\Hidden::make('author_id')
                                ->default(Auth::user()->id),

                        Forms\Components\Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name', fn (Builder $query) => $query->where('category_type_id', CategoryType::Article->value)->orderBy('sort'))
                            ->hidden(fn (Get $get): bool => !in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Gallery->value])  || !$get('article_type_id') )
                            ->required(fn (Get $get): bool => in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Gallery->value])),
                
                        Forms\Components\CheckboxList::make('tags')
                            ->label(__('Tags'))
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ])
                            ->helperText(__('Select tags for this article'))
                            ->hidden(fn (Get $get): bool => !in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Gallery->value]) || !$get('article_type_id'))
                            ->required(fn (Get $get): bool => in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Gallery->value]))
                            ->relationship('tags')
                            ->options(Tag::where('is_active', true)->orderBy('name->en')
                                ->get()
                                ->pluck('name', 'id')),
                        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')->label(__('Image'))
                    ->square()
                    ->size(50)
                    ->default(asset('images/no-image.jpg')),
                TextColumn::make('title')->label(__('Title'))
                ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('title->' . app()->getLocale(), $direction))
                ->wrap()->searchable(),
                TextColumn::make('articleType.name')->label(__('Type'))->sortable(),
                TextColumn::make('category.name')->label(__('Category'))
                ->sortable(query: fn (Builder $query, string $direction) => $query->join('categories', 'categories.id', '=', 'articles.category_id')->orderBy('categories.name->' . app()->getLocale(), $direction))
                ->searchable(),
                TextColumn::make('published_at')->label(__('Published At'))->dateTime('d F Y H:i')->sortable(),
                TextColumn::make('author.name')->label(__('Author'))->sortable()->searchable(),
                TextColumn::make('hit')->label(__('View Count'))->sortable(),
                ToggleColumn::make('is_active')->label(__('Is Active ?'))->sortable(),
                TextColumn::make('tags.name')
                    ->label(__('Tags'))
                    ->searchable()
                    ->sortable(
                        query: fn (Builder $query, string $direction) => $query->join('article_tag', 'article_tag.article_id', '=', 'articles.id')
                            ->join('tags', 'tags.id', '=', 'article_tag.tag_id')
                            ->orderBy('tags.name->' . app()->getLocale(), $direction)
                    )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('Category'))
                    ->options(fn () => \App\Models\Category::where('category_type_id', CategoryType::Article->value)->orderBy('sort')->pluck('name', 'id'))
                    ->placeholder(__('All Categories'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('author_id')
                    ->label(__('Author'))
                    ->options(
                        \App\Models\Employee::whereIn('id', Article::pluck('author_id'))->orderBy('name')->pluck('name', 'id')
                    )
                    ->placeholder(__('All Authors'))
                    ->searchable(),
                ], layout: FiltersLayout::AboveContent)
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
