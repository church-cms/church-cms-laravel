<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChurchDetail;
use App\Models\Church;
use App\Traits\Common;
use OpenApi\Attributes as OA;

/**
 * ChurchDetailsController
 *
 * Delivers church metadata and organizational information via API.
 * Returns detailed church information including contact, location, and logo.
 *
 * @package App\Http\Controllers\Api
 * @uses Common Trait for file path utilities
 */
class ChurchDetailsController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/church/details/{church_id}',
        tags: ['ChurchDetails'],
        summary: 'Get church details by church ID',
        operationId: '21c3f4daa89e704858570f38c70e4eb4',
        parameters: [
            new OA\Parameter(
                name: 'church_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/ChurchDetailResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function show($church_id)
    {
        //
        $church = Church::where('id', $church_id)->first();
        $churchdetail = [];

        $churchdetails  = ChurchDetail::select('meta_key', 'meta_value')->where('church_id', $church->id)->get();
        $plucked  = $churchdetails->pluck('meta_value', 'meta_key');

        $churchdetail['church_name']    = ucwords($church->name);
        $churchdetail['church_logo']    = $plucked['church_logo'] === '-' ? '' : $this->getFilePath($plucked['church_logo']);
        $churchdetail['short_summary']  = $plucked['short_summary'] === '-' ? '' : $plucked['short_summary'];
        $churchdetail['long_summary']   = $plucked['long_summary'] === '-' ? '' : $plucked['long_summary'];
        $churchdetail['quotes']         = $plucked['quotes'] === '-' ? '' : $plucked['quotes'];
        $churchdetail['phone']          = $plucked['phone'] === '-' ? '' : $plucked['phone'];
        $churchdetail['email']          = $plucked['email'] === '-' ? '' : $plucked['email'];
        $churchdetail['address']        = $plucked['address'] === '-' ? '' : $plucked['address'];
        $churchdetail['latitude']       = $plucked['latitude'] === '-' ? '' : $plucked['latitude'];
        $churchdetail['longitude']      = $plucked['longitude'] === '-' ? '' : $plucked['longitude'];
        $churchdetail['website']        = $plucked['website'] === '-' ? '' : $plucked['website'];
        $churchdetail['facebook']       = $plucked['facebook'] === '-' ? '' : $plucked['facebook'];
        $churchdetail['twitter']        = $plucked['twitter'] === '-' ? '' : $plucked['twitter'];
        $churchdetail['instagram']      = $plucked['instagram'] === '-' ? '' : $plucked['instagram'];

        return $churchdetail;
    }
}
