<?php
namespace Model;

use \Entity\Message;

class MessageManagerPDO extends MessageManager
{
  protected function add(Message $message)
  {
    $q = $this->dao->prepare('INSERT INTO messages SET firstName = :firstName, lastName = :lastName, email = :email,  edition_date = NOW(), message= :message');

    $q->bindValue(':firstName', $message->firstName());
    $q->bindValue(':lastName',  $message->lastName());
    $q->bindValue(':email',     $message->email());
    $q->bindValue(':message',   $message->message());

    $q->execute();

    $message->setId($this->dao->lastInsertId());
  }

  protected function update(Message $message)
  {
    $requete = $this->dao->prepare('UPDATE messages SET firstName = :firstName, lastName = :lastName, email = :email, modify_date = NOW(), message = :message WHERE id = :id');

    $requete->bindValue(':id',        $message->id(), \PDO::PARAM_INT);
    $requete->bindValue(':firstName', $message->login());
    $requete->bindValue(':email',     $message->email());
    $requete->bindValue(':lastName',  $message->lastName());
    $requete->bindValue(':message',   $message->message());

    $requete->execute();
  }
}
