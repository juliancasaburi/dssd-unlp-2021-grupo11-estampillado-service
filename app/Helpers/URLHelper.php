<?php

namespace App\Helpers;

class URLHelper
{
    /**
     * Obtener endpoint de la API de generación de códigos QR.
     *
     * @return string
     */
    public function getQRAPIURL()
    {
        return config('services.qr.api_url');
    }
}
