<?php
session_start();

if (!isset($_SESSION['low'])) {
  $_SESSION['low'] = 1;
  $_SESSION['high'] = 1000;
  $_SESSION['moves'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $action = $data['action'];

  if ($action === 'guess') {
    $mid = floor(($_SESSION['low'] + $_SESSION['high']) / 2);
    $_SESSION['mid'] = $mid;
    echo json_encode(['mid' => $mid]);
  } elseif ($action === 'less') {
    $_SESSION['high'] = $_SESSION['mid'] - 1;
    $_SESSION['moves']++;
    echo json_encode(['result' => 'continue']);
  } elseif ($action === 'greater') {
    $_SESSION['low'] = $_SESSION['mid'] + 1;
    $_SESSION['moves']++;
    echo json_encode(['result' => 'continue']);
  } elseif ($action === 'equal') {
    $_SESSION['moves']++;
    echo json_encode(['result' => 'win', 'mid' => $_SESSION['mid'], 'moves' => $_SESSION['moves']]);
  } elseif ($action === 'restart') {
    // Reset the game
    session_destroy();
    session_start();
    $_SESSION['low'] = 1;
    $_SESSION['high'] = 1000;
    $_SESSION['moves'] = 0;
    echo json_encode(['status' => 'restarted']);
  }
}
?>
