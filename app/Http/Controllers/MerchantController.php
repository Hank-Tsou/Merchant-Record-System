<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index(){
        $merchants = MerchantResource::collection(Merchant::paginate(10));
        return inertia("Merchants/Index", ['merchants' => $merchants]);
    }
}
