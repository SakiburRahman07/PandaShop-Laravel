<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    
    public function VendorAllProduct(){

        $id = Auth::user()->id;
        $products = Product::where('vendor_id',$id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all',compact('products'));
    } // End Method 

    public function VendorAddProduct(){

        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('vendor.backend.product.vendor_product_add',compact('brands','categories'));

    } // End Method 

    public function VendorGetSubCategory($category_id){
        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
            return json_encode($subcat);

    }// End Method 

    public function VendorStoreProduct(Request $request){


        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        function loadImageFromFile($file) {
            $extension = $file->getClientOriginalExtension();
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    return imagecreatefromjpeg($file);
                case 'png':
                    return imagecreatefrompng($file);
                case 'gif':
                    return imagecreatefromgif($file);
                case 'bmp':
                    // Not native in PHP, you may need to implement this function or use an external library
                    return false;
                default:
                    return false; // Unsupported image type
            }
        }
    
    
    
    
        $img = loadImageFromFile($image);
    
        if(!$img){
            echo 'Unsupported image type';
            exit;
        }

        $width = imagesx($img);
        $height = imagesy($img);
    
        $newWidth = 800; // New width
        $newHeight = 800; // New height
    
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        imagejpeg($newImage, 'upload/products/thambnail/'.$name_gen);
    
      //  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        
    
        imagedestroy($img);
        imagedestroy($newImage);

      //  Image::make($image)->resize(800,800)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

        $product_id = Product::insertGetId([

            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,
            'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),

            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp, 

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals, 

            'product_thambnail' => $save_url,
            'vendor_id' => Auth::user()->id,
            'status' => 1,
            'created_at' => Carbon::now(), 

        ]);

        /// Multiple Image Upload From her //////

        $images = $request->file('multi_img');
        foreach($images as $imgg){
            $make_name = hexdec(uniqid()).'.'.$imgg->getClientOriginalExtension();

        
        
            $img = loadImageFromFile($imgg);
        
            if(!$img){
                echo 'Unsupported image type';
                exit;
            }
    
            $width = imagesx($img);
            $height = imagesy($img);
        
            $newWidth = 300; // New width
            $newHeight = 300; // New height
        
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
            imagejpeg($newImage, 'upload/products/multi-image/'.$make_name);
        
          //  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
            
        
            imagedestroy($img);
            imagedestroy($newImage);

      //  Image::make($img)->resize(800,800)->save('upload/products/multi-image/'.$make_name);
        $uploadPath = 'upload/products/multi-image/'.$make_name;


        MultiImg::insert([

            'product_id' => $product_id,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(), 

        ]); 
        } // end foreach

        /// End Multiple Image Upload From her //////

        $notification = array(
            'message' => 'Vendor Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.all.product')->with($notification); 


    } // End Method 

    public function VendorEditProduct($id){

        $multiImgs = MultiImg::where('product_id',$id)->get();

        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $products = Product::findOrFail($id);
        return view('vendor.backend.product.vendor_product_edit',compact('brands','categories', 'products','subcategory','multiImgs'));
    }// End Method 



public function VendorUpdateProduct(Request $request){

             $product_id = $request->id;

             Product::findOrFail($product_id)->update([

            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_name' => $request->product_name,
            'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),

            'product_code' => $request->product_code,
            'product_qty' => $request->product_qty,
            'product_tags' => $request->product_tags,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp, 

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,  

            'status' => 1,
            'created_at' => Carbon::now(), 

        ]);


         $notification = array(
            'message' => 'Vendor Product Updated Without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.all.product')->with($notification); 

    }// End Method 



    public function VendorUpdateProductThabnail(Request $request){

        $pro_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

        function loadImageFromFile($file) {
            $extension = $file->getClientOriginalExtension();
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    return imagecreatefromjpeg($file);
                case 'png':
                    return imagecreatefrompng($file);
                case 'gif':
                    return imagecreatefromgif($file);
                case 'bmp':
                    // Not native in PHP, you may need to implement this function or use an external library
                    return false;
                default:
                    return false; // Unsupported image type
            }
        }
    
    
    
    
        $img = loadImageFromFile($image);
    
        if(!$img){
            echo 'Unsupported image type';
            exit;
        }

        $width = imagesx($img);
        $height = imagesy($img);
    
        $newWidth = 800; // New width
        $newHeight = 800; // New height
    
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        imagejpeg($newImage, 'upload/products/thambnail/'.$name_gen);
    
      //  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        
    
        imagedestroy($img);
        imagedestroy($newImage);


       // Image::make($image)->resize(800,800)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

         if (file_exists($oldImage)) {
           unlink($oldImage);
        }

        Product::findOrFail($pro_id)->update([

            'product_thambnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Vendor Product Image Thambnail Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 


    //Vendor Multi Image Update 
    public function VendorUpdateProductmultiImage(Request $request){

        $imgs = $request->multi_img;

        foreach($imgs as $id => $imgg ){
            $imgDel = MultiImg::findOrFail($id);
            unlink($imgDel->photo_name);

   $make_name = hexdec(uniqid()).'.'.$imgg->getClientOriginalExtension();

   function loadImageFromFile($file) {
    $extension = $file->getClientOriginalExtension();
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            return imagecreatefromjpeg($file);
        case 'png':
            return imagecreatefrompng($file);
        case 'gif':
            return imagecreatefromgif($file);
        case 'bmp':
            // Not native in PHP, you may need to implement this function or use an external library
            return false;
        default:
            return false; // Unsupported image type
    }
}




$img = loadImageFromFile($imgg);

if(!$img){
    echo 'Unsupported image type';
    exit;
}

$width = imagesx($img);
$height = imagesy($img);

$newWidth = 800; // New width
$newHeight = 800; // New height

$newImage = imagecreatetruecolor($newWidth, $newHeight);
imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

imagejpeg($newImage, 'upload/products/multi-image/'.$make_name);

//  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);


imagedestroy($img);
imagedestroy($newImage);


      //  Image::make($img)->resize(800,800)->save('upload/products/multi-image/'.$make_name);
        $uploadPath = 'upload/products/multi-image/'.$make_name;

        MultiImg::where('id',$id)->update([
            'photo_name' => $uploadPath,
            'updated_at' => Carbon::now(),

        ]); 
        } // end foreach

         $notification = array(
            'message' => 'Vendor Product Multi Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method 


 public function VendorMultiimgDelete($id){
        $oldImg = MultiImg::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImg::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Vendor Product Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 

    public function VendorProductInactive($id){

        Product::findOrFail($id)->update(['status' => 0]);
        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 


      public function VendorProductActive($id){

        Product::findOrFail($id)->update(['status' => 1]);
        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 


     public function VendorProductDelete($id){

        $product = Product::findOrFail($id);
        unlink($product->product_thambnail);
        Product::findOrFail($id)->delete();

        $imges = MultiImg::where('product_id',$id)->get();
        foreach($imges as $img){
            unlink($img->photo_name);
            MultiImg::where('product_id',$id)->delete();
        }

        $notification = array(
            'message' => 'Vendor Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 


}
