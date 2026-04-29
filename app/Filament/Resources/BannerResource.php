<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'manage_banners';
    protected static ?string $model = Banner::class;

    protected static ?string $navigationGroup = '📝 Nội dung (Content)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Banner quảng cáo';
    protected static ?string $modelLabel = 'Banner quảng cáo';
    protected static ?string $pluralModelLabel = 'Banner quảng cáo';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Banner Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('link')
                            ->label('Target Link')
                            ->url()
                            ->maxLength(255)
                            ->helperText('The URL users will be redirected to when clicking the banner.'),

                        Forms\Components\FileUpload::make('image')
                            ->label('Banner Image')
                            ->image()
                            ->directory('banners')
                            ->required()
                            ->columnSpanFull()
                            ->imageEditor(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\Select::make('author_id')
                            ->label('Creator')
                            ->relationship('author', 'username')
                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->full_name && $record->full_name !== '') ? $record->full_name . " ({$record->username})" : $record->username)
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Banner Preview')
                    ->square()
                    ->size(80),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.username')
                    ->label('Creator')
                    ->description(fn (Banner $record): ?string => $record->author?->full_name)
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('link')
                    ->searchable()
                    ->toggleable()
                    ->url(fn (Banner $record) => $record->link, true)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
