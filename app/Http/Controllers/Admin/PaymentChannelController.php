<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePaymentChannelRequest;
use App\Models\PaymentChannel;
use App\Services\Admin\AdminPaymentChannelQueryService;
use App\Services\Admin\AdminPaymentChannelService;

class PaymentChannelController extends Controller
{
    public function __construct(
        private readonly AdminPaymentChannelQueryService $paymentChannelQueryService,
        private readonly AdminPaymentChannelService $paymentChannelService
    ) {
    }

    public function index()
    {
        $channels = $this->paymentChannelQueryService->allOrdered();

        return view('admin.payments.index', compact('channels'));
    }

    public function create()
    {
        return view('admin.payments._form', [
            'channel' => new PaymentChannel(),
            'methodLabels' => $this->paymentChannelQueryService->methodLabels(),
        ]);
    }

    public function store(SavePaymentChannelRequest $request)
    {
        $this->paymentChannelService->create(
            data: $request->validatedData(),
            qrImage: $request->file('qr_image')
        );

        return redirect()
            ->route('admin.payment-channels.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentChannel $paymentChannel)
    {
        return view('admin.payments._form', [
            'channel' => $paymentChannel,
            'methodLabels' => $this->paymentChannelQueryService->methodLabels(),
        ]);
    }

    public function update(SavePaymentChannelRequest $request, PaymentChannel $paymentChannel)
    {
        $this->paymentChannelService->update(
            paymentChannel: $paymentChannel,
            data: $request->validatedData(),
            qrImage: $request->file('qr_image'),
            removeQrImage: $request->shouldRemoveQrImage()
        );

        return redirect()
            ->route('admin.payment-channels.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentChannel $paymentChannel)
    {
        $this->paymentChannelService->delete($paymentChannel);

        return redirect()
            ->route('admin.payment-channels.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}