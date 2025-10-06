<?php
use Entities\PopularOrganizationDesignation;
$id = $_POST['id'] ?? $_GET['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) { redirect('/'); exit; }
$entity = Entities\PopularOrganizationDesignation::find($id);
if (!$entity) { redirect('/'); exit; }
$entity->fill($_POST);
if ($entity->save()) { $_SESSION['success'] = 'Updated!'; redirect('/' . basename(dirname(__FILE__)) . '/' . $entity->id); } else { $_SESSION['_errors'] = $entity->getErrors(); redirect('/' . basename(dirname(__FILE__)) . '/' . $id . '/edit'); }