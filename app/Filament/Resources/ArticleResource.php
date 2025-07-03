<?php

namespace App\Filament\Resources;

use App\Enums\ArticleType as EnumsArticleType;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\ArticleType;
use Filament\Forms;
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
use Illuminate\Support\Facades\Auth;

class ArticleResource extends Resource
{

    use Translatable;

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    public static function getModelLabel(): string
    {
        return __('Article');
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Radio::make('article_type_id', fn (Builder $query) => $query->orderBy('id'))
                            ->label(__('Article Type'))
                            ->options(ArticleType::pluck('name', 'id'))
                            ->inline()
                            ->inlineLabel(false)
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->live(),

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
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([

                        Forms\Components\Select::make('author_id')
                            ->label(__('Author'))
                            ->default(Auth::user()->id)
                            ->relationship('author', 'name', fn (Builder $query) => $query->where('is_active', true)->orderBy('name'))
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name', fn (Builder $query) => $query->orderBy('sort'))
                            ->hidden(fn (Get $get): bool => !in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Blog->value, EnumsArticleType::Gallery->value])  || !$get('article_type_id') )
                            ->required(fn (Get $get): bool => in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Blog->value, EnumsArticleType::Gallery->value])),
                
                        Forms\Components\FileUpload::make('image')
                            ->label(__('Image'))
                            ->image()
                            ->imageEditor()
                            ->directory(config('services.disk.article.image'))
                            ->preserveFilenames()
                            ->columnSpanFull()
                            ->required(fn (Get $get): bool => in_array($get('article_type_id'), [EnumsArticleType::News->value, EnumsArticleType::Blog->value, EnumsArticleType::Gallery->value])),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Is Active ?'))
                            ->default(true)
                            ->inline(false),

                        Forms\Components\DatePicker::make('published_at')
                            ->label(__('Published At'))
                            ->default(now())
                            ->required()
                            ->columnSpanFull()
                            ->native(false)
                            ->displayFormat('d F Y')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image')->label(__('Image'))
                    ->square()
                    ->size(50)
                    ->default(asset('images/no-image.jpg')),
                TextColumn::make('title')->label(__('Title'))->sortable()->wrap()->searchable(),
                TextColumn::make('articleType.name')->label(__('Type'))->sortable(),
                TextColumn::make('category.name')->label(__('Category'))->sortable()->searchable(),
                TextColumn::make('created_at')->label(__('Created At'))->dateTime('d F Y')->sortable(),
                TextColumn::make('author.name')->label(__('Author'))->sortable()->searchable(),
                TextColumn::make('hit')->label(__('View Count'))->sortable(),
                ToggleColumn::make('is_active')->label(__('Is Active ?'))->sortable()
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
