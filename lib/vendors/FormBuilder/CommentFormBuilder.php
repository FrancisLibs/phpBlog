<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\TextField;
use \OCFram\HiddenField;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{
  public function build()
  {
    $this->form
       ->add(new TextField([
        'divClass'=>'form-group',
        'label' => 'Commentaire',
        'name' => 'contenu',
        'labelClass' => 'label col-4 col-form-label col-form-label-sm inputsm',
        'widgetClass' => 'widget form-control input-sm w-50',
        'rows' => 7,
        'cols' => 50,
        'validators' => [
          new NotNullValidator('Merci de spécifier le contenu du post'),
        ],
       ]))
      ->add(new HiddenField([
        'name' => 'post_id',
       ]))
      ->add(new HiddenField([
        'name' => 'state',
       ]))
      ->add(new HiddenField([
        'name' => 'id',
       ]))
      ->add(new HiddenField([
        'name' => 'formToken'
       ]));
  }
}
