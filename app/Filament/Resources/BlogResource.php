<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('category_id')->relationship('category', 'category_id')->required(),
                    DateTimePicker::make('time_stamp')->required(),
                    Toggle::make('is_active')->default(true)->label('Apakah Aktif?')
                ])->columns(3),
                Section::make()->schema([
                    FileUpload::make('image')->image()->imageEditor()->directory('blog/thumbnails')
                ]),
                Section::make('Indonesia')->schema([
                    TextInput::make('id_title')->required()->label('Judul'),
                    Textarea::make('id_summary')->required()->label('Ringkasan'),
                    RichEditor::make('id_content')->required()->label('Konten'),
                ]),
                Section::make('English')->schema([
                    TextInput::make('en_title')->label('Title'),
                    Textarea::make('en_summary')->label('Summary'),
                    RichEditor::make('en_content')->label('Content'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('time_stamp', 'desc')
            ->columns([
                ImageColumn::make('image')->label('Thumbnail'),
                TextColumn::make('id_title')->label('Judul Berita')->limit(50),
                TextColumn::make('time_stamp')->label('Tanggal Pembuatan')->dateTime('d F Y'),
                TextColumn::make('hit')->label('Views'),
                ToggleColumn::make('is_active')->label('Aktif')
            ])
            ->filters([
                //
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
