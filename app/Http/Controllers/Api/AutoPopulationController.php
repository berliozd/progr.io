<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AutoPopulations;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AutoPopulationController extends Controller
{
    public function index(Request $request)
    {
        return $this->getAutoPopulations($request->get('all'));
    }

    private function getAutoPopulations($all = true): Collection
    {
        if ($all) {
            $autoPopulations = AutoPopulations::all();
        } else {
            $autoPopulations = AutoPopulations::where('code', 'on')->orWhere('code', 'off')->get();
        }
        foreach ($autoPopulations as $autoPopulation) {
            $autoPopulation->label = trans('app.project.auto_populations.' . $autoPopulation->code);
        }
        return $autoPopulations;
    }
}
