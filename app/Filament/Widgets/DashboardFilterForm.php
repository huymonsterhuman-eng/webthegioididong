<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;

/**
 * Widget chứa form filter theo khoảng thời gian cho toàn bộ Dashboard.
 *
 * Mặc định 30 ngày gần nhất. User chọn range → bấm "Áp dụng" → widget này
 * dispatch event `dashboardFilterApplied` để các widget khác lắng nghe.
 */
class DashboardFilterForm extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.dashboard-filter-form';

    // sort = -1 đảm bảo widget này hiện ĐẦU TIÊN (trước StatsOverview sort=1)
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        // Default: 30 ngày gần nhất
        $this->form->fill([
            'startDate' => now()->subDays(30)->toDateString(),
            'endDate'   => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Từ ngày')
                    ->native(false)
                    ->maxDate(now())
                    ->displayFormat('d/m/Y')
                    ->required(),
                DatePicker::make('endDate')
                    ->label('Đến ngày')
                    ->native(false)
                    ->maxDate(now())
                    ->displayFormat('d/m/Y')
                    ->required()
                    ->afterOrEqual('startDate'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    /**
     * Áp dụng filter: dispatch event cho các widget body re-render.
     */
    public function apply(): void
    {
        $state = $this->form->getState();

        $this->dispatch(
            'dashboardFilterApplied',
            startDate: $state['startDate'] ?? null,
            endDate:   $state['endDate']   ?? null,
        );
    }

    /**
     * Reset về 30 ngày gần nhất.
     */
    public function reset30Days(): void
    {
        $this->form->fill([
            'startDate' => now()->subDays(30)->toDateString(),
            'endDate'   => now()->toDateString(),
        ]);
        $this->apply();
    }

    /**
     * Preset nhanh 7 ngày.
     */
    public function reset7Days(): void
    {
        $this->form->fill([
            'startDate' => now()->subDays(7)->toDateString(),
            'endDate'   => now()->toDateString(),
        ]);
        $this->apply();
    }

    /**
     * Preset nhanh tháng hiện tại.
     */
    public function resetThisMonth(): void
    {
        $this->form->fill([
            'startDate' => now()->startOfMonth()->toDateString(),
            'endDate'   => now()->toDateString(),
        ]);
        $this->apply();
    }
}
