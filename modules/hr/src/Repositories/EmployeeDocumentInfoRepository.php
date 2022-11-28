<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use SkylarkSoft\GoRMG\HR\Models\HrEmployeeDocument;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeDocumentInfoRequest;

class EmployeeDocumentInfoRepository
{

    public function get($employee)
    {
        return $employee->document;
    }

    public function store($employee, EmployeeDocumentInfoRequest $request)
    {
        $document_types             = ['nid', 'birth_certificate', 'photo', 'character_certificate', 'ssc_certificate', 'hsc_certificate', 'biodata', 'medical_certificate', 'signature', 'masters', 'hons', 'others'];
        $employee_document          = $employee->document;
        if(empty($employee_document)) {
            $employee_document = new HrEmployeeDocument();
            $employee_document->employee_id = $employee->id;
        }

        foreach($document_types as $document_type) {
            $data[$document_type]   = '';
            if($request->hasFile($document_type)) {
                $prev_file_path     = storage_path()."/app/public/{$document_type}/" . $employee_document->$document_type;
                if(file_exists($prev_file_path) && !empty($employee_document->$document_type)) {
                    unlink($prev_file_path);
                }
                $filenameWithExt    = $request->file($document_type)->getClientOriginalName();
                $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension          = $request->file($document_type)->getClientOriginalExtension();
                $data[$document_type]    = $filename.'_'.time().'.'.$extension;
                $path               = $request->file($document_type)->storeAs("/{$document_type}", $data[$document_type]);
                $employee_document->$document_type = $data[$document_type];
            }
        }

        $employee_document->save();

        return $employee_document;
    }
}
