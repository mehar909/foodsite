<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    //get all menu items
    public function index()
    {
        return MenuItem::all();
        //return MenuItem::all();//where('is_available', true)->get();
    }

    //admin adds a new menu item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        $menuItem = MenuItem::create($request->only(['name', 'description', 'price', 'is_available' => true]));

        return response()->json(['message' => 'Item added', 'data' => $menuItem], 201);
    }

    //admin toggles availability of a menu item
    public function toggleAvailability($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->is_available = !$item->is_available; // Toggle availability
        $item->save();

        return response()->json(['message' => 'Availability updated']);
    }

    //admin deletes a menuItem
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }
}
