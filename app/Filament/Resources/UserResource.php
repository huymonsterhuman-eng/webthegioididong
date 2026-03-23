<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_users';
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = '🔐 Hệ thống (System)';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Người dùng';
    protected static ?string $modelLabel = 'Người dùng';
    protected static ?string $pluralModelLabel = 'Người dùng';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'banned' => 'Banned',
                        'unverified' => 'Unverified',
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Role')
                    ->helperText('Chọn vai trò Spatie cho tài khoản này. Vai trò quyết định những màn hình Admin mà user được phép truy cập.'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(',')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'banned' => 'danger',
                        'unverified' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'banned' => 'Banned',
                        'unverified' => 'Unverified',
                    ]),
                Tables\Filters\Filter::make('new_this_month')
                    ->label('New registrations this month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn(User $record): string => $record->status === 'banned' ? 'Unlock' : 'Lock')
                    ->color(fn(User $record): string => $record->status === 'banned' ? 'success' : 'danger')
                    ->icon(fn(User $record): string => $record->status === 'banned' ? 'heroicon-m-lock-open' : 'heroicon-m-lock-closed')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->status = $record->status === 'banned' ? 'active' : 'banned';
                        $record->save();
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
