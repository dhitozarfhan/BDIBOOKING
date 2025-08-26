<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use App\Models\EmployeeStatus;
use App\Models\Position;
use App\Models\Rank;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getModelLabel(): string
    {
        return __('Employee');
    }

    public static function form(Form $form): Form
    {
        // dd( array_map(static fn(PermissionType $permissionType) => $permissionType->value, PermissionType::cases()) );
        $schema = [
            Forms\Components\TextInput::make('username')->label(__('Username'))
            ->required()->maxLength(18)->unique(ignoreRecord:true)
        ];
        if(Auth::user()->hasPermissionTo(PermissionType::RolePermission->value)){
            $schema[] = Forms\Components\Select::make('roles')->label(__('Roles'))
            ->relationship('roles', 'name')->multiple()->preload()->required()
            // ->in(['1','2','3','4'])
            // ->in(Role::pluck('id')->toArray())
            ;
        }

        $schema = array_merge($schema, [
            Forms\Components\Radio::make('employee_status_id')->label(__('Employee Status'))
            ->options(EmployeeStatus::pluck('description', 'id'))
            ->inline()->inlineLabel(false)
            ->required()
            ->live(),

            Forms\Components\TextInput::make('nip')->label(__('NIP'))
            ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') )
            ->length(18)->requiredIf('employee_status_id', [1,2,3,4])
            ->validationMessages([
                'required_if' => 'NIP wajib diisi.',
            ]),

            Grid::make()->schema([

                Forms\Components\TextInput::make('name')->label(__('Name'))
                ->columnSpan(2)
                    ->required()
                    ->maxLength(80),
                Forms\Components\TextInput::make('title_pre')->label(__('Front Title'))
                    ->maxLength(30),
                Forms\Components\TextInput::make('title_post')->label(__('Back Title'))
                    ->maxLength(100),
            ])->columns(4),
        ]);

        if(Auth::user()->hasPermissionTo(PermissionType::Employee->value)){

            return $form->schema(array_merge($schema, [

                Forms\Components\TextInput::make('birth_place')->label(__('Birth Place'))->maxLength(50)->required(),
                Forms\Components\DatePicker::make('birth_date')->label(__('Birth Date'))->native(false)->displayFormat('j F Y')->required(),
                Forms\Components\Select::make('gender_id')->label(__('Gender'))->relationship('gender', 'type')->required(),
                Forms\Components\Select::make('religion_id')->label(__('Religion'))->relationship('religion', 'name', fn (Builder $query) => $query->orderBy('sort'))->required(),
                Forms\Components\Select::make('education_id')->label(__('Education'))->relationship('education', 'name', fn (Builder $query) => $query->orderBy('id'))->required(),
                Forms\Components\Select::make('rank_id')->label(__('Rank'))->relationship('rank', 'name', fn (Builder $query) => $query->orderBy('id'))
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') )
                ->requiredIf('employee_status_id', [1,2,3,4])
                ->validationMessages([
                    'required_if' => 'Pangkat wajib diisi.',
                ]),
                Forms\Components\DatePicker::make('tmt_rank')->label(__('TMT Rank'))->native(false)->displayFormat('j F Y')
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') )
                ->requiredIf('employee_status_id', [1,2,3,4])
                ->validationMessages([
                    'required_if' => 'TMT Pangkat wajib diisi.',
                ]),
                Forms\Components\Select::make('position_id')->label(__('Position'))->relationship('position', 'name', fn (Builder $query) => $query->orderBy('id'))
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') )
                ->requiredIf('employee_status_id', [1,2,3,4])
                ->validationMessages([
                    'required_if' => 'Jabatan wajib diisi.',
                ]),
                Forms\Components\DatePicker::make('tmt_position')->label(__('TMT Position'))->native(false)->displayFormat('j F Y')
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') ),
                Forms\Components\DatePicker::make('tmt_work')->label(__('TMT Work'))->native(false)->displayFormat('j F Y')
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') ),
                Forms\Components\DatePicker::make('tmt_pns')->label(__('TMT PNS'))->native(false)->displayFormat('j F Y')
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') ),
                Forms\Components\TextInput::make('karpeg_number')->label(__('Karpeg Number'))->maxLength(100)
                ->hidden(fn (Get $get): bool => in_array($get('employee_status_id'), [5, 6])  || !$get('employee_status_id') ),
                Forms\Components\TextInput::make('ktp_number')->label(__('KTP Number'))->maxLength(100),
                Forms\Components\TextInput::make('askes_number')->label(__('Askes Number'))->maxLength(100),
                Forms\Components\TextInput::make('npwp')->label(__('NPWP'))->maxLength(100),
                Forms\Components\Textarea::make('address')->label(__('Address')),
                Forms\Components\TextInput::make('phone')->label(__('Phone'))->maxLength(100)->numeric(),
                Forms\Components\TextInput::make('mobile')->label(__('Mobile'))->maxLength(100)->numeric(),
                Forms\Components\TextInput::make('email')->label(__('Email'))->maxLength(100)->required()->unique(ignoreRecord:true)->email(),
                // Forms\Components\TextInput::make('image')
                    // ->maxLength(100),
                // Forms\Components\TextInput::make('thumbnail')
                //     ->maxLength(100),
                Forms\Components\TextInput::make('password')->label(__('Password'))
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),

            ]));
        }
        else {
            return $form->schema(request()->route()->parameter('record') == Auth::user()->id ? $schema : array_merge($schema, [
                Forms\Components\TextInput::make('password')->label(__('Password'))
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->sortable()->searchable(),
                TextColumn::make('username')->label(__('Username'))->sortable()->searchable(),
                TextColumn::make('nip')->label(__('NIP'))->sortable()->searchable(),
                TextColumn::make('rank.name')->label(__('Rank'))->sortable()->searchable(),
                TextColumn::make('position.name')->label(__('Position'))->sortable()->searchable(),
                TextColumn::make('employeeStatus.description')->label(__('Employee Status'))->sortable()->searchable(),
                TextColumn::make('roles.name')->label(__('Roles'))->searchable()->badge(),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('employee_status_id')->label(__('Employee Status'))->options(EmployeeStatus::pluck('description', 'id')),
                Tables\Filters\SelectFilter::make('rank_id')->label(__('Rank'))->options(Rank::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('position_id')->label(__('Position'))->options(Position::pluck('name', 'id')),
            ],
            // layout: FiltersLayout::AboveContent
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

}
