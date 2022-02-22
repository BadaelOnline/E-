<?php

namespace App\Service\Stores;

use App\Models\Images\Banner;
use App\Models\Stores\Store;
use App\Models\Stores\StoreTranslation;
use App\Models\User;
use App\Notifications\StoreRegistration;
use App\Notifications\SendRequest;
use App\Scopes\BrandScope;
use App\Service\Attachments\AttachmentService;
use App\Service\SocialMedia\SocialMediaService;
use App\Service\Subscriptions\SubscriptionsService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\SendRequest1;


class  StoreService
{
    use GeneralTrait;
    private $storeTranslation;
    private $Store;
    private $attachmentsService;
    private $banner;
    private $SocialMediaService;
    private $subscriptionService;
    private $user;

    public function __construct(Store $store, StoreTranslation $storeTranslation,
                                Banner $banner,User $user,
                                AttachmentService $attachmentsService,
                                SubscriptionsService $subscriptionService,
                                SocialMediaService $SocialMediaService)
    {
        $this->storeModel = $store;
        $this->storeTranslation = $storeTranslation;
        $this->attachmentService = $attachmentsService;
        $this->banner = $banner;
        $this->SocialMediaService = $SocialMediaService;
        $this->subscriptionService = $subscriptionService;
        $this->user = $user;
    }

    private function fillStore($request_arr, $store_social, $logo_folder)
    {
        return (
        ['currency_id' => $request_arr['currency_id'],
            'location_id' => $request_arr['location_id'],
            'social_media_id' => $store_social,
            'activity_type_id' => $request_arr['activity_type_id'],
            'owner_id' => $request_arr['owner_id'],
            'section_id' => $request_arr['section_id'],
            'is_approved' => 0,
            'is_active' => 1,
            'logo' => $this->upload($request_arr['logo'], $logo_folder),
        ]);
    }

    /****________________   admins dashboard ________________****/
    /****________________   Store's approved ________________****/
    public function approve($id)
    {
        try {
            $store = $this->storeModel->find($id);
            return $store == null ? $this->returnSuccessMessage('Store', 'This stores not found') :
                $this->returnData('Store', $store->update(['is_approved' => true]), 'This Store Is Approved Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****________________   Store's list ________________****/
    public function dashgetAll()
    {
        try {
            $store = $this->storeModel->with([
                'Section',
                'Brand' => function ($q) {
                    return $q->withoutGlobalScope(BrandScope::class)
                        ->select(['brands.id'])
                        ->with(['BrandTranslation' => function ($q) {
                            return $q->where('brand_translation.local',
                                '=',
                                Config::get('app.locale'))
                                ->select(['brand_translation.name', 'brand_translation.brand_id'
                                ])->get();
                        }])->get();
                },
                'StoreImage' => function ($q) {
                    return $q->where('is_cover', 1)
                        ->get();
                }
            ])->get();
            return count($store) > 0 ? $this->returnData('Stores', $store, 'done') :
                $this->returnSuccessMessage('Store', 'stores doesnt exist yet');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /****________________   client side functions ________________****/
    /****________________ Get All Active Store Or By ID  ________________****/
    public function getAll()
    {
        try {
            $store = $this->storeModel->with([
                'Section',
                'Product',
                'Brand' => function ($q) {
                    return $q->withoutGlobalScope(BrandScope::class)
                        ->select(['brands.id'])
                        ->with(['BrandTranslation' => function ($q) {
                            return $q->where('brand_translation.local', '='
                                , Config::get('app.locale'))
                                ->select(['brand_translation.name', 'brand_translation.brand_id'
                                ])->get();
                        }])->get();
                },
                'StoreImage' => function ($q) {
                    return $q->where('is_cover', 1)->get();
                }])->get();
            return count($store) > 0 ? $this->returnData('Stores', $store, 'done') :
                $this->returnSuccessMessage('Store', 'stores doesnt exist yet');
        } catch (\Exception $ex) {

            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function getById($store_id)
    {
        try {
            $store = $this->storeModel->with(['Product' => function ($q) use ($store_id) {
                return $q->with(['Category' => function ($q) {
                    return $q->with('Section')->get();
                }, 'StoreProduct' => function ($q) use ($store_id) {
                    return $q->where('store_id', $store_id)->get();
                }])->get();
            }, 'Section', 'Brand'])->find($store_id);
            return is_null($store) ? $this->returnSuccessMessage('Store', 'stores doesnt exist yet') :
                $this->returnData('Stores', $store, 'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /****________________  This Functions For Trashed Store  ________________****/
    /****________________  Get All Trashed Stores Or By ID   ________________****/
    public function getTrashed()
    {
        try {
            $store = $this->storeModel->where('is_active', 0)->get();
            if (count($store) > 0) {
                return $response = $this->returnData('Store', $store, 'done');
            } else {
                return $response = $this->returnSuccessMessage('Store', 'stores doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****________________Restore Store Fore Active status  ________________****/
    public function restoreTrashed($id)
    {
        try {
            $store = $this->storeModel->find($id);
            return is_null($store) ? $this->returnSuccessMessage('Store', 'This stores not found') :
                $this->returnData('Store', $store->update(['is_active' => true]), 'This Store Is restore  Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****________________   Store's Soft Delete   ________________****/
    public function trash($id)
    {
        try {
            $store = $this->storeModel->find($id);
            return is_null($store) ? $this->returnSuccessMessage('Store', 'This stores not found') :
                $this->returnData('Store', $store->update(['is_active' => false]), 'This Store Is trashed Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****________________  Create Store   ________________****/
    public function create($request)
    {
        try {
            $front=collect($request);
            /***  //transformation to collection*////
             $admin=User::where('email','superadministrator@app.com')->findOrFail(1);
//             $alaa='fahed9285@gmail.com';
//            Notification::send($alaa, new SendRequest($request,$alaa));
//            Mail::send('email.sendmail',compact('front'),
//                function ($message){
//                    $message->to('fahed8592@gmail.com','laravel')
//                    ->subject('store register request');
//                });
            $stores = collect($request->store)->all();
            $attachments = collect($request->attachments);
            $subscriptions = collect($request->subscriptions)->all();
            $logo_folder = public_path('images/stores/logo/');
            DB::beginTransaction();
            $store_social = $this->SocialMediaService->create($request->social_media);
            /****create the default language's store****/
            $unTransStore_id = $this->storeModel->insertGetId(
                $this->fillStore($request, $store_social, $logo_folder)
            );
            if (isset($stores) && count($stores)) {
                /**insert other traslations for products**/
                foreach ($stores as $store) {
                    $transStore_arr[] = [
                        'local' => $store['local'],
                        'name' => $store['name'],
                        'store_id' => $unTransStore_id
                    ];
                }
                $this->storeTranslation->insert($transStore_arr);
            }
            if ($request->has('section')) {
                $store = $this->storeModel->find($unTransStore_id);
                $store->Section()->syncWithoutDetaching($request->get('section'));
            }
            $this->subscriptionService->create($unTransStore_id, $request->plan_id);
            if (isset($attachments) && count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $this->attachmentService->create($attachment, $unTransStore_id, 1);
                }
            }
            DB::commit();
            Notification::send($admin, new StoreRegistration($admin,$unTransStore_id));
            return $this->returnData('Store', [$unTransStore_id, $transStore_arr], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('store', $ex->getMessage());
        }
    }

    /****__________________  Update Store   ___________________****/
    public function update(Request $request, $id)
    {
        try {
            //$validated = $request->validated();
            $store = $this->storeModel->find($id);
            if (!$store)
                return $this->returnError('400', 'not found this Store');
            if (!($request->has('stores.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $logo_folder = public_path('images/stores/logo/');

            DB::beginTransaction();
            $nStore = $this->storeModel->where('stores.id', $id)
                ->update([
                    'currency_id' => $request['currency_id'],
                    'location_id' => $request['location_id'],
                    'social_media_id' => $request['social_media_id'],
                    'activity_type_id' => $request['activity_type_id'],
                    'owner_id' => $request['owner_id'],
                    'section_id' => $request['section_id'],
                    'is_active' => $request['is_active'],
                    'is_approved' => 0,
                    'logo' => $this->upload($request['logo'], $logo_folder)
                ]);
            $stores = collect($request->store)->all();
            //Stores in database
            $dbdstores = $this->storeModel->where('Store_id', $id)->get();
            foreach ($dbdstores as $dbdstore) {
                foreach ($stores as $store) {
                    $values = StoreTranslation::where('store_id', $id)
                        ->where('local', $store['local'])
                        ->update([
                            'title' => $store['title'],
                            'local' => $store['local'],
                            'store_id' => $id
                        ]);
                }
            }
            if ($request->has('section')) {
                $store = $this->storeModel->find($id);
                $store->Section()->syncWithoutDetaching($request->get('section'));
            }
            $images = $request->images;
            if ($request->has('images')) {
                foreach ($images as $image) {
                    $storeImages = $this->storeModel->find($id);
                    $storeImages->StoreImage()->insert([
                        'store_id' => $id,
                        'image' => $image['image'],
                        'is_cover' => $image['is_cover']
                    ]);
                }
            }
            DB::commit();
            return $this->returnData('Store', [$nStore, $dbdstores], 'Updated Done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****________________  ٍsearch for Store _________________****/
    public function search($title)
    {
        try {
            $store = DB::table('Store')
                ->where("name", "like", "%" . $title . "%")
                ->get();
            if (!$store) {
                return $this->returnError('400', 'not found this Store');
            } else {
                return $this->returnData('Store', $store, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /****_______________  Delete Store   ________________****/
    public function delete($id)
    {
        try {
            $store = $this->storeModel->find($id);
            if ($store->is_active == 0) {
                $store = Store::destroy($id);

            }
            return $this->returnData('Category', $store, 'This Store Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function getSectionInStore($id)
    {
        try {
            $store = $this->storeModel->with('Section')->find($id);
            return is_null($store) ?
                $this->returnSuccessMessage('Store', 'This stores not found') :
                $this->returnData('Category', $store, 'This Store Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function account($storeId)
    {
        try {
            $store = $this->storeModel->with('Shipping_Method','Banner')->find($storeId);
            return is_null($store) ?
                $this->returnSuccessMessage('Store', 'This stores not found') :
                $this->returnData('Store', $store, 'Done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('store', $ex->getMessage());
        }
    }

    public function createBanner(Request $request, $storeId)
    {
        try {
//            return $request;
            $folder = public_path('images/stores/banners' . '/' . $storeId . '/');
            $banners = collect($request->banners)->all();
            if (isset($banners) && count($banners)) {
                foreach ($banners as $banner) {
                    $banners_arr[] = [
                        'image' => $this->upload($banner['image'], $folder),
                        'description' => $banner['description'],
                        'store_id' => $storeId,
                        'is_active' => $banner['is_active'],
                        'is_appear' => $banner['is_appear']
                    ];
                }
                $this->banner->insert($banners_arr);
            }
            return $this->returnData('Banners', [$banners], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('store', $ex->getMessage());
        }
    }

    public function updateBanner($request, $bannerId, $storeId)
    {
        try {
//            return $request;
            $folder = public_path('images/stores/banners' . '/' . $storeId . '/');
             $banner = $this->banner->find($bannerId);
             $file=$banner->image;
//            File::delete($file);
            $banner->update([
                'image' => $this->upload($request['image'],$folder),
                'description' => $request['description'],
                'is_active' => $request['is_active'],
                'is_appear' => $request['is_appear']
            ]);
            return $this->returnData('Banners', $banner, 'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function getBanner($storeId)
    {
        try {
            $banner = $this->banner->where('banners.store_id', $storeId)->get();
            return is_null($banner) ?
                $this->returnSuccessMessage('Banner', 'This Banner not found') :
                $this->returnData('Banner', $banner, 'Done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function upload($image, $folder)
    {
        // $folder = public_path('images/attachments/stores' . '/' . $id . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        //  $imageUrl='images/attachments/stores/' . $id  . '/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder, $filename);
        return $filename;
    }

    public function storeUsers($storeId)
    {
        try {
            return $this->storeModel->with('User')->find($storeId);
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function storeUsersDelete($storeId, $userId)
    {
        try {
            $store = $this->storemodel->find($storeId);
            $store->User()->detach($userId);
            $store_user = $store->with('User')->get();
            return $this->returnData('store_user', $store_user, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function sendMail()
    {
        $details=[
            'title'=>'test',
            'bode'=>'test'
        ];
        Mail::to('fahed8592@gmail.com',)->send(new SendRequest1($details));
    }
}
