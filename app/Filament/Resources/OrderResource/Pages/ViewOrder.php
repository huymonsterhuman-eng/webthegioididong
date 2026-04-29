<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_invoice')
                ->label('In hóa đơn')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('admin.orders.invoice', $this->record), shouldOpenInNewTab: true),
        ];
    }
}
