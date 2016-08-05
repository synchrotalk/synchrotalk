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


    return
    [
      'design' => 'networks/auth/sequence.play',
      'data' =>
      [
        'sequence' => $this->get_sequence($auth_type),
      ],
    ];
  }

  private function require_known_instruction($sequence_type, $instruction)
  {
    $sequence = $this->get_sequence($sequence_type);

    phoxy_protected_assert(in_array($instruction, $sequence), "Sorry action invalid");
  }

  public function get_sequence($sequence_type)
  {
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

    return $sequences[$sequence_type];
  }

  protected function FetchRequirments($sequence_type, $instruction)
  {
    $this->require_known_instruction($sequence_type, $instruction);
  }

  protected function FetchQuestion($sequence_type, $instruction, $question)
  {
    $this->require_known_instruction($sequence_type, $instruction);
  }

  protected function FetchAnswer($sequence_type, $instruction, $answer)
  {
    $this->require_known_instruction($sequence_type, $instruction);
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
