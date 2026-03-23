<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_reviews';
    protected static ?string $model = Review::class;

    protected static ?string $navigationGroup = '🛒 Kinh doanh (Sales)';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Đánh giá';
    protected static ?string $modelLabel = 'Đánh giá';
    protected static ?string $pluralModelLabel = 'Đánh giá';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->description('View the customer review and manage its visibility.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username')
                            ->disabled()
                            ->label('Customer'),

                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->disabled()
                            ->label('Product'),

                        Forms\Components\TextInput::make('rating')
                            ->disabled()
                            ->numeric()
                            ->label('Rating (1-5)'),

                        Forms\Components\Textarea::make('comment')
                            ->disabled()
                            ->columnSpanFull()
                            ->label('Customer Comment'),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('reviews')
                            ->disabled()
                            ->columnSpanFull()
                            ->label('Attached Image'),
                    ])->columns(2),

                Forms\Components\Section::make('Moderation')
                    ->schema([
                        Forms\Components\Toggle::make('is_hidden')
                            ->label('Hide review from public view')
                            ->helperText('Enable this if the review contains inappropriate content.'),

                        Forms\Components\Textarea::make('admin_reply')
                            ->label('Admin Reply')
                            ->rows(4)
                            ->helperText('Respond to the customer. This will be publicly visible under their review.')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        1, 2 => 'danger',
                        3 => 'warning',
                        4, 5 => 'success',
                        default => 'gray',
                    })
                    ->icon('heroicon-s-star'),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('is_hidden')
                    ->label('Hidden'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_hidden')
                    ->label('Hidden Status'),
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
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
