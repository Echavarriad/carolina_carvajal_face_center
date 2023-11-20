<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Larapack\ConfigWriter\Repository as Repo;
use App\Models\{Cart as CartModel, CartItem, Favorite, CartAddress, UserShippingPoint, Order, OrderItem, OrderAddress, Payment};
use Illuminate\Support\Facades\Artisan;
use File;
use Cart;

class SettingsController extends Controller {

    public function index() {
        // Artisan::call('cron:sync_erp');
        // Artisan::call('cache:clear');
        // Artisan::call('route:cache');
        // Artisan::call('config:cache');
        // Artisan::call('view:clear');
        // dd(config('settings'));
        $settings = config('settings');
        return view('admin.'.'settings.settings', compact('settings'));
    }

    public function reset(){
        Cart::removeCart();
        CartModel::truncate();
        CartItem::truncate();
        CartAddress::truncate();
        // Favorite::truncate();
        Order::truncate();
        OrderItem::truncate();
        OrderAddress::truncate();
        Payment::truncate();
        //UserShippingPoint::truncate();
        return redirect()->route('settings');
    }

    public function update(Request $request) {
        $config = new Repo('settings');
        $data = $request->except(['_token', '_method','logo', 'logo_footer', 'logo_email', 'favicon', 'image_default_product']);
        // dd($data);

        foreach ($data as $field => $value) {
            $config->set($field, $value);
        }

        // dd($config);

        $config->set('doc_guarantee', $this->files($request, 'doc_guarantee'));
        $config->set('doc_policy', $this->files($request, 'doc_policy'));
        $config->set('doc_catalog', $this->files($request, 'doc_catalog'));

        $fields = $data;
        if ($request->logo) {
            $request->validate([
                'logo' => 'image|mimes:jpg,jpeg,bmp,png,svg|max:3000',
            ]
            );

            $current = config('settings.shop_logo');
            if ($current != "") {
                File::delete('uploads/' . $current);
            }
            $fields['shop_logo'] = \FlipUpload::save($request->logo, 'general');
        }

        if ($request->logo_footer) {
            $request->validate([
                'logo_footer' => 'image|mimes:jpg,jpeg,bmp,png,svg|max:3000',
            ]
            );

            $current = config('settings.shop_logo_footer');
            if ($current != "") {
                File::delete('uploads/' . $current);
            }
            $fields['shop_logo_footer'] = \FlipUpload::save($request->logo_footer, 'general');
        }

        if ($request->logo_email) {
            $request->validate([
                'logo_email' => 'image|mimes:jpg,jpeg,bmp,png|max:3000',
            ]
            );
            $currentFav = config('settings.shop_logo_email');
            if ($currentFav != "") {
                File::delete('uploads/' . $currentFav);
            }
            $fields['shop_logo_email'] = \FlipUpload::save($request->logo_email, 'general');
        }
        if ($request->favicon) {
            $request->validate([
                'favicon' => 'image|mimes:jpg,jpeg,bmp,png,ico|max:3000',
            ]
            );
            $currentFav = config('settings.shop_favicon');
            if ($currentFav != "") {
                File::delete('uploads/' . $currentFav);
            }
            $fields['shop_favicon'] = \FlipUpload::save($request->favicon, 'general');
        }

        if ($request->image_default_product) {
            $request->validate([
                'image_default_product' => 'image|mimes:jpg,jpeg,bmp,png,ico|max:3000',
            ]
            );
            $currentFav = config('settings.image_default_product');
            if ($currentFav != "") {
                File::delete('uploads/' . $currentFav);
            }
            $fields['image_default_product'] = \FlipUpload::save($request->image_default_product, 'general');
        }
        if ($request->doc_policy) {
            $request->validate([
                'doc_policy' => 'mimes:pdf',
            ]
            );
            $currentFav = config('settings.doc_policy');
            if ($currentFav != "") {
                File::delete('uploads/' . $currentFav);
            }
            $fields['doc_policy'] = \FlipUpload::save($request->doc_policy, 'general');
        }
        // dd($fields);
        $this->updateFields($fields);

        session()->flash('flash.success_config', 'Configuraciones actualizadas con éxito');
        return redirect()->route('settings');
    }

    private function files($request, $name) {
        $currentName = config('settings.' . $name);
        if ($request->file($name)) {
            $request->validate([$name => 'mimes:pdf|max:10240']);
            File::delete('uploads/' . $currentName);
            return \FlipUpload::save($request->file($name) , 'files');
        }
        return $currentName;
    }

    public function updateFields($fields) {
        $config = new Repo('settings');
        foreach ($fields as $field => $value) {
            $config->set($field, $value);
        }
        $config->save();
    }

    public function mode_maintenance(){
        $data= request()->all();
        
        if($data['status']== 'true'){
            $status= true;
            Artisan::call('down');
            $message= 'Está en modo mantenimiento';
        }else{
            $status= false;
            Artisan::call('up');
            $message= 'NO esta en modo mantenimiento';
        }
        $fields[$data['id']]= $status;

        $this->updateFields($fields);

        return response()->json(['status' => true , 'message'=> $message]);
    }

    public function debug(){
        $data= request()->all();
        
        if($data['status']== 'true'){
            $status = true;
            $message= 'Modo desarrollo activado';
        }else{
            $status = false;
            $message= 'Modo producción activado';
        }
        $fields[$data['id']]= $status;
        $fields['version_cache'] = time();
        $this->updateFields($fields);

        return response()->json(['status' => true , 'message'=> $message]);
    }

}

