<?php

namespace App\Filament\Resources\GoodsReceiptResource\Pages;

use App\Filament\Resources\GoodsReceiptResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewGoodsReceipt extends ViewRecord
{
    protected static string $resource = GoodsReceiptResource::class;

    // Override to show a nice view instead of the create form
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Receipt Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Receipt Code')
                            ->formatStateUsing(fn ($state) => 'PR-' . str_pad($state, 4, '0', STR_PAD_LEFT)),
                        Infolists\Components\TextEntry::make('supplier.name')
                            ->label('Supplier'),
                        Infolists\Components\TextEntry::make('user.username')
                            ->label('Created By'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('vnd'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('note')
                            ->label('Note')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Items Imported')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('details')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Qty'),
                                Infolists\Components\TextEntry::make('import_price')
                                    ->label('Import Price')
                                    ->money('vnd'),
                            ])
                            ->columns(3)
                            ->label(''),
                    ]),
            ]);
    }
}
