<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('id', 'desc')->get();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        PaymentMethod::create($request->all());
        return redirect()->route('admin.payment_methods.index')->with('success', 'Thêm phương thức thanh toán thành công!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $paymentMethod->id,
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $paymentMethod->update($request->all());
        return redirect()->route('admin.payment_methods.index')->with('success', 'Cập nhật phương thức thanh toán thành công!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('admin.payment_methods.index')->with('success', 'Đã xóa phương thức thanh toán!');
    }
}
