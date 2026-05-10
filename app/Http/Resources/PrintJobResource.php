<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintJobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'attempts' => $this->attempts,

            'order_id' => $this->order_id,
            'cashier_shift_id' => $this->cashier_shift_id,

            'payload' => $this->payload ?? [],

            'error_message' => $this->error_message,
            'printed_at' => optional($this->printed_at)->format('Y-m-d H:i:s'),
            'failed_at' => optional($this->failed_at)->format('Y-m-d H:i:s'),
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}