<?php
namespace App\Service\Categories;

use App\Http\Requests\Category\CategoryRequest;
use App\Models\Categories\CategoryTranslation;
use App\Models\Categories\Category;
use App\Scopes\SectionScope;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class CategoryService
{
    use GeneralTrait;
    private $categoryModel;
    private $categoryTranslation;
    private $PAGINATION_COUNT;

    /**
     * Category Service constructor.
     * @param Category $category
     * @param CategoryTranslation $categoryTranslation
     */
    public function __construct(Category $category , CategoryTranslation $categoryTranslation)
    {
        $this->categoryModel=$category;
        $this->PAGINATION_COUNT=25;
        $this->categoryTranslation=$categoryTranslation;
    }
    /*___________________________________________________________________________*/
/** category list  */
    public function list()
    {
     try{
         $list = $this->categoryModel
             ->with(['Section'=> function ($q) {
                         return $q->withoutGlobalScope(SectionScope::class)
                             ->select(['sections.id'])
                             ->with(['SectionTranslation'=>function($q){
                                 return $q->where('section_translations.local',
                                     '=',
                                     Config::get('app.locale'))
                                     ->select(['section_translations.name','section_translations.section_id'])
                                     ->get();
                             },'Category'])
                     ->get();
             }])
             ->get();
         return $this->returnData('category',$list,200);
        }catch(\Exception $ex){
         return $this->returnError('400', $ex->getMessage());
     }
    }
    /*___________________________________________________________________________*/
    /****Get All Active category Or By ID  ****/
    public function getAll()
    {
        try{
            Gate::authorize('Read Category');

            $category = $this->categoryModel->with(['Section','Parent'])->paginate($this->PAGINATION_COUNT);
            if (count($category) > 0){
                return $this->returnData('Category',$category,'done');
            }else{
                return $this->returnSuccessMessage('Category','Category doesnt exist yet');
            }
    }catch(\Exception $ex){
        return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getById($id)
    {
        try{
            Gate::authorize('Read Category');
            $category =$this->categoryModel->with(['Section','Parent'])->find($id);
            if (is_null($category) ){
                return $this->returnSuccessMessage('This Category not found','done');
            }else{
                return $this->returnData('Category',$category,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getCategoryBySelf($id)
    {
        $category=$this->categoryModel->with('Parent')->get();
        return $response= $this->returnData('Category',$category,'done');
    }
    /*___________________________________________________________________________*/
        /****ــــــThis Functions For Trashed category  ****/
    /****Get All Trashed category Or By ID  ****/
    public function getTrashed()
    {
        try{
            Gate::authorize('Read Category');
            $category = $this->categoryModel->where('categories.is_active',0)->get();
          return $this -> returnData('Category',$category,'done');
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****Restore category Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed( $id)
    {
        try{
            Gate::authorize('Restore Category');
            $category=$this->categoryModel->find($id);
            if (is_null($category) ){
                return $response= $this->returnSuccessMessage('This Category not found','done');
            }else{
                $category->is_active=true;
                $category->save();
                return $this->returnData('Category', $category,'This Category Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****   category's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash( $id)
    {
        try{
            Gate::authorize('Delete Category');

            $category=$this->categoryModel->find($id);
            $category=$this->categoryModel->find($id);
            if (is_null($category) ){
                return $response= $this->returnSuccessMessage('This Category not found','done');
            }else{
                $category->is_active=false;
                $category->save();
                return $this->returnData('Category', $category,'This Category Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Create category   ***
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    /*___________________________________________________________________________*/
    public function create(CategoryRequest $request)
    {
        try {
            Gate::authorize('Create Category');

            $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            $request->is_appear ? $is_appear = true : $is_appear = false;
            //transformation to collection
            $allcategories = collect($request->category)->all();
            DB::beginTransaction();
            // //create the default language's product
            $folder = public_path('images/categories' . '/');

            $unTransCategory_id = $this->categoryModel->insertGetId([
                'slug' => $request['slug'],
                'is_active' => $request['is_active'],
                'section_id' => $request['section_id'],
                'parent_id' => $request['parent_id'],
                'image' =>$this->upload( $request['image'],$folder)

            ]);
            //check the category and request
            if (isset($allcategories) && count($allcategories)) {
                //insert other traslations for products
                foreach ($allcategories as $allcategory) {
                    $transCategory_arr[] = [
                        'name' => $allcategory ['name'],
                        'local' => $allcategory['local'],
                        'category_id' => $unTransCategory_id,
                    ];
                }
                $this->categoryTranslation->insert($transCategory_arr);
            }
                DB::commit();
                return $this->returnData('category', [$unTransCategory_id,$transCategory_arr],'done');
            }
            catch(\Exception $ex)
            {
                DB::rollback();
                return $this->returnError('category', $ex->getMessage());
            }
        }
    /*___________________________________________________________________________*/
    /****  Update category   ***
     * @param CategoryRequest $request
     * @param $id
     * @return Exception|JsonResponse
     */
    public function update(CategoryRequest $request,$id)
    {
        try{
            Gate::authorize('Update Category');

            $request->validated();
             $category= $this->categoryModel->find($id);
//            $old_image=$category->image;
            if(!$category)
                return $this->returnError('400', 'not found this Category');
            if (!($request->has('category.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);
           $ncategory=$this->categoryModel->where('categories.id',$id)
               ->update([
                   'slug'      =>$request['slug'],
                   'is_active' =>$request['is_active'],
                   'section_id' =>$request['section_id'],
                   'parent_id' =>$request['parent_id'],
//                   'image' =>$request['image']
            ]);
              $request_category = array_values($request->category);
                    foreach($request_category as $request_categor){
                         $this->categoryTranslation->where('category_translations.category_id',$id)
                            ->where('local',$request_categor['local'])
                            ->update([
                            'name'=>$request_categor['name'],
                            'local'=>$request_categor['local'],
                            'category_id'=>$id
                        ]);
                    }
            DB::commit();
            return $this->returnData('Category', [$id,$request_category],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  ٍsearch for category   ***
     * @param $name
     * @return JsonResponse
     */
    public function search($name)
    {
        try {
            Gate::authorize('Read Category');

            $category = DB::table('categories')
                ->where("name","like","%".$name."%")
                ->get();
            if (!$category)
            {
                return $this->returnError('400', 'not found this Category');
            }
            else
            {
                return $this->returnData('Category', $category,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Delete category   ***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try{
            Gate::authorize('Delete Category');

            $category=$this->categoryModel->find($id);
        if ($category->is_active=0)
            {
                $category=$this->categoryModel->destroy($id);
                 return $this->returnData('Category', $category,'This Category Is deleted Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function upload($image,$folder)
    {
//        $image = $request->file('image');
//        $folder = public_path('images/categories' . '/');
//        $filename = time() . '.' . $image->getClientOriginalName();
//        if (!File::exists($folder)) {
//            File::makeDirectory($folder, 0775, true, true);
//        }
//        $image->move($folder,$filename);
//        return $filename;
        $folder = public_path('images/categories' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[]='images/categories/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder,$filename);
        return $filename;
    }


    public function update_upload(Request $request,$id)
    {
        $category= $this->categoryModel->find($id);
        if (is_null($category) ){
            return $this->returnSuccessMessage('This Category not found','done');
        }
        $old_image=$category->image;
        $image = $request->file('image');
        $old_images=public_path('images/categories' . '/' .$old_image);
        if(File::exists($old_images)){
            unlink($old_images);
        }
        $folder = public_path('images/categories' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $category->update(['image' => $filename]);/**update in database**/
        $image->move($folder,$filename);
        return $filename;
    }
}
