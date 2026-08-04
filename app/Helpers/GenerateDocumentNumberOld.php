<?php

namespace App\Helpers;

use App\Models\Table\Document;

class GenerateDocumentNumber{
    
    public static function generateNumber($documentType, $companyCode, $departmentCode, $signBy, $lastNumber=""){
        $currentMonth = date('n');  
        $romanMonth = self::convertToRoman($currentMonth);
        $yearLastTwoDigits = self::getLastTwoDigitsOfYear();
        $currentNumberDocument = "";

        if($lastNumber != ""){
            $currentNumberDocument = $lastNumber;
        }else{
            $currentNumberDocument = "000";
        }

        $nextNumber = $currentNumberDocument + 1;
        $generateNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $generateDocumentNumber = "";

        if($signBy != ""){
            $generateDocumentNumber = "$documentType/$companyCode-$departmentCode/$signBy/$generateNumber/$romanMonth/$yearLastTwoDigits";
        }else{
            $generateDocumentNumber = "$documentType/$companyCode-$departmentCode/$generateNumber/$romanMonth/$yearLastTwoDigits";
        }

        $documentFilling = Document::where('contract_number', $generateDocumentNumber)->first();

        if($documentFilling != null){
            $nextNumber2 = $generateNumber + 1;
            $generateNumber = str_pad($nextNumber2, 3, '0', STR_PAD_LEFT);

            if($signBy != ""){
                $generateDocumentNumber = "$documentType/$companyCode-$departmentCode/$signBy/$generateNumber/$romanMonth/$yearLastTwoDigits";
            }else{
                $generateDocumentNumber = "$documentType/$companyCode-$departmentCode/$generateNumber/$romanMonth/$yearLastTwoDigits";
            }
        }

        return [
            'generateDocumentNumber' => $generateDocumentNumber,
            'numberDocument' => $generateNumber,
        ];
    }

    private static function convertToRoman($number)
    {
        // Daftar angka Romawi
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        // Mengembalikan angka Romawi sesuai dengan angka bulan
        return $map[$number] ?? null; // Mengembalikan null jika angka tidak valid
    }

    private static function getLastTwoDigitsOfYear()
    {
        // Ambil tahun saat ini dalam format empat digit
        $currentYear = date('Y');
        
        // Ambil dua digit terakhir dari tahun
        $lastTwoDigits = substr($currentYear, -2);
        
        return $lastTwoDigits;
    }
}
