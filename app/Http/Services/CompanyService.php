<?php

namespace App\Http\Services;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function getCompanyDetailsByID($id)
    {
        return Company::whereIn('companyId', $id)->get();
    }

    public function getAllCompanies()
    {
        return Company::all();
    }

    public function createCompany($allCompanies, $request)
    {
        Company::insert([
            'companyId' => count($allCompanies)+1,
            'companyName' => $request->companyName,
            'companyRegistrationNumber' => $request->companyRegistrationNumber,
            'companyFoundationDate' => $request->companyFoundationDate,
            'country' => $request->country,
            'zipCode' => $request->zipCode,
            'city' => $request->city,
            'streetAddress' => $request->streetAddress,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'companyOwner' => $request->companyOwner,
            'employees' => $request->employees,
            'activity' => $request->activity,
            'active' => $request->active,
            'email' => $request->email,
            'password' => $request->password,
        ]);
    }

    public function updateCompany($key, $value, $id)
    {
        DB::update('update '.Company::DB.' set '.$key.' = "'.$value.'" where companyId = ?', [$id]);
    }

    public function getCompaniesSince2001TillNow()
    {
        return DB::select(DB::raw('select selected_date as date, testcompanydb.companyName from
        (select adddate("2001-01-01",t4*10000 + t3*1000 + t2*100 + t1*10 + t0)
        selected_date from
        (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
        (select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
        (select 0 t2 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
        (select 0 t3 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
        (select 0 t4 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
        left join testcompanydb on CAST(`companyFoundationDate` AS DATETIME) = v.selected_date
        where selected_date between "2001-01-01" and CURDATE()
        ORDER BY selected_date'));
    }

    public function setDB()
    {
        DB::statement('ALTER TABLE '.Company::DB.' ADD CONSTRAINT UC_'.Company::DB.' UNIQUE (companyId, companyName, companyRegistrationNumber, email);');
        DB::statement('ALTER TABLE '.Company::DB.' MODIFY COLUMN companyRegistrationNumber VARCHAR(255)');
        DB::statement('ALTER TABLE '.Company::DB.' MODIFY COLUMN companyFoundationDate VARCHAR(255)');
    }
}
