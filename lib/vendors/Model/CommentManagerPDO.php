<?php
namespace Model;

use \Entity\Comment;

class CommentManagerPDO extends CommentManager
{
  protected function add(Comment $comment)
  {
    $q = $this->dao->prepare('INSERT INTO comments SET contenu = :contenu, edition_date = NOW(), state = :state, user_id = :user_id, post_id = :post_id');

    $q->bindValue(':contenu',   $comment->contenu());
    $q->bindValue(':state',     0);
    $q->bindValue(':user_id',   1);
    $q->bindValue(':post_id',   $comment->post_id());

    $q->execute();

    $comment->setId($this->dao->lastInsertId());
  }

  public function delete($id)
  {
    $this->dao->exec('DELETE FROM comments WHERE id = '.(int) $id);
  }

  public function deleteFromPost($postId)
  {
    $this->dao->exec('DELETE FROM comments WHERE post_id = '.(int) $postId);
  }

  public function getListOf($postId, $state)
  {
    if (!ctype_digit($postId))
    {
      throw new \InvalidArgumentException('L\'identifiant du post passé doit être un nombre entier valide');
    }

    $q = $this->dao->prepare('SELECT c.id, contenu, edition_date, state, user_id, post_id, u.name AS author_name FROM comments c INNER JOIN users u ON u.id = c.user_id WHERE post_id = :postId AND (state = 1 OR state = :state)');

    $q->bindValue(':postId', $postId, \PDO::PARAM_INT);
    $q->bindValue(':state', $state, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    $comments = $q->fetchAll();

    foreach ($comments as $comment)
    {
      $comment->setEdition_date(new \DateTime($comment->edition_date()));
    }

    return $comments;
  }

  protected function update(Comment $comment)
  {
    $q = $this->dao->prepare('UPDATE comments SET contenu = :contenu, state = :state WHERE comments.id = :id');

    $q->bindValue(':contenu', $comment->contenu());
    $q->bindValue(':state',   $comment->state());
    $q->bindValue(':id',      $_GET['id']);

    $q->execute();
  }

  public function get($id)
  {
    $q = $this->dao->prepare('SELECT c.id, contenu, edition_date, state, user_id, post_id, u.name AS author_name FROM comments c INNER JOIN users u ON c.user_id = u.id WHERE c.id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();

    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

    return $q->fetch();
  }

  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM comments')->fetchColumn();
  }

  public function countUnvalidate()
  {
    return $this->dao->query('SELECT COUNT(*) FROM comments WHERE state = 0')->fetchColumn();
  }
}
