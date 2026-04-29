@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b">
        <a href="{{ route('account.addresses') }}" class="text-gray-400 hover:text-brand-blue transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Cập nhật địa chỉ</h2>
    </div>

    <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="max-w-xl">
        @csrf
        @method('put')

        <div class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên người nhận</label>
                <input type="text" name="name" id="name" value="{{ old('name', $address->name) }}" required
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" required
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ cụ thể (Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP)</label>
                <textarea name="address" id="address" rows="3" required
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">{{ old('address', $address->address) }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_default" id="is_default" value="1" {{ (old('is_default', $address->is_default)) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue h-4 w-4">
                <label for="is_default" class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</label>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-4">
            <button type="submit" class="bg-brand-blue text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-medium">
                Cập nhật
            </button>
            <a href="{{ route('account.addresses') }}" class="text-gray-600 hover:text-gray-900 transition font-medium">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>
@endsection
