<?php


namespace App\Service\Item;


use App\Http\Requests\Item\ItemRequest;
use App\Models\Item\Item;
use App\Models\Item\ItemTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class ItemService
{
    private $ItemModel;
    use GeneralTrait;

    public function __construct(Item $Item)
    {
        $this->ItemModel=$Item;
    }
    public function get()
    {
        try {
            $Item = $this->ItemModel::paginate(5);
            return $this->returnData('Item', $Item, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getById($id)
    {
        try{
            $Item= $this->ItemModel::find($id);
            if (is_null($Item)){
                return $this->returnSuccessMessage('this Item not found','done');
            }
            else{
                return $this->returnData('Item',$Item,'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    //__________________________________________________________//
    public function create( ItemRequest $request )
    {
        try {
            $allItem = collect($request->Item)->all();
            DB::beginTransaction();
            $unTransItem_id = Item::insertGetId([
                'image'   => $request['image'],
                'is_approved' => $request['is_approved'],
                'is_active'   => $request['is_active'],
                'product_id'   => $request['product_id'],
            ]);
            if (isset($allItem)) {
                foreach ($allItem as $allItems) {
                    $transItem[] = [
                        'name' => $allItems ['name'],
                        'short_description' => $allItems ['short_description'],
                        'long_description' => $allItems ['long_description'],
                        'locale' => $allItems['locale'],
                        'item_id' => $unTransItem_id,
                    ];
                }
                ItemTranslation::insert($transItem);
            }
            DB::commit();
            return $this->returnData('Item',[$unTransItem_id, $transItem],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('Item', $ex->getMessage());
        }

    }
    //___________________________________________________________________//
    public function update(ItemRequest $request,$id)
    {
        try{
            $Item= Item::find($id);
            if(!$Item)
                return $this->returnError('400', 'not found this Item');
            $allItem = collect($request->Item)->all();
            if (!($request->has('items.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newItem=Item::where('items.id',$id)
                ->update([
                    'image'   => $request['image'],
                    'is_approved' => $request['is_approved'],
                    'is_active'   => $request['is_active'],
                    'product_id'   => $request['product_id'],
                ]);

            $ss=ItemTranslation::where('item_translations.item_id',$id);
            $collection1 = collect($allItem);
            $allItemlength=$collection1->count();
            $collection2 = collect($ss);

            $db_Item= array_values(ItemTranslation::where('item_translations.item_id',$id)
                ->get()
                ->all());
            $dbItem = array_values($db_Item);
            $request_Item= array_values($request->Item);
            foreach($dbItem as $dbItems){
                foreach($request_Item as $request_Items){
                    $values=ItemTranslation::where('item_translations.item_id',$id)
                        ->where('locale',$request_Items['locale'])
                        ->update([
                            'name' => $request_Items ['name'],
                            'short_description' => $request_Items ['short_description'],
                            'long_description' => $request_Items ['long_description'],
                            'locale' => $request_Items['locale'],
                            'item_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('Item', [$dbItem,$values],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    //____________________________________________________________//
    public function search($name)
    {
        try {
            $Item = DB::table('item_translations')
                ->where("name", "like", "%" . $name . "%")
                ->get();
            if (!$Item) {
                return $this->returnError('400', 'not found this Item');
            } else {
                return $this->returnData('Item', $Item, 'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function trash( $id)
    {
        try{
            $Item= $this->ItemModel::find($id);
            if (is_null($Item)) {
                return $this->returnSuccessMessage('This $Item not found', 'done');
            }
            else
            {
                $Item->is_active=0;
                $Item->save();
                return $this->returnData('Item', $Item,'This  Item is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }

    }
    public function getTrashed()
    {
        try{
            $Item= $this->ItemModel::NotActive();
            return $this -> returnData('Item',$Item,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }    }
    public function restoreTrashed( $id)
    {
        try{
            $Item=Item::find($id);
            if (is_null($Item)) {
                return $this->returnSuccessMessage('This Item not found', 'done');
            }
            else
            {
                $Item->is_active=1;
                $Item->save();
                return $this->returnData('Item', $Item,'This Item is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    //________________________________________//
    public function delete($id)
    {
        try{
            $Item = Item::find($id);
            if ($Item->is_active == 0) {

                $Item->delete();
                $Item->ItemTranslation()->delete();
                return $this->returnData('Item', $Item, 'This Item is deleted Now');
            }
            else{
                return $this->returnData('Item', $Item, 'This Item can not deleted Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getRestaurant($id)
    {
        try{
            $Item= Item::with('Restaurant')->find($id);
            return $this->returnData('Item', $Item, 'done');


        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function getProduct($id)
    {
        try{
            $Item= Item::with('RestaurantProduct')->find($id);
            return $this->returnData('Item', $Item, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }


    public function getCategory($id)
    {
        try{
            $Item= Item::with('RestaurantCategory')->find($id);
            return $this->returnData('Item', $Item, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
