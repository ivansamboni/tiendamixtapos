<?php

namespace App\Jobs;

use App\Mail\VentaRealizadaMailable;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarFacturaClienteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orden;
    /**
     * Create a new job instance.
     */
    public function __construct($orden)
    {
        $this->orden = $orden;  //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orden = $this->orden; 
        $negocio = Setting::first(); 
        $pdf = Pdf::loadView('facturas.facturaPdf', [
            'orden' => $orden,
            'negocio' => $negocio
        ])->output();

        Mail::to($orden->client->email)
            ->send(new VentaRealizadaMailable($orden, $negocio, $pdf));
    }
}
