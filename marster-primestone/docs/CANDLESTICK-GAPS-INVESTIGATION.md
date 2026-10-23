# Marster / PrimeStone — Candlestick Gap Investigation

**Site:** http://marster.primestonebroker.com/ (also https://primestonebroker.com/)  
**Date:** 23 Oct 2026 (Asia/Calcutta)  
**Branch:** `cursor/marster-candlestick-gaps-1e07`

---

## Confirmed root cause (architecture)

The public Forex Trade chart is **not** using TradingView Charting Library + a custom Datafeed/UDF adapter.

It embeds TradingView’s **public Advanced Chart widget**:

- Script: `https://s3.tradingview.com/tv.js`
- Init on `/trade` and `/trade/{PAIR}`:

```js
new TradingView.widget({
  width: "100%",
  height: 450,
  symbol: "FX_IDC:EURUSD",   // built from pair.listed_market_name + symbol
  interval: "D",
  timezone: "Etc/UTC",
  theme: "dark",
  style: "1",
  locale: "en",
  enable_publishing: false,
  allow_symbol_change: true,
  container_id: "tradingview_92622"
});
```

Implications:

| Assumption in the fix brief | Live site reality |
|---|---|
| Custom historical OHLC API for the chart | **None** — candles come from TradingView’s `FX_IDC` feed |
| Backend candle aggregation / getBars | **Not present** for the chart |
| WebSocket → subscribeBars live candles | Pusher is used for **order book / trades**, not chart bars |
| `/trade/history/:sym` | Returns **user trade history**, not OHLC (`{"success":true,"trades":[]}`) |

So large gaps cannot be fixed by changing Laravel OHLC aggregation — that pipeline does not drive this chart. Gaps are either:

1. Normal FX session/weekend spacing from TradingView’s `FX_IDC` series, exaggerated visually (thin bodies, wide time scale), and/or  
2. A **widget configuration / symbol-source** choice (`FX_IDC` + default `interval: "D"`), and/or  
3. A separate authenticated chart not visible without login (no `charting_library` / `datafeed` assets found on the public host).

Reproduced on EURUSD dark theme: sparse daily candles, **Vol 0**, platform header price ≠ chart last price (separate feeds).

Screenshot: `/opt/cursor/artifacts/screenshots/marster-eurusd-chart.png`

---

## Files / endpoints involved (live)

| Item | Location |
|------|----------|
| Trade UI | `http://marster.primestonebroker.com/trade/EUR_USD` |
| Widget bootstrap | Inline script on trade page (`tv.js` + `TradingView.widget`) |
| Pair metadata | `GET /trade/pairs` → `listed_market_name: "FX_IDC"` for listed pairs |
| Trade history (not OHLC) | `GET /trade/history/:curSym` |
| Order book | `GET /trade/order/book/:curSym` |
| Realtime | Pusher (`assets/global/js/pusher.min.js`, `broadcasting.js`) for orders/trades |
| Likely Blade source (on server, not in this repo) | `resources/views/templates/basic/.../trade*.blade.php` (ViserLab-style Laravel crypto/FX theme) |

Laravel source is **not** in `dmssystem`. `github.com/Rajk15/Primestone` exists but is **empty**. Prior agents could not authenticate to cPanel/FTP with available credentials.

---

## Hosting access attempts

| Method | Result |
|--------|--------|
| GoDaddy Customer ID `747695038` + `$$Smart123` → SSO/API | Not usable from this environment (SSO/CAPTCHA; API 401) |
| cPanel `https://primestonebroker.com:2083` usernames tried (`747695038`, `thedatatrade`, `primestone`, …) | Login failed |
| FTP/SSH to `118.139.183.221` | Auth failed (prior agent) |

**Missing evidence to complete a deployed fix:** working cPanel/SFTP credentials **or** a non-empty GitHub repo with the Laravel app.

---

## What would permanently fix gaps (once source/hosting is available)

### A. If staying on the free TradingView widget (smallest change)

Apply `patches/trade-tradingview-widget.patch.md` guidance:

1. Prefer liquid TradingView symbols for majors, e.g. `FX:EURUSD` / `OANDA:EURUSD`, instead of sparse `FX_IDC:*` where appropriate.  
2. Default intraday interval used by traders (e.g. `15` or `60`) instead of only `D`, if product expects intraday density.  
3. Increase chart height / enable `autosize` so candle body width is readable.  
4. Keep `timezone: "Etc/UTC"`; do **not** invent a custom datafeed in the free widget (unsupported).  
5. Accept that FX weekend/holiday gaps are real; do not synthesize fake candles.

### B. If the product truly needs broker OHLC (matches the original brief)

Requires a real project change, not a widget tweak:

1. License/embed **TradingView Charting Library** (or Lightweight Charts).  
2. Implement Datafeed `onReady` / `resolveSymbol` / `getBars` / `subscribeBars`.  
3. Backend OHLC from the authorised market-data provider (MetaAPI/MT5 or other), UTC ms bar open times, correct FX session.  
4. Wire live ticks to update/create bars; leave order execution / demo-live / commissions untouched.

That stack is **not** on the live public trade page today.

---

## Tests performed

| Test | Result |
|------|--------|
| Locate chart library | Pass — `tv.js` free widget |
| Custom datafeed / charting_library assets | Not found (404) |
| EURUSD page widget config | Pass — `FX_IDC:EURUSD`, interval `D` |
| `/trade/history/EUR_USD` OHLC | Fail as OHLC source — empty trades array only |
| Light/dark | Widget `theme: "dark"` hardcoded in embed (TV UI can toggle inside iframe) |
| Deploy code fix | **Blocked** — no source / no hosting write access |
| Upstream TV history API gap metrics | Blocked from this VM (TradingView history hosts unreachable/closed) |

---

## Remaining limitations

- Cannot compare before/after bar counts from an authorised broker OHLC API because the chart does not call one.  
- Cannot patch production Blade without hosting or repo access.  
- Screen recording referenced in the brief was not present in this workspace’s uploads folder.
