<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderActivityResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderActivityResource extends Resource
{
    use \App\Filament\Traits\HasResourcePermission;
    protected static string $requiredPermission = 'view_order_logs';
    protected static ?string $model = ActivityLog::class;
    
    // Ensure Filament generates unique routes/slugs for this resource
    protected static ?string $slug = 'order-activity-logs';

    protected static ?string $navigationGroup = '🛒 Kinh doanh (Sales)';
    protected static ?int $navigationSort = 2; // Below Orders
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Nhật ký Đơn hàng';
    protected static ?string $modelLabel = 'Nhật ký Đơn hàng';
    protected static ?string $pluralModelLabel = 'Nhật ký Đơn hàng';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('action_type', 'order');
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
                    ->default('Hệ thống tự động'),
                
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Đơn hàng #')
                    ->formatStateUsing(fn ($state) => "ĐH-#" . $state)
                    ->url(fn ($record) => OrderResource::getUrl('view', ['record' => $record->subject_id]))
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Hành động')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed_order', 'delivered_order' => 'success',
                        'cancelled_order' => 'danger',
                        'shipping_order' => 'warning',
                        'created_order' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'confirmed_order' => 'Xác nhận',
                        'delivered_order' => 'Giao thành công',
                        'cancelled_order' => 'Hủy đơn',
                        'shipping_order' => 'Đang giao',
                        'created_order' => 'Tạo mới',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
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
                Tables\Actions\ViewAction::make()->label('Xem chi tiết'),
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
                Infolists\Components\Section::make('Thông tin hoạt động')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')->label('Thời gian')->dateTime('d/m/Y H:i:s'),
                        Infolists\Components\TextEntry::make('user.username')->label('Người thực hiện')->default('Hệ thống tự động'),
                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('Mã Đơn hàng')
                            ->formatStateUsing(fn ($state) => "ĐH-#" . $state),
                        Infolists\Components\TextEntry::make('action')->label('Mã hành động')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('description')->label('Mô tả chi tiết')->columnSpanFull(),
                    ])->columns(4),
                
                Infolists\Components\Section::make('Chi tiết thay đổi')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                $props = $record->properties ?? [];
                                $mapping = [
                                    'old_status' => 'Trạng thái cũ',
                                    'new_status' => 'Trạng thái mới',
                                    'order_id' => 'Mã Đơn',
                                    'tracking_number' => 'Mã vận đơn',
                                    'ip' => 'Địa chỉ IP',
                                    'user_agent' => 'Trình duyệt/Thiết bị',
                                ];
                                $translated = [];
                                foreach ($props as $key => $value) {
                                    $newKey = $mapping[$key] ?? $key;
                                    $translated[$newKey] = is_array($value) ? json_encode($value) : $value;
                                }
                                return $translated;
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
            'index' => Pages\ManageOrderActivities::route('/'),
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
