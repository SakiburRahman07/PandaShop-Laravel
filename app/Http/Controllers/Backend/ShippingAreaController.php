<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistricts;
use App\Models\ShipState;
use Carbon\Carbon;

class ShippingAreaController extends Controller
{
    public function AllDivision(){
        $division = ShipDivision::latest()->get();
        return view('backend.ship.division.division_all',compact('division'));
    } // End Method 

    public function AddDivision(){
        return view('backend.ship.division.division_add');
    }// End Method 


    // public function StoreDivision(Request $request){ 

    //     ShipDivision::insert([ 
    //         'division_name' => $request->division_name, 
    //     ]);

    //    $notification = array(
    //         'message' => 'ShipDivision Inserted Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.division')->with($notification); 

    // }// End Method 

    public function StoreDivision(Request $request){ 
        $existingDivision = ShipDivision::where('division_name', $request->division_name)->first();
    
        if ($existingDivision) {
            $notification = array(
                'message' => 'Division name already exists',
                'alert-type' => 'error'
            );
            return redirect()->route('all.division')->with($notification);
        }
    
        ShipDivision::insert([ 
            'division_name' => $request->division_name, 
        ]);
    
        $notification = array(
            'message' => 'ShipDivision Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.division')->with($notification); 
    }
    

    public function EditDivision($id){

        $division = ShipDivision::findOrFail($id);
        return view('backend.ship.division.division_edit',compact('division'));

    }// End Method 


    //  public function UpdateDivision(Request $request){

    //     $division_id = $request->id;

    //      ShipDivision::findOrFail($division_id)->update([
    //         'division_name' => $request->division_name,
    //     ]);

    //    $notification = array(
    //         'message' => 'ShipDivision Updated Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.division')->with($notification); 


    // }// End Method 

    public function UpdateDivision(Request $request){
        $division_id = $request->id;
        $newDivisionName = $request->division_name;
    
        $division = ShipDivision::findOrFail($division_id);
    
        $existingDivision = ShipDivision::where('division_name', $newDivisionName)
                                         ->where('id', '!=', $division_id)
                                         ->first();
    
        if ($existingDivision) {
            $notification = array(
                'message' => 'Division name conflict with another division',
                'alert-type' => 'error'
            );
            return redirect()->route('all.division')->with($notification);
        }
    
        $division->update([
            'division_name' => $newDivisionName,
        ]);
    
        $notification = array(
            'message' => 'ShipDivision Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.division')->with($notification); 
    }
    


    public function DeleteDivision($id){

        ShipDivision::findOrFail($id)->delete();

         $notification = array(
            'message' => 'ShipDivision Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 

    /////////////// District CRUD ///////////////


    public function AllDistrict(){
        $district = ShipDistricts::latest()->get();
        return view('backend.ship.district.district_all',compact('district'));
    } // End Method 

    public function AddDistrict(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        return view('backend.ship.district.district_add',compact('division'));
    }// End Method 


// public function StoreDistrict(Request $request){ 

//         ShipDistricts::insert([ 
//             'division_id' => $request->division_id, 
//             'district_name' => $request->district_name,
//         ]);

//        $notification = array(
//             'message' => 'ShipDistricts Inserted Successfully',
//             'alert-type' => 'success'
//         );

//         return redirect()->route('all.district')->with($notification); 

//     }// End Method 

public function StoreDistrict(Request $request){ 
    $existingDistrict = ShipDistricts::where('district_name', $request->district_name)
                                     ->where('division_id', $request->division_id)
                                     ->first();

    if ($existingDistrict) {
        $notification = array(
            'message' => 'District name already exists for this division',
            'alert-type' => 'error'
        );
        return redirect()->route('all.district')->with($notification);
    }

    ShipDistricts::insert([ 
        'division_id' => $request->division_id, 
        'district_name' => $request->district_name,
    ]);

    $notification = array(
        'message' => 'ShipDistricts Inserted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('all.district')->with($notification); 
}


    public function EditDistrict($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistricts::findOrFail($id);
        return view('backend.ship.district.district_edit',compact('district','division'));

    }// End Method 


    // public function UpdateDistrict(Request $request){

    //     $district_id = $request->id;

    //      ShipDistricts::findOrFail($district_id)->update([
    //          'division_id' => $request->division_id, 
    //         'district_name' => $request->district_name,
    //     ]);

    //    $notification = array(
    //         'message' => 'ShipDistricts Updated Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.district')->with($notification); 


    // }// End Method 

    public function UpdateDistrict(Request $request){
        $district_id = $request->id;
        $newDistrictName = $request->district_name;
    
        // Retrieve the district with the given ID
        $district = ShipDistricts::findOrFail($district_id);
    
        // Check if the new district name conflicts with another district's name
        $existingDistrict = ShipDistricts::where('district_name', $newDistrictName)
                                          ->where('id', '!=', $district_id)
                                          ->where('division_id', $request->division_id)
                                          ->first();
    
        if ($existingDistrict) {
            $notification = array(
                'message' => 'District name already exists for this division',
                'alert-type' => 'error'
            );
            return redirect()->route('all.district')->with($notification);
        }
    
        $district->update([
            'division_id' => $request->division_id, 
            'district_name' => $newDistrictName,
        ]);
    
        $notification = array(
            'message' => 'ShipDistricts Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.district')->with($notification); 
    }
    


     public function DeleteDistrict($id){

        ShipDistricts::findOrFail($id)->delete();

         $notification = array(
            'message' => 'ShipDistricts Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 

     /////////////// State CRUD ///////////////


     public function AllState(){
        $state = ShipState::latest()->get();
        return view('backend.ship.state.state_all',compact('state'));
    } // End Method 


    public function AddState(){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistricts::orderBy('district_name','ASC')->get();
         return view('backend.ship.state.state_add',compact('division','district'));
    }// End Method 


    public function GetDistrict($division_id){
        $dist = ShipDistricts::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
            return json_encode($dist);

    }// End Method 

    // public function StoreState(Request $request){ 

    //     ShipState::insert([ 
    //         'division_id' => $request->division_id, 
    //         'district_id' => $request->district_id, 
    //         'state_name' => $request->state_name,
    //     ]);

    //    $notification = array(
    //         'message' => 'ShipState Inserted Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.state')->with($notification); 

    // }// End Method 

    public function StoreState(Request $request){ 
        $existingState = ShipState::where('state_name', $request->state_name)
                                   ->where('division_id', $request->division_id)
                                   ->where('district_id', $request->district_id)
                                   ->first();
    
        if ($existingState) {
            $notification = array(
                'message' => 'State already exists',
                'alert-type' => 'error'
            );
            return redirect()->route('all.state')->with($notification);
        }
    
        ShipState::insert([ 
            'division_id' => $request->division_id, 
            'district_id' => $request->district_id, 
            'state_name' => $request->state_name,
        ]);
    
        $notification = array(
            'message' => 'ShipState Inserted Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.state')->with($notification); 
    }
    

    public function EditState($id){
        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistricts::orderBy('district_name','ASC')->get();
        $state = ShipState::findOrFail($id);
         return view('backend.ship.state.state_edit',compact('division','district','state'));
    }// End Method 


    //  public function UpdateState(Request $request){

    //     $state_id = $request->id;

    //      ShipState::findOrFail($state_id)->update([
    //         'division_id' => $request->division_id, 
    //         'district_id' => $request->district_id, 
    //         'state_name' => $request->state_name,
    //     ]);

    //    $notification = array(
    //         'message' => 'ShipState Updated Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.state')->with($notification); 


    // }// End Method 

    public function UpdateState(Request $request){
        $state_id = $request->id;
        $newStateName = $request->state_name;
    
        $state = ShipState::findOrFail($state_id);
    
        $existingState = ShipState::where('state_name', $newStateName)
                                   ->where('division_id', $request->division_id)
                                   ->where('district_id', $request->district_id)
                                   ->where('id', '!=', $state_id)
                                   ->first();
    
        if ($existingState) {
            $notification = array(
                'message' => 'State name already exists for this division and district',
                'alert-type' => 'error'
            );
            return redirect()->route('all.state')->with($notification);
        }
    
        $state->update([
            'division_id' => $request->division_id, 
            'district_id' => $request->district_id, 
            'state_name' => $newStateName,
        ]);
    
        $notification = array(
            'message' => 'ShipState Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('all.state')->with($notification); 
    }
    

 public function DeleteState($id){

        ShipState::findOrFail($id)->delete();

         $notification = array(
            'message' => 'ShipState Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 
}
