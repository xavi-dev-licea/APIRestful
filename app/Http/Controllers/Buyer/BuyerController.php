<?php

namespace APIRestful\Http\Controllers\Buyer;

use APIRestful\Buyer;
use Illuminate\Http\Request;
use APIRestful\Http\Controllers\APIController;

class BuyerController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();

        return $this->showAll($compradores);
    }

    

    
    public function show(Buyer $buyer)
    {
        //$comprador = Buyer::has('transactions')->findOrFail($id);

        return $this->showOne($buyer);
    }

    
}
