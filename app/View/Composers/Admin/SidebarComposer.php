<?php

namespace App\View\Composers\Admin;

use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

/**
 * Gives every admin page the sidebar menu (from config/admin.php) with live badge counts.
 */
class SidebarComposer
{
    public function compose(View $view)
    {
        // The login page has no menu (and must work before anyone is logged in).
        if (!Auth::guard('admin')->check()) {
            $view->with('adminMenu', []);
            return;
        }

        // Compose once per request even though many admin views are rendered.
        static $data;

        if ($data === null) {
            $badges = $this->badges();
            $menu   = [];

            foreach (config('admin.navigation') as $group) {
                $items = [];
                foreach ($group['items'] as $item) {
                    if (!Route::has($item['route'])) {
                        continue; // feature not built yet -> no link
                    }
                    if (!empty($item['permission']) && !Auth::guard('admin')->user()->hasPermission($item['permission'])) {
                        continue; // this admin's role may not open it
                    }
                    $item['count'] = isset($item['badge']) ? (int) ($badges[$item['badge']] ?? 0) : 0;
                    $items[] = $item;
                }
                if ($items) {
                    $menu[] = ['heading' => $group['heading'], 'items' => $items];
                }
            }

            $data = $menu;
        }

        $view->with('adminMenu', $data);
    }

    /** Add a line here when a new section needs a number badge in the menu. */
    protected function badges()
    {
        return [
            'contact_new' => ContactMessage::newCount(),
            'quote_new'   => QuoteRequest::newCount(),
        ];
    }
}
