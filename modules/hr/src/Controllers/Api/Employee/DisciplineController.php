<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\Forms\DisciplineForm;
use Symfony\Component\HttpFoundation\Response;

class DisciplineController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {

    }


    public function store(DisciplineForm $form)
    {
        if ( $data = $form->handle()) {
            return response(['success' => true, 'data' => $data], Response::HTTP_CREATED);
        } else {
            return response(['success' => 'false'], Response::HTTP_BAD_REQUEST);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(DisciplineForm $form, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
