<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{

    public function doctorApi(Request $request)
    {

        $response = [
            "success" => false,
            "message" => "No data",
            "data" => '',
        ];

        if ($doctor = Doctor::where('phone', '+' . $request->phone)->first()) {
            if (Hash::check($request->password, $doctor->password)) {

                $response['success'] = true;
                $response['message'] = 'Success';
                $response['data'] = $doctor;

            } else {
                $response['success'] = false;
                $response['message'] = 'Incorrect password';
                $response['data'] = '';
            }
        }
        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $doctors = Doctor::with('hospital')->paginate(10);
        } else {
            $doctors = Doctor::where('user_id', Auth::user()->id)->paginate(10);
        }
        return view('admin.doctor.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.doctor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            '_token' => ['required'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['token'] = Hash::make($data['_token']);
        if ($request->has('image')) {
            $data['image'] = $this->imageUpload($request, 'uploads/doctor');
        }
        Doctor::create($data);
        return redirect()->route('admin.doctor.index')->with('message', 'Successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = Doctor::findOrFail($id)->with('hospital')->get();
        dd($doctor);
        return view('admin.doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        return view('admin.doctor.edit', compact('doctor'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'string', 'max:255'],
            '_token' => ['required'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['token'] = Hash::make($data['_token']);
        Doctor::create($data);
        return redirect()->route('admin.doctor.index')->with('message', 'Successfully added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return back();
    }
}
