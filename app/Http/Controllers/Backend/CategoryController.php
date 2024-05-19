<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function AllCategory(){
        $categories = Category::latest()->get();
        return view('backend.category.category_all',compact('categories'));
    } // End Method 


    public function AddCategory(){
        return view('backend.category.category_add');
    }// End Method 



//  public function StoreCategory(Request $request){

//         $image = $request->file('category_image');
//         $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
//         //sob dhoroner chobir jonno function 
//         function loadImageFromFile($file) {
//             $extension = $file->getClientOriginalExtension();
//             switch ($extension) {
//                 case 'jpg':
//                 case 'jpeg':
//                     return imagecreatefromjpeg($file);
//                 case 'png':
//                     return imagecreatefrompng($file);
//                 case 'gif':
//                     return imagecreatefromgif($file);
//                 case 'bmp':
//                     // Not native in PHP, you may need to implement this function or use an external library
//                     return false;
//                 default:
//                     return false; // Unsupported image type
//             }
//         }
    
    
    
    
//         $img = loadImageFromFile($image);
    
//         if(!$img){
//             echo 'Unsupported image type';
//             exit;
//         }

//         $width = imagesx($img);
//         $height = imagesy($img);
    
//         $newWidth = 120; // New width
//         $newHeight = 120; // New height
    
//         $newImage = imagecreatetruecolor($newWidth, $newHeight);
//         imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
//         imagejpeg($newImage, 'upload/category/'.$name_gen);
    
//       //  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        
    
//         imagedestroy($img);
//         imagedestroy($newImage);

//         //Image::make($image)->resize(120,120)->save('upload/category/'.$name_gen);
//         $save_url = 'upload/category/'.$name_gen;

//         Category::insert([
//             'category_name' => $request->category_name,
//             'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
//             'category_image' => $save_url, 
//         ]);

//        $notification = array(
//             'message' => 'Category Inserted Successfully',
//             'alert-type' => 'success'
//         );

//         return redirect()->route('all.category')->with($notification); 

//     }// End Method 

//     public function EditCategory($id){
//         $category = Category::findOrFail($id);
//         return view('backend.category.category_edit',compact('category'));
//     }// End Method 

public function StoreCategory(Request $request){

    // Check if the category already exists
    $existingCategory = Category::where('category_name', $request->category_name)->first();
    if ($existingCategory) {
        $notification = array(
            'message' => 'Category Already Exists',
            'alert-type' => 'error'
        );
        return redirect()->route('all.category')->with($notification);
    }

    // Process the category image
    $image = $request->file('category_image');
    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

    // Function to load image from file
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
                // BMP is not natively supported in PHP, may need an external library
                return false;
            default:
                return false; // Unsupported image type
        }
    }

    $img = loadImageFromFile($image);

    if (!$img) {
        $notification = array(
            'message' => 'Unsupported image type',
            'alert-type' => 'error'
        );
        return redirect()->route('all.category')->with($notification);
    }

    // Resize the image
    $width = imagesx($img);
    $height = imagesy($img);

    $newWidth = 120; // New width
    $newHeight = 120; // New height

    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    imagejpeg($newImage, 'upload/category/'.$name_gen);
    $save_url = 'upload/category/'.$name_gen;

    imagedestroy($img);
    imagedestroy($newImage);

    // Insert the new category into the database
    Category::insert([
        'category_name' => $request->category_name,
        'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
        'category_image' => $save_url, 
    ]);

    $notification = array(
        'message' => 'Category Inserted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('all.category')->with($notification); 
}

public function EditCategory($id){
    $category = Category::findOrFail($id);
    return view('backend.category.category_edit', compact('category'));
}



//   public function UpdateCategory(Request $request){

//         $cat_id = $request->id;
//         $old_img = $request->old_image;

//         if ($request->file('category_image')) {

//         $image = $request->file('category_image');
//         $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

//         function loadImageFromFile($file) {
//             $extension = $file->getClientOriginalExtension();
//             switch ($extension) {
//                 case 'jpg':
//                 case 'jpeg':
//                     return imagecreatefromjpeg($file);
//                 case 'png':
//                     return imagecreatefrompng($file);
//                 case 'gif':
//                     return imagecreatefromgif($file);
//                 case 'bmp':
//                     // Not native in PHP, you may need to implement this function or use an external library
//                     return false;
//                 default:
//                     return false; // Unsupported image type
//             }
//         }
    
    
    
    
//         $img = loadImageFromFile($image);
    
//         if(!$img){
//             echo 'Unsupported image type';
//             exit;
//         }

//         $width = imagesx($img);
//         $height = imagesy($img);
    
//         $newWidth = 120; // New width
//         $newHeight = 120; // New height
    
//         $newImage = imagecreatetruecolor($newWidth, $newHeight);
//         imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
//         imagejpeg($newImage, 'upload/category/'.$name_gen);
    
//       //  Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        
    
//         imagedestroy($img);
//         imagedestroy($newImage);

//      //   Image::make($image)->resize(120,120)->save('upload/category/'.$name_gen);
//         $save_url = 'upload/category/'.$name_gen;

//         if (file_exists($old_img)) {
//            unlink($old_img);
//         }

//         Category::findOrFail($cat_id)->update([
//             'category_name' => $request->category_name,
//             'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
//             'category_image' => $save_url, 
//         ]);

//        $notification = array(
//             'message' => 'Category Updated with image Successfully',
//             'alert-type' => 'success'
//         );

//         return redirect()->route('all.category')->with($notification); 

//         } else {

//              Category::findOrFail($cat_id)->update([
//             'category_name' => $request->category_name,
//             'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)), 
//         ]);

//        $notification = array(
//             'message' => 'Category Updated without image Successfully',
//             'alert-type' => 'success'
//         );

//         return redirect()->route('all.category')->with($notification); 

//         } // end else

//     }// End Method 
public function UpdateCategory(Request $request){

    $cat_id = $request->id;
    $old_img = $request->old_image;

    // Check if the category name already exists for another category
    $existingCategory = Category::where('category_name', $request->category_name)
                                ->where('id', '!=', $cat_id)
                                ->first();
    if ($existingCategory) {
        $notification = array(
            'message' => 'Category Name Conflict With Another Name',
            'alert-type' => 'error'
        );
        return redirect()->route('all.category')->with($notification);
    }

    if ($request->file('category_image')) {

        $image = $request->file('category_image');
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
                    // BMP is not natively supported in PHP, may need an external library
                    return false;
                default:
                    return false; // Unsupported image type
            }
        }

        $img = loadImageFromFile($image);

        if (!$img) {
            $notification = array(
                'message' => 'Unsupported image type',
                'alert-type' => 'error'
            );
            return redirect()->route('all.category')->with($notification);
        }

        $width = imagesx($img);
        $height = imagesy($img);

        $newWidth = 120; // New width
        $newHeight = 120; // New height

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagejpeg($newImage, 'upload/category/'.$name_gen);
        $save_url = 'upload/category/'.$name_gen;

        imagedestroy($img);
        imagedestroy($newImage);

        if (file_exists($old_img)) {
            unlink($old_img);
        }

        Category::findOrFail($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
            'category_image' => $save_url, 
        ]);

        $notification = array(
            'message' => 'Category Updated with Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);

    } else {

        Category::findOrFail($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)), 
        ]);

        $notification = array(
            'message' => 'Category Updated without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }
}// End Method


    public function DeleteCategory($id){

        $category = Category::findOrFail($id);
        $img = $category->category_image;
        unlink($img ); 

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method 


}
