<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //Show all listings
    public function index(){
        
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    //Show a single listing
    public function show(Listing $listing){
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //Show a view to create a new listing
    public function create(){
        return view('listings.create');
    }

    //Create the new listing
    public function store(Request $request){
        // dd($request->file('logo'));
        $formData = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',

        ]); 

        if($request->hasFile('logo')){
            $formData['logo'] = $request->file('logo')->store('logos','public');
        }

        $formData['user_id'] = auth()->id();

        Listing::create($formData);
        
        // return redirect('/listings/' . $listing->id);

        return redirect('/')->with('message', 'Listing Created Successfully!');
    }

    //Show a view to edit an existing listing
    public function edit(Listing $listing){
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    //Update the edited listing
    public function update(Request $request, Listing $listing){

        //Make sure the user is the owner of the listing
        if($listing->user_id !== auth()->id()){
            return redirect('/listings/' . $listing->id)->with('error', 'You are not authorized to edit this listing!');
        }

        $formData = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')->ignore($listing->id)],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',

        ]); 

        if($request->hasFile('logo')){
            $formData['logo'] = $request->file('logo')->store('logos','public');
        }

        $listing->update($formData);

        return redirect('/listings/' . $listing->id)->with('message', 'Listing Updated Successfully!');
    }

    //Delete the listing
    public function destroy(Listing $listing){
        //Make sure the user is the owner of the listing
        if($listing->user_id !== auth()->id()){
            return redirect('/listings/' . $listing->id)->with('error', 'You are not authorized to edit this listing!');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing Deleted Successfully!');
    }

    //Mangae listings
    public function manage(){
        return view('listings.manage', [
            'listings' => auth()->user()->listings()->get()]);
    }

}
