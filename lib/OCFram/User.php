<?php
namespace OCFram;

session_start();

class User
{
  public function setSession($session, $attr)
  {
    $_SESSION[$session] = $attr;
  }

  public function sessionExist($attr)
  {
    return isset($_SESSION[$attr]);
  }

  public function getAttribute($attr)
  {
    return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
  }

  public function getFlash()
  {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
  }

  public function hasFlash()
  {
    return isset($_SESSION['flash']);
  }

  public function isAuthenticated()
  {
    return isset($_SESSION['user']) && $_SESSION['user'] === true;
  }

  public function setAttribute($attr, $value)
  {
    $_SESSION[$attr] = $value;
  }

  public function setAuthenticated($authenticated = true)
  {
    if (!is_bool($authenticated))
    {
      throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
    }
    $_SESSION['user'] = $authenticated;

  }

  public function setFlash($value)
  {
    $_SESSION['flash'] = $value;
  }

  public function endSession()
  {
    $_SESSION = array();
    session_destroy();
  }
}
