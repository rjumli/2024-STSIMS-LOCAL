<?php

namespace App\Services\Qualifier;

/**
 * Class ViewService.
 */
class ViewService
{
    public function lists($request){
        $bearer = $request->bearerToken();
        if($bearer){
            $token = PersonalAccessToken::findToken($bearer);
            $region = $token->tokenable->profile->agency->region_code;
        }else{
            $region = null;
        }

        $info = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;
        $location = (!empty(json_decode($request->location))) ? json_decode($request->location) : NULL;
        $keyword = $info->keyword;

        $data = IndexResource::collection(
            Qualifier::
            with('address.region','address.province','address.municipality','address.barangay')
            ->with('profile')
            ->with('program:id,name','subprogram:id,name','type','status:id,name,type,color,others')
            ->whereHas('profile',function ($query) use ($keyword) {
                $query->when($keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere('spas_id','LIKE','%'.$keyword.'%');
                });
            })
            ->whereHas('address',function ($query) use ($location) {
                if(!empty($location)){
                    (property_exists($location, 'region')) ? $query->where('region_code',$location->region)->where('is_permanent',1) : '';
                    (property_exists($location, 'province')) ? $query->where('province_code',$location->province)->where('is_permanent',1) : '';
                    (property_exists($location, 'municipality')) ? $query->where('municipality_code',$location->municipality)->where('is_permanent',1) : '';
                    (property_exists($location, 'barangay')) ? $query->where('barangay_code',$location->barangay)->where('is_permanent',1) : '';
                }
            })
            ->where(function ($query) use ($info) {
                ($info->program == null) ? '' : $query->where('program_id',$info->program);
                // ($filter != null) ? ($filter->subprogram == null) ? '' : $query->where('subprogram_id',$filter->subprogram) : '';
                ($info->category == null) ? '' : $query->where('category_id',$info->category);
                ($info->status == null) ? '' : $query->where('status_id',$info->status);
                ($info->year == null) ? '' : $query->where('qualified_year',$info->year);
                ($info->type == null) ? '' : $query->where('status_type',$info->type);
            })
            ->when($region, function ($query, $region) {
                $query->whereHas('address',function ($query) use ($region) {
                    $query->where('region_code',$region); 
                });
            })
            ->paginate($info->counts)
            ->withQueryString()
        );

        return $data;
    }
}
