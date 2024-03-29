<?php

namespace APIRestful\Http\Controllers\Product;

use APIRestful\User;
use APIRestful\Product;
use APIRestful\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use APIRestful\Http\Controllers\APIController;
use APIRestful\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends APIController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input' . TransactionTransformer::class)->only(['store']);
    }


    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        }

        if (!$buyer->esVerificado()) {
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }

        if (!$product->seller->esVerificado()) {
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }

        if (!$product->estaDisponible()) {
            return $this->errorResponse('El producto no está disponible para esta transacción', 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('El producto no cuenta con la cantidad sufuciente para esta transacción', 409);
        }

        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }

}
