<?php
use Entities\PopularSkill;
$id = $_POST['id'] ?? $_GET['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id || !verify_csrf()) { redirect('/'); exit; }
$entity = Entities\PopularSkill::find($id);
if ($entity && $entity->delete()) { $_SESSION['success'] = 'Deleted!'; } else { $_SESSION['error'] = 'Failed to delete'; }
redirect('/' . basename(dirname(__FILE__)));