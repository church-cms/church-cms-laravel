<?php

namespace App\Helpers;

use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;

class SiteHelper
{
    public static function getAdmin($church_id)
    {
        $churchCacheKey = "admin".$church_id;
        return Cache::remember( $churchCacheKey, env('CACHE_TIME'), function () use ($church_id)  {
            return User::ByChurch($church_id)->ByRole(3)->first();
        });
    }

    public static function getCountries()
    {
        return Cache::remember( "countries", env('CACHE_TIME'), function ()  {
            $country = Country::where('status','1')->get();
            return CountryResource::collection($country)->keyby('id');
        });
    }

    public static function getStates()
    {
        return Cache::remember( "states", env('CACHE_TIME'), function ()  {
            $state = State::get();
            return StateResource::collection($state)->groupby('country_id');
        });
    }

    public static function getCities()
    {
        return Cache::remember( "cities", env('CACHE_TIME'), function ()  {
            $city  = City::get();
            return CityResource::collection($city)->groupby('state_id');
        });
    }

    public static function getOccupationList()
    {
        $array = [];

        $list_id = array('business','doctor','engineer','government_employee','home_maker','lawyer','pastor','police','professionals','self_employed','student','teacher','others');
        $list_name = array('Business','Doctor','Engineer','Government Employee','Home Maker','Lawyer','Pastor','Police','Professionals','Self Employed','Student','Teacher','Others');

        return Cache::remember( "occupationlist", env('CACHE_TIME'), function () use($list_id,$list_name) {
            for($i = 1 ; $i <= count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i-1];
                $array[$i]['name'] = $list_name[$i-1];
            }
            return $array;
        });
    }

    public static function getMaritalStatusList()
    {
        $array = [];

        $list_id = array('single','married','ended_by_death','ended_by_divorce','separated');
        $list_name = array('Single','Married','Ended By Death','Ended By Divorce','Separated');

        return Cache::remember( "maritalstatuslist", env('CACHE_TIME'), function () use($list_id,$list_name) {
            for($i = 1 ; $i <= count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i-1];
                $array[$i]['name'] = $list_name[$i-1];
            }
            return $array;
        });
    }

    public static function getRelationList()
    {
        $array = [];

        $list_id = array('father','mother','child','partner');
        $list_name = array('Father','Mother','Child','Husband/Wife');

        return Cache::remember( "relationlist", env('CACHE_TIME'), function () use($list_id,$list_name) {
            for($i = 1 ; $i <= count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i-1];
                $array[$i]['name'] = $list_name[$i-1];
            }
            return $array;
        });
    }

    public static function getMonths()
    {
        $array = [];

        $list_id = array('01','02','03','04','05','06','07','08','09','10','11','12');
        $list_name = array('January','February','March','April','May','June','July','August','September','October','November','December');

        return Cache::remember( "monthlist", env('CACHE_TIME'), function () use($list_id,$list_name) {
            for($i = 1 ; $i <= count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i-1];
                $array[$i]['name'] = $list_name[$i-1];
            }
            return $array;
        });
    }

    public static function getGenderList()
    {
        $array = [];

        $list_id = array('male','female');
        $list_name = array('Male','Female');

        return Cache::remember( "genderlist", env('CACHE_TIME'), function () use($list_id,$list_name) {
            for($i = 1 ; $i <= count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i-1];
                $array[$i]['name'] = $list_name[$i-1];
            }
            return $array;
        });
    }

    public static function getFeedbackCategoryList()
    {
        $array = [];

        $list_id = array('bug','suggestion','others');
        $list_name = array('Bugs','Suggestions','Others');

        return Cache::remember( "feedback_category_list", env('CACHE_TIME'), function () use($list_id,$list_name) {

            for($i = 0 ; $i < count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i];
                $array[$i]['name'] = $list_name[$i];
            }
            return $array;
        });
    }

    public static function getMarriageStatus()
    {
        $array = [];

        $list_id = array('single','married','ended_by_death','ended_by_divorce','separated');
        $list_name = array('Single','Married','Ended_by_death','Ended_by_divorce','Separated');

        return Cache::remember( "marriage_status_list", env('CACHE_TIME'), function () use($list_id,$list_name) {

            for($i = 0 ; $i < count($list_name) ; $i++)
            {
                $array[$i]['id'] = $list_id[$i];
                $array[$i]['name'] = $list_name[$i];
            }
            return $array;
        });
    }
}
