<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\lawn;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LDAP\Result;

class backendController extends Controller

{
    public function home()
    {
        $feedbacks = Feedback::where('status', 1)->get();
        $agents = Agent::where('status', 1)->get();
        return view('index', compact('feedbacks', 'agents'));
    }
    public function agent()
    {
        // $feedbacks = Feedback::all();
        $agents = Agent::where('status', 1)->get();
        return view('agent', compact('agents'));
    }
    public function about()
    {
        $feedbacks = Feedback::where('status', 1)->get();
        return view('about', compact('feedbacks'));
    }
    public function lawn()
    {
        $lawns = lawn::where('status', 1)->get();
        return view('lawns', compact('lawns'));
    }
    public function register(Request $request)
    {

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        $user = new Customer();
        $user->name     = $request['name'];
        $user->email    = $request['email'];
        $user->contact   = $request['mobile'];
        $user->status = 1;
        $user->password = Hash::make($request['password']);

        $user->save();

        if (Auth::guard('customer')->attempt($credentials)) {

            return redirect()->route('mybooking');
        } else {
            return back();
        }
    }
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request['username'],
            'password' => $request['password'],
        ];

        // $user = new User();
        // $user->name     = "admin";
        // $user->email    = $request['username'];
        // $user->password = Hash::make($request['password']);

        // $user->save();

        if (Auth::guard('customer')->attempt($credentials)) {
            // Auth::guard('customer')->check();
            return redirect()->route('mybooking');
        } else {
            return back()->with('msg', 'login_failed');
        }
    }
    public function logout()
    {
        Auth::guard('customer')->logout();

        // Auth::gaurd('customer')->logout();
        return redirect('/login');
    }
    public function lawns_search(Request $request)
    {
        $lawns = lawn::orWhere('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('specification', 'LIKE', '%' . $request->search . '%')
            ->orWhere('desription', 'LIKE', '%' . $request->search . '%')
            ->where('status', 1)->get();
        return view('lawns', compact('lawns'));
    }
    public function mybooking()
    {
        $mybookings = Booking::Select('lawns.*', 'bookings.name as booking_name', 'bookings.email as booking_email', 'bookings.mobile as booking_contact', 'bookings.created_at as booking_at', 'bookings.booking_date as booking_date')->leftJoin('lawns', 'bookings.lawn_id', '=', 'lawns.id')->where('userid', Auth::guard('customer')->user()->id)->orderBy('bookings.id', 'DESC')->get();
        // return $mybookings;
        return view('mybooking', compact('mybookings'));
    }
    public function check_availibility(Request $request)
    {
        // return $request;
        if (Booking::where('lawn_id', $request->lawn_id)->where('booking_date', $request->booking_date)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
    public function booknow(Request $request)
    {
        // return $request;/
        $booking = new Booking();
        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->mobile = $request->mobile;
        $booking->lawn_id = $request->lawn_id;
        $booking->userid = Auth::guard('customer')->user()->id;
        $booking->event  =  $request->event;
        $booking->booking_date = $request->booking_date;
        $booking->booking_notes = $request->notes;
        $booking->payment_status = true;
        $booking->status = 1;
        $booking->save();
        return redirect()->route('mybooking');
    }
    public function aboutlawn($id)
    {

        $lawn = lawn::where('id', $id)->first();
        return view('/about_lawm', compact('lawn'));
    }


    public function lawn_vendor_booking(Request $request)
    {
    }
    public function vendor_login(Request $request)
    {
        $credentials = [
            'email' => $request['username'],
            'password' => $request['password'],
        ];

        // $user = new User();
        // $user->name     = "admin";
        // $user->email    = $request['username'];
        // $user->password = Hash::make($request['password']);

        // $user->save();

        if (Auth::guard('vendor')->attempt($credentials)) {
            Auth::guard('vendor')->check();
            return redirect()->route('vendordashboard');
        } else {
            return  back();
        }
    }
    public function vendor_logout()
    {
        Auth::guard('vendor')->logout();

        return redirect('/vendor/login');
    }
    public function vendor_profile()
    {

        return view('vendor.profile');
    }
    public function vendor_update_profile(Request $request)
    {
        $vendor = Vendor::where('id', Auth::guard('vendor')->user()->id)->first();
        if (isset($request->profile)) {
            // unlink('/images/agent/' . $agent->profile);
            $profileName = rand(1000, 9999) . time() . '.' . $request->profile->extension();

            $request->profile->move('images/vendor', $profileName);
            $vendor->profile = $profileName;
        }
        $vendor->update();
        return back()->with('status', 'Profile updated Successfully');
    }
    public function vendor_update(Request $request)
    {
        $vendor = Vendor::where('id', Auth::guard('vendor')->user()->id)->first();


        $vendor->name = $request->name;
        $vendor->contact = $request->phone;
        $vendor->address = $request->address;
        $vendor->update();

        return back()->with('status', 'Profile updated Successfully');
    }
    public function vendor_change_password()
    {
        return view('vendor.security');
    }
    public function vendor_change_password_post(Request $request)
    {



        #Match The Old Password
        if (!Hash::check($request->cpassword, Auth::guard('vendor')->user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        Vendor::whereId(Auth::guard('vendor')->user()->id)->update([
            'password' => Hash::make($request->npassword)
        ]);
        return back()->with("status", "Password changed successfully!");
    }
    public function vendor_notification()
    {
        return view('vendor.notification');
    }
    public function vendor_all_booking()
    {
        $bookings = Booking::select('bookings.*', 'lawns.name as lawn_name', 'lawns.locality as locality', 'lawns.contact as lawn_contact')->leftJoin('lawns', 'bookings.lawn_id', '=', 'lawns.id')->leftJoin('vendors', 'lawns.vendor_id', '=', 'vendors.id')->where('vendors.id', Auth::guard('vendor')->user()->id)->get();

        return view('vendor.booking', compact('bookings'));
    }

    public function admin_login(Request $request)
    {
        $credentials = [
            'email' => $request['username'],
            'password' => $request['password'],
        ];

        //       $user = User::where('email','a@a.com')->first();
        //         $user->password = Hash::make($request['password']);

        //         $user->update();
        // return $user;
        if (Auth::attempt($credentials)) {
            return redirect()->route('admindashboard');
        } else {
            return back()->with('msg', "Username or Password are wrong");
        }
    }
    public function admin_Logout()
    {

        Auth::logout();
        return redirect('/admin/login');
    }
    public function send_sms($request)
    {
        $request['to'];
        $request['subject'];
        $request['massege'];
    }
    public function senquery(Request $request)
    {
        // return $request;
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->contact = $request->contact;
        $contact->subject =  $request->subject;
        $contact->massage = $request->massage;
        $contact->status = 1;
        $contact->save();

        return back()->with('msg', 'Contact form added successfully');
    }
    public function get_enquery()
    {
        return view('contact');
    }
    public function enquery()
    {
        $enqueries =  Contact::all();
        return view('admin.enquery', compact('enqueries'));
    }
    public function customer()
    {
        $customers = Customer::all();
        return view('admin.customer', compact('customers'));
    }
    function booking()
    {
        $bookings = Booking::select('bookings.*', 'lawns.name as lawn_name', 'lawns.locality as locality', 'lawns.contact as lawn_contact')->leftJoin('lawns', 'bookings.lawn_id', '=', 'lawns.id')->get();
        return view('admin.booking', compact('bookings'));
    }
    public function enquery_destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect('/admin/enquery')->with('Enquery Deleted Successfully');
    }
    public function profile()
    {
        return view('admin.profile');
    }
    public function billing()
    {
        return view('admin.billing');
    }
    public function notification()
    {
        return view('admin.notification');
    }
    public function security()
    {
        return view('admin.security');
    }
    public function admin_change_password_post(Request $request)
    {




        #Match The Old Password
        if (!Hash::check($request->cpassword, Auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }

        #Update the new Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->npassword)
        ]);
        return redirect('/admin/logout')->with("status", "Password changed successfully!");
    }

    public function admin_update_profile(Request $request)
    {
        $admin = User::where('id', Auth::user()->id)->first();
        if (isset($request->profile)) {
            // unlink('/images/agent/' . $agent->profile);
            $profileName = rand(1000, 9999) . time() . '.' . $request->profile->extension();

            $request->profile->move('images/user', $profileName);
            $admin->profile = $profileName;
        }
        $admin->update();
        return back()->with('status', 'Profile updated Successfully');
    }
    public function admin_update(Request $request)
    {
        // return $request;
        $admin = User::where('id', Auth::user()->id)->first();

        $admin->name = $request->name;
        $admin->mobile = $request->mobile;
        $admin->position = $request->position;
        $admin->location = $request->address;
        $admin->birthday = $request->birthday;
        $admin->update();

        return back()->with('status', 'Profile updated Successfully');
    }
    // agent
    public function agent_login(Request $request)
    {

        $credentials = [
            'email' => $request['username'],
            'password' => $request['password'],
        ];

        // $user = new User();
        // $user->name     = "admin";
        // $user->email    = $request['username'];
        // $user->password = Hash::make($request['password']);

        // $user->save();

        if (Auth::guard('agent')->attempt($credentials)) {
            return redirect('/agent/customer');
        } else {
            return back()->with('msg', 'username or password are wrong');
        }
    }
    public function agent_Logout()
    {

        Auth::guard('agent')->logout();
        return redirect('/agent/login');
    }
    public function agent_profile()
    {

        return view('agent.profile');
    }
    public function agent_update_profile(Request $request)
    {
        $agent = agent::where('id', Auth::guard('agent')->user()->id)->first();
        if (isset($request->profile)) {
            // unlink('/images/agent/' . $agent->profile);
            $profileName = rand(1000, 9999) . time() . '.' . $request->profile->extension();

            $request->profile->move('images/agent', $profileName);
            $agent->profile = $profileName;
        }
        $agent->update();
        return back()->with('status', 'Profile updated Successfully');
    }
    public function agent_update(Request $request)
    {
        $agent = agent::where('id', Auth::guard('agent')->user()->id)->first();
      

        $agent->name = $request->name;
        $agent->contact = $request->mobile;
        $agent->address = $request->address;
        $agent->designation = $request->position;
        $agent->update();

        return back()->with('status', 'Profile updated Successfully');
    }
    public function agent_change_password()
    {
        return view('agent.security');
    }
    public function agent_change_password_post(Request $request)
    {
      


        #Match The Old Password
        if (!Hash::check($request->cpassword, Auth::guard('agent')->user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        agent::whereId(Auth::guard('agent')->user()->id)->update([
            'password' => Hash::make($request->npassword)
        ]);
        return back()->with("status", "Password changed successfully!");
    }
    public function agent_notification()
    {
        return view('agent.notification');
    }
    public function agent_all_booking()
    {
        $bookings = Booking::select('bookings.*', 'lawns.name as lawn_name', 'lawns.locality as locality', 'lawns.contact as lawn_contact')->leftJoin('lawns', 'bookings.lawn_id', '=', 'lawns.id')->leftJoin('customers', 'customers.id', '=', 'bookings.userid')->where('customers.introducer', Auth::guard('agent')->user()->id)->get();

        return view('agent.booking', compact('bookings'));
    }
    function agent_customer()
    {
        $customers = Customer::where('introducer', Auth::guard("agent")->user()->id)->get();
        return view('agent.customer', compact('customers'));
    }
    function add_customer()
    {
        return view('agent.addcustomer');
    }
    function add_post_customer(Request $request)
    {
        $user = new Customer();
        $user->name     = $request['name'];
        $user->email    = $request['email'];
        $user->contact   = $request['mobile'];
        $user->introducer   =  Auth::guard('agent')->user()->id;
        $user->status = 1;
        $user->password = Hash::make($request['password']);

        $user->save();
        $customers = Customer::where('introducer', Auth::guard("agent")->user()->id)->get();
        return view('agent.customer', compact('customers'));
    }
}
