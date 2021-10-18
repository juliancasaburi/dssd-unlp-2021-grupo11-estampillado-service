<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\QRHelper;

class EstampilladoController extends Controller
{
    /**
     * Estampillar.
     *
     * @OA\Post(
     *    path="/api/estampillar",
     *    summary="Estampillar",
     *    description="Recibe un número de expediente y el archivo del estatuto. Entrega un número de hash y código QR",
     *    operationId="estampillar",
     *    tags={"estampillar"},
     *    security={{ "apiAuth": {} }},
     *    @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                property="frontend_endpoint",
     *                type="string"
     *             ),
     *             @OA\Property(
     *                property="numero_expediente",
     *                type="string"
     *             ),
     *             @OA\Property(
     *                property="archivo_estatuto",
     *                type="file"
     *             ),
     *          ),
     *      )
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Estampillado exitoso."
     *    ),
     *     @OA\Response(
     *       response=401,
     *       description="Unauthorized"
     *    ),
     *    @OA\Response(
     *       response=500,
     *       description="500 internal server error",
     *       @OA\JsonContent(
     *          example="500 internal server error"
     *       )
     *    ),
     * ) 
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function estampillar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_expediente' => 'required|string',
            'frontend_endpoint' => 'required',
            //'archivo_estatuto' => 'required|mimes:docx,odt,pdf'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }

        // TODO: generar numero hash
        $numeroHash = "123";

        // Generar Código QR
        $qrHelper = new QRHelper();
        $qr = $qrHelper->generarQR("{$request->input('frontend_endpoint')}/sa/{$numeroHash}");
        $ImgfileEncode = base64_encode($qr);

        return response()->json([
            "numero_hash" => $numeroHash,
            "qr" => $ImgfileEncode,
        ], 200);
    }
}
