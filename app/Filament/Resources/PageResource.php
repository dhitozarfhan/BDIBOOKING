<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('admin_id'),
                    TextInput::make('slug'),
                    DateTimePicker::make('time_stamp')->required(),
                ])->columns(3),
                Section::make('Indonesia')->schema([
                    TextInput::make('id_title'),
                    Textarea::make('id_summary'),
                    RichEditor::make('id_content'),
                ]),
                Section::make('English')->schema([
                    TextInput::make('en_title'),
                    Textarea::make('en_summary'),
                    RichEditor::make('en_content'),
                ]),
                Section::make('Toggle')->schema([
                    Toggle::make('is_active')->default(false),
                    Toggle::make('enable_comment')->default(false),
                    Toggle::make('auto_accept_comment')->default(false),
                    Toggle::make('email_notification_comment')->default(false),
                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_id'),
                TextColumn::make('admin_id'),
                TextColumn::make('time_stamp'),
                TextColumn::make('id_title'),
                ToggleColumn::make('is_active'),
                ToggleColumn::make('enable_comment'),
                ToggleColumn::make('auto_accept_comment'),
                ToggleColumn::make('email_notification_comment'),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
