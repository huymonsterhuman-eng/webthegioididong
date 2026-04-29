@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">Đổi mật khẩu</h2>

    @if (session('status') === 'password-updated')
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p>Mật khẩu của bạn đã được cập nhật thành công!</p>
        </div>
    @endif

    <form method="post" action="{{ route('account.password.update') }}" class="max-w-xl">
        @csrf
        @method('put')

        <div class="space-y-5">
            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                <input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" autocomplete="current-password" />
                @error('current_password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                <input id="update_password_password" name="password" type="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" autocomplete="new-password" />
                @error('password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" autocomplete="new-password" />
                @error('password_confirmation', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="bg-brand-blue text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-medium">
                Cập nhật mật khẩu
            </button>
        </div>
    </form>
</div>
@endsection
