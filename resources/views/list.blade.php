@extends('layouts.app')

@section('content')
<style>
table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
}

.error_cls {
    color: red;
}
</style>
<div style="align-items: center;padding: 1rem;">
    <div style="display: flex;justify-content: space-between;align-items: center;padding: 1rem;">
        <h3>Table-1</h3>
        <h3>
            <a onclick="add_form()">Add new</a>
        </h3>
    </div>

    <table id="detail_list" style="width: 100%;border-color: #000;">
        <thead>
            <tr style="text-align:center;" scope="row">
                <th>Name</th>
                <th>Contact no</th>
                <th>Hobby</th>
                <th>Category</th>
                <th>Profile Pic</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align:center;" scope="row">
                <td colspan="6"> No Records Found!!</td>
            </tr>
        </tbody>
    </table>
</div>
<div id="add_overlay" class="overlay" style="display:none;">
    <div class="provider-form provider-form-overlay">
        <div style="display: flex;color:#000;justify-content: space-between;align-items: center;padding: 1rem;">
            <h1>Add/Edit Form</h1>
            <i style="color:#000;" class="fa-solid fa-xmark fa-3x" onclick="close_view()"></i>
        </div>
        <div class="container mt-5">

            <form method="POST" enctype="multipart/form-data" id="user_details" action="{{ url('save') }}">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input type="hidden" id="user_id" name="user_id">
                <table>
                    <tr>
                        <td>Name</td>
                        <td><input type=" text" name="name" id="name"
                                style="background-color: #5ba2e687;border-width: 0.05;">
                            <span class="error_cls" id="name_error"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Contact no</td>
                        <td><input type=" text" name="contact_no" id="contact_no" minlength="10" maxlength="15"
                                style="background-color: #5ba2e687;border-width: 0.05;" onKeyPress=" return numonly()">
                            <span class="error_cls" id="contact_no_error"></span>

                        </td>
                    </tr>
                    <tr>
                        <td>Hobby</td>
                        <td>
                            @foreach ($hobbies as $hobby)
                            [<input class="new_check" type="checkbox" name="{{$hobby->hobby}}" id="{{$hobby->id}}">]
                            <label class="form-check-label" for="scrap">
                                {{$hobby->hobby}}
                            </label>
                            @endforeach
                            <input type="hidden" name="hobbies" id="hobbies">
                            <input type="hidden" name="hobby_names" id="hobby_names">
                            <span class="error_cls" id="hobbies_error"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>
                            {!! Form::select('category_id',$categories,'',['id'=>'category_id','placeholder' =>
                            '--select--'])!!}


                            <span class="error_cls" id="category_id_error"></span>

                        </td>
                    </tr>
                    <tr>
                        <td>Profile pic</td>
                        <td>
                            <!-- <input type=" text" name="path" id="path" value=""
                                style="width: 100px;border-width: 0.05;background-color: #5ba2e687;">
                            <button style="background-color: #5ba2e687;width: 70px;height:20px;border-width: 0.05;"
                                onclick="event.preventDefault();$('#image').click()">Upload</button> -->
                            <input type="file" name="image" placeholder="Choose image" id="image">
                            <span class="error_cls" id="image_error"></span>

                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" ; colspan="2">
                            <button style="background-color: #75b99087;width: 100px;height:30px;border-width: 0.2;"
                                onclick="event.preventDefault();return save_data();">Save</button>
                            <button style="background-color: #d67c9687;width: 100px;height:30px;border-width: 0.2;"
                                onclick="event.preventDefault();return close_view();">Cancel</button>
                        </td>
                    </tr>
                </table>

            </form>
        </div>
    </div>
    <script src="{{ asset('asset/js/list.js') }}"></script>

    @endsection