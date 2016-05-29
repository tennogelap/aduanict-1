<?php

namespace App\Http\Controllers;

use App\AssetsLocation;
use App\Branch;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Complain;
use App\User;
use App\ComplainSource;
use App\ComplainCategory;

use Validator;
use App\Http\Requests\ComplainRequest;
use Auth;

class ComplainController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complains = Complain::orderBy('complain_id','desc')->paginate(20);

        return view('complains/index',compact('complains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //prepare users record for dropdown

        $users = User::where('id','!=',Auth::user()->id)->lists('name','id');

        $users = array(''=>'Pilih Pengguna') + $users->all();

        //prepare complain category for dropdown

        $complain_categories = $this->get_complain_categories();

        //prepare complain source for dropdown

        $complain_sources = $this->get_complain_sources();

        //prepare locations dropdown

        $locations = $this->get_locations();

        //prepare branch dropdown

        $branches = $this->get_branches();

        //prepare assets dropdown

        $assets = $this->get_assets();

        return view('complains/create',compact('users','complain_categories','complain_sources','locations','branches','assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComplainRequest $request)
    {
        $user_id = Auth::user()->id;

        $complain_description = $request->complain_description;
        $user_emp_id = $request->user_emp_id;

        if (empty($user_emp_id))
        {
            $user_emp_id = Auth::user()->id;
        }

        //initilize complain object

        $complain = new Complain;
        $complain->user_id = $user_id;
        $complain->complain_description = $complain_description;
        $complain->user_emp_id = $user_emp_id;

        $complain->complain_category_id = $request->complain_category_id;
        $complain->complain_source_id = $request->complain_source_id;

        $aduan_category_exception_value = array('5','6');

        if (!in_array($request->complain_category_id,$aduan_category_exception_value))
        {
            //$complain->branch_id = $request->branch_id;
            $complain->lokasi_id = $request->lokasi_id;
            $complain->ict_no = $request->ict_no;
        }
        else
        {
            //$complain->branch_id = null;
            $complain->lokasi_id = null;
            $complain->ict_no = null;
        }

        //save complain object

        $complain->save();

        //after success, redirect to index

        return redirect(route('complain.index'));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('complains/show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complain = Complain::find($id);

        return view('complains/edit',compact('complain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComplainRequest $request, $id)
    {
        $complain_description = $request->complain_description;
        
        $complain = Complain::find($id);

        $complain->complain_description = $complain_description;
        
        $complain->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complain = Complain::find($id);
        $complain->delete();

        return back();
    }

    /*
     * Get complain categories
     * */

    function get_complain_categories()
    {
        //prepare complain category for dropdown

        $complain_categories = ComplainCategory::lists('description','category_id');

        $complain_categories = array(''=>'Pilih Kategori') + $complain_categories->all();

        return $complain_categories;
    }

    function get_complain_sources()
    {
        $complain_sources = ComplainSource::lists('description','source_id');

        $complain_sources = array(''=>'Pilih Kaedah') + $complain_sources->all();

        return $complain_sources;
    }

    function get_locations()
    {
        $branch_id = \Request::input('branch_id');

        if (!empty($branch_id))
        {
            $locations = AssetsLocation::where('branch_id',$branch_id)->lists('location_description','location_id');
        }
        else
        {
            $locations = AssetsLocation::lists('location_description','location_id');
        }

        $locations = array(''=>'Pilih Lokasi') + $locations->all();

        return $locations;
    }

    function get_assets()
    {
        $assets = array('1'=>'PC','2'=>'Laptop','3'=>'Projektor');

        return $assets;
    }

    function get_assets_real()
    {
        $lokasi_id = \Request::input('lokasi_id');

        if (!empty($lokasi_id))
        {
            $locations = AssetsLocation::where('lokasi_id',$lokasi_id)->lists('location_description','location_id');
        }
        else
        {
            $locations = AssetsLocation::lists('location_description','location_id');
        }

        $locations = array(''=>'Pilih Lokasi') + $locations->all();

        return $locations;
    }

    function get_branches()
    {
        $branches = Branch::lists('branch_description','id');

        $branches = array(''=>'Pilih Cawangan') + $branches->all();

        return $branches;
    }

}
