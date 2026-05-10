@if ($paginator->lastPage() > 1)
    <div class="pagination-wrapper">
        <ul class="pagination">
            {{-- ⬅ Назад --}}
            @if ($paginator->onFirstPage())
                <li class="disabled arrow">
                    <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                </li>
            @else
                <li class="arrow">
                    <a href="{{ $paginator->previousPageUrl() }}#{{ $anchor }}">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="">
                    </a>
                </li>
            @endif

            <!-- Цифры -->
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                @if ($i == $paginator->currentPage())
                    <li class="active">
                        <span>{{ $i }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}#{{ $anchor }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            <!-- Вперёд -->
            @if ($paginator->hasMorePages())
                <li class="arrow">
                    <a href="{{ $paginator->nextPageUrl() }}#{{ $anchor }}">
                        <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                    </a>
                </li>
            @else
                <li class="disabled arrow">
                    <img loading="lazy" decoding="async" src="/img/arrow.svg" alt="" class="arrow-right">
                </li>
            @endif
        </ul>
    </div>
@endif
