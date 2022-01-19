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
//         \App\Models\User::factory(10)->create();
        $this->call([
            LaratrustSeeder::class,
//            RolePermissionSeeder::class,
//            BrandSeeder::class,
//            ProductSectionSeeder::class,
//            BrandSectionSeeder::class,
//            CategoriesSeeder::class,
//            CustomFieldSeeder::class,
//            CustomField_CustomFieldValue::class,
//            ProductCategorySeeder::class,
//            ProductImageSeeder::class,
//            ProductCustomFieldSeeder::class,
//            ProductsSeeder::class,
//            SectionSeeder::class,
//            StoreBrandSeeder::class,
//            StoreProductSeeder::class,
//            StoreSectionSeeder::class,
            StoreSeeder::class,
//            StoreImagesSeeder::class,
//            ProductsSeeder::class,
//            StoreSeeder::class,
//            StoreImagesSeeder::class,
//
//
//            DoctorSeeder::class,
//            PatientSeeder::class,
//            DoctorPatientSeeder::class,
//            DoctorSpecialtySeeder::class,
//            DoctorHospitalSeeder::class,
//            DoctorMedicalDeviceSeeder::class,
//            DoctorRateSeeder::class,
//            ActiveTimeSedeer::class,
//            appointmentSedeer::class,
//            ClinicSedeer::class,
//            HospitalSedeer::class,
//            HospitalMedicalDeviceSeeder::class,
//            MedicalDeviceSedeer::class,
//            SocialMediaSedeer::class,
//            SpecialtySedeer::class,
//
//            RestaurantSeeder::class,
//            RestaurantTypeSeeder::class,
//            RestaurantTypeRestaurantSeeder::class,
//            RestaurantCategorySeeder::class,
//            RestaurantProductSeeder::class,
//            ItemSeeder::class,
//            RestauranCategoryItemSeeder::class,
//            RestauranCategoryProductSeeder::class,
//            RestaurantItemSeeder::class,
//            RestaurantRestauranCategorySeeder::class,
//            RestaurantRestauranProductSeeder::class,
//            RestaurantManagerSeeder::class,
//
//            OfferSeeder::class,
//            CommentSeeder::class,
//            InteractionSeeder::class,


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
            ReachSeeder::class,
            BannerSeeder::class,
            StoreUserSeeder::class,
            StorePaymentSeeder::class,
        ]);

    }
}
