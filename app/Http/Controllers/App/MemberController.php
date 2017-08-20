<?php

namespace App\Http\Controllers\App;
use App\Member, App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session, GlobalClass;

class MemberController extends Controller
{
    public function index()
    {
    	$data['member'] = Member::where('id_company', Auth::user()->id_company)->get();

    	return view('app.member.index', $data);
    }

    public function create()
    {
    	return view('app.member.create');
    }

    public function store(Request $r)
    {
    	$this->validate($r, [
            'name' => 'required',
            'email' => 'required',
            'position' => 'required',
            'password' => 'required',
        ]);

        $member = new Member;

        // Upload Image
		$destination = resource_path('assets/kotakarsip/img/data-img/pengguna');
		$photo_arr = GlobalClass::Upload($r->file('photo'), $destination, 200);
		$photo = implode(',',$photo_arr);

        $member->name = $r->name;
        $member->id_company = $r->id_company;
        $member->phone = $r->phone;
        $member->email = $r->email;
        $member->email_status = 'pending';
        $member->position = $r->position;
        $member->photo = $photo;
        $member->status = $r->status;
        $member->remember_token = $r->_token;
        $member->password = bcrypt($r->password);
        $member->save();

        Session::flash('message', "Berhasil menambahkan anggota tim baru");
		return redirect()->route('member');
    }

    public function edit($id)
    {
    	$data['member'] = Member::find($id);

    	return view('app.member.edit', $data);
    }

    public function update($id, Request $r)
    {
    	$this->validate($r, [
            'name' => 'required',
            'email' => 'required',
            'position' => 'required',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $member = Member::find($id);

        // Upload Image
		if ($r->hasFile('photo')) 
		{
			// Remove Old photo
			$old = User::where('_id', $id)->first();
			@unlink(resource_path('assets/kotakarsip/img/data-img/pengguna').'/'.$old->photo);

			// Upload Image
			$destination = resource_path('assets/kotakarsip/img/data-img/pengguna');
			$photo_arr = GlobalClass::Upload($r->file('photo'), $destination, 200);
			$photo = implode(',',$photo_arr);
			
			// Save to DB
			$member->photo = $photo;
		}

        $member->name = $r->name;
        $member->phone = $r->phone;
        $member->email = $r->email;
        $member->position = $r->position;
        $member->status = $r->status;
        if ($r->password != "") {
        	$member->password = bcrypt($r->password);
        }
        $member->save();

        Session::flash('message', "Berhasil mengubah data");
		return redirect()->route('member');
    }

    public function delete(Request $r)
    {
    	Member::where('_id', $r->id)->delete();

    	return redirect()->back();
    }
}
