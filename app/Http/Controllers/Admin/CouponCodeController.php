<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use Illuminate\Http\Request;

class CouponCodeController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Coupon Code Manager';
        $coupons = CouponCode::latest()->paginate(getPaginate(10));
        return view('admin.coupon_codes.index')->with(compact('coupons', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Add Coupon Code Page';
        return view('admin.coupon_codes.add')->with(compact('page_title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'code' => 'required',
            'supply' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'valid_upto'=>'required'
        ]);
        $coupon = new CouponCode();
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->supply = $request->supply;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->frequency = $request->frequency;
        $coupon->is_active = $request->is_active;
        $coupon->valid_upto = $request->valid_upto;
        $coupon->save();
        return redirect()->route('admin.coupons');
    }


    public function edit($id)
    {
        $page_title = 'Edit Coupon Code Page';
        $coupon = CouponCode::find($id);
        return view('admin.coupon_codes.edit')->with(compact('coupon', 'page_title'));
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'code' => 'required',
            'supply' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'valid_upto'=>'required'
        ]);
        $coupon = CouponCode::find($request->id);
        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->supply = $request->supply;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->frequency = $request->frequency;
        $coupon->is_active = $request->is_active;
        $coupon->valid_upto = $request->valid_upto;
        $coupon->save();
        return redirect()->route('admin.coupons');
    }


    public function destroy($id)
    {
        $center = CouponCode::where('id', $id)->first();
        $center->delete();
        return redirect()->back()->with('success', 'Coupon Code deleted Successfully!');
    }
}
