<style>
    .head-banner {
        color: #0275d8;
    }
</style>

<span class="head-banner"
      style="font-weight: 400;
      word-spacing: 0.2em;
      letter-spacing: 1px;
      margin-top: 12px;
      padding-top: 4px">

      @foreach(getHeadBanner(request()->path()) as $key=> $banner)
        @if($key % 2 == 0)
            {{ $banner }}
        @else
            <span class="head-banner" style="font-weight: 700;">
              {{ $banner }}
            </span>
        @endif
      @endforeach
</span>
