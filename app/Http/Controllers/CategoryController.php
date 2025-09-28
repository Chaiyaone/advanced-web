<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Config, Validator;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('category/index',compact('categories'));
    }
    public function search(Request $request){
        $query = $request->q;
        if($query) {
            $categories = Category::where('name', 'like', '%'.$query.'%')
            ->get();
        } else {
            $categories = Category::all();
        }
            return view('category/index', compact('categories'));
    }
    public function edit($id = null) {
        $categories = Category::pluck('name', 'id')->prepend('เลือกรายการ', '');
        if($id) {
            $category = Category::where('id', $id)->first();
            return view('category/edit')
            ->with('category', $category)
            ->with('categories', $categories);
        } else {
        return view('category/add')
        ->with('categories', $categories);
        }
    }
    public function update(Request $request) {
        $id = $request->id;
        $temp = array(
            'name' => $request->name, //ทดลองฟิลด์เดียวก่อน
        );
        $rules = array(
            'name' => 'required',
        );
        $messages = array(
            'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
            'numeric' => 'กรุณากรอกข้อมูล :attribute ให้เป็นตัวเลข',
        );
        $validator = Validator::make($temp, $rules, $messages);
        if ($validator->fails()) {
            return redirect('category/edit')
            ->withErrors($validator)
            ->withInput();
        }

        $category = Category::find($id);
        $category->name = $request->name;

        $category->save();
        // return redirect('product')
        // ->with('ok', true)
        // ->with('msg', 'บันทึกขอมูลเรียบร้อยแลว้');

        return redirect('category')
        ->with('ok', true)
        ->with('msg', 'บันทึกข้อมูลเรียบร้อยแล้ว');
}

public function insert(Request $request) {
        $id = $request->id;
        $temp = array(
            'name' => $request->name, //ทดลองฟิลด์เดียวก่อน
        );
        $rules = array(
            'name' => 'required',
        );
        $messages = array(
            'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
            'numeric' => 'กรุณากรอกข้อมูล :attribute ให้เป็นตัวเลข',
        );
        $validator = Validator::make($temp, $rules, $messages);
        if ($validator->fails()) {
            return redirect('category/edit')
            ->withErrors($validator)
            ->withInput();
        }

        $category = new Category();
        $category->name = $request->name;

        $category->save();
        // return redirect('product')
        // ->with('ok', true)
        // ->with('msg', 'บันทึกขอมูลเรียบร้อยแลว้');

        return redirect('category')
        ->with('ok', true)
        ->with('msg', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }
    public function remove($id) {
        Category::find($id)->delete();
        return redirect('category')
        ->with('ok', true)
        ->with('msg', 'ลบข้อมูลสําเร็จ');
    }
}
