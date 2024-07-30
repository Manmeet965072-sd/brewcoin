<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
    <div id="tradingview_eb28f"></div>
    <div class="tradingview-widget-copyright"><a href="https://in.tradingview.com/symbols/BTCUSDT/?exchange=BINANCE" rel="noopener" target="_blank"><span class="blue-text">BTCUSDT Chart</span></a> by TradingView</div>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        var pathArray = window.location.pathname.split('/');

        var symbol = pathArray[2];
        new TradingView.widget({
            "autosize": true,
            "symbol": "BINANCE:" + symbol + "USDT",
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "light",
            "style": "1",
            "locale": "in",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_eb28f"
        });
    </script>
</div>
<!-- TradingView Widget END -->