<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanyController extends Controller
{
    /*
    * Tudj felvinni új céget az adatbázisban
    */
    public function createCompany(CreateRequest $request)
    {
        $companies = Company::all();
        $validated = $request->validated();
        if($validated)
        {
            Company::insert([
                'companyId' => count($companies)+1,
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
            return response()->json([
                'status' => 'Sikeres cégfelvétel!'
            ]);
        }
    }

    /*
    * Tudj lekérni adatokat ID-alapján egy adott cégről (több id-t is be lehet adni)
    */
    public function getCompanyDetailsByID(Request $request)
    {
        $data = $request->all();
        $allCompanies = Company::all();
        $companyIDs = array();
        foreach ($data as $key => $value)
        {
            if(gettype($value) == 'integer' && $value <= count($allCompanies)){
                $companyIDs[] = $value;
            }
        }
        $companies = Company::whereIn('companyId', $companyIDs)->get();
        return response()->json([
            'message' => $companies
        ]);

    }

    /*
    * Tudj módosítani létező cégről adatokat
    */
    public function updateCompany(CreateRequest $request)
    {
        $validated = $request->validated();
        if($validated)
        {
            $data = $request->all();
            foreach ($data as $key => $value)
            {
                DB::update('update '.Company::DB.' set '.$key.' = "'.$value.'" where companyId = ?', [$request->companyId]);
            }
            return response()->json([
                'status' => 'Sikeres update!'
            ]);
        }
    }

    /*
    * Készíts egy olyan lekérdezést amely visszaadja, hogy 2001.01.01 napjától kezdve
    * egészen a mai napig az adott napon mely cégek alakultak meg. (azon a napon ahol
    * nem volt cég alapítás ott null értéket vegyen fel)
    */
    public function getCompaniesSince2001TillNow()
    {
        $query = DB::select(DB::raw('select selected_date as date, testcompanydb.companyName from
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
        return response()->json([
            'status' => 'Sikeres lekérdezés',
            'message' => $query,
        ]);
    }

    public function setDB()
    {
        DB::statement('ALTER TABLE '.Company::DB.' ADD CONSTRAINT UC_'.Company::DB.' UNIQUE (companyId, companyName, companyRegistrationNumber, email);');
        DB::statement('ALTER TABLE '.Company::DB.' MODIFY COLUMN companyRegistrationNumber VARCHAR(255)');
        DB::statement('ALTER TABLE '.Company::DB.' MODIFY COLUMN companyFoundationDate VARCHAR(255)');
        return response()->json([
            'status' => 'Db settings OK!'
        ]);
    }

    /*
    Készíts egy lekérdezést melynek az oszlopai az “activity” mezőben lévő értékek
    (ezek dinamikus adatok), sorai pedig az adott activity-hez tartozó cég név legyen.
    Megoldás:
        SET @sql = NULL;
        SELECT
        GROUP_CONCAT(DISTINCT
            CONCAT(
            'case when activity = ''',
            activity,
            ''' then companyName end AS `',
            activity, '`'
            )
        ) INTO @sql
        FROM
        testcompanydb;
        SET @sql = CONCAT('SELECT ', @sql, '
                        FROM testcompanydb');
        PREPARE stmt1 FROM @sql;
        EXECUTE stmt1;
    */
}
