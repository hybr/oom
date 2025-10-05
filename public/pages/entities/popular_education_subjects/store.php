<?php
use Entities\PopularEducationSubject;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) { redirect('/'); exit; }
$entity = new Entities\PopularEducationSubject();
$entity->fill($_POST);
if ($entity->save()) { $_SESSION['success'] = 'Saved!'; redirect('/' . basename(dirname(__FILE__)) . '/' . $entity->id); } else { $_SESSION['_errors'] = $entity->getErrors(); redirect('/' . basename(dirname(__FILE__)) . '/create'); }