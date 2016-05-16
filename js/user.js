user = function()
{
}

user.login = function(cb, r)
{
  for (var k in r.data.user)
    window.user[k] = r.data.user[k];

  cb();
}

user.author = function(id, users)
{
  if (id == window.user.id)
    return window.user;

  for (var k in users)
  {
    var v = users[k];

    if (v.id == id)
      break;
  }

  if (v.id != id)
    notify("error", "Unable to find author " + id);

  return v;
}

user.download_demo_login_data = function()
{
  phoxy.ApiRequest('accounts/demo_me');
}

window.user = user;

user.download_demo_login_data();
