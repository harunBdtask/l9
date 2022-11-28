<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;

class ImportDocumentAcceptanceController extends Controller
{
    public function index()
    {
        return view('commercial::import-document-acceptance.index');
    }

    public function create()
    {
        return 'hi';
    }
}
