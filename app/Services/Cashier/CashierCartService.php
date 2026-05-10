<?php

namespace App\Services\Cashier;

use App\Models\Menu;
use Illuminate\Support\Collection;

class CashierCartService
{
    private const SESSION_KEY = 'cashier_cart';

    public function all(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function put(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function add(int $menuId, int $quantity, ?string $note = null): void
    {
        $menu = Menu::query()
            ->where('is_active', true)
            ->where('is_available', true)
            ->findOrFail($menuId);

        $note = $this->normalizeNote($note);

        $cart = $this->all();
        $cartKey = $this->cartKey($menu->id, $note);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'menu_id' => $menu->id,
                'quantity' => $quantity,
                'note' => $note,
            ];
        }

        $this->put($cart);
    }

    public function updateQuantities(array $quantities): void
    {
        $cart = $this->all();

        foreach ($quantities as $cartKey => $quantity) {
            if (! isset($cart[$cartKey])) {
                continue;
            }

            if ((int) $quantity <= 0) {
                unset($cart[$cartKey]);
                continue;
            }

            $cart[$cartKey]['quantity'] = (int) $quantity;
        }

        $this->put($cart);
    }

    public function remove(string $cartKey): void
    {
        $cart = $this->all();

        unset($cart[$cartKey]);

        $this->put($cart);
    }

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

            $note = $this->normalizeNote($item['note'] ?? null);
            $cartKey = $this->cartKey($menu->id, $note);

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

    public function initialCartForView(): Collection
    {
        return collect($this->all())
            ->map(function ($item, $key) {
                $menu = Menu::query()
                    ->where('is_active', true)
                    ->where('is_available', true)
                    ->find($item['menu_id'] ?? null);

                if (! $menu) {
                    return null;
                }

                return [
                    'key' => (string) $key,
                    'menu_id' => $menu->id,
                    'name' => $menu->name,
                    'normal_price' => (int) $menu->normal_price,
                    'quantity' => max((int) ($item['quantity'] ?? 1), 1),
                    'note' => $item['note'] ?? '',
                ];
            })
            ->filter()
            ->values();
    }

    private function normalizeNote(?string $note): ?string
    {
        $note = trim((string) $note);

        return $note !== '' ? $note : null;
    }

    private function cartKey(int $menuId, ?string $note): string
    {
        return md5($menuId . '|' . ($note ?? ''));
    }
}