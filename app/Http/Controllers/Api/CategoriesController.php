<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use ApiResponseTrait;

    public function getCategories(){
        $categories = Category::select('id','name_'.app()->getLocale() .' as name')->get();
        if(!$categories){
            return $this->returnErrorResponse('categories not found','404');
        }
        return $this->returnResponseData('categories',$categories,'Success','S200');
    }

    public function getCategoryById(Request $request){
        $category = Category::select('id','name_'.app()->getLocale() .' as name')->find($request->id);
        if(!$category){
            return $this->returnErrorResponse('category not found','404');
        }
        return $this->returnResponseData('categories',$category,'Success');
    }
    public function changeCategoryStatus(Request $request){
        Category::where('id',$request->id)->update(['status'=> $request->status]);
        return $this->returnSuccessMessage('Status Changed successfully');
    }
}
