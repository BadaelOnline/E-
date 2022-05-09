<?php

namespace Database\Seeders;

use App\Models\DoctorRate\DoctorRate;
use App\Models\RestaurantType\RestaurantType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LaratrustSeeder::class,
            BrandSeeder::class,
            ProductSectionSeeder::class,
            BrandSectionSeeder::class,
            CategoriesSeeder::class,
            CustomFieldSeeder::class,
            CustomField_CustomFieldValue::class,
            ProductCategorySeeder::class,
            ProductImageSeeder::class,
            ProductCustomFieldSeeder::class,
            ProductsSeeder::class,
            SectionSeeder::class,
            StoreBrandSeeder::class,
            StoreProductSeeder::class,
            StoreSectionSeeder::class,
            StoreSeeder::class,
            StoreImagesSeeder::class,
            ProductsSeeder::class,
            StoreSeeder::class,
            StoreProductsDetails::class,
            SocialMediaSedeer::class,
            OfferSeeder::class,
            store_product_offerSeeder::class,
            CurrencySeeder::class,
            SubscriptionSeeder::class,
            PlanSeeder::class,
            TransactionSeeder::class,
            PaymentSeeder::class,
            OrderSeeder::class,
            ShippingSeeder::class,
            ShippingStoreSeeder::class,
            OrderDetailsSeeder::class,
            ActivityTypeSeeder::class,
            AttachmentSeeder::class,
            AttachmentTypeSeeder::class,
            BannerSeeder::class,
            StoreUserSeeder::class,
            StorePaymentSeeder::class,
            LocationSeeder::class,
            DetailsCustomValue::class,
            StoreProductsDetails::class,
            ProductCustomValueSeeder::class
        ]);

    }
}
