<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemActivityResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SystemActivityResource extends Resource
{
    use \App\Filament\Traits\HasResourcePermission;
    protected static string $requiredPermission = 'view_system_logs';
    protected static ?string $model = ActivityLog::class;
    
    // Ensure Filament generates unique routes/slugs for this resource
    protected static ?string $slug = 'system-activity-logs';

    protected static ?string $navigationGroup = '🔐 Hệ thống (System)';
    protected static ?int $navigationSort = 4; // Put under System
    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationLabel = 'Nhật ký Hệ thống';
    protected static ?string $modelLabel = 'Nhật ký Hệ thống';
    protected static ?string $pluralModelLabel = 'Nhật ký Hệ thống';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('action_type', 'system');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Người thực hiện')
                    ->sortable()
                    ->searchable()
                    ->default('Khách/Hệ thống'),

                Tables\Columns\TextColumn::make('action')
                    ->label('Mã hành động')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user_login' => 'success',
                        'user_logout' => 'gray',
                        'user_password_changed' => 'warning',
                        'user_roles_changed' => 'danger',
                        'role_permissions_changed' => 'danger',
                        default => 'primary',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Chi tiết bảo mật')
                    ->searchable()
                    ->wrap(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('Lọc theo mã hành động')
                    ->options([
                        'user_login' => 'Đăng nhập',
                        'user_logout' => 'Đăng xuất',
                        'user_password_changed' => 'Đổi mật khẩu',
                        'user_roles_changed' => 'Đổi vai trò nhân sự',
                        'role_permissions_changed' => 'Đổi quyền của vai trò',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')->label('Từ ngày'),
                        \Filament\Forms\Components\DatePicker::make('created_until')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Xem kỹ thuật'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin Bảo mật')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')->label('Thời gian')->dateTime('d/m/Y H:i:s'),
                        Infolists\Components\TextEntry::make('user.username')->label('Người thực hiện')->default('Hệ thống/Khách'),
                        Infolists\Components\TextEntry::make('action')->label('Mã hành động')
                            ->badge()
                            ->color('danger'),
                        Infolists\Components\TextEntry::make('description')->label('Mô tả chi tiết')->columnSpanFull(),
                    ])->columns(3),
                
                Infolists\Components\Section::make('Dữ liệu kỹ thuật đính kèm (Variables)')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                return $record->properties ?? [];
                            })
                            ->columnSpanFull()
                    ])->visible(fn ($record) => !empty($record->properties)),
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
            'index' => Pages\ManageSystemActivities::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
