var ajax_booster =
{
  init()
  {
    phoxy.state.ajax.booster = {};
    phoxy._.internal.Override(phoxy._.api, 'ajax', function _ajax_booster(url, cb)
    {
      ajax_booster.request(url, cb);
    });
  }
  ,
  request: function(url, cb, data)
  {
    this.schedule(url, cb, data);
  }
  ,
  schedule: function(url, cb, data)
  {
    var task =
    {
      cb: cb,
      data: data,
    };

    if (typeof phoxy.state.ajax.booster[url] != 'undefined')
      return phoxy.state.ajax.booster[url].push(task);

    phoxy.state.ajax.booster[url] = [task];
    this.execute_next(url);
  }
  ,
  execute_next: function(url)
  {
    var task = phoxy.state.ajax.booster[url].shift();

    var shedule_next = function()
    {
      ajax_booster.finished(url);
      task.cb.apply(phoxy, arguments);
    };

    this.ajax(url, shedule_next, task.data);
  }
  ,
  finished: function(url)
  {
    if (phoxy.state.ajax.booster[url].length > 0)
      return this.execute_next(url);

    delete phoxy.state.ajax.booster[url];
  }
  ,
  ajax: function(url, cb, data)
  {
    phoxy._.EarlyStage.ajax(url, cb)
  }
};

ajax_booster.init();
