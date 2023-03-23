<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use App\Http\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /*
    * Tudj felvinni új céget az adatbázisban
    */
    public function createCompany(CreateRequest $request)
    {
        $companyService = new CompanyService();
        $allCompanies = $companyService->getAllCompanies();
        $validated = $request->validated();
        if($validated)
        {
            $companyService->createCompany($allCompanies, $request);
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
        $companyService = new CompanyService();
        $allCompanies = $companyService->getAllCompanies();
        $companyIDs = array();
        $data = $request->all();
        foreach ($data as $key => $value)
        {
            if(gettype($value) == 'integer' && $value <= count($allCompanies)){
                $companyIDs[] = $value;
            }
        }
        $companies = $companyService->getCompanyDetailsByID($companyIDs);
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
        $companyService = new CompanyService();
        if($validated)
        {
            $data = $request->all();
            foreach ($data as $key => $value)
            {
                $companyService->updateCompany($key, $value, $request->companyId);
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
        $companyService = new CompanyService();
        $query = $companyService->getCompaniesSince2001TillNow();
        return response()->json([
            'status' => 'Sikeres lekérdezés',
            'message' => $query,
        ]);
    }

    public function setDB()
    {
        $companyService = new CompanyService();
        $companyService->setDB();
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
