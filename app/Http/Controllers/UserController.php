<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Config, Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('users/index',compact('users'));
    }

    public function search(Request $request){
        $query = $request->q;
        if($query) {
            $users = User::where('name', 'like', '%'.$query.'%')
            ->orWhere('email', 'like', '%'.$query.'%')
            ->get();
        } else {
            $users = User::all();
        }
            return view('users/index', compact('users'));
    }


    public function edit($id = null) {
        $levels = [
            'customer' => 'customer',
            'admin' => 'admin',
            'employee' => 'employee',
        ];

        $user_levels = collect($levels)->prepend('เลือกรายการสถานะ', ''); 

        if($id) {
            $user = User::where('id', $id)->first(); return view('users.edit')
            ->with('user', $user)
            ->with('user_levels', $user_levels);
        } else {
            return view('users/add')
            ->with('user', $user_levels);
        }
    }


    public function update(Request $request) {
        $id = $request->id;
        $temp = array(
            'name' => $request->name, 
            'email' => $request->email,
            'level' => $request->level,
        );
        $rules = array(
            'name' => 'required',
            // แก้ไขตรงนี้: กำหนดให้ email ต้องไม่ซ้ำในตาราง users แต่ให้ยกเว้น ID ปัจจุบัน ($id)
            'email' => 'required|email|unique:users,email,' . $id,
            'level' => 'required',
        );
        $messages = array(
            'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
            'unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
        );

        $validator = Validator::make($temp, $rules, $messages);
        if ($validator->fails()) {
            // สำคัญ: ต้อง Redirect กลับไปที่หน้า edit พร้อม ID
            return redirect('users/edit/' . $id)
            ->withErrors($validator)
            ->withInput();
        }

        $users = User::find($id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->level = $request->level;

        $users->save();

        return redirect('users')
        ->with('ok', true)
        ->with('msg', 'บันทึกการแก้ไขข้อมูลเรียบร้อยแล้ว');
    }


    public function insert(Request $request) {
        // ... (โค้ด insert เดิมที่ถูกต้อง)
        $temp = array(
            'name' => $request->name, 
            'email' => $request->email,
            'password' => $request->password,
            'level' => $request->level,
        );
        $rules = array( 
            'name' => 'required|max:255', 
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:8',
            'level' => 'required',
        );
        $messages = array(
            'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
            'unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'min' => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
        );

        $validator = Validator::make($temp, $rules, $messages);
        if ($validator->fails()) {
            return redirect('users/add')
            ->withErrors($validator)
            ->withInput();
        }

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->level = $request->level; 

        $user->save();

        return redirect('users')
        ->with('ok', true)
        ->with('msg', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


    public function remove($id) {
        User::find($id)->delete();
        return redirect('users')
        ->with('ok', true)
        ->with('msg', 'ลบข้อมูลสําเร็จ');
    }
}