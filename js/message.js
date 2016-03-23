message = function(css, text)
{
  debugger;
  phoxy.ApiRequest(['utils/message', css, text],function(){});
}
