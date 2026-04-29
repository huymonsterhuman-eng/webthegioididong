@extends('layouts.account')

@section('account_content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">Thông tin tài khoản</h2>

    @if (session('status') === 'profile-updated')
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p>Cập nhật thông tin thành công!</p>
        </div>
    @endif

    <form method="POST" action="{{ route('account.profile.update') }}" class="max-w-2xl">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Username (Readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên đăng nhập</label>
                <div class="w-full bg-gray-100 text-gray-600 rounded-md border-gray-300 px-4 py-2 text-sm cursor-not-allowed">
                    {{ $user->username }}
                </div>
            </div>

            <!-- Email (Readonly for now) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="w-full bg-gray-100 text-gray-600 rounded-md border-gray-300 px-4 py-2 text-sm cursor-not-allowed">
                    {{ $user->email }}
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                    <option value="" disabled {{ is_null($user->gender) ? 'selected' : '' }}>Chọn giới tính</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Birthday -->
            <div>
                <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $user->birthday) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                @error('birthday') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-4 border-t pt-6 mt-6">
            <button type="submit" class="bg-brand-blue text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-medium">
                Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
