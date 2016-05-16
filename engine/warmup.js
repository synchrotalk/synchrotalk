var warmup_obj =
{
  wait: true,
  config: "/api/phoxy",
  skip_initiation: true,
  sync_cascade: false,
  verbose: 0,
  OnWaiting: function()
  {
    phoxy._.EarlyStage.async_require[0] = "/enjs.js";
    phoxy._.EarlyStage.async_require.push("//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min.js");
    phoxy._.EarlyStage.EntryPoint();
  },
  OnBeforeCompile: function()
  {
  },
  OnAfterCompile: function()
  {
    phoxy.Config()['api_dir'] = '/' + phoxy.Config()['api_dir'];
    phoxy.Config()['ejs_dir'] = '/' + phoxy.Config()['ejs_dir'];
    phoxy.Config()['js_dir'] = '/' + phoxy.Config()['js_dir'];

    phoxy.Override('ApiAnswer', function not_found(data)
    { // 404 page overriding example
      if (data["error"] === 'Module not found'
          || data["error"] === "Unexpected RPC call (Module handler not found)")
      {
        $('.removeafterload').remove();

        if (typeof message != 'function')
          return phoxy.ApiRequest("utils/page404");

        return message('error', 'Some requests are failed');
      }
      return arguments.callee.origin.apply(this, arguments);
    })

    phoxy.Log(3, "Phoxy ready. Starting");
  },
  OnBeforeFirstApiCall: function()
  {
    // Disable tracking analytics if no indicator set
    if (!phoxy.Config()['ga'])
      return ga = function() {};

    if (typeof ga == 'undefined')
      return setTimeout(arguments.callee, 100);

    ga('create', phoxy.Config()['ga'], 'auto');
    ga('send', 'pageview');

    phoxy.Override('ApiRequest', function(request)
    {
      var link = request;
      if (typeof request == 'array')
        link = request[0];

      ga('send', 'event', 'api', link);

      return arguments.callee.origin.apply(this, arguments);
    });

    function track_url(url)
    {
      ga('set', 'page', typeof url == 'array' ? url[0] : url);
      ga('send', 'pageview');
    }

    phoxy._.internal.Override(phoxy._.click, 'OnPopState', function(e)
    {
      track_url(e.target.location.pathname);

      return arguments.callee.origin.apply(this, arguments);
    });

    phoxy.Override('ChangeURL', function(url)
    {
      var ret = arguments.callee.origin.apply(this, arguments);

      track_url(url);
      return ret;
    });

  },
  OnExecutingInitialClientCode: function()
  {
    // Enable jquery in EJS context
    var origin_hook = EJS.Canvas.prototype.hook_first;
    EJS.Canvas.prototype.hook_first = function jquery_hook_first()
    {
      return $(origin_hook.apply(this, arguments));
    }
  },
  OnInitialClientCodeComplete: function()
  {
    phoxy.Log(3, "Initial handlers complete");
    $('.removeafterload').remove();
  }
  ,
  OnFirstPageRendered: function()
  {
    phoxy.Log(3, "First page rendered");
  }
};

if (typeof phoxy.prestart === 'undefined')
  phoxy = warmup_obj;
else
{
  phoxy.prestart = warmup_obj;
  phoxy.prestart.OnWaiting();
}

(function()
{
  var d = document;
  var js = d.createElement("script");
  js.type = "text/javascript";
  js.src = "/phoxy/phoxy.js";
  js.setAttribute("async", "");
  d.head.appendChild(js);
})();


// Loading animation
(function()
{
  if (typeof phoxy._ === 'undefined')
    return setTimeout(arguments.callee, 10);

  var percents = phoxy._.EarlyStage.LoadingPercentage();
  var element = document.getElementById('percent');

  if (element === null)
    return;
  element.style.width = percents + "px";
  element.style.opacity = percents / 100 + 0.5;
  setTimeout(arguments.callee, 50);

  if (percents === 100)
    $('.removeafterload').css('opacity', 0);
})();
