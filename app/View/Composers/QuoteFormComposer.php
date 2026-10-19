<?php

namespace App\View\Composers;

use App\Services\Quote\QuoteOptionsService;
use Illuminate\View\View;

/**
 * Feeds the website's quote page ($quoteOptions) without touching the page controller.
 */
class QuoteFormComposer
{
    protected $options;

    public function __construct(QuoteOptionsService $options)
    {
        $this->options = $options;
    }

    public function compose(View $view)
    {
        $view->with('quoteOptions', $this->options->forForm());
    }
}
