<?php

namespace App\Filament\Resources;

use App\Enums\ArticleType as EnumsArticleType;
use App\Enums\CategoryType;
use App\Enums\PermissionType;
use App\Filament\Resources\InformationResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InformationResource extends Resource
{

    use Translatable;

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    
    public static function getNavigationSort(): ?int
    {
        return 11;
    }
    
    public static function getModelLabel(): string
    {
        return __('Public Information');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false;
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Radio::make('parent_id', fn (Builder $query) => $query->orderBy('id'))
                            ->label(__('Public Information Type'))
                            ->options(Category::where('category_type_id', CategoryType::InformationType->value)->pluck('name', 'id'))
                            // ->default(fn (Get $get) => $get('parent_id') ?? Category::where('category_type_id', CategoryType::InformationType->value)->first()?->id)
                            ->inline()
                            ->inlineLabel(false)
                            ->required()
                            ->hidden(fn (string $operation): bool => $operation === 'edit')
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
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory(config('services.disk.article.content'))
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                    
                        Forms\Components\Section::make()
                            ->columns(1)
                            ->schema([

                                Forms\Components\Select::make('category_id')
                                    ->label(__('Category'))
                                    ->options(
                                        //buatkan group select kategori dari parent dan child
                                        function(Get $get, $operation){
                                            if($operation === 'edit'){
                                                $record = static::getModel()::find($get('id'));
                                                $category = Category::where('id', $record->category_id)->first();
                                                return Category::where('category_type_id', CategoryType::Information->value)->where('parent_id', $category->parent_id)
                                                    ->orderBy('sort')->pluck('name', 'id');
                                            }
                                            else {
                                                return Category::where('category_type_id', CategoryType::Information->value)->where('parent_id', $get('parent_id'))
                                                    ->orderBy('sort')->pluck('name', 'id');
                                            }
                                        }
                                    )
                                    ->required(),
                        
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('Is Active ?'))
                                    ->default(true)
                                    ->inline(false),

                                //year
                                Forms\Components\TextInput::make('year')
                                    ->label(__('Year'))
                                    ->numeric()
                                    ->maxValue(date('Y') + 1)
                                    ->minValue(2000)
                                    ->maxLength(4),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('Published At'))
                                    ->seconds(false)
                                    // ->minutesStep(15)
                                    ->default(now())
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d F Y H:i')
                                
                            ]),

                        Forms\Components\Section::make(__('Material Files').' PDF, WORD, SPREASHEET, PRESENTATION, ZIP, MP4')->schema([
                            Forms\Components\FileUpload::make('files')->label('')
                            ->directory(config('services.disk.lms.material'))
                            ->storeFileNamesIn('original_files')
                            // ->downloadable()->openable()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.oasis.opendocument.presentation',
                                'application/vnd.oasis.opendocument.spreadsheet',
                                'application/vnd.oasis.opendocument.text',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip',
                                'application/x-zip-compressed',
                                'audio/mpeg',
                                'video/mpeg',
                                'video/mp4'
                            ])
                            ->maxSize(10240)
                            ->multiple()->reorderable(),
                        ])->columns(1)
                    ])
                    ->columnSpan(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'asc')
            ->groups([
                Group::make('category_id')->label(__('Public Information Type'))->titlePrefixedWithLabel(false)->getTitleFromRecordUsing(fn (Article $record): string => $record->category->name)->collapsible()
            ])
            ->defaultGroup('category_id')
            ->columns([
                TextColumn::make('title')->label(__('Title'))->wrap()->searchable(),
                TextColumn::make('category.name')->label(__('Category'))->searchable(),
                TextColumn::make('published_at')->label(__('Published At'))->dateTime('d F Y H:i'),
                TextColumn::make('hit')->label(__('View Count')),
                ToggleColumn::make('is_active')->label(__('Is Active ?'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInformation::route('/'),
            'create' => Pages\CreateInformation::route('/create'),
            'edit' => Pages\EditInformation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('article_type_id', EnumsArticleType::Information->value);
    }
}
