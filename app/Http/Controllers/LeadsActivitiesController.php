<?php

namespace App\Http\Controllers;

use App\Actions\StoreActivity;
use App\Exceptions\NotifiableException;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadsActivitiesController extends Controller
{
    /**
     * @var StoreActivity
     */
    protected $storeActivity;

    public function __construct(StoreActivity $storeActivity)
    {
        $this->storeActivity = $storeActivity;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Lead $lead, Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'observation' => 'required',
            'type' => 'required',
            'files.*' => 'max:5600',
        ]);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }

        $data['user_id'] = $request->user()->id;
        $data['dealership_id'] = $request->user()->dealership_id;

        $this->storeActivity->handle($lead, $data);
        $this->notifySuccess('Se registró la actividad', 'Todo bien');

        return redirect()->back();
    }
}
