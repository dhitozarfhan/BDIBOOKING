<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformationResource\Pages;
use App\Filament\Resources\InformationResource\RelationManagers;
use App\Models\Information;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ramsey\Uuid\Type\Integer;

class InformationResource extends Resource
{
    protected static ?string $model = Information::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('category_id')->relationship('category', 'category_id')->required(),
                    DateTimePicker::make('time_stamp')->required(),
                    TextInput::make('year')->numeric(),
                ])->columns(3),
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
                Section::make()->schema([
                    FileUpload::make('file')->appendFiles()->previewable()->acceptedFileTypes(['application/pdf'])->label('Upload File (opsional)')->directory('information/file'),
                    TextInput::make('sort')->numeric()->required(),
                    Toggle::make('is_active')->default(true)
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('information_id')->label('ID'),
                TextColumn::make('category_id')->label('Category'),
                TextColumn::make('time_stamp')->label('Timestamp'),
                TextColumn::make('id_title')->label('Judul'),
                TextColumn::make('sort')->label('Sort'),
                TextColumn::make('is_active')->label('Aktif'),
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
            'index' => Pages\ListInformation::route('/'),
            'create' => Pages\CreateInformation::route('/create'),
            'edit' => Pages\EditInformation::route('/{record}/edit'),
        ];
    }
}
