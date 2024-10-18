<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SlideshowResource\Pages;
use App\Filament\Resources\SlideshowResource\RelationManagers;
use App\Models\Slideshow;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SlideshowResource extends Resource
{
    protected static ?string $model = Slideshow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')->label('Slide Image')->image()->directory('slideshows'),
                TextInput::make('en_title')->label('Title'),
                TextInput::make('id_title')->label('Judul'),
                Textarea::make('en_description')->label('Description'),
                Textarea::make('id_description')->label('Deskripsi'),
                TextInput::make('path')->label('Link Path')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Slide Image'),
                TextColumn::make('en_title')->label('Title'),
                TextColumn::make('id_title')->label('Judul'),
                TextColumn::make('en_description')->label('Description'),
                TextColumn::make('id_description')->label('Deskripsi'),
                TextColumn::make('path')->label('Link Path'),
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
            'index' => Pages\ListSlideshows::route('/'),
            'create' => Pages\CreateSlideshow::route('/create'),
            'edit' => Pages\EditSlideshow::route('/{record}/edit'),
        ];
    }
}
