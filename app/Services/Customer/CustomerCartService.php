<?php

namespace App\Services\Customer;

use App\Models\CafeTable;
use App\Models\Menu;

class CustomerCartService
{
    public function buildFromJson(string $cartJson): array
    {
        $decodedCart = json_decode($cartJson, true);

        if (! is_array($decodedCart) || count($decodedCart) === 0) {
            return [];
        }

        $cart = [];

        foreach ($decodedCart as $item) {
            if (
                ! isset($item['menu_id'], $item['quantity']) ||
                (int) $item['quantity'] < 1
            ) {
                continue;
            }

            $menu = Menu::query()
                ->where('is_active', true)
                ->where('is_available', true)
                ->find($item['menu_id']);

            if (! $menu) {
                continue;
            }

            $note = isset($item['note']) ? trim((string) $item['note']) : null;
            $note = $note !== '' ? $note : null;

            $cartKey = md5($menu->id . '|' . ($note ?? ''));

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += (int) $item['quantity'];
                continue;
            }

            $cart[$cartKey] = [
                'menu_id' => $menu->id,
                'quantity' => (int) $item['quantity'],
                'note' => $note,
            ];
        }

        return $cart;
    }

    public function getFromSession(CafeTable $table): array
    {
        return session($this->sessionKey($table), []);
    }

    public function putToSession(CafeTable $table, array $cart): void
    {
        session([$this->sessionKey($table) => $cart]);
    }

    public function forget(CafeTable $table): void
    {
        session()->forget($this->sessionKey($table));
    }

    private function sessionKey(CafeTable $table): string
    {
        return 'customer_cart_table_' . $table->id;
    }
}