@php
    $statusOrder = array_column($steps, 'key');
    $currentIndex = array_search($current, $statusOrder);
    $isCancelled = $cancelled ?? false;
@endphp

<div class="w-full px-4 py-5">
    {{-- Cancelled banner --}}
    @if($isCancelled)
        <div class="flex items-center gap-2 mb-4 px-4 py-2 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Phiếu này đã bị huỷ
        </div>
    @endif

    {{-- Stepper --}}
    <div class="flex items-start">
        @foreach($steps as $i => $step)
            @php
                $stepIndex  = $i;
                $isPast     = !$isCancelled && $currentIndex !== false && $stepIndex < $currentIndex;
                $isCurrent  = !$isCancelled && $current === $step['key'];
                $isFuture   = !$isCancelled && ($currentIndex === false || $stepIndex > $currentIndex);
            @endphp

            {{-- Step circle + label --}}
            <div class="flex flex-col items-center flex-shrink-0">
                <div @class([
                    'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all',
                    'bg-green-500 text-white shadow-sm'                      => $isPast,
                    'bg-blue-600 text-white ring-4 ring-blue-100 shadow-md'  => $isCurrent,
                    'bg-gray-200 text-gray-400'                              => $isFuture,
                    'bg-red-100 text-red-400'                                => $isCancelled,
                ])>
                    @if($isPast)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $i + 1 }}
                    @endif
                </div>
                <span @class([
                    'mt-1.5 text-xs font-medium text-center w-20',
                    'text-green-600'  => $isPast,
                    'text-blue-700'   => $isCurrent,
                    'text-gray-400'   => $isFuture,
                    'text-red-400'    => $isCancelled,
                ])>
                    {{ $step['label'] }}
                </span>
            </div>

            {{-- Connector line --}}
            @if(!$loop->last)
                <div @class([
                    'flex-1 h-1 mt-4 mx-1 rounded-full transition-all',
                    'bg-green-400' => $isPast,
                    'bg-blue-300'  => $isCurrent,
                    'bg-gray-200'  => $isFuture || $isCancelled,
                ])></div>
            @endif
        @endforeach
    </div>
</div>
