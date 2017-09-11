<?php

namespace App\Http\Controllers\App;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class MemberController extends Controller
{
    public function index()
    {
        return view('app.member.index');
    }

    public function getData()
    {
    	$member = User::where('id_company', Auth::user()->id_company)->get();

        return response()->json(['users' => $member]);
    }

    public function create()
    {
    	return view('app.member.create');
    }

    public function store(Request $r)
    {
    	$this->validate($r, [
            'name'      => 'required',
            'email'     => 'required|email|max:255|unique:users',
            'position'  => 'required',
            'password'  => 'required|min:5|confirmed',
        ]);

        $member = new User;
        $member->name = $r->name;
        $member->id_company = Auth::user()->id_company;
        $member->phone = $r->phone;
        $member->email = $r->email;
        $member->email_status = 'pending';
        $member->position = $r->position;
        $member->status = $r->status;
        $member->remember_token = $r->_token;
        $member->password = bcrypt($r->password);

        // Upload Image
        $destination = public_path('assets/app/img/users');
        $photo_arr = GlobalClass::Upload($r->file('photo'), $destination);
        $photo = implode(',', $photo_arr);
        $member->photo = $photo;

        $member->save();

        Session::flash('message', "Berhasil menambahkan anggota tim baru");
		return redirect()->route('member');
    }

    public function edit($id)
    {
    	$data['member'] = User::find($id);

    	return view('app.member.edit', $data);
    }

    public function update($id, Request $r)
    {
        $member = User::find($id);

        $this->validate($r, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$member->_id.',_id',
            'phone'     => 'required',
            'status'    => 'required',
            'position'  => 'required',
        ]);

        $member->name = $r->name;
        $member->email = $r->email;
        $member->phone = $r->phone;
        $member->position = $r->position;
        $member->status = $r->status;
        if ($r->password != "") {
        	$member->password = bcrypt($r->password);
        }

        // Upload Image
        if ($r->hasFile('photo')) 
        {
            // Remove Old photo
            $old = User::where('_id', $id)->first();
            @unlink(public_path('assets/app/img/users').'/'.$old->photo);

            // Upload Image
            $destination = public_path('assets/app/img/users');
            $photo_arr = GlobalClass::Upload($r->file('photo'), $destination, 200);
            $photo = implode(',',$photo_arr);
            
            // Save to DB
            $member->photo = $photo;
        }

        $member->save();

        Session::flash('message', "Berhasil mengubah data");
		return redirect()->route('member');
    }

    public function delete(Request $r)
    {
    	User::where('_id', $r->id)->delete();

    	return redirect()->back();
    }
}
