<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicLayout extends Component
{
    public function __construct(
        public string $title = '',
        public string $metaDescription = 'Join giveaways, participate easily, and check winners publicly.',
        public ?string $metaImage = null,
        public ?string $canonical = null,
        public string $bodyClass = ''
    ) {
        $this->title = $title ?: config('app.name', 'Giveaway App');
        $this->metaDescription = $metaDescription;
        $this->metaImage = $metaImage;
        $this->canonical = $canonical ?: url()->current();
        $this->bodyClass = $bodyClass;
    }

    public function render(): View|Closure|string
    {
        return view('layouts.app');
    }
}
