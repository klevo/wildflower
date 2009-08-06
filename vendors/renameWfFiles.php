<?php

try {
  $iter = new DirectoryIterator('/www/wildflower/app/views/');
  print WalkDirectory($iter);
}
catch (Exception $e) {
  print_r($e);
}

function WalkDirectory(DirectoryIterator $iter, $depth = 0) {
  $return = str_repeat(' ', 
  ($depth * 5)).$iter->getPathName()."\n";
  
  while ($iter->valid()) {
    $node = $iter->current();

    if ($node->isDir() && $node->isReadable() && !$node->isDot()) {
      $return .= WalkDirectory
      (new DirectoryIterator($node->getPathname()), $depth + 1);
    }
    elseif ($node->isFile())
      $return .= str_repeat
      (' ', ((1 + $depth) * 5)).$node->getFilename()."\n";
      
      // Rename wf_ to admin_
      $oldName = $node->getFilename();

      if (strpos($oldName, 'wf_') === 0) {
          $newFileName = str_replace('wf_', 'admin_', $node->getFilename());
          $path = str_replace('/www/wildflower/', '', $node->getPathname());
          $cmd = 'git mv ' . $path . ' ' . str_replace($oldName, $newFileName, $path) . "\n";
          $return .= $cmd;
          exec($cmd);
      }
      
    $iter->next();      
  }
  
  return $return;
}