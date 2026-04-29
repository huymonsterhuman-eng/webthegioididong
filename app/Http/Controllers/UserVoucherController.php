<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class UserVoucherController extends Controller
{
    /**
     * Display the user's collected vouchers.
     */
    public function index()
    {
        $user = auth()->user();
        
        $vouchers = $user->vouchers()->orderByPivot('created_at', 'desc')->get();
        
        return view('account.vouchers.index', compact('vouchers'));
    }

    /**
     * Collect a voucher from the homepage or elsewhere.
     */
    public function saveVoucher(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu mã giảm giá.']);
        }

        $voucher = Voucher::find($request->voucher_id);
        
        if (!$voucher || !$voucher->is_active || ($voucher->expires_at && $voucher->expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc không tồn tại.']);
        }

        $alreadyCollected = $user->vouchers()->where('voucher_id', $voucher->id)->exists();
        
        if ($alreadyCollected) {
            return response()->json(['success' => false, 'message' => 'Bạn đã lưu mã giảm giá này rồi.']);
        }

        $user->vouchers()->attach($voucher->id, ['is_used' => false]);

        return response()->json([
            'success' => true, 
            'message' => 'Lưu mã giảm giá thành công!',
        ]);
    }
}
