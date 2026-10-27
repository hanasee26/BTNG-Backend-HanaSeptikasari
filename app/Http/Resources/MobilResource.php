<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobilResource extends JsonResource
{
    /**
     * Disable default wrapping when manual wrapping is used.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'brand' => $this->brand,
            'harga' => (int) $this->harga,
            'tahun' => (int) $this->tahun,
            'transmisi' => $this->transmisi,
            'bahan_bakar' => $this->bahan_bakar,
            'status' => $this->status,
            'foto' => $this->foto,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
