<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\CollectionResource\Pages;
use App\Models\Collection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Filament\Resources\CollectionResource\RelationManagers;

class CollectionResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'manage_collections';
    protected static ?string $model = Collection::class;

    protected static ?string $navigationGroup = '📝 Nội dung (Content)';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Bộ sưu tập';
    protected static ?string $modelLabel = 'Bộ sưu tập';
    protected static ?string $pluralModelLabel = 'Bộ sưu tập';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Thông tin chung')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên bộ sưu tập')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->columnSpanFull(),
                    ])->columns(2),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make([
                    Forms\Components\Section::make('Hiển thị')->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Thuộc Bộ sưu tập (Tùy chọn)')
                            ->relationship('parent', 'name', modifyQueryUsing: fn(Builder $query, ?Collection $record) => $query->whereNull('parent_id')->when($record, fn($q) => $q->where('id', '!=', $record->id)))
                            ->searchable()
                            ->preload()
                            ->hint('Chỉ cho phép chọn bộ sưu tập cấp 1 (Tùy chọn)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0),
                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh đại diện')
                            ->image()
                            ->directory('collections'),
                    ])
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên bộ sưu tập')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Bộ sưu tập Cha')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Số sản phẩm')
                    ->badge()
                    ->color('success'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Kích hoạt'),
                Tables\Columns\TextInputColumn::make('sort_order')
                    ->label('Thứ tự')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Lọc theo Cha')
                    ->relationship('parent', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Trạng thái kích hoạt'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            \App\Filament\Resources\CollectionResource\RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
