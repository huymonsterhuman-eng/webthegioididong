<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Filament\Traits\HasResourcePermission;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_brands';
    protected static ?string $model = Brand::class;

    protected static ?string $navigationGroup = '📦 Sản phẩm (Catalog)';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationLabel = 'Thương hiệu';
    protected static ?string $modelLabel = 'Thương hiệu';
    protected static ?string $pluralModelLabel = 'Thương hiệu';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('brands'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record, Tables\Actions\DeleteAction $action) {
                        if ($record->products()->count() > 0) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Không thể xóa danh mục này vì vẫn còn ' . $record->products()->count() . ' sản phẩm bên trong. Vui lòng chuyển sản phẩm sang danh mục khác trước.')
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records, Tables\Actions\DeleteBulkAction $action) {
                            foreach ($records as $record) {
                                if ($record->products()->count() > 0) {
                                    \Filament\Notifications\Notification::make()
                                        ->danger()
                                        ->title("Không thể xóa danh mục '{$record->name}' vì vẫn còn " . $record->products()->count() . " sản phẩm bên trong. Vui lòng chuyển sản phẩm sang danh mục khác trước.")
                                        ->send();

                                    $action->cancel();
                                }
                            }
                        }),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
