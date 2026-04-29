@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6 pb-4 border-b">
        <h2 class="text-xl font-bold text-gray-800">Sổ địa chỉ</h2>
        <a href="{{ route('account.addresses.create') }}" class="flex items-center gap-2 bg-brand-yellow text-brand-dark px-4 py-2 rounded-md hover:bg-yellow-500 transition font-medium text-sm">
            <i class="fa-solid fa-plus"></i> Thêm địa chỉ mới
        </a>
    </div>

    @if (session('status') === 'address-added')
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p>Đã thêm địa chỉ mới thành công!</p>
        </div>
    @endif
    @if (session('status') === 'address-updated')
        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p>Đã cập nhật địa chỉ thành công!</p>
        </div>
    @endif
    @if (session('status') === 'address-deleted')
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p>Đã xóa địa chỉ thành công!</p>
        </div>
    @endif

    @if($addresses->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <i class="fa-solid fa-map-location-dot text-5xl mb-4 opacity-20"></i>
            <p>Bạn chưa có địa chỉ nào được lưu.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $address)
                <div class="border rounded-lg p-5 relative {{ $address->is_default ? 'border-brand-blue bg-blue-50/30' : 'border-gray-200' }}">
                    @if($address->is_default)
                        <span class="absolute top-4 right-4 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 uppercase tracking-wider">
                            <i class="fa-solid fa-star"></i> Mặc định
                        </span>
                    @endif
                    
                    <div class="mb-3 pr-20">
                        <p class="font-bold text-gray-800 text-lg">{{ $address->name }}</p>
                        <p class="text-gray-600 text-sm mt-1">
                            <i class="fa-solid fa-phone text-gray-400 w-4"></i> {{ $address->phone }}
                        </p>
                    </div>
                    
                    <p class="text-gray-700 text-sm mb-4 leading-relaxed line-clamp-2" title="{{ $address->address }}">
                        <i class="fa-solid fa-location-dot text-gray-400 w-4"></i> {{ $address->address }}
                    </p>
                    
                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-100">
                        <a href="{{ route('account.addresses.edit', $address) }}" class="text-brand-blue hover:text-blue-800 text-sm font-medium">Sửa</a>
                        <span class="text-gray-300">|</span>
                        <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');" class="inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Xóa</button>
                        </form>
                        
                        @if(!$address->is_default)
                            <form method="POST" action="{{ route('account.addresses.default', $address) }}" class="ml-auto">
                                @csrf
                                @method('patch')
                                <button type="submit" class="text-gray-500 border border-gray-300 rounded px-2 py-1 text-xs hover:bg-gray-50 transition">Thiết lập mặc định</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
