<?php

class main extends api
{
  protected function Reserve()
  {
    unset($this->addons['result']);
    return
    [
      'design' => 'main/body',
      'script' => '/js/message.js',
    ];
  }

  protected function home()
  {
    return
    [
      'design' => 'main/home',
    ];
  }
}
