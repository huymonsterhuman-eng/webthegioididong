{{--
    View cho DashboardFilterForm widget.
    Bao gồm:
      - Form 2 DatePicker (Từ ngày / Đến ngày) — auto-rendered từ $this->form
      - Nút "Áp dụng" gọi method apply()
      - 3 preset nhanh: 7 ngày / 30 ngày / Tháng này
--}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-funnel" class="h-5 w-5 text-primary-500"/>
                <span>Bộ lọc thời gian</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Áp dụng cho các widget có ý nghĩa theo thời gian (doanh thu, đơn hàng, biến động kho).
        </x-slot>

        <form wire:submit="apply">
            {{ $this->form }}

            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-white/10 flex flex-wrap items-center gap-2">
                <x-filament::button
                    type="submit"
                    icon="heroicon-m-check"
                    color="primary"
                >
                    Áp dụng
                </x-filament::button>

                <x-filament::button
                    type="button"
                    wire:click="reset7Days"
                    icon="heroicon-m-arrow-path"
                    color="gray"
                    outlined
                >
                    7 ngày
                </x-filament::button>

                <x-filament::button
                    type="button"
                    wire:click="reset30Days"
                    icon="heroicon-m-arrow-path"
                    color="gray"
                    outlined
                >
                    30 ngày
                </x-filament::button>

                <x-filament::button
                    type="button"
                    wire:click="resetThisMonth"
                    icon="heroicon-m-calendar"
                    color="gray"
                    outlined
                >
                    Tháng này
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
