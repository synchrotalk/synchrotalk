user = function()
{
}

user.login = function(cb, r)
{
  for (var k in r.data.user)
    window.user[k] = r.data.user[k];

  cb();
}

window.user = user;
