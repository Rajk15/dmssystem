# Proposed trade-page TradingView widget patch

Apply on the Laravel Blade that renders the trade chart (search server for `tradingview_92622` or `s3.tradingview.com/tv.js`).

**Do not apply until** source or cPanel access is available. This does **not** invent OHLC; it only improves the embed configuration.

## Current (live)

```html
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script>
  new TradingView.widget({
    "width": "100%",
    "height": 450,
    "symbol": "FX_IDC:EURUSD",
    "interval": "D",
    "timezone": "Etc/UTC",
    "theme": "dark",
    "style": "1",
    "locale": "en",
    "enable_publishing": false,
    "allow_symbol_change": true,
    "container_id": "tradingview_92622"
  });
</script>
```

Symbol is typically built as `{{ $pair->listed_market_name }}:{{ str_replace('_', '', $pair->symbol) }}` → `FX_IDC:EURUSD`.

## Recommended embed (majors)

```html
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script>
  (function () {
    var pairSymbol = @json(str_replace('_', '', $pair->symbol ?? 'EURUSD')); // e.g. EURUSD
    var listed = @json($pair->listed_market_name ?? 'FX_IDC');
    // Prefer TV FX board for majors; keep listed market for exotics if needed.
    var majors = {
      EURUSD: 'FX:EURUSD',
      GBPUSD: 'FX:GBPUSD',
      USDJPY: 'FX:USDJPY',
      USDCAD: 'FX:USDCAD',
      USDCHF: 'FX:USDCHF',
      AUDUSD: 'FX:AUDUSD',
      NZDUSD: 'FX:NZDUSD',
      EURJPY: 'FX:EURJPY',
      GBPJPY: 'FX:GBPJPY',
      BTCUSD: 'BINANCE:BTCUSDT'
    };
    var tvSymbol = majors[pairSymbol] || (listed + ':' + pairSymbol);

    new TradingView.widget({
      autosize: true,
      height: 520,
      symbol: tvSymbol,
      interval: "60",
      timezone: "Etc/UTC",
      theme: document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark',
      style: "1",
      locale: "en",
      toolbar_bg: "#f1f3f6",
      enable_publishing: false,
      hide_side_toolbar: false,
      allow_symbol_change: false,
      container_id: "tradingview_92622"
    });
  })();
</script>
```

## Notes

- `allow_symbol_change: false` keeps traders on the pair they selected in PrimeStone (avoids drifting to unrelated TV symbols).
- Theme should follow the site light/dark toggle if one exists.
- Weekend gaps on FX will remain; that is correct market behaviour.
- For broker-native candles, replace this widget with Charting Library + a real datafeed backed by the broker’s OHLC — out of scope until that code exists in repo.
