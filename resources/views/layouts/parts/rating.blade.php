@for($r = 1; $r <= $rating; $r++)
<span class="rating__item">
    <span class="material-icons">star</span>
</span>
@endfor

@if($rating > ($r-1))
<span class="rating__item">
    <span class="material-icons">star_half</span>
</span>
@else
<span class="rating__item">
    <span class="material-icons">star_border</span>
</span>
@endif

@for($r_a = $r; $r < 5; $r++) 
<span class="rating__item">
    <span class="material-icons">star_border</span>
</span>
@endfor