<?php

namespace App\Http\Controllers;

use App\Show;
use App\Representation;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class CartController extends Controller
{

    public function index()
    {

        return view ('cart.index');
    }
    
    public function store(Request $request)
    {        
        /*$duplicata = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id == $request->show_id;
        });

        if ($duplicata->isNotEmpty()) {
            return back()->with('success', 'Le spectacle a déjà été ajouté.');
        }*/
        $rep =  Representation::find($request->representation_id);
        
        // $show =  Show::find($request->show_id);
        
        /*Cart::add($rep->id, $rep->show->title,1, $rep->show->price)
                ->associate('App\Representation');*/
        
        /*Cart::add($show->id, $show->title,1, $show->price)
                ->associate('App\Show');*/
        Cart::add(['id' => $rep->show->id, 'name' => $rep->show->title, 'qty' => 1, 'price' => $rep->show->price, 'options' => ['date' => $rep->when]]);
        

        return back()->with('success','Le produit a bien été ajouté.');

    }
    
    public function update(Request $request, $rowId)
    {
        $data = $request->json()->all();
        
        $validates = Validator::make($request->all(), [
            'qty' => 'numeric|required|between:1,10',
        ]);

        if ($validates->fails()) {
            Session::flash('error', 'Le nombre de places doit est compris entre 1 et 10.');
            return response()->json(['error' => 'Le nombre de places n\'a pas été mis à jour']);
        }
        
        Cart::update($rowId, $data['qty']);

        Session::flash('success', 'Le nombre de places est passé à ' . $data['qty'] . '.');
        return response()->json(['success' => 'Le nombre de places a été mis à jour']);
    }
    
    public function destroy($rowId)
    {
        Cart::remove($rowId);
        
        return back()->with('success', 'L\'article a bien été supprimé.');
    }
}
