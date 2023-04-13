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


        $arr_user_details = User_detail::join('categories', 'categories.id', 'user_details.category_id')
            // ->leftjoin('user_hobbies','user_details.id','user_hobbies.user_id')
            // ->leftjoin('hobbies','hobbies.id','user_hobbies.hobby_id')
            ->select('user_details.*', 'categories.category')->get();
        // $arr_user_details = $arr_user_details->orderBy('id', 'DESC')->paginate(10);
        // return $arr_user_details;
        if (count($arr_user_details) > 0) {
            // $slno = $arr_user_details->firstItem();
            $slno = 1;
            $table = '';
            foreach ($arr_user_details as $objDet) {
                $profile_img = url('public/uploads/images/' . $objDet->profile_img);
                $table .= "<tr style='text-align:center;' scope='row'>";
                $table .= "<td width='15%'>$objDet->name</td>";
                $table .= "<td width='10%'>$objDet->contact_no</td>";
                $table .= "<td width='20%'>$objDet->hobbies</td>";
                $table .= "<td width='10%'>$objDet->category</td>";
                $table .= "<td width='10%'><img src ='$profile_img' width='100' height='100'></td>";
                $table .= '<td width="10%"><a onclick="edit_User_detail(' . $objDet->id . ')" style="padding: 0 0.5rem;color:#12AF41; cursor: pointer;" >Edit</a>/
                    <a onclick="delete_User_detail(' . $objDet->id . ')" style="padding: 0 0.5rem;color:#E91F1F; cursor: pointer;" >Delete</a></td></tr>';
                $slno++;
            }
        } else {

            $table = "<tr><td colspan=6 style='text-align:center'>No Results Found</td></tr>";
            $showing = "";
            $pagination = "";
        }

        return array('table' => $table);
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


        $objUserDetails->name = $request->name;
        $objUserDetails->contact_no = $request->contact_no;
        $objUserDetails->hobbies = $request->hobby_names;
        $objUserDetails->category_id = $request->category_id;
        if ($product_img)
            $objUserDetails->profile_img = $product_img;

        $objUserDetails->save();

        if ($request->hobbies) {

            $arr_hobbies = explode(',', $request->hobbies);

            UserHobby::where('user_id', $objUserDetails->id)->delete();
            for ($i = 0; $i < count($arr_hobbies); $i++) {
                if ($arr_hobbies[$i]) {
                    $user_hobby = new UserHobby();
                    $user_hobby->user_id =  $objUserDetails->id;
                    $user_hobby->hobby_id =  $arr_hobbies[$i];
                    $user_hobby->save();
                }
            }
        }
        return response()->json(['status' => 'User Details has been saved']);
    }

    public function data_view(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $details = User_detail::find($id);
            $hobbies = UserHobby::where('user_id', $id)->pluck('hobby_id');

            return response()->json(['details' => $details, 'hobbies' => $hobbies]);
        }
    }
    public function delete_user(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $details = User_detail::find($id);

            $details->delete();
            return response()->json("success");
        }
    }
}