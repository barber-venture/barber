<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="<?php echo  h($class) ?>"><?php echo  h($message) ?></div>
