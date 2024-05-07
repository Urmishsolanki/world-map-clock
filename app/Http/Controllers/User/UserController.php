<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car_info;
use App\Models\Body_type;
use App\Models\Brand;
use App\Models\Car_category;
use App\Models\Fuel_type;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Equipments;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Paymenthistory;
use Validator;
use Response;
use Redirect;
use DB,DateTime;
use PHPMailer;
use DataTables;

class UserController extends Controller
{
    public function submitad(){
        //$id=Auth::user()->id;
        //$cardata = Car_info::where('id',$id)->first();
        $formtype="1";  
        $brands = Brand::all();   
        $body_types = Body_type::all();
        $years = get_year(); 
        $equipments = Equipments::all(); 
        $condition=vehicle_condition();  
        $get_kms=get_kms(); 
        $color=Color::all();      
        $fuel_type=Fuel_type::all();     
        $template = 'home_list';
        $media_id = 'home_list'; 
        return view('submitad',compact('fuel_type','color','equipments','formtype','years','body_types','brands','condition','template','media_id','get_kms'));
    } 

    public function store(Request $request)
    {
        // code...
        $input = $request->all();  
        if($input['formtype'] == 1){
            //echo "<pre>"; print_r($input); exit();            
            $messages = [ 
                'brand_id.required' => 'Required field.',
                'model_id.required' => 'Required field.',
                'name.required' => 'Required field.',
                'fuel_type.required' => 'Required field.',
                'greade.required' => 'Required field.',
                'door.required' => 'Required field.',             
                'cc.required' => 'Required field.',
                'seat.required' => 'Required field.',
                'body_type.required' => 'Required field.',   
                'transmission.required' => 'Required field.',
                'drive.required' => 'Required field.',                 
                'r-year.required' => 'Required field.',
                'dimension.required' => 'Required field.',
                'color' => 'Required field.',
                'year.required' =>'Required field.',
                'm3.required' => 'Required field.',                 
                'port.required' => 'Required field.',
                'stereo.required' => 'Required field.',            
                'tyre.required' =>'Required field.',
                'vin.required' =>'Required field.',
               // 'url.required' =>'Required field.',
                'pab_no.required' =>'Required field.',
                'condition.required' =>'Required field.',
                'miles.required' =>'Required field.',  
                'price.required' =>'Required field.',        
            ];
            $fields=[
                //'formtype' => $request->get('formtype'),
                'brand_id' => $request->get('brand_id'),
                'model_id' => $request->get('model_id'),
                'name' => $request->get('name'),
                'fuel_type' => $request->get('fuel_type'),
                'greade' => $request->get('greade'),
                'door' => $request->get('door'),
                'cc' => $request->get('cc'),
                'seat' => $request->get('seat'),
                'body_type' => $request->get('body_type'),
                'transmission' => $request->get('transmission'),
                'color' => $request->get('color'),
                'drive' => $request->get('drive'),
                'r-year' => $request->get('r-year'),
                'dimension' => $request->get('dimension'),
                'year' => $request->get('year'),
                'm3' => $request->get('m3'),
                'port' => $request->get('port'),
                'stereo' => $request->get('stereo'),
                'tyre' => $request->get('tyre'),
                'vin' => $request->get('tyre'),
              //  'url' => $request->get('url'),
                'pab_no' => $request->get('pab_no'),
                'condition' => $request->get('condition'),
                'miles' => $request->get('miles'),
                'price' => $request->get('miles'),
            ];
            
            $rules=[                
                'brand_id' => 'required',
                'model_id' => 'required',
                'name' => 'required',
                'fuel_type' => 'required',
                'greade' => 'required',
                'door' => 'required',                
                'cc' => 'required',
                'seat' => 'required',   
                'body_type' => 'required',   
                'transmission' => 'required',  
                'color' => 'required',  
                'drive' => 'required',                   
                'r-year' => 'required', 
                'dimension' => 'required',   
                'year' => 'required',   
                'm3' => 'required',                   
                'port' => 'required',
                'stereo' => 'required',                
                'tyre' => 'required', 
                'vin' => 'required',   
                //'url' => 'required', 
                'pab_no' => 'required',
                'condition' => 'required',
                'miles' => 'required',
                'price' => 'required',
            ];
            $validator = Validator::make($fields, $rules, $messages);
            if($validator->fails()){
                return Redirect::to('submit-ad')->withInput()->withErrors($validator);
            }else{               
                $brand_name='';  $model_name='';
                if($input['brand_id'] !=""){
                    $brandsname = Brand::where('id','=',$input['brand_id'])->first(); 
                    $brand_name=$brandsname->brand_name;
                }
                if($input['model_id'] !=""){
                    $modelname = CarModel::where('id','=',$input['model_id'])->first();
                    $model_name=$modelname->model_name;
                }  
                $car_name = $brand_name.' '.$model_name; 
                $caradd = new Car_info();
                $caradd->user_id = "1";  
                $caradd->formtype = $input['formtype'];              
                $caradd->brand_id = $input['brand_id'];
                $caradd->model_id = $input['model_id'];
                $caradd->name = $input['name']; 
                $caradd->car_name = $car_name; 
                $caradd->fuel_id = $input['fuel_type'];
                $caradd->greade = $input['greade'];              
                $caradd->door = $input['door'];
                $caradd->cc = $input['cc'];
                $caradd->seat = $input['seat'];
                $caradd->body_type = $input['body_type'];
                $caradd->transmission = $input['transmission'];
                $caradd->color = $input['color'];
                $caradd->drive = $input['drive'];
                $caradd->r_year = $input['r-year'];
                $caradd->dimension = $input['dimension'];
                $caradd->year = $input['year'];
                $caradd->m3 = $input['m3'];
                $caradd->port = $input['port'];
                $caradd->stereo = $input['stereo'];
                $caradd->tyre = $input['tyre'];
                $caradd->vin = $input['vin'];
               // $caradd->url = $input['url']; 
                $caradd->pab_no = $input['pab_no']; 
                $caradd->vehicle_condition = $input['condition']; 
                $caradd->miles = $input['miles']; 
                $caradd->price = $input['price'];       
                $caradd->publishdate = date('Y-m-d H:i:s');            
                $caradd->created_at = date('Y-m-d H:i:s');  
                $caradd->updated_at = date('Y-m-d H:i:s');
                $caradd->save();

                return redirect('/user/car-edit/'.$caradd->id.'/2');
            }
        }

    } 

    public function edit($id,$formtype){        
        $cardata = Car_info::where('id',$id)->first();
        $brands = Brand::all();   
        $body_types = Body_type::all();
        $years = get_year(); 
        $equipments = Equipments::all(); 
        $condition=vehicle_condition();  
        $get_kms=get_kms();  
        $model=CarModel::where('brand_id',$cardata->brand_id)->get();
        $color=Color::all();      
        $fuel_type=Fuel_type::all();  
        $template = 'home_list';
        $media_id = 'home_list'; 
        return view('submitedit',compact('fuel_type','color','id','cardata','equipments','formtype','years','body_types','brands','condition','template','media_id','get_kms','model'));
    }

    public function update(Request $request)
    {
        $input = $request->all();
        //echo "<pre>"; print_r($input); exit();
        $id = $input['carid'];

        if($input['formtype'] == 1){
            $messages = [ 
                'brand_id.required' => 'Required field.',
                'model_id.required' => 'Required field.',
                'name.required' => 'Required field.',
                'fuel_type.required' => 'Required field.',
                'greade.required' => 'Required field.',
                'door.required' => 'Required field.',             
                'cc.required' => 'Required field.',
                'seat.required' => 'Required field.',
                'body_type.required' => 'Required field.',   
                'transmission.required' => 'Required field.',
                'drive.required' => 'Required field.',                 
                'r-year.required' => 'Required field.',
                'dimension.required' => 'Required field.',
                'color' => 'Required field.',
                'year.required' =>'Required field.',
                'm3.required' => 'Required field.',                 
                'port.required' => 'Required field.',
                'stereo.required' => 'Required field.',            
                'tyre.required' =>'Required field.',
                'vin.required' =>'Required field.',
                //'url.required' =>'Required field.',
                'pab_no.required' =>'Required field.',
                'condition.required' =>'Required field.',
                'miles.required' =>'Required field.',  
                'price.required' =>'Required field.',       
            ];
            $fields=[
                //'formtype' => $request->get('formtype'),
                'brand_id' => $request->get('brand_id'),
                'model_id' => $request->get('model_id'),
                'name' => $request->get('name'),
                'fuel_type' => $request->get('fuel_type'),
                'greade' => $request->get('greade'),
                'door' => $request->get('door'),
                'cc' => $request->get('cc'),
                'seat' => $request->get('seat'),
                'body_type' => $request->get('body_type'),
                'transmission' => $request->get('transmission'),
                'color' => $request->get('color'),
                'drive' => $request->get('drive'),
                'r-year' => $request->get('r-year'),
                'dimension' => $request->get('dimension'),
                'year' => $request->get('year'),
                'm3' => $request->get('m3'),
                'port' => $request->get('port'),
                'stereo' => $request->get('stereo'),
                'tyre' => $request->get('tyre'),
                'vin' => $request->get('tyre'),
                //'url' => $request->get('url'),
                'pab_no' => $request->get('pab_no'),
                'condition' => $request->get('condition'),
                'miles' => $request->get('miles'),
                'price' => $request->get('price'),
            ];
            
            $rules=[                
                'brand_id' => 'required',
                'model_id' => 'required',
                'name' => 'required',
                'fuel_type' => 'required',
                'greade' => 'required',
                'door' => 'required',                
                'cc' => 'required',
                'seat' => 'required',   
                'body_type' => 'required',   
                'transmission' => 'required',  
                'color' => 'required',  
                'drive' => 'required',                   
                'r-year' => 'required', 
                'dimension' => 'required',   
                'year' => 'required',   
                'm3' => 'required',                   
                'port' => 'required',
                'stereo' => 'required',                
                'tyre' => 'required', 
                'vin' => 'required',   
                //'url' => 'required', 
                'pab_no' => 'required',
                'condition' => 'required',
                'miles' => 'required',
                'price' => 'required',
            ];

            $validator = Validator::make($fields, $rules, $messages);
            if($validator->fails()){                
                return Redirect::to('/user/car-edit/'.$id.'/'.$input['formtype'])->withInput()->withErrors($validator);
            }else{   
                $brand_name='';  $model_name='';
                if($input['brand_id'] !=""){
                $brandsname = Brand::where('id','=',$input['brand_id'])->first(); 
                $brand_name=$brandsname->brand_name;
                }

                if($input['model_id'] !=""){
                $modelname = CarModel::where('id','=',$input['model_id'])->first();
                $model_name=$modelname->model_name;
                }  
                $car_name = $brand_name.' '.$model_name;           
                $caradd = Car_info::find($id);
                $caradd->user_id = "1";  
                $caradd->formtype = $input['formtype'];              
                $caradd->brand_id = $input['brand_id'];
                $caradd->model_id = $input['model_id'];
                $caradd->name = $input['name']; 
                $caradd->car_name = $car_name;                              
                $caradd->brand_id = $input['brand_id'];
                $caradd->fuel_id = $input['fuel_type'];
                $caradd->greade = $input['greade'];              
                $caradd->door = $input['door'];
                $caradd->cc = $input['cc'];
                $caradd->seat = $input['seat'];
                $caradd->body_type = $input['body_type'];
                $caradd->transmission = $input['transmission'];
                $caradd->color = $input['color'];
                $caradd->drive = $input['drive'];
                $caradd->r_year = $input['r-year'];
                $caradd->dimension = $input['dimension'];
                $caradd->year = $input['year'];
                $caradd->m3 = $input['m3'];
                $caradd->port = $input['port'];
                $caradd->stereo = $input['stereo'];
                $caradd->tyre = $input['tyre'];
                $caradd->vin = $input['vin'];
                //$caradd->url = $input['url']; 
                $caradd->pab_no = $input['pab_no']; 
                $caradd->vehicle_condition = $input['condition']; 
                $caradd->miles = $input['miles']; 
                $caradd->price = $input['price']; 

                /*if($input['publishdate']!=""){
                    $date =explode("-",$input['publishdate']);
                    $time= explode(' ', $date[2]);
                    $time1 = explode(':', $time[1]);
                    $finaldateTime = $time[0].'-'.$date[0].'-'.$date[1].' '.$time1[0].':'.$time1[1].':'.date('s');
                    $caradd->publishdate =  $finaldateTime;                           
                }else
                {
                    $caradd->publishdate =date('Y-m-d H:i:s');    
                }*/

                $caradd->status = "pending"; 
                $caradd->created_at = date('Y-m-d H:i:s');  
                $caradd->updated_at = date('Y-m-d H:i:s');
                $caradd->save();

                return redirect('/user/car-edit/'.$caradd->id.'/2');
            }
        }elseif($input['formtype'] == 2){
            $carupdate = Car_info::find($id);

            //Car Features Validate
            $messages = [ 
                'car_features.required' => 'Atleast One Checkbox Checked.',
            ];  
            $fields=[
                'car_features' => $request->get('car_features'),
            ];   
            $rules=[                
                'car_features' => 'required',
            ];
            $validator = Validator::make($fields, $rules, $messages);
            if($validator->fails()){                
                return Redirect::to('/user/car-edit/'.$carupdate->id.'/2')->withInput()->withErrors($validator);
            }else{
                $data = array();
                $maindata = json_encode($input['car_features']); 
            } 

            $carupdate->car_features = $maindata;
            $carupdate->updated_at = date('Y-m-d H:i:s');
            $carupdate->save();
            return redirect('/user/car-edit/'.$carupdate->id.'/3');

        }elseif($input['formtype'] == 3){  
            $caradd = Car_info::find($id);
            $messages = [ 
                'firstname.required' => 'Required field.',
                'lastname.required' => 'Required field.',
                'phone.required' => 'Required field.',
                'email.required' => 'Required field.',
                'address.required' => 'Required field.',
            ];  
            $fields=[
                'firstname' => $request->get('firstname'),
                'lastname' => $request->get('lastname'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'address' => $request->get('address'),
            ];   
            $rules=[                
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'address' => 'required',

            ];
            $validator = Validator::make($fields, $rules, $messages);
            if($validator->fails()){                
                return Redirect::to('/user/car-edit/'.$caradd->id.'/3')->withInput()->withErrors($validator);
            }else{
                $caradd->firstname = $input['firstname'];              
                $caradd->lastname = $input['lastname'];
                $caradd->phone = $input['phone'];
                $caradd->email = $input['email']; 
                $caradd->address = $input['address']; 
                $caradd->save();
                return redirect('/user/car-edit/'.$caradd->id.'/4');
            }
        }elseif($input['formtype'] == 4){ 
            $caradd = Car_info::find($id);

            //echo "<pre>"; print_r($input); exit();


            return redirect('/user/car-edit/'.$caradd->id.'/4');
        }
    }



    public function modelSelect($brandid){
        $data = array();
        $modelget = CarModel::where('brand_id',$brandid)->get();        
        if(!empty($modelget)){
            foreach($modelget as $key =>$value){
                $data[] = array('id' => $value->id, 'model_name' => $value->model_name);

            }
        }
        if(!empty($data)){
            return $data;
        }
    }
    public function carextimage($position,$id){
        $data = "";
        $car = Car_info::select('id','ext_images')->where('id',$id)->first();
        $ext_json = json_decode($car->ext_images);
        if(!empty($ext_json[$position])){
            $data = array("imgSrcExt" => url('public/images/noimage.png'));
            if(file_exists(public_path('/car_all/cars/'.$ext_json[$position]))){
                $data = array("imgSrcExt" => url('public/car_all/cars/'.$ext_json[$position]));
            }
        }else{
            $data = array("imgSrcExt" => url('public/images/noimage.png'));
        }
        echo json_encode($data);
    }

    public function carpayment(Request $request,$id){
            $car_info = Car_info::find($id); 

            $input = $request->all();
            $CLIENTID="Aa0GN8wjGW9bSCWi2dISbPVnaeruu510nc8ggbR5Xaqk_w0CT8hJVmoMN2vazaKX3-ngreWG7X27qOLK";
            $CLIENTSECRET="EIIR6McuFJ9P0WnwZKYZE7H-KsYThbOYgomcslwUUun8MyfDmJBMF7u4HKId6j-0kXLGwmm8Z9Qw-U2a";
            $PAYPAL_API_URL="https://api.sandbox.paypal.com/";

            $ch = curl_init();
            $client= $CLIENTID;
            $secret= $CLIENTSECRET; 
            curl_setopt($ch, CURLOPT_URL, $PAYPAL_API_URL."v1/oauth2/token");
            /*curl_setopt($ch, CURLOPT_URL, “https://api.paypal.com/v1/oauth2/token”);*/
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $client.":".$secret);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            $result = curl_exec($ch);

            if(empty($result))die("Error: No response.");
            else
            {
                $json = json_decode($result);                
              $accessToken=$json->access_token;
            } 

            //Define Payment Related Test Detail             
            $first_name=$car_info->firstname;
            $last_name=$car_info->lastname;
            $email= $car_info->email;
            $address= $car_info->address;   
            $phone= $car_info->phone;
            $city= "Ahemedbad";
            $country= "India";
            $zip="380009";
            $state="Gujrat";  

            //Card Details
            $ccnum= $input['card_number'];
            $credit_card_type= "visa";
            $ccmo= $input['exp_date_mm'];
            $ccyr= $input['exp_date_yy'];
            $cvv2_number= $input['csc_value'];            
            $cost= $input['user_payment_amount'];   

            $ch = curl_init();
            $data = '{
            "intent": "sale",
                "payer": {
                "payment_method": "credit_card",
                "payer_info": {
                "email": "'.$email.'",
                "shipping_address": {
                "recipient_name": "'.$first_name.' '.$last_name.'",
                "line1": "'.$address.'",
                "city": "'.$city.'",
                "country_code": "'.$country.'",
                "postal_code": "'.$zip.'",
                "state": "'.$state.'",
                "phone": "'.$phone.'"
            },
            "billing_address": {
            
            }
            },
            "funding_instruments": [{
            "credit_card": {
            "number": "'. $ccnum.'",
            "type": "'.$credit_card_type.'",
            "expire_month": "'.$ccmo.'",
            "expire_year": "'.$ccyr.'",
            "cvv2": "'.$cvv2_number.'",
            "first_name": "'.$first_name.'",
            "last_name": "'.$last_name.'",
            "billing_address": {
            
                        }
                    }
                }]
            },
            "transactions": [{
            "amount": {
            "total": "'.$cost.'",
            "currency": "USD"
            }    
           
                }]
            }';
            curl_setopt($ch, CURLOPT_URL, $PAYPAL_API_URL."v1/payments/payment");
            /*curl_setopt($ch, CURLOPT_URL, “https://api.paypal.com/v1/payments/payment”);*/
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$json->access_token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            //curl_close($ch);
            $json = json_decode($result);
            //echo '<pre>';print_r($json);

            if(isset($json->name) && $json->name=='VALIDATION_ERROR'){
                //echo 'error';
                $str1 = '<ul class="error">';
                foreach ($json->details as $key => $value) {
                    $a = '';
                    if($value->field=='payer.funding_instruments[0].credit_card.number'){

                        $a = 'Card Number';
                    }elseif($value->field=='payer.funding_instruments[0].credit_card.expire_month'){
                        $a = 'Expiration Month';
                    }elseif($value->field=='payer.funding_instruments[0].credit_card.cvv2'){
                        $a = 'Expiration CVV';
                    }elseif($value->field=='payer.funding_instruments[0].credit_card.expire_year'){
                        $a = 'Expiration Year';
                    }

                    if($value->issue=='{NotCvv2Number}'){
                        $str1 .= '<li>'.$a.' '.'Must not be blank'.'</li>';
                    }else{
                        $str1 .= '<li>'.$a.' '.$value->issue.'</li>';    
                    }                    
                }
                $str1 .='</ul>';
                $str=0;
                exit(json_encode(array('data' => $str,'e' =>$str1)));
            }

            if(isset($json->name) && $json->name=='DCC_PREPROCESSOR_ERROR'){
                $str11 = '<ul class="error">';
                    $str11 .= '<li>Please enter valid card detail</li>';
                $str11 .= '</ul>';
                $str=0;
                exit(json_encode(array('data' => $str,'e' =>$str11)));
            }

            if($json->state=="approved"){
                $payment=new Paymenthistory();
                $payment->car_id =$car_info->id;
                $payment->transaction_id = $json->transactions[0]->related_resources[0]->sale->id;    
                $payment->customer_id = "";                
                $payment->invoice_no =rand();;    
                $payment->amount =$json->transactions[0]->amount->total; 
                $payment->data =$result;    
                $payment->payment_status = "paid";  
                $payment->payment_date = date('Y-m-d H:i:s');  
                $payment->created_at = date('Y-m-d H:i:s');  
                $payment->updated_at = date('Y-m-d H:i:s');
                $payment->save();
                $str=1;
                exit(json_encode(array('data' => $str,)));
            }else{
                $str=0;
                exit(json_encode(array('data' => $str,)));
            }
    }

    public function paypal_return_url(Request $request){
        $template = 'home_list';
        $media_id = 'home_list'; 
        return view('thankyou',compact('template','media_id') );
    }

}
