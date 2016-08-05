<?php

class auth extends api
{
  public function load($network_name)
  {
    $network = phoxy::Load('networks/network')->get_network_object($network_name);

    return $network->auth();
  }

  protected function add($network_name)
  {
    $auth = $this->load($network_name);

    $auth_type = $auth->preferred_authtype();

    $sequences =
    [
      'direct'   =>
      [

      ],

      'callback' =>
      [
      ],

      'redirect' =>
      [
        'redirect_auth_requirments',
        'redirect_auth_question',
        'redirect_auth_answer',
      ],
    ];

    return
    [
      'design' => 'play_sequence',
      'data' =>
      [
        'sequence' => $sequences[$auth_type],
      ],
    ];
  }

  protected function FetchRequirments()
  {
  }

  protected function FetchQuestion()
  {
  }

  protected function FetchAnswer()
  {
  }

  private function RefactorDirectAnswer()
  { // This code is not working and has to be re-factored
    $user = $network->log_in($login, $password);
    phoxy_protected_assert($user, "Login/password invalid");

    $user['network'] = $network;

    $accounts[$network] =
    [
      "network" => $network,
      "login" => $login,
      "password" => $password,
      "user" => $user,
    ];

    return
    [
      "design" => "accounts/create/welcome",
      "data" =>
      [
        'user' => $user,
        'next' => 'inbox',
      ],
      "script" => "user",
      "before" => "user.login",
    ];
  }


}
