<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Product;
use App\Category;
use App\Payment;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'role','phone_no','province','city'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin(){
        if (env('ADMIN') == $this->email || env('ADMIN2a') == $this->email || env('ADMIN2b') == $this->email){
            return true;
        }else{
            return false;
        }
    }

    public function isAdmin2A(){
        if($this->role == 'admin2a'){
            return true;
        }else{
            return false;
        }
    }

    public function isAdmin2B(){
        if($this->role == 'admin2b'){
            return true;
        }else{
            return false;
        }
    }


    public function isAdmin3(){
        if (env('ADMIN3') == $this->email){
            return true;
        }else{
            return false;
        }
    }

    public function isSupplier(){
        if($this->role == 'supplier'){
            return true;
        }
        return false;
    }
    public function isCustomer(){
        if (env('ADMIN') == $this->email){
            return false;
        }
        if($this->role == 'customer'){
            return true;
        }
        return false;
    }
    public function products(){
        return $this->hasMany(Product::class);
    }

    public function addProduct($params){
        return $this->products()->create($params);
    }

     public function categories(){
        return $this->hasMany(Category::class);
    }

    public function customerOrders(){
        return $this->hasMany(Order::class, 'user_id');
    }
   public function supplierOrders(){
        return $this->hasMany(Order::class, 'supplier_id');
    }

    public function customerPayments(){
        return $this->hasMany(Payment::class, 'user_id');
    }
    public function supplierPayments(){
        return $this->hasMany(Payment::class, 'supplier_id');
    }
    public function customerInsurances(){
        return $this->hasMany(Insurance::class, 'user_id');
    }
    public function getOppositeRole(){
        if ($this->role == 'customer'){
            return 'supplier';
        }elseif ($this->role == 'supplier'){
            return 'customer';
        }else{
            return '';
        }
    }

    public static function province(){
        return [
            'Abra','Agusan Del Norte','Agusan Del Sur','Aklan','Albay','Antique','Apayao','Aurora','Basilan','Bataan','Batanes','Batangas','Benguet','Biliran','Bohol','Bukidnon','Bulacan','Cagayan','Camarines Norte','Camarines Sur','Camiguin','Capiz','Catanduanes','Cavite','Cebu','Compostella Valley','Cotabato','Davao Del Norte','Davao Del Sur','Davao Occidental','Davao Oriental','Dinagat Islands','Eastern Samar','Ifugao','Ilocos Norte','Ilocos Sur','Iloilo','Isabela','Kalinga','La Union','Laguna','Lanao Del Norte','Lanao Del Sur','Leyte','Maguindanao','Marinduque','Masbate','Misamis Occidental','Misamis Oriental','Mountain Province','Negros Occidental','Negros Oriental','Northern Samar','Nueva Ecija','Nueva Vizcaya','Occidental Mindoro','Oriental Mindoro','Palawan','Pampanga','Pangasinan','Quezon','Quirino','Rizal','Romblon','Samar','Sarangani','Siquijor','Sorsogon','South Cotabato','Southern Leyte','Sultan Kudarat','Sulu','Surigao Del Norte','Surigao Del Sur','Tarlac','Tawi-Tawi','Zambales','Zamboanga Del Norte','Zamboanga Del Sur','Zamboanga Sibugay'
        ];
    }

    public static function cities(){
        return [
            'Alaminos','Angeles','Antipolo','Bacolod','Bacoor','Bago','Baguio','Bais','Balanga','Batac','Batangas City','Bayawan','Baybay','Bayugan','Biñan','Bislig','Bogo','Borongan','Butuan','Cabadbaran','Cabanatuan','Cabuyao','Cadiz','Cagayan de Oro','Calamba','Calapan','Calbayog','Caloocan','Candon','Canlaon','Carcar','Catbalogan','Cauayan','Cavite City','Cebu City','Cotabato City','Dagupan','Danao','Dapitan','Dasmariñas','Davao City','Digos','Dipolog','Dumaguete','El Salvador','Escalante','Gapan','General Santos','General Trias','Gingoog','Guihulngan','Himamaylan','Ilagan','Iligan','Iloilo City','Imus','Iriga','Isabela','Kabankalan','Kidapawan','Koronadal','La Carlota','Lamitan','Laoag','Lapu‑Lapu','Las Piñas','Legazpi','Ligao','Lipa','Lucena','Maasin','Mabalacat','Makati','Malabon','Malaybalay','Malolos','Mandaluyong','Mandaue','Manila','Marawi','Marikina','Masbate City','Mati','Meycauayan','Muñoz','Muntinlupa','Naga(Camarines Sur)','Naga (Cebu)','Navotas','Olongapo','Ormoc','Oroquieta','Ozamiz','Pagadian','Palayan','Panabo','Parañaque','Pasay','Pasig','Passi','Puerto Princesa','Quezon City','Roxas','Sagay','Samal','San Carlos (Negros Occidental)','San Carlos (Pangasinan)','San Fernando (La Union)','San Fernando (Pampanga)','San Jose','San Jose del Monte','San Juan','San Pablo','San Pedro','Santa Rosa','Santiago','Silay',' Sipalay','Sorsogon City','Surigao City','Tabaco','Tabuk','Tacloban','Tacurong','Tagaytay','Tagbilaran','Taguig','Tagum','Talisay (Cebu)','Talisay (Negros Occidental)','Tanauan','Tandag','Tangub','Tanjay','Tarlac City','Tayabas','Toledo','Trece Martires','Tuguegarao','Urdaneta','Valencia','Valenzuela','Victorias','Vigan','Zamboanga City'
        ];
    }
}








