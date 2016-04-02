var thread = function thread(){};

thread.getEvent = function(event, user)
{
  var event_data = JSON.parse(event.data);
  event_data['user'] = user;

  //last id
  if(typeof last_id === 'undefined')
  {
    if(thread.ejsThread.messages[0])
      last_id = thread.ejsThread.messages[thread.ejsThread.messages.length - 1]['id'];
    else
      last_id = 0;
  }
  if(event_data.thread_id==thread.ejsThread.t_id)
  {
    if(event_data.messsage_id > last_id)
    {
      last_id = event_data.messsage_id;
      thread.ejsThread.onEvent(event_data, thread.ejsThread.first());
    }
  }
  else
  {
    console.log('Thread is not openned!');
  }
}

window.EventModules['thread'] = thread;
