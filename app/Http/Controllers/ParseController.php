<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        log($request->all());

    }
}
