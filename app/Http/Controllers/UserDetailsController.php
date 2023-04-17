<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Hobby;
use App\Models\User_detail;
use App\Models\UserHobby;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use Illuminate\Database\Eloquent\Collection;

class UserDetailsController extends Controller
{
    public function index()
    {
        $categories = Category::pluck('category', 'id'); //get category list
        $hobbies = Hobby::get(); //get category list
        return view('list')
            ->with('hobbies', $hobbies)
            ->with('categories', $categories);
    }

    public function list_data(Request $request)
    {
        $arr_user_details = User_detail::with('category')->with('hobbies')->get();
        return array('tableData' => $arr_user_details, "countData" => count($arr_user_details));
    }

    public function save(Request $request)
    {

        if ($request->user_id) {
            $validatedData = $request->validate([
                'name' => 'required|unique:user_details,name,' . $request['user_id'] . ',id',
                'category_id' => 'required',

            ]);
            $objUserDetails = User_detail::find($request->user_id);
        } else {
            $validatedData = $request->validate([
                'category_id' => 'required',
                'name' => 'required|unique:user_details,name,NULL,id',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

            ]);
            $objUserDetails = new User_detail;
        }
        $product_img = "";
        if ($request->file('image')) {
            $file = $request->file('image');

            $extension = $file->getClientOriginalName();
            $filename = time() . "." . $extension;

            $destinationPath = public_path('/uploads');
            $file->move($destinationPath . '/images',  $filename);
            $product_img =  $filename;
        }
        try {
            $objUserDetails->name = $request->name;
            $objUserDetails->contact_no = $request->contact_no;
            $objUserDetails->category_id = $request->category_id;
            if ($product_img)
                $objUserDetails->profile_img = $product_img;

            $objUserDetails->save();

            if ($request->hobbies) {

                $arr_hobbies = explode(',', $request->hobbies);

                UserHobby::where('user_detail_id', $objUserDetails->id)->delete();
                for ($i = 0; $i < count($arr_hobbies); $i++) {
                    if ($arr_hobbies[$i]) {
                        $user_hobby = new UserHobby();
                        $user_hobby->user_detail_id =  $objUserDetails->id;
                        $user_hobby->hobby_id =  $arr_hobbies[$i];
                        $user_hobby->save();
                    }
                }
            }
            return response()->json(["status" => "success", "message" => "Saved successfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "failed", "message" => $e]);
        }
    }

    public function data_view(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $details = User_detail::find($id);
            $hobbies = $details->hobbies()->pluck('hobbies.id')->toArray();

            // $hobbies = collect($details->hobbies)->pluck('id');

            return response()->json(['details' => $details, 'hobbies' => $hobbies]);
        }
    }
    public function delete_user(Request $request)
    {
        try {
            $id = $request->id;
            if ($id != "") {
                $details = User_detail::find($id);

                $details->delete();
                return response()->json(["status" => "success", "message" => "Deleted successfully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => "failed", "message" => $e]);
        }
    }
}